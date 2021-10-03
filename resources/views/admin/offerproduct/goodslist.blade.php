@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <div style="margin-top: 10px;width: 100%;margin-left: 10px;">
        <button class="layui-btn layui-btn-normal layui-btn-radius" onclick="layer_show('添加认购产品','{{url('admin/offerproduct/goodsadd')}}')">添加认购产品</button>


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
            table.render({
                elem: '#demo'
                ,url: "{{url('admin/offerproduct/goodslist_data')}}" //数据接口
                ,page: true //开启分页
                ,id:'mobileSearch'
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', Width:50, sort: true}
                    ,{field: 'name', title: '产品名', Width:150}
                    ,{field: 'icon', title: '图标', Width:150}
                    ,{field: 'circulation', title: '发行量', minWidth:50}
                    ,{field: 'start_price', title: '初始发行价', minWidth:150}
                    ,{field: 'now_price', title: '当前价', minWidth:50}
                    ,{field: 'min_increase', title: '今日价格最小涨幅(%)', minWidth:50}
                    ,{field: 'max_increase', title: '今日价格最大涨幅(%)', minWidth:50}
                    ,{field: 'rise_fall_probability', title: '今日价格涨跌几率(%)', minWidth:50}
                    ,{field: 'exchange_start_dete', title: '兑换开始日期', minWidth:80}
                    ,{field: 'end_date', title: '结束日期', minWidth: 80},
                    ,{field:'status', title:'是否开启', minWidth:100, templet: '#switchTpl', unresize: true}
                    ,{title:'操作',toolbar: '#barDemo'}

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