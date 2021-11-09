@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
    </div>

    <div class="layui-inline" style="margin-left: 10px;">
        <label>用户账号</label>
        <div class="layui-input-inline">
            <input type="text" name="account" placeholder="请输入手机号或邮箱" autocomplete="off" class="layui-input" value="">
        </div>
    </div>

    <div class="layui-btn-group">
        <button class="layui-btn btn-search" id="account_number" lay-submit lay-filter="account_number"> <i class="layui-icon layui-icon-search"></i> </button>
    </div>

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
            var data_table = table.render({
                elem: '#demo'
                ,url: "{{url('admin/offerproduct/getCbvWalletData')}}" //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: '用户ID', Width:50, sort: true}
                    ,{field: 'account_number', title: '用户名', Width:150}
                    ,{field: 'cbv_balance', title: '持有数', Width:150}
                    ,{field: 'cbv_now_price', title: '折合（$）', minWidth:50}

                ]]
            });


            //监听热卖操作
            form.on('switch(sexDemo)', function(obj){
                var id = this.value;
                $.ajax({
                    url:'{{url('admin/offerproduct/goodsstart')}}',
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
                            url:'{{url('admin/offerproduct/goodsdelete')}}',
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
                    layer_show('编辑','{{url('admin/offerproduct/goodsadd')}}?id='+data.id);
                }
            });

            //监听提交
            form.on('submit(account_number)', function(data){
                var account_number = $('[name="account"]').val();
                data_table.reload({
                    where:{account_number:account_number},
                    page: {curr: 1}         //重新从第一页开始
                });
                return false;
            });

        });
    </script>

@endsection