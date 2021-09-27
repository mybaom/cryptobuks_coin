@extends('admin._layoutNew')

@section('page-head')
<style>
    [hide] {
        display: none;
    }
</style>
@endsection

@section('page-content')
<div class="layui-form">
    <div class="layui-item">
        <div class="layui-inline" style="margin-left: 10px;">
            <label>用户账号</label>
            <div class="layui-input-inline">
                <input type="text" name="account" placeholder="请输入手机号或邮箱" autocomplete="off" class="layui-input" value="">
            </div>
        </div>
       
        <div class="layui-inline" style="margin-left: 10px;">
            <label>产品</label>
            
            <div class="layui-input-inline" style="width: 90px">
                 <select name="goods_id" lay-filter="" lay-search>
                    <option value=""></option>
                    @if(!empty($ulipaigoods))
                    @foreach($ulipaigoods as $ug)
                    <option value="{{$ug->id}}" >{{$ug->title}}</option>
                    @endforeach
                        @endif
                </select>
                
            </div>
            
            
        </div>
        
        
        <div class="layui-btn-group">
           
            <button class="layui-btn btn-search" id="mobile_search" lay-submit lay-filter="mobile_search"> <i class="layui-icon layui-icon-search"></i> </button>
           

        </div>
    </div>
</div>
<table id="userlist" lay-filter="userlist"></table>
@endsection

@section('scripts')


<script>        
    layui.use(['element', 'form', 'layer', 'table'], function () {
        var element = layui.element
            ,layer = layui.layer
            ,table = layui.table
            ,$ = layui.$
            ,form = layui.form
        var user_table = table.render({
            elem: '#userlist'
            ,toolbar: true
            ,url: '/admin/ulipai/orderlist_data'
            ,page: true
            ,limit: 100
            ,limits: [20, 50, 100, 200, 500, 1000]
            ,height: 'full-60'
            ,cols: [[
                {field: '', type: 'checkbox'}
                ,{field: 'id', title: 'ID', width: 100}
                ,{field:'username', title:'用户账号', width:150}
                ,{field:'goods_id', title:'产品ID', width:150, hide: true}
                ,{field:'title', title:'产品名', width:150, hide: true}
                ,{field:'num', title: '投资数量',width: 180, hide: true}
                ,{field:'profit', title:'收益', width:100}
                ,{field: 'cycle', title: '周期（天）', width: 150}
                ,{field: 'income_days', title: '收益天数', width: 150}
                ,{field: 'status', title: '状态', width: 90, templet:"#status_t"}
                ,{field:'addtime', title:'投资时间', width:200} 
                ,{field:'endtime', title:'结束时间', width:200} 
            ]]
        });

        $('input[name=account]').keypress(function (event) {
            if (event.charCode == 13) {
                $('#mobile_search').click();
            }
        });

        

        form.on('submit(mobile_search)', function(obj) {
            user_table.reload({
                where: obj.field
            });
            return false;
        });

       

    });
</script>
@endsection