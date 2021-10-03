<?php
namespace Tests;

use App\Agent;
use App\Setting;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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

    public function testd()
    {

        echo substr('2021-10-10 10:02:01', 5, 11);die;


        try {
            $id = 1;
            $startDate = date('Y-m-d H:i') . ':00';
            $endDate = date('Y-m-d H:i', time() - (3600 * 24 * 3)) . ':00';
            $getDbData = DB::table('offer_product_increase_record')
                ->where('obp_id', $id)
                ->where('time_type', 1)
                ->where('minute', '<=', $startDate)
                ->where('minute', '>=', $endDate)
                ->get()->toArray();
            $result = new \stdClass();
            $result->min1 = new \stdClass();
            $result->min5 = new \stdClass();
            $result->min15 = new \stdClass();
            $result->min30 = new \stdClass();
            $result->hour = new \stdClass();
            foreach ($getDbData as $k => $v) {
                $result->min1->date[] = $v->minute;
                $result->min1->data[] = [$v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                $min = date('i', strtotime($v->minute));
                if (($min % 5) == 0) {
                    $result->min5->date[] = $v->minute;
                    $result->min5->data[] = [$v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 15) == 0) {
                    $result->min15->date[] = $v->minute;
                    $result->min15->data[] = [$v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 30) == 0) {
                    $result->min30->date[] = $v->minute;
                    $result->min30->data[] = [$v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
                if (($min % 60) == 0) {
                    $result->hour->date[] = $v->minute;
                    $result->hour->data[] = [$v->open_price, $v->close_price, $v->lowest_price, $v->highest_price];
                }
            }
        }catch (\Exception $e){
            echo $e->getMessage();
        }

        var_dump($result);
    }



}