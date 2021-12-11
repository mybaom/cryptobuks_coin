@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">产品名</label>
            <div class="layui-input-inline">
                <input type="text" name="title" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->title)){{$result->title}}@endif">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">最小限额</label>
            <div class="layui-input-inline">
                <input type="text" name="min" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->min)){{$result->min}}@endif">
            </div>
        </div>
         <div class="layui-form-item">
            <label class="layui-form-label">最大限额</label>
            <div class="layui-input-inline">
                <input type="text" name="max" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->max)){{$result->max}}@endif">
            </div>
        </div>
        
{{--        --}}
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">最小日收益率%</label>--}}
{{--            <div class="layui-input-inline">--}}
{{--                <input type="text" name="min_daily_yield" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->min_daily_yield)){{$result->min_daily_yield}}@endif">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        --}}
{{--         <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">最大日收益率%</label>--}}
{{--            <div class="layui-input-inline">--}}
{{--                <input type="text" name="max_daily_yield" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->max_daily_yield)){{$result->max_daily_yield}}@endif">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        --}}
{{--        --}}
{{--        --}}
{{--        <div class="layui-form-item">--}}
{{--            <label class="layui-form-label">今日收益率设置%</label>--}}
{{--            <div class="layui-input-inline">--}}
{{--                <input type="text" name="interest_rate_today" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->interest_rate_today)){{$result->interest_rate_today}}@endif">--}}
{{--            </div>--}}
{{--        </div>--}}

        <div class="layui-form-item">
            <label class="layui-form-label">CBV收益</label>
            <div class="layui-input-inline">
                <input type="text" name="today_profit" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->today_profit)){{$result->today_profit}}@endif">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">产品周期（天）</label>
            <div class="layui-input-inline">
                <input type="text" name="cycle" lay-verify="required" autocomplete="off" placeholder="" class="layui-input" value="@if(!empty($result->cycle)){{$result->cycle}}@endif">
            </div>
        </div>
        

        <input type="hidden" name="id" value="@if(!empty($result->id)){{$result->id}}@endif">
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="demo1">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
    </form>

@endsection

@section('scripts')
    <script>


        layui.use(['form','laydate'],function () {
            var form = layui.form
                ,$ = layui.jquery
                ,laydate = layui.laydate
                ,index = parent.layer.getFrameIndex(window.name);
            //监听提交
            form.on('submit(demo1)', function(data){
                var data = data.field;
                $.ajax({
                    url:'{{url('admin/ulipai/goodsadd')}}'
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