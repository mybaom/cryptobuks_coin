<?php

namespace App\Http\Controllers\Agent;

use App\Agent;
use App\FeedBack;
use Illuminate\Support\Facades\DB;

class FeedbackController extends Controller
{
    // 留言管理
    public function index()
    {
        return view("agent.feedback.index");
    }

    // 留言记录数据
    public function data()
    {
        $account_number = request()->input('account_number', '');
        $start = request()->input('start', '');
        $end = request()->input('end', '');
        $limit = request()->get('limit', 20);


        $agentId = Agent::getAgentId();
        $agent = Agent::find($agentId);

        $query = DB::table('feedback')
            ->select(
                'feedback.id',
                'users.account_number',
                'feedback.content',
                'feedback.reply_content',
                DB::raw('FROM_UNIXTIME(feedback.create_time) as create_time'),
                DB::raw('FROM_UNIXTIME(feedback.reply_time) as reply_time')
            );

        if ($account_number) {
            $query->where('users.account_number', $account_number);
        }
        if (!empty($start) && !empty($end)) {
            $query->whereBetween('feedback.create_time', [strtotime($start . ' 0:0:0'), strtotime($end . ' 23:59:59')]);
        }


        if($agent->level = 1){
            $res = Agent::where('parent_agent_id', $agentId)->get();
            $userIds = array_column(json_decode(json_encode($res)), 'user_id');
            $query->whereIn('users.parent_id', $userIds);
        }else{
            $query->where('users.parent_id', $agent->user_id);
        }

        $list = $query->leftJoin('users', 'users.id', 'feedback.user_id')
            ->paginate($limit);


        return $this->layuiData($list);
    }

    public function reply()
    {
        $id = request()->get('id');
        $info = FeedBack::find($id);

        return view("agent.feedback.reply", ['info' => $info]);
    }

    public function doreply()
    {
        $id = request()->get('id');
        $reply_content = request()->get('reply_content');

        $info = FeedBack::find($id);
        if(! $info)
        {
            return $this->ajaxReturn([], '留言不存在');
        }

        $info->reply_content = $reply_content;
        $info->reply_time = time();
        $res = $info->save();

        if(! $res)
        {
            return $this->ajaxReturn([], '回复失败');
        }

        return $this->ajaxReturn([], '回复成功');
    }

}