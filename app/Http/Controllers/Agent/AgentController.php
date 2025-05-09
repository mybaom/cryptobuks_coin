<?php
/**
 * Created by PhpStorm.
 * User: YSX
 * Date: 2018/12/4
 * Time: 19:08
 */

namespace App\Http\Controllers\Agent;


use App\Agent;
use App\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgentController extends Controller
{

    /**代理商信息
     * 可以传用户id也可以传代理商id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function info()
    {
        $agent_id = request('agent_id', 0);
        $user_id  = request('user_id', Users::getUserId());

        if (!$agent_id && !$user_id) {
            return $this->error('参数错误');
        }

        $agent = new Agent();

        if ($agent_id) {
            $agent = $agent->where('id', $agent_id);
        }

        if ($user_id) {
            $user  = Users::find($user_id);
            $agent = $agent->where('id', 0);
        }

        $agent = $agent->first();

        return $this->success($agent);
    }

    /**
     * 下级代理列表
     */
    public function subordinateAgentList(Request $request)
    {
        $agentId = Agent::getAgentId();
        $list = DB::table('agent')
            ->select('id', 'username', 'recharge_distribution', DB::raw('FROM_UNIXTIME(reg_time) as reg_time'))
            ->where('parent_agent_id', $agentId)
            ->paginate(15);


        return $this->layuiData($list);
    }

}