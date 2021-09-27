@extends('admin._layoutNew')

@section('page-head')

@endsection

@section('page-content')
    <form class="layui-form" action="">
        <div class="layui-form-item">
            <label class="layui-form-label">用户手机号或邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="account" autocomplete="off" placeholder="" class="layui-input" value="{{$result->account}}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">居住地址</label>
            <div class="layui-input-block">
                <input type="text" name="address" autocomplete="off" placeholder="" class="layui-input" value="{{$result->address}}" disabled>
            </div>
        </div>
       

        
         <div class="layui-form-item layui-form-text">
            <label class="layui-form-label">居住地证明</label>
            <div class="layui-input-block">
               
                <img src="@if(!empty($result->front_pic)){{$result->front_pic}}@endif" id="img_thumbnail" class="thumbnail" style="display: @if(!empty($result->front_pic)){{"block"}}@else{{"none"}}@endif;max-width: 200px;height: auto;margin-top: 5px;">
                
            </div>
        </div>
        
        
        
    </form>

@endsection

