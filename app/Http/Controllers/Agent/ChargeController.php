<?php
namespace App\Http\Controllers\Agent;

use App\Agent;
use Illuminate\Support\Facades\DB;

class ChargeController extends Controller
{

    public function index()
    {
        return view("agent.charge.index");
    }

    // 充值记录数据
    public function data()
    {
        $account_number = request()->input('account_number', '');
        $start = request()->input('start', '');
        $end = request()->input('end', '');
        $limit = request()->get('limit', 20);
        $page  = request()->get('page', 1);
        $offstart = ($page - 1) * $limit;
        $where = '';
        if ($account_number) {
            $where = "u.account_number = '$account_number'";
        }
        if (!empty($start) && !empty($end)) {
            $start = $start . ' 0:0:0';
            $end   = $end . ' 23:59:59';
            $where .= ($where ? ' and ' : '') . "c.created_at between '$start' and '$end'";
        }
        if($where)
        {
            $where = 'where ' . $where;
        }

        $agentId = Agent::getAgentId();
        $sql = $this->getDataSql('count(user_id) as c', $agentId, $where, $offstart, $limit);
        $count   = DB::select($sql);
        $count = $count[0]->c;

        $field = "
            t.user_id,
            t.type,
            c.amount,
            u.account_number,
            c.created_at,
            if(c.`status` = 1, '', updated_at) as updated_at,
            if(c.`status` = 1, '充值中', '充值成功') as status
        ";

        $sql = $this->getDataSql($field, $agentId, $where, $offstart, $limit);
        $list    = DB::select($sql);
        $list = json_decode(json_encode($list, JSON_UNESCAPED_UNICODE), true);

        return response()->json(['code' => 0,'msg' => '','count' => $count,'data' => $list, 'extra_data' => '']);
    }

    public function getDataSql($field, $agentId, $where, $offstart, $limit)
    {
        return "
            select
                $field
            from(
                select user_id,'代理' as type from agent where parent_agent_id = $agentId
                UNION all
                select id as user_id,'用户' as type from users where parent_id in(select user_id from agent where parent_agent_id = $agentId)
            ) t
            JOIN users u on u.id = t.user_id
            JOIN charge_req c ON t.user_id = c.uid
            $where
            limit $offstart, $limit
        ";
    }

}