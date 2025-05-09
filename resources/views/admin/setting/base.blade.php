@extends('admin._layoutNew')
@section('page-head')
<style>
    li[hidden] {
        display: none;
    }
    .layui-form-label{
        width: 180px;
    }
    .layui-input-block{
        margin-left: 210px;
    }
</style>
@stop
@section('page-content')

    <div class="larry-personal-body clearfix">
        <form class="layui-form col-lg-5">
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">通知设置</li>
                    <li>基础设置</li>
                    <li>杠杆设置</li>
                    <li hidden>奖励设置</li>
                    <li hidden>工作室</li>
                    <li hidden>通证规则</li>
                    <li>秒合约</li>
                </ul>
                <div class="layui-tab-content">
                    <!--通知设置开始-->
                    <div class="layui-tab-item layui-show">
                        <div id="email">
                            <div>
                                <div class="layui-form-item ecology">
                                   <div class="layui-inline">
                                        <label class="layui-form-label">邮箱账号</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="1" placeholder="" name="phpMailer_username"
                                                type="text" onkeyup=""
                                                value="@if(isset($setting['phpMailer_username'])){{$setting['phpMailer_username'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">Token密码</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="required" placeholder="请输入最大值"
                                                name="phpMailer_password" onkeyup="" type="password"
                                                value="@if(isset($setting['phpMailer_password'])){{$setting['phpMailer_password'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">端口</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="required" placeholder="请输入比例"
                                                name="phpMailer_port" type="text"
                                                value="@if(isset($setting['phpMailer_port'])){{$setting['phpMailer_port'] ?? ''}}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">Host</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="required" placeholder="请输入最小值"
                                                name="phpMailer_host" type="text"
                                                value="@if(isset($setting['phpMailer_host'])){{$setting['phpMailer_host'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">发件人</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="1" placeholder="" name="submail_from_name"
                                                   type="text" onkeyup=""
                                                   value="@if(isset($setting['submail_from_name'])){{$setting['submail_from_name'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <div id="sms">
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">ztpay</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input"  placeholder="Appid"
                                                name="smsBao_username" type="text"
                                                value="ztpayo9bves1xnkqsg"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">AppSecret</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" type="password"  name="password"
                                                value="PXvzyfBllzQVxtjBRe7DCEZ3yRv95RRr" placeholder="AppSecret" >
                                        </div>
                                    </div>
                                 
                                </div>

                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">赛邮（国内appid）</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="required" placeholder=""
                                                name="submail_appid" type="text"
                                                value="{{$setting['submail_appid'] ?? '' }}"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">赛邮（国内appkey）</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" type="text" lay-verify="required" name="submail_appkey"
                                                value="{{$setting['submail_appkey']  ?? '' }}" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">赛邮（国内模板）</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" type="text" lay-verify="required" name="submail_template"
                                                value="{{$setting['submail_template']  ?? '' }}" placeholder="" >
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">赛邮（国外appid）</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input"  name="submail_overseas_appid"
                                                value="{{$setting['submail_overseas_appid'] ?? '' }}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">赛邮（国外appkey）</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input"  name="submail_overseas_appkey"
                                                value="{{$setting['submail_overseas_appkey'] ?? '' }}" placeholder="">
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label">赛邮（国外模板）</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input"  name="submail_overseas_template"
                                                value="{{$setting['submail_overseas_template'] ?? '' }}" placeholder="">
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                       
                    </div>
                    <!--基础设置开始-->
                    <div class="layui-tab-item">
                        <!-- <div class="layui-form-item">
                            <label class="layui-form-label">成为商家锁定USDT值</label>
                            <div class="layui-input-block">
                                <input type="text" name="tobe_seller_lockusdt" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['tobe_seller_lockusdt'])){{$setting['tobe_seller_lockusdt'] ?? ''}}@endif">
                            </div>
                        </div> -->

                        <!-- <div class="layui-form-item">
                            <label class="layui-form-label">资产兑换手续费百分比</label>
                            <div class="layui-input-block">
                                <input type="text" name="currency_to_usdt_fee" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['currency_to_usdt_fee'])){{$setting['currency_to_usdt_fee'] ?? ''}}@endif">
                            </div>
                        </div> -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">一级代理分佣（%）</label>
                            <div class="layui-input-block">
                                <input type="text" name="recharge_distribution_I" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['recharge_distribution_I'])){{$setting['recharge_distribution_I']}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">二级代理分佣（%）</label>
                            <div class="layui-input-block">
                                <input type="text" name="recharge_distribution_II" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['recharge_distribution_II'])){{$setting['recharge_distribution_II']}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">三级代理分佣（%）</label>
                            <div class="layui-input-block">
                                <input type="text" name="recharge_distribution_III" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['recharge_distribution_III'])){{$setting['recharge_distribution_III']}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">余额宝利率（%）</label>
                            <div class="layui-input-block">
                                <input type="text" name="yubao_linv" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['yubao_linv'])){{$setting['yubao_linv']}}@endif">
                            </div>
                        </div>
                         <div class="layui-form-item">
                            <label class="layui-form-label">币币交易手续费系数</label>
                            <div class="layui-input-block">
                                <input type="text" name="COIN_TRADE_FEE" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['COIN_TRADE_FEE'])){{$setting['COIN_TRADE_FEE']}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">USDT兑换YMT手续费百分比</label>
                            <div class="layui-input-block">
                                <input type="text" name="currency_to_usdt_bmb_fee" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['currency_to_usdt_bmb_fee'])){{$setting['currency_to_usdt_bmb_fee'] ?? ''}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">USDT兑换YMT数量</label>
                            <div class="layui-input-block">
                                <input type="text" name="currency_to_usdt_bmb" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['currency_to_usdt_bmb'])){{$setting['currency_to_usdt_bmb'] ?? ''}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">YMT兑换USDT手续费百分比</label>
                            <div class="layui-input-block">
                                <input type="text" name="currency_to_bmb_usdt_fee" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['currency_to_bmb_usdt_fee'])){{$setting['currency_to_bmb_usdt_fee'] ?? ''}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">YMT兑换USDT数量</label>
                            <div class="layui-input-block">
                                <input type="text" name="currency_to_bmb_usdt" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['currency_to_bmb_usdt'])){{$setting['currency_to_bmb_usdt'] ?? ''}}@endif">
                            </div>
                        </div>
                        <!-- <div class="layui-form-item">
                            <label class="layui-form-label">资产兑换手续费百分比</label>
                            <div class="layui-input-block">
                                <input type="text" name="currency_to_usdt_fee" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['currency_to_usdt_fee'])){{$setting['currency_to_usdt_fee'] ?? ''}}@endif">
                            </div>
                        </div> -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">法币交易一天最大取消次数</label>
                            <div class="layui-input-block">
                                <input type="text" name="userLegalDealCancel" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['userLegalDealCancel'])){{$setting['userLegalDealCancel'] ?? ''}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">法币交易待付款时间分钟数</label>
                            <div class="layui-input-block">
                                <input type="text" name="userLegalDealCancel_time" autocomplete="off" class="layui-input"
                                       value="@if(isset($setting['userLegalDealCancel_time'])){{$setting['userLegalDealCancel_time'] ?? ''}}@endif">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">版本号</label>
                            <div class="layui-input-block">
                                <input type="text" name="version" autocomplete="off" class="layui-input"
                                    value="@if(isset($setting['version'])){{$setting['version'] ?? ''}}@endif">
                            </div>
                        </div>
                        <!-- <div class="layui-form-item">
                            <label class="layui-form-label">PB汇率</label>
                            <div class="layui-input-block">
                                <input type="text" name="ExRate" autocomplete="off" class="layui-input"
                                    value="@if(isset($setting['ExRate'])){{$setting['ExRate']}}@endif">
                            </div>
                        </div> -->
                        <div class="layui-form-item">
                            <label class="layui-form-label">USDT汇率</label>
                            <div class="layui-input-block">
                                <input type="text" name="USDTRate" autocomplete="off" class="layui-input"
                                    value="@if(isset($setting['USDTRate'])){{$setting['USDTRate']}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否开启充提币功能</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="radio" name="is_open_CTbi" value="1" title="打开" @if (isset($setting['is_open_CTbi'])) {{$setting['is_open_CTbi'] == 1 ? 'checked' : ''}} @endif >
                                    <input type="radio" name="is_open_CTbi" value="0" title="关闭" @if (isset($setting['is_open_CTbi'])) {{$setting['is_open_CTbi'] == 0 ? 'checked' : ''}} @else checked @endif >
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">提币时使用链上接口</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="radio" name="use_chain_api" value="1" title="打开" @if (isset($setting['use_chain_api'])) {{$setting['use_chain_api'] == 1 ? 'checked' : ''}} @endif >
                                    <input type="radio" name="use_chain_api" value="0" title="关闭" @if (isset($setting['use_chain_api'])) {{$setting['use_chain_api'] == 0 ? 'checked' : ''}} @else checked @endif >
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">持险生币比例</label>
                            <div class="layui-input-block" style="width: 227px">
                                <input type="text" name="insurance_money_rate " autocomplete="off" class="layui-input"
                                    value="@if(isset($setting['insurance_money_rate'])){{$setting['insurance_money_rate']}}@endif"><span style="position: absolute;right: 5px;top: 12px;">%</span>
                            </div>
                        </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">客服连接</label>
                                <div class="layui-input-block" >
                                    <input type="text" name="customer_service" autocomplete="off" class="layui-input"
                                           value="@if(isset($setting['customer_service'])){{$setting['customer_service']}}@endif">
                                </div>
                            </div>                     <div class="layui-form-item">
                                <label class="layui-form-label">app下载</label>
                                <div class="layui-input-block" >
                                    <input type="text" name="appxiazai" autocomplete="off" class="layui-input"
                                           value="@if(isset($setting['appxiazai'])){{$setting['appxiazai']}}@endif">
                                </div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">充值USDT地址</label>
                                <div class="layui-input-block" >
                                    <input type="text" name="address" autocomplete="off" class="layui-input"
                                           value="@if(isset($setting['address'])){{$setting['address']}}@endif">
                                </div>
                            </div>

                    </div>
                    <!--杠杆设置开始-->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <label class="layui-form-label">交易手数倍数</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <button class="layui-btn layui-btn-sm" type="button" id="handle_multi_set">点击设置</button>
                                </div>
                                <div class="layui-form-mid layui-word-aux">设置交易的手数和倍数</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">止盈止亏功能</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="radio" name="user_set_stopprice" value="1" title="打开" @if (isset($setting['user_set_stopprice'])) {{$setting['user_set_stopprice'] == 1 ? 'checked' : ''}} @endif >
                                    <input type="radio" name="user_set_stopprice" value="0" title="关闭" @if (isset($setting['user_set_stopprice'])) {{$setting['user_set_stopprice'] == 0 ? 'checked' : ''}} @else checked @endif >
                                </div>
                                <div class="layui-form-mid layui-word-aux">打开用户将可以针对交易设置止盈止亏价</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">杠杆交易委托功能</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="radio" name="open_lever_entrust" value="1" title="打开" @if (isset($setting['open_lever_entrust'])) {{$setting['open_lever_entrust'] == 1 ? 'checked' : ''}} @endif >
                                    <input type="radio" name="open_lever_entrust" value="0" title="关闭" @if (isset($setting['open_lever_entrust'])) {{$setting['open_lever_entrust'] == 0 ? 'checked' : ''}} @else checked @endif >
                                </div>
                                <div class="layui-form-mid layui-word-aux">打开后前台可以进行杠杆交易委托,即限价交易</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">爆仓风险率指标</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="text" name="lever_burst_hazard_rate" class="layui-input" value="{{$setting['lever_burst_hazard_rate'] ?? 0 }}" placeholder="杠杆交易风险率达到或低于设定值时爆仓">
                                </div>
                                <div class="layui-form-mid layui-word-aux">%</div>
                                <div class="layui-form-mid layui-word-aux">用户的风险率达到或低于该值时触发爆仓</div>
                            </div>
                        </div>
                        <div class="layui-form-item" hidden>
                            <label class="layui-form-label">赠送虚拟账户</label>
                            <div class="layui-input-block">
                                <input type="text" name="give_virtual_account" autocomplete="off" class="layui-input"
                                    value="@if(isset($setting['give_virtual_account'])){{$setting['give_virtual_account']}}@endif">
                            </div>
                        </div>
                        <div class="layui-form-item" hidden>
                            <label class="layui-form-label">头寸</label>
                            <div class="layui-input-block">
                                <input type="text" name="lever_position" autocomplete="off" class="layui-input" value="@if(isset($setting['lever_position'])){{$setting['lever_position']}}@endif">
                            </div>
                        </div>
                    </div>
                    <!--通证设置开始-->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <label class="layui-form-label">历史盈亏释放比例</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="text" name="profit_loss_release" class="layui-input" value="{{$setting['profit_loss_release'] ?? 0 }}" placeholder="通证兑换USDT比例设置">
                                </div>
                                <div class="layui-form-mid layui-word-aux">千分比例</div>
                                <div class="layui-form-mid layui-word-aux">历史盈亏释放比例</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">通证兑换USDT比例</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="text" name="candy_tousdt" class="layui-input" value="{{$setting['candy_tousdt'] ?? 0 }}" placeholder="通证兑换USDT比例设置">
                                </div>
                                <div class="layui-form-mid layui-word-aux">%</div>
                                <div class="layui-form-mid layui-word-aux">1通证能兑换多少USDT</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">手续费结算比例</label>
                            <div class="layui-input-block">
                            @include('admin.setting.lever_tradefee_settle')
                            </div>
                        </div>
                        
                        <div class="layui-form-item" hidden>
                            <label class="layui-form-label">手续费结算笔数要求</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="text" name="lever_fee_need_trades" class="layui-input" value="{{$setting['lever_fee_need_trades'] ?? ''}}" placeholder="各级手续费结算比例">
                                </div>
                                <div class="layui-form-mid layui-word-aux"></div>
                                <div class="layui-form-mid layui-word-aux">拿手续费的用户自身最低交易笔数,符合条件才能拿到奖励,用英文逗号分隔,例如:1,2,3</div>
                            </div>
                        </div>
                        <div class="layui-form-item" hidden>
                            <label class="layui-form-label">手续费结算各级比例</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="text" name="lever_fee_reward_ratio" class="layui-input" value="{{$setting['lever_fee_reward_ratio'] ?? '' }}" placeholder="各级手续费结算比例">
                                </div>
                                <div class="layui-form-mid layui-word-aux">%</div>
                                <div class="layui-form-mid layui-word-aux">每级比例用英文逗号分隔,例如:8,2,5</div>
                            </div>
                        </div>
                    </div>

                    <!--工作室设置开始-->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <label class="layui-form-label">升级条件:</label>
                            <div class="layui-inline">
                                <label class="layui-form-label" style="padding: 9px 4px; width: unset;">直推实名</label>
                                <div class="layui-input-inline" style="width: 80px">
                                    <input class="layui-input" name="upgrade_atelier_must_has_son" type="text" value="{{$setting['upgrade_atelier_must_has_son'] ?? 0}}" lay-verify placeholder="" >
                                </div>
                                <div class="layui-form-mid">人, </div>
                                <label class="layui-form-label" style="padding: 9px 4px; width: unset;">团队实名</label>
                                <div class="layui-input-inline" style="width: 80px">
                                    <input class="layui-input" name="upgrade_atelier_must_team_son" type="text" value="{{$setting['upgrade_atelier_must_team_son'] ?? 0}}" lay-verify placeholder="" >
                                </div>
                                <div class="layui-form-mid">人, </div>
                                <label class="layui-form-label" style="padding: 9px 4px; width: unset;">团队充值</label>
                                <div class="layui-input-inline" style="width: 80px">
                                    <input class="layui-input" name="upgrade_atelier_must_team_recharge" type="text" value="{{$setting['upgrade_atelier_must_team_recharge'] ?? 0}}" lay-verify placeholder="">
                                </div>
                                <div class="layui-form-mid">USDT</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">奖励条件:</label>
                            <div class="layui-inline">
                                <label class="layui-form-label" style="padding: 9px 4px; width: unset;">每日已开仓交易不低于</label>
                                <div class="layui-input-inline" style="width: 80px">
                                    <input class="layui-input" name="atelier_reward_day_must_trade" type="text" value="{{$setting['atelier_reward_day_must_trade'] ?? 0}}" lay-verify placeholder="" >
                                </div>
                                <div class="layui-form-mid">笔</div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">奖励规则:</label>
                            <div class="layui-inline">
                                <label class="layui-form-label" style="padding: 9px 4px; width: unset;">奖励伞下用户交易手续费</label>
                                <div class="layui-input-inline" style="width: 80px">
                                    <input class="layui-input" name="atelier_reward_ratio" type="text" value="{{$setting['atelier_reward_ratio'] ?? 0}}" lay-verify placeholder="" >
                                </div>
                                <div class="layui-form-mid">%的通证, </div>
                                <label class="layui-form-label" style="padding: 9px 4px; width: unset;">日封顶</label>
                                <div class="layui-input-inline" style="width: 80px">
                                    <input class="layui-input" name="atelier_reward_day_limit" type="text" value="{{$setting['atelier_reward_day_limit'] ?? 0}}" lay-verify placeholder="" >
                                </div>
                                <div class="layui-form-mid"></div>
                            </div>
                            
                            
                        </div>
                    </div>
                    <!--通证奖励规则设置开始-->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <label class="layui-form-label">是否开启通证转账功能</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline">
                                    <input type="radio" name="is_open_transfer_candy" value="1" title="打开" @if (isset($setting['is_open_transfer_candy'])) {{$setting['is_open_transfer_candy'] == 1 ? 'checked' : ''}} @endif >
                                    <input type="radio" name="is_open_transfer_candy" value="0" title="关闭" @if (isset($setting['is_open_transfer_candy'])) {{$setting['is_open_transfer_candy'] == 0 ? 'checked' : ''}} @else checked @endif >
                                </div>
                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">通证转账收取卖家手续费百分比</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="1" placeholder="" name="transfer_candy_rate" type="text" onkeyup="" value="@if(isset($setting['transfer_candy_rate'])){{$setting['transfer_candy_rate'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item ecology">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">通证转账最小值</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="1" placeholder="" name="transfer_candy_min" type="text" onkeyup="" value="@if(isset($setting['transfer_candy_min'])){{$setting['transfer_candy_min'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item ecology">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">通证转账最大值</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="1" placeholder="" name="transfer_candy_max" type="text" onkeyup="" value="@if(isset($setting['transfer_candy_max'])){{$setting['transfer_candy_max'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item ecology">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">实名认证送通证数量</label>
                                        <div class="layui-input-inline">
                                            <input class="layui-input" lay-verify="1" placeholder="" name="real_name_candy" type="text" onkeyup="" value="@if(isset($setting['real_name_candy'])){{$setting['real_name_candy'] ?? ''}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="layui-form-item ecology">
                                </div>
                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">直推</label>
                                        <div class="layui-input-inline" style="width: 50px;">
                                            <input class="layui-input" lay-verify="required" placeholder="直推" name="zhitui2_number" type="text" value="@if(isset($setting['zhitui2_number'])){{$setting['zhitui2_number'] ?? '' }}@endif"><span style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 50px;">人实名,</label>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label" style="width: 44px;">送通证</label>
                                        <div class="layui-input-inline">
                                            <input style="width: 100px;" class="layui-input" lay-verify="required" placeholder="" name="zhitui2_candy"
                                                   value="@if(isset($setting['zhitui2_candy'])){{$setting['zhitui2_candy']  ?? '' }}@endif">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">直推</label>
                                        <div class="layui-input-inline" style="width: 50px;">
                                            <input class="layui-input" lay-verify="required" placeholder="直推"
                                                   name="zhitui3_number" type="text"
                                                   value="@if(isset($setting['zhitui3_number'])){{$setting['zhitui3_number'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人实名,实名团队</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="实名团队"
                                                   name="zhitui3_real_teamnumber" type="text"
                                                   value="@if(isset($setting['zhitui3_real_teamnumber'])){{$setting['zhitui3_real_teamnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人,充值美金达到</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="充值美金"
                                                   name="zhitui3_top_upnumber" type="text"
                                                   value="@if(isset($setting['zhitui3_top_upnumber'])){{$setting['zhitui3_top_upnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label" style="width: 44px;">送通证</label>
                                        <div class="layui-input-inline">
                                            <input style="width: 100px;" class="layui-input" lay-verify="required" placeholder="" name="zhitui3_candy"
                                                   value="@if(isset($setting['zhitui3_candy'])){{$setting['zhitui3_candy']  ?? '' }}@endif">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">直推</label>
                                        <div class="layui-input-inline" style="width: 50px;">
                                            <input class="layui-input" lay-verify="required" placeholder="直推"
                                                   name="zhitui4_number" type="text"
                                                   value="@if(isset($setting['zhitui4_number'])){{$setting['zhitui4_number'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人实名,实名团队</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="实名团队"
                                                   name="zhitui4_real_teamnumber" type="text"
                                                   value="@if(isset($setting['zhitui4_real_teamnumber'])){{$setting['zhitui4_real_teamnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人,充值美金达到</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="充值美金"
                                                   name="zhitui4_top_upnumber" type="text"
                                                   value="@if(isset($setting['zhitui4_top_upnumber'])){{$setting['zhitui4_top_upnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label" style="width: 44px;">送通证</label>
                                        <div class="layui-input-inline">
                                            <input style="width: 100px;" class="layui-input" lay-verify="required" placeholder="" name="zhitui4_candy"
                                                   value="@if(isset($setting['zhitui4_candy'])){{$setting['zhitui4_candy']  ?? '' }}@endif">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">直推</label>
                                        <div class="layui-input-inline" style="width: 50px;">
                                            <input class="layui-input" lay-verify="required" placeholder="直推"
                                                   name="zhitui5_number" type="text"
                                                   value="@if(isset($setting['zhitui5_number'])){{$setting['zhitui5_number'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人实名,实名团队</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="实名团队"
                                                   name="zhitui5_real_teamnumber" type="text"
                                                   value="@if(isset($setting['zhitui5_real_teamnumber'])){{$setting['zhitui5_real_teamnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人,充值美金达到</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="充值美金"
                                                   name="zhitui5_top_upnumber" type="text"
                                                   value="@if(isset($setting['zhitui5_top_upnumber'])){{$setting['zhitui5_top_upnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label" style="width: 44px;">送通证</label>
                                        <div class="layui-input-inline">
                                            <input style="width: 100px;" class="layui-input" lay-verify="required" placeholder="" name="zhitui5_candy"
                                                   value="@if(isset($setting['zhitui5_candy'])){{$setting['zhitui5_candy']  ?? '' }}@endif">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div>
                            <div>
                                <div class="layui-form-item ecology">
                                    <div class="layui-inline">
                                        <label class="layui-form-label">直推</label>
                                        <div class="layui-input-inline" style="width: 50px;">
                                            <input class="layui-input" lay-verify="required" placeholder="直推"
                                                   name="zhitui6_number" type="text"
                                                   value="@if(isset($setting['zhitui6_number'])){{$setting['zhitui6_number'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人实名,实名团队</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="实名团队"
                                                   name="zhitui6_real_teamnumber" type="text"
                                                   value="@if(isset($setting['zhitui6_real_teamnumber'])){{$setting['zhitui6_real_teamnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                        <label class="layui-form-label" style="width: 110px;">人,充值美金达到</label>
                                        <div class="layui-input-inline" style="width: 80px;">
                                            <input class="layui-input" lay-verify="required" placeholder="充值美金"
                                                   name="zhitui6_top_upnumber" type="text"
                                                   value="@if(isset($setting['zhitui6_top_upnumber'])){{$setting['zhitui6_top_upnumber'] ?? '' }}@endif"><span
                                                    style="position: absolute;right: 5px;top: 12px;"></span>
                                        </div>
                                    </div>
                                    <div class="layui-inline">
                                        <label class="layui-form-label" style="width: 44px;">送通证</label>
                                        <div class="layui-input-inline">
                                            <input style="width: 100px;" class="layui-input" lay-verify="required" placeholder="" name="zhitui6_candy"
                                                   value="@if(isset($setting['zhitui6_candy'])){{$setting['zhitui6_candy']  ?? '' }}@endif">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!--合约风控设置开始-->
                    <div class="layui-tab-item">
                        <div class="layui-form-item">
                            <label class="layui-form-label">风控参数</label>
                            <div class="layui-input-block">
                                <div class="layui-inline btn-group layui-btn-group">
                                    <button class="layui-btn layui-btn-primary cateManage" type="button" id="add_seconds"><i class="layui-icon layui-icon-log"></i>秒数设置</button>
                                    <button class="layui-btn layui-btn-primary" type="button" id="add_number"><i class="layui-icon layui-icon-about"></i>数量设置</button>
                                    <button class="layui-btn layui-btn-primary" type="button" id="user"> <i class="layui-icon layui-icon-username"></i> 用户管理</button>
                                    <button class="layui-btn layui-btn-primary" type="button" id="currency_risk"> <i class="layui-icon layui-icon-dollar"></i>币种风控</button>
                                    <button class="layui-btn layui-btn-primary" type="button" id="second_trade"> <i class="layui-icon layui-icon-form"></i>秒合约交易</button>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">风控类型</label>
                            <div class="layui-input-block">
                                <select name="risk_mode" lay-verify="required" lay-filter="risk_mode">
                                    <option value=""></option>
                                    <option value="0" {{ ($setting['risk_mode']?? 0) == 0 ? 'selected' : '' }} >无</option>
                                    <option value="1" {{ ($setting['risk_mode']?? 0) == 1 ? 'selected' : '' }} >用户点控</option>
                                    <option value="2" {{ ($setting['risk_mode']?? 0) == 2 ? 'selected' : '' }} >全局群控</option>
                                    <option value="3" {{ ($setting['risk_mode']?? 0) == 3 ? 'selected' : '' }} >金额控</option>
                                    <option value="4" {{ ($setting['risk_mode']?? 0) == 4 ? 'selected' : '' }} >交易单控</option>
                                    <option value="5" {{ ($setting['risk_mode']?? 0) == 5 ? 'selected' : '' }} >币种群控</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">风控提前影响秒数</label>
                            <div class="layui-input-block">
                                <input type="text" name="risk_end_ago_max" required  lay-verify="required" placeholder="平仓前多少秒开始被风控影响" autocomplete="off" class="layui-input" value="{{ $setting['risk_end_ago_max'] ?? 0}}">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">概率控</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="risk_probability_switch" value="0" title="关闭" {{($setting['risk_probability_switch'] ?? 0) == 0 ? 'checked' : ''}} >
                                <input type="radio" name="risk_probability_switch" value="1" title="开启" {{($setting['risk_probability_switch'] ?? 0) == 1 ? 'checked' : ''}} >
                            </div>
                            <div class="layui-form-mid layui-word-aux">作为一种补充,不受风控总开关影响</div>
                        </div>

                        <div class="layui-form-item layui-form-text" id="risk_profit_probability">
                            <label class="layui-form-label">盈利概率</label>
                            <div class="layui-input-inline">
                                <input class="layui-input" type="text" name="risk_profit_probability" placeholder="" value="{{$setting['risk_profit_probability'] ?? ''}}">
                            </div>
                            <div class="layui-form-mid layui-word-aux">%</div>
                            <div class="layui-form-mid layui-word-aux">不能超过100%</div>
                        </div>

                        <div class="layui-form-item" {{ ($setting['risk_mode'] ?? 0) == 2 ? '' : 'hidden'}} id="risk_group_result">
                            <label class="layui-form-label">群控结果</label>
                            <div class="layui-input-block">
                                <input type="radio" name="risk_group_result" value="1" title="盈利" {{($setting['risk_group_result'] ?? 1) == 1 ? 'checked' : ''}} >
                                <input type="radio" name="risk_group_result" value="-1" title="亏损" {{($setting['risk_group_result'] ?? -1) == -1 ? 'checked' : ''}}>
                            </div>
                        </div>

                        <div class="layui-form-item layui-form-text" {{ ($setting['risk_mode'] ?? 0) == 3 ? '' : 'hidden'}}  id="risk_money_profit_probability">
                            <label class="layui-form-label">金额概率设置</label>
                            <div class="layui-input-block">
                                <textarea class="layui-textarea" name="risk_money_profit_probability" placeholder="格式:最小值-最大值:盈利概率,用|线分隔">{{$setting['risk_money_profit_probability'] ?? ''}}</textarea>
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">交易保险</label>
                            <div class="layui-input-block">
                                <div class="layui-inline btn-group layui-btn-group">
                                    <button class="layui-btn layui-btn-primary" type="button" id="insurance_setup"><i class="layui-icon layui-icon-password"></i>规则设置</button>
                                </div>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">受保时间</label>
                            <div class="layui-input-block">
                                <div class="layui-input-inline" style="width:100px;">
                                    <input type="text" class="layui-input layui-date" name="insurance_start" value="{{$setting['insurance_start']}}" placeholder="开始时间" >
                                </div>
                                <div class="layui-form-mid layui-word-aux">至</div>
                                <div class="layui-input-inline" style="width:100px;">
                                    <input type="text" class="layui-input layui-date" name="insurance_end" value="{{$setting['insurance_end']}}" placeholder="结束时间">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            
            <div class="layui-form-item">
                    <div class="layui-input-block">
                        <button class="layui-btn" lay-submit lay-filter="website_submit">立即提交</button>
                        <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                    </div>
            </div>
        </form>
    </div>
@stop
@section('scripts')
    <script type="text/javascript">
        layui.use(['element', 'form', 'upload', 'layer', 'laydate'], function () {
            var element = layui.element
                ,layer = layui.layer
                ,form = layui.form
                ,laydate = layui.laydate
                ,$ = layui.$;
            $('#handle_multi_set').click(function () {
                layer.open({
                    type: 2
                    ,title: '杠杆交易手数和倍数设置'
                    ,content: '/admin/levermultiple/index'
                    ,area: ['600px', '430px']
                    ,id: 99
                });
            });
            $('#add_seconds').click(function() {
                layer.open({
                    type: 2
                    ,title: '秒数设置'
                    ,content: '/admin/micro_seconds_index'
                    ,area: ['600px', '430px']
                    ,id: 100
                });
            });
            $('#add_number').click(function() {
                layer.open({
                    type: 2
                    ,title: '数量设置'
                    ,content: '/admin/micro_number_index'
                    ,area: ['600px', '430px']
                    ,id: 101
                });
            });
            $('#user').click(function () {
                parent.winui.window.open({
                    id: 'user_index'
                    ,type: 2
                    ,title: '用户管理'
                    ,content: '/admin/user/user_index'
                });
            });
            $('#currency_risk').click(function () {
                parent.layer.open({
                    type: 2
                    ,title: '币种风控'
                    ,content: '/admin/currency/micro_match'
                    ,area: ['640px', '500px']
                    ,id: 103
                    ,maxmin: true
                    ,zIndex: parent.layer.zIndex
                });
            });
            $('#second_trade').click(function () {
                parent.winui.window.open({
                    id: 'second_trade'  
                    ,type: 2
                    ,title: '秒合约交易'
                    ,content: '/admin/micro_order'
                });
            });
            form.on('submit(website_submit)', function (data) {
                var data = data.field;
                $.ajax({
                    url: '/admin/setting/postadd',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function (res) {
                        layer.msg(res.message);
                    }
                });
                return false;
            });
            form.on('select(risk_mode)', function (data) {
                if (data.value == 2) {
                    $('#risk_group_result').removeAttr('hidden');
                } else {
                    $('#risk_group_result').attr('hidden', true);
                }
                if (data.value == 3) {
                    $('#risk_money_profit_probability').removeAttr('hidden');
                } else {
                    $('#risk_money_profit_probability').attr('hidden', true);
                }
            });
            $('.layui-date').each(function (index, element) {
                //console.log(element)
                laydate.render({ 
                    elem: element
                    ,type: 'time'
                    ,format: 'HH:mm'
                });
            });

            $('#insurance_setup').click(function () {
                parent.layer.open({
                    type: 2
                    ,title: '保险规则'
                    ,content: '/admin/insurance_rules_index'
                    ,area: ['800px', '500px']
                    ,id: 103
                    ,maxmin: true
                    ,zIndex: parent.layer.zIndex
                });
            });
            
            var template = `
                <tr>
                    <td>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 90px;">
                                <input class="layui-input" name="generation[]" value="" required lay-verify="required">
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="layui-inline">
                            <div class="layui-input-inline" style="width: 90px;">
                                <input class="layui-input" name="reward_ratio[]" value="" required lay-verify="required">
                            </div>
                            <div class="layui-form-mid">
                                <span>%</span></div>
                            </div>
                        </td>
                        <td>
                            <div class="layui-input-inline" style="width: 90px;">
                                <input class="layui-input" name="need_has_trades[]" value="" required lay-verify="required">
                            </div>
                        </td>
                        <td>
                            <div class="layui-input-inline">
                            <button class="layui-btn layui-btn-sm layui-btn-danger" type="button" lay-event="del">删除</button>
                            </div>
                    </td>
                </tr>`;
            $('#addLeverTradeOption').click(function () {
                $('#leverTradeFeeOption').append(template);
            });
            $('#leverTradeFeeOption').on('click', 'button[lay-event]', function () {
                var that = this
                    ,event = $(this).attr('lay-event')
                if (event == 'del') {
                    layer.confirm('真的确定要删除吗?' , {
                        title: '删除确认'
                        ,icon: 3
                    }, function (index) {
                        $(that).parent().parent().parent().remove();
                        layer.close(index);
                    });
                }
            });
        });
    </script>
@stop