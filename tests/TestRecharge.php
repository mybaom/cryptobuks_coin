<?php
namespace Tests;

use App\Agent;
use App\Setting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class TestRecharge extends BaseTestCase
{
    use CreatesApplication;

    public function testRechange(){
        $getRechargeAddress = [
            'btc' => Setting::getValueByKey('recharge_btc_address', ''),
            'eth' => Setting::getValueByKey('recharge_eth_address', ''),
            'usdt' => Setting::getValueByKey('recharge_usdt_address', '')
        ];

        var_dump($getRechargeAddress);
        //Agent::getAgentId();
    }

    public function testa(){
        echo floor(1695/10)*10 . "\n";
    }


    public function testgetOfferProductHQ()
    {
        $advanceMin = 2; // 提前预设两分钟后的数据
        $currentMin = date('i', time() + 60 * $advanceMin);
        $isOneMin = true;
        $isFiveMin = ($currentMin % 5) == 0 ? true : false;
        $isFifteenMin = ($currentMin % 15) == 0 ? true : false;
        $isThirtyMin = ($currentMin % 30) == 0 ? true : false;
        $isHourMin = ($currentMin % 60) == 0 ? true : false;

        var_dump($currentMin. " 1分钟：$isOneMin 5分钟：$isFiveMin 15分钟：$isFifteenMin 30分钟：$isThirtyMin 1小时：$isHourMin");
    }



}