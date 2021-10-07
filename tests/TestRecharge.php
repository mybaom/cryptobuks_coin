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

    public function testGetDays()
    {
        $current_date = date('Y-m-d', time()).' 00:00:00';
        $days = [];
        $months = [];
        for ($i = 30; $i >= 0; $i--)
        {
            $days[] = date('Y-m-d H:i:s' , strtotime("$current_date - $i day"));
        }
        for ($i = 12; $i >= 0; $i--)
        {
            $months[] = date('Y-m-d H:i:s' , strtotime("$current_date - $i month"));
        }
        $datas = [
            'days'  => $days,
            'weeks' => [
                date('Y-m-d H:i:s' , strtotime("$current_date - 5 week")),
                date('Y-m-d H:i:s' , strtotime("$current_date - 4 week")),
                date('Y-m-d H:i:s' , strtotime("$current_date - 3 week")),
                date('Y-m-d H:i:s' , strtotime("$current_date - 2 week")),
                date('Y-m-d H:i:s' , strtotime("$current_date - 1 week")),
                date('Y-m-d H:i:s' , strtotime("$current_date - 0 week")),
            ],
            'months' => $months
        ];

        var_dump($datas['days']);
        $getDbData = DB::table('offer_product_increase_record')
            ->select(
                'minute',
                DB::raw('FORMAT(open_price, 8) as open_price'),
                DB::raw('FORMAT(close_price, 8) as close_price'),
                DB::raw('FORMAT(lowest_price, 8) as lowest_price'),
                DB::raw('FORMAT(highest_price, 8) as highest_price')
            )

            ->where('obp_id', 1)
            ->where('time_type', 1)
            ->whereIn('minute', $datas['days'])
            ->get()
            ->toArray();

        var_dump($getDbData);
    }

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