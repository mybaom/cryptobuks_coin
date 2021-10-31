@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">绑定用户账号</label>
            <div class="layui-input-inline">
                <input type="text" name="user_account" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->name)){{$result->name}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">上级代理账号</label>
            <div class="layui-input-inline">
                <input type="text" name="parent_account" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->start_price)){{$result->start_price}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">代理等级</label>
            <div class="layui-input-inline">
                <select name="level" lay-verify="required">
                    @for($i = 0; $i <= $level; $i ++)
                        <option value="<?php $i; ?>" <?php if($result->level == $i): ?> selected <?php endif; ?>><?php if($i == 0): ?>超管代理<?php endif; ?><?php if($i > 0): ?>{{$i}}级代理<?php endif; ?></option>
                    @endfor

                </select>
{{--                <input type="text" name="level" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->now_price)){{$result->now_price}}@endif">--}}
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-inline">
                <select name="status" lay-verify="required">
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