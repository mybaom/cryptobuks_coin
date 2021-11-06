@extends('agent.layadmin')

@section('page-head')

@endsection

@section('page-content')


    <div class="layui-fluid">
        <div class="layui-card">
            <div class="layui-form layui-card-header layuiadmin-card-header-auto" lay-filter="layadmin-userfront-formlist">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">开始日期</label>
                        <div class="layui-input-block">
                            <input type="text" name="start" id="datestart" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">结束日期</label>
                        <div class="layui-input-block">
                            <input type="text" name="end" id="dateend" placeholder="yyyy-MM-dd" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">用户账户</label>
                        <div class="layui-input-block">
                            <input type="text" name="account_number" placeholder="请输入" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button class="layui-btn layuiadmin-btn-useradmin" lay-submit lay-filter="san-user-search">
                            <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>
                        </button>
                    </div>


                </div>
            </div>


            <div class="layui-card-body">
                <div class="layui-carousel layadmin-backlog" style="background-color: #fff">
                    <table id="san-user-manage" lay-filter="san-user-manage"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script type="text/html" id="table-useradmin-webuser">
{{--        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="reply">回复</a>--}}
{{--        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="wallet_info">查看资金</a>--}}
{{--        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="order">查看合约订单</a>--}}
{{--        <a class="layui-btn layui-btn-normal layui-btn-xs" lay-event="micro_risk">秒合约点控</a>--}}
    </script>


<script>
    layui.use(['index','laydate','form','table'], function () {
            var $ = layui.$
                ,admin = layui.admin
            , table = layui.table
            , layer = layui.layer
            , laydate = layui.laydate
            , form = layui.form;


        //日期
        laydate.render({
            elem: '#datestart'
        });
        laydate.render({
            elem: '#dateend'
        });

        // console.log(parent_id);


        load();

        function load() {

            table.render({
                elem: '#san-user-manage'
                , url: '/agent/charge/data'
                , cols: [[
                    {type: 'checkbox', fixed: 'left'}
                    , {field: 'user_id', width: 60, title: 'ID', sort: true}
                    , {field: 'account_number', title: '用户账户', minWidth: 150}
                    , {field: 'amount', title: '金额' , width : 120}
                    , {field: 'type', title: '类型' , width : 120}
                    , {field: 'status', title: '状态', minWidth: 150}
                    , {field: 'created_at', title: '充值时间' , width : 120}
                    , {field: 'updated_at', title: '完成时间' , width : 120}
                    // , {title: '操作', width: 300, align: 'center', fixed: 'right', toolbar: '#table-useradmin-webuser'}
                ]]
                , page: true
                , limit: 30
                , height: 'full-320'
                , text: '对不起，加载出现异常！'
                
                , done: function (res) { //这里要说明一下：done 是只有 response 的 code 正常才会执行。而 succese 则是只要 http 为 200 就会执行
                    if (res !== 0) {
                        if (res.code === 1001) {
                            //清空本地记录的 token，并跳转到登入页
                            admin.exit();
                        }
                    }
                }
            });
        }

        table.on('tool(san-user-manage)', function (obj) {
            var event = obj.event;
            var data = obj.data;

            if (event == 'reply') {
                //查看订单

                layer.open({
                    title: '回复'
                    , type: 2
                    , content: '{{url('/agent/feedback/reply')}}?id=' + data.id
                    // , maxmin: true
                    , area: ['1000px', '600px']
                });
            }
        });


        form.render(null, 'layadmin-userfront-formlist');

        //监听搜索
        form.on('submit(san-user-search)', function (data) {
            var field = data.field;

            //执行重载
            table.reload('san-user-manage', {
                where: field
                , page: {
                    curr: 1 //重新从第 1 页开始
                }
                , done: function (res) { //这里要说明一下：done 是只有 response 的 code 正常才会执行。而 succese 则是只要 http 为 200 就会执行

                    if (res.code === 1001) {
                        //清空本地记录的 token，并跳转到登入页
                        admin.exit();
                    }
                    if (res.code === 1) {
                        layer.msg(res.msg, {icon: 5});
                    }
                }
            });
        });


    });
</script>

@endsection