<?php
namespace App\Http\Controllers\Agent;

use App\Agent;
use Illuminate\Support\Facades\DB;

class AgentCommissionController extends Controller
{
    // 佣金管理
    public function index()
    {
        return view("agent.agent_commission.index");
    }

    // 佣金记录数据
    public function data()
    {
        $account_number = request()->input('account_number', '');
        $start = request()->input('start', '');
        $end = request()->input('end', '');
        $limit = request()->get('limit', 20);


        $agentId = Agent::getAgentId();
        $query = DB::table('agent_commission')
            ->select(
                'agent_commission.id',
                'users.account_number',
                'charge_amount',
                'commission',
                DB::raw('if(agent_commission.type = 1, "用户充值返佣", "下级代理用户充值返佣") as type'),
                'charge_req.created_at',
                'charge_req.updated_at'
            );

        if ($account_number) {
            $query->where('users.account_number', $account_number);
        }
        if (!empty($start) && !empty($end)) {
            $query->whereBetween('charge_req.created_at', [$start . ' 0:0:0', $end . ' 23:59:59']);
        }


        $list = $query->leftJoin('charge_req', 'charge_req_id', 'charge_req.id')
            ->leftJoin('users', 'users.id', 'agent_commission.user_id')
//            ->select('id', 'username', DB::raw('FROM_UNIXTIME(reg_time) as reg_time'))
            ->where('agent_commission.agent_id', $agentId)
            ->paginate($limit);


        return $this->layuiData($list);
    }

}