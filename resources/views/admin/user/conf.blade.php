@extends('admin._layoutNew')
@section('page-head')

@stop
@section('page-content')
    <div class="larry-personal-body clearfix">
        <form class="layui-form col-lg-5">
            <div class="layui-form-item">
                <label class="layui-form-label">用户账号</label>
                <div class="layui-input-block">
                    <input type="text" name="account" autocomplete="off" class="layui-input layui-disabled" value="{{ $results['account'] }}" placeholder="" disabled>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">钱包币种</label>
                <div class="layui-input-block">
                    <select name="id">
                        @foreach ($results['list'] as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">调节账户</label>
                <div class="layui-input-block">
                    <select name="type" lay-verify="required">
                        <option value=""></option>
                        <hr class="layui-bg-gray">
                        <optgroup label="币币充值">
                            <option value="3">余额</option>
                        </optgroup>
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">调节方式</label>
                <div class="layui-input-block">
                    <input type="radio" lay-filter="way" name="way" onclick="changeWay(this)" value="recharge" title="用户充值"  checked>
                    <input type="radio" lay-filter="way" name="way" onclick="changeWay(this)" value="increment" title="增加余额">
                    <input type="radio" lay-filter="way" name="way" onclick="changeWay(this)" value="decrement" title="减少余额">
                </div>
            </div>
            <div class="layui-form-item bank-item">
                <label class="layui-form-label">用户卡号</label>
                <div class="layui-input-block">
                    <input type="text" name="user_account" placeholder="请输入用户的银行卡号" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item bank-item">
                <label class="layui-form-label">充值卡号</label>
                <div class="layui-input-block">
                    <input type="text" name="target_account" placeholder="请输入用户充值到哪个银行的卡号" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-conf-value">
                <label class="layui-form-label">充值金额</label>
                <div class="layui-input-block">
                    <input type="text" name="conf_value" required  lay-verify="required" placeholder="请输入需要充值的金额" autocomplete="off" class="layui-input">
                </div>
            </div>
            <div class="layui-form-item layui-form-text">
                <label class="layui-form-label">充值备注</label>
                <div class="layui-input-block">
                    <textarea name="info" placeholder="请输入内容" class="layui-textarea" lay-verify="required"></textarea>
                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="user_submit">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
        </form>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">

        import Select from "../../../../public/m/element-ui/lib/select";
        layui.use(['form','upload','layer'], function () {
            var layer = layui.layer;
            var form = layui.form;
            var $ = layui.$;

            //监听多选框点击事件  主要是通过  lay-filter="way"  来监听
            form.on('radio(way)', function (data) {
                console.log( data );　　//打印当前选择的信息
                var value = data.value;   //  当前选中的value值
                if(value == 'recharge'){
                    $('.bank-item').show();
                    $('.layui-form-text label').text('充值备注');
                    $('.layui-form-conf-value label').text('充值金额');
                    $('.layui-form-conf-value input').attr('placeholder', '请输入需要充值的金额');
                }else{
                    $('.bank-item').hide();
                    $('.layui-form-text label').text('调节备注');
                    $('.layui-form-conf-value label').text('调节值');
                    $('.layui-form-conf-value input').attr('placeholder', '请输入需要调节的数值');
                }
            });

            form.on('submit(user_submit)', function (data) {
                var data = data.field;
                if(data.way == 'recharge'){
                    data.way = 'increment';
                }
                data.is_recharge = 1;
                console.log(data);

                $.ajax({
                    url:'{{url('admin/user/conf')}}',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        layer.msg(res.message);
                        if(res.type == 'ok') {
                            var index = parent.layer.getFrameIndex(window.name);
                            parent.layer.close(index);
                            parent.window.location.reload();
                        }else{
                            return false;
                        }
                    }
                });
                return false;
            });
        });
        export default {
            components: {Select}
        }
    </script>
@stop