@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">名称</label>
            <div class="layui-input-inline">
                <input type="text" name="name" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->name)){{$result->name}}@endif">
            </div>
        </div>
        <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">logo</label>
            <div class="layui-input-block">
                <button class="layui-btn" type="button" id="upload_test">选择图片</button>
                <br>
                <img src="<?php if(!empty($result->icon)): ?><?php echo e($result->icon); ?><?php endif; ?>" id="img_thumbnail" class="thumbnail" style="display: <?php if(!empty($result->icon)): ?><?php echo e("block"); ?><?php else: ?><?php echo e("none"); ?><?php endif; ?>;max-width: 200px;height: auto;margin-top: 5px;">
                <input type="hidden" name="icon" id="thumbnail" value="<?php if(!empty($result->icon)): ?><?php echo e($result->icon); ?><?php endif; ?>">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">初始发行价</label>
            <div class="layui-input-inline">
                <input type="text" name="start_price" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->start_price)){{$result->start_price}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">当前价</label>
            <div class="layui-input-inline">
                <input type="text" name="now_price" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->now_price)){{$result->now_price}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">发行量</label>
            <div class="layui-input-inline">
                <input type="text" name="circulation" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->circulation)){{$result->circulation}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">今日价格涨跌几率(%)</label>
            <div class="layui-input-inline">
                <input type="text" name="rise_fall_probability" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->rise_fall_probability)){{$result->rise_fall_probability}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">今日价格最小涨幅(%)</label>
            <div class="layui-input-inline">
                <input type="text" name="min_increase" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->min_increase)){{$result->min_increase}}@endif">
            </div>
        </div>
         <div class="layui-form-item">
            <label class="layui-form-label">今日价格最大涨幅(%)</label>
            <div class="layui-input-inline">
                <input type="text" name="max_increase" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->max_increase)){{$result->max_increase}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">结束日期</label>
            <div class="layui-input-inline">
                <input type="text" name="end_date" id="end_date" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->end_date)){{$result->end_date}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">兑换开始日期</label>
            <div class="layui-input-inline">
                <input type="text" name="exchange_start_dete" id="exchange_start_dete" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->exchange_start_dete)){{$result->exchange_start_dete}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <select name="type" lay-verify="required">
                    <option value="0" <?php if($result->status =='0'): ?> selected <?php endif; ?>>关闭</option>
                    <option value="1" <?php if($result->status =='1'): ?> selected <?php endif; ?>>启用</option>
                </select>
            </div>
        </div>

        <input type="hidden" name="id" value="@if(!empty($result->id)){{$result->id}}@endif">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </form>

@endsection

@section('scripts')
    <script>


        layui.use(['form','upload','laydate'],function () {
            var form = layui.form
                ,$ = layui.jquery
                ,laydate = layui.laydate
                ,upload = layui.upload
                ,index = parent.layer.getFrameIndex(window.name);


            //执行一个laydate实例
            laydate.render({
                elem: '#end_date' //指定元素
            });
            laydate.render({
                elem: '#exchange_start_dete' //指定元素
            });

            var uploadInst = upload.render({
                elem: '#upload_test' //绑定元素
                ,url: '{{URL("api/upload")}}?scene=admin' //上传接口
                ,done: function(res){
                    //上传完毕回调
                    if (res.type == "ok"){
                        $("#thumbnail").val(res.message)
                        $("#img_thumbnail").show()
                        $("#img_thumbnail").attr("src",res.message)
                    } else{
                        alert(res.message)
                    }
                }
                ,error: function(){
                    //请求异常回调
                }
            });
            //监听提交
            form.on('submit(demo1)', function(data){
                var data = data.field;
                $.ajax({
                    url:'{{url('admin/offerproduct/goodsadd')}}'
                    ,type:'post'
                    ,dataType:'json'
                    ,data : data
                    ,success:function(res){
                        if(res.type=='error'){
                            layer.msg(res.message);
                        }else{
                            parent.layer.close(index);
                            parent.window.location.reload();
                        }
                    }
                });
                return false;
            });
        });
    </script>

@endsection