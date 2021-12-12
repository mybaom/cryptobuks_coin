@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
{{--    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">--}}
{{--        <button class="layui-btn layui-btn-normal layui-btn-radius" onclick="layer_show('添加代理','{{url('admin/agent/add')}}')">添加代理</button>--}}


{{--    </div>--}}

    <script type="text/html" id="switchTpl">
        <input type="checkbox" name="status" value="@{{d.id}}" lay-skin="switch" lay-text="是|否" lay-filter="sexDemo" @{{ d.status == 1 ? 'checked' : '' }}>
    </script>
   

    <table id="demo" lay-filter="test"></table>
    <script type="text/html" id="barDemo">

        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>


    </script>

@endsection

@section('scripts')
    <script>

        layui.use(['table','form'], function(){
            var table = layui.table;
            var $ = layui.jquery;
            var form = layui.form;
            //第一个实例
            table.render({
                elem: '#demo'
                ,url: "{{url('admin/agent/list_data')}}" //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', Width:50, sort: true}
                    ,{field: 'username', title: '代理账号', Width:150}
                    ,{field: 'level', title: '代理等级', Width:150}
                    ,{field: 'lower_agent_count', title: '下级代理数', Width:150}

                    ,{field: 'user_count', title: '累计邀请用户', Width:150}
                    ,{field: 'recharge_amount', title: '充值总金额', Width:150}
                    ,{field: 'recharge_income', title: '充值总收益', Width:150}
                    ,{field: 'direct_recharge_amount', title: '直接用户充值总额', Width:150}
                    ,{field: 'direct_recharge_income', title: '直接用户充值收益', Width:150}
                    ,{field: 'indirect_recharge_amount', title: '下级代理用户充值总额', Width:150}
                    ,{field: 'indirect_recharge_income', title: '下级代理用户充值收益', Width:150}

                    ,{field: 'account_number', title: '用户账号', Width:150}
                    ,{field: 'parent_agent_id', title: '上级ID', Width:150}
                    ,{field: 'parent_agent_name', title: '上级', Width:150}
                    ,{field: 'reg_time', title: '创建日期', minWidth: 80}
                    ,{field:'status', title:'是否冻结', minWidth:100, templet: '#switchTpl', unresize: true}
                    // ,{title:'操作',toolbar: '#barDemo'}

                ]]
            });
            
            
            //监听热卖操作
            form.on('switch(sexDemo)', function(obj){
                var id = this.value;
                $.ajax({
                    url:'{{url('admin/agent/start')}}',
                    type:'post',
                    dataType:'json',
                    data:{id:id},
                    success:function (res) {
                        if(res.error != 0){
                            layer.msg(res.message);
                        }
                    }
                });
            });



            table.on('tool(test)', function(obj){
                var data = obj.data;
                if(obj.event === 'del'){
                    layer.confirm('真的删除行么', function(index){
                        $.ajax({
                            url:'{{url('admin/agent/del')}}',
                            type:'post',
                            dataType:'json',
                            data:{id:data.id},
                            success:function (res) {
                                if(res.type == 'error'){
                                    layer.msg(res.message);
                                }else{
                                    obj.del();
                                    layer.close(index);
                                }
                            }
                        });


                    });
                } else if(obj.event === 'edit'){
                    layer_show('编辑','{{url('admin/agent/add')}}?id='+data.id);
                }
            });

            //监听提交
            form.on('submit(mobile_search)', function(data){
                var account_number = data.field.account_number;
                table.reload('mobileSearch',{
                    where:{account_number:account_number},
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection