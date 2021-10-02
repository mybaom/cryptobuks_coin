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

}