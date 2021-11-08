<?php
namespace App\Http\Controllers\Agent;

use App\Agent;
use App\Users;
use Illuminate\Support\Facades\DB;

class CbvController extends Controller
{

    public function index()
    {
        return view("agent.cbv.index");
    }

    // cbv数据
    public function data()
    {
        $account_number = request()->input('account_number', '');
        $limit = request()->get('limit', 20);
        $page  = request()->get('page', 1);

        $users = new Users();

        $count = new Users();

        $users = $users->leftjoin("offer_product_wallet", "offer_product_wallet.user_id", "=", "users.id");
        $users = $users->leftjoin("offer_buy_product", "offer_buy_product.id", "=", "offer_product_wallet.obp_id");
        $count = $count->leftjoin("offer_product_wallet", "offer_product_wallet.user_id", "=", "users.id");
        $count = $count->leftjoin("offer_buy_product", "offer_buy_product.id", "=", "offer_product_wallet.obp_id");

        $agentId = Agent::getAgentId();
        $agentInfo = Agent::find($agentId);
        if ($agentInfo->level > 1) {
            $users = $users->where('users.parent_id', $agentInfo->user_id);
            $count = $count->where('users.parent_id', $agentInfo->user_id);
        }else{
            $res = Agent::where('parent_agent_id', $agentId)->get();
            $userIds = array_column(json_decode(json_encode($res)), 'user_id');
            array_push($userIds, $agentInfo->user_id);
            if($userIds) {
                $users = $users->whereIn('users.parent_id', $userIds);
                $count = $count->whereIn('users.parent_id', $userIds);
            }
        }

        if ($account_number) {
            $users = $users->where('users.account_number', $account_number);
            $count = $count->where('users.account_number', $account_number);
        }

        $list = $users->select("users.*",
            DB::raw("ifnull(round(offer_product_wallet.balance, 4), 0) as balance"),
            DB::raw("ifnull(round(offer_product_wallet.balance * offer_buy_product.now_price, 4), 0) as now_price")
        )->paginate($limit);

        $total = $count->select(DB::raw("round(sum(offer_product_wallet.balance), 4) as total"))->limit(($page-1)*$limit,$limit)->first();

        return $this->layuiData($list, $total->total);

    }

}