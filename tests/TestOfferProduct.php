<?php
namespace Tests;

use App\Currency;
use App\OfferProduct;
use App\Service\RedisService;
use App\UsersWallet;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use function GuzzleHttp\Psr7\str;

class TestOfferProduct extends BaseTestCase
{
    use CreatesApplication;

    public function testGetNewTimeData()
    {
//        echo date('Y-m-d H:i') . ':00';die;
        $redis = \Illuminate\Support\Facades\Redis::connection();
        //$redis = RedisService::getInstance();
        $id = 1;
        $time = strtotime('2021-10-10 13:23:00');
        $minute = date('YmdHi', $time);
        $yesteDayMinute = date('YmdHi', $time - 3600 * 24);
        $searchMinute = date('Y-m-d H:i', $time) . ':00';
        $yestedaySearchMinute = date('Y-m-d H:i:s', strtotime($searchMinute) - 3600*24);

        $todayCacheKey = 'offer_product_data_' . $minute . '_' . $id;
        $yestedayCacheKey = 'offer_product_data_' . $yesteDayMinute . '_' . $id;
        $currentDataString = $redis->get($todayCacheKey);
        $yesteDayDataString = $redis->get($todayCacheKey);

        if(empty($currentDataString)){
            $getCurrentMinuteData = DB::table('offer_product_increase_record')
                ->where('obp_id', $id)
                ->where('minute', '=', $searchMinute)->get()->first();
            if(! $getCurrentMinuteData){
                throw new \Exception('current data not found.');
            }
            $setCacheResult = $redis->set($todayCacheKey, json_encode($getCurrentMinuteData, JSON_UNESCAPED_UNICODE));
            $redis->expire($todayCacheKey, 90000);
            if(! $setCacheResult)
            {
                throw new \Exception('set redis fail.');
            }
            $currentDataString = json_encode($getCurrentMinuteData, JSON_UNESCAPED_UNICODE);
        }
        if(empty($yesteDayDataString)){
            $getYesteDayMinuteData = DB::table('offer_product_increase_record')
                ->where('obp_id', $id)
                ->where('minute', '=', $yestedaySearchMinute)->get()->first();
            if(! $getYesteDayMinuteData){
                throw new \Exception('yesteday data not found.');
            }
            $setCacheResult = $redis->set($yestedayCacheKey, json_encode($getYesteDayMinuteData, JSON_UNESCAPED_UNICODE));
            $redis->expire($yestedayCacheKey, 3600);
            if(! $setCacheResult)
            {
                throw new \Exception('set redis fail.');
            }
            $yesteDayDataString = json_encode($getYesteDayMinuteData, JSON_UNESCAPED_UNICODE);
        }

        $currentData = json_decode($currentDataString, true);
        $yesteDayData = json_decode($yesteDayDataString, true);



        $nowPrice = $this->subPrice(rand($currentData['lowest_price'] * 10000000000
                , $currentData['highest_price'] * 10000000000) / 10000000000);
        $nowPrice = $this->numberAddSubRand($nowPrice, $currentData['open_price'] < $currentData['close_price'] ? true : false);
        // 涨幅百分比
        $proportion = $this->numberAddSubRand(round(abs(($currentData['open_price'] - $yesteDayData['close_price']) / $yesteDayData['close_price']) * 100, 2), $nowPrice > $yesteDayData['close_price']);
        $price1 = $this->numberAddSubRand($this->subPrice( rand($currentData['lowest_price'] * 10000000000, $currentData['highest_price'] * 10000000000) / 10000000000), $nowPrice > $yesteDayData['close_price']);
        $price3 = $this->numberAddSubRand($this->subPrice(rand($currentData['lowest_price'] * 10000000000, $currentData['highest_price'] * 10000000000) / 10000000000), $nowPrice > $yesteDayData['close_price']);
        list($highestPrice, $nowPrice, $lowestPrice) = $this->getSortList([$price1, $nowPrice, $price3]);

        $result = [
            'now_price' => $nowPrice,
            'proportion' => $proportion,
            'highest_price' => $highestPrice,
            'lowest_price' => $lowestPrice,
        ];

        var_dump($result);

    }

    public function testProportion(){
        $cbv = OfferProduct::getProductById(1);
        $cbv->change = 1;
        var_dump($cbv->change);
        die;
        $time = time();
        $minute = date('YmdHi', $time);
        $yesteDayMinute = date('YmdHi', $time - 3600 * 24);
        $searchMinute = date('Y-m-d H:i', $time) . ':00';
        $yestedaySearchMinute = date('Y-m-d H:i:s', strtotime($searchMinute) - 3600*24);
        echo $searchMinute;
        echo $yestedaySearchMinute;die;

        echo substr($this->numberAddSubRand(round(abs((0.0002251520 - 0.0001663320) / 0.0001663320) * 100, 2), 0.0002251520 > 0.0001663320), 0, 5);
    }

    private function getSortList($arr)
    {
        rsort($arr);
        return $arr;
    }


    /**
     * 剪切价格字段到理想的位数
     * @param $number
     * @return false|string
     */
    public function subPrice($number)
    {
        //$number = "10.0000234100100";
        $beforeInt = substr($number,0, strrpos($number,"."));
        if($beforeInt > 0){
            return substr($number, 0, 9);
        }else{
            $afterNumber = substr($number,strripos($number,".")+1);
            $afterInt = 0;
            for ($i = 0; $i < strlen($afterNumber); $i++){
                if($afterNumber[$i] != 0){
                    $afterInt = $i;
                    break;
                }
            }
            $subLength = 8;
            switch ($afterInt){
                case 8:
                    $subLength = 8;
                    break;
                case 7:
                    $subLength = 8;
                    break;
                case 6:
                    $subLength = 8;
                    break;
                case 5:
                    $subLength = 8;
                    break;
                case 4:
                    $subLength = 8;
                    break;
                case 3:
                    $subLength = 7;
                    break;
                case 2:
                    $subLength = 7;
                    break;
                case 1:
                    $subLength = 6;
                    break;
            }
            return substr($number, 0, $subLength + 2);
        }
    }

    /**
     * 随机在最尾数涨幅
     * @param $number
     */
    public function numberAddSubRand($number, $zf){
        //$number = "10.000023";
        //$number = 10.01;
//        $zf = true;
        $randNum = rand(0, 3);
        $afterNumber = substr($number,strripos($number,".")+1);
        if($zf) {
            return $this->convert_scientific_number_to_normal($number + pow(10, strlen($afterNumber) * -1) * $randNum);
        }else{
            return $this->convert_scientific_number_to_normal($number - pow(10, strlen($afterNumber) * -1) * $randNum);
        }
    }


    /**
     * 将科学计数法的数字转换为正常的数字
     * 为了将数字处理完美一些，使用部分正则是可以接受的
     * @author loveyu
     * @param string $number
     * @return string
     */
    function convert_scientific_number_to_normal($number)

    {
        if(stripos($number, 'e') === false) {
            //判断是否为科学计数法
            return $number;
        }

        if(!preg_match(
            "/^([\\d.]+)[eE]([\\d\\-\\+]+)$/",
            str_replace(array(" ", ","), "", trim($number)), $matches)
        ) {
            //提取科学计数法中有效的数据，无法处理则直接返回
            return $number;
        }

        //对数字前后的0和点进行处理，防止数据干扰，实际上正确的科学计数法没有这个问题

        $data = preg_replace(array("/^[0]+/"), "", rtrim($matches[1], "0."));
        $length = (int)$matches[2];
        if($data[0] == ".") {
            //由于最前面的0可能被替换掉了，这里是小数要将0补齐
            $data = "0{$data}";
        }

        //这里有一种特殊可能，无需处理
        if($length == 0) {
            return $data;

        }

        //记住当前小数点的位置，用于判断左右移动
        $dot_position = strpos($data, ".");
        if($dot_position === false) {
            $dot_position = strlen($data);

        }

        //正式数据处理中，是不需要点号的，最后输出时会添加上去
        $data = str_replace(".", "", $data);
        if($length > 0) {
            //如果科学计数长度大于0
            //获取要添加0的个数，并在数据后面补充
            $repeat_length = $length - (strlen($data) - $dot_position);
            if($repeat_length > 0) {
                $data .= str_repeat('0', $repeat_length);
            }

            //小数点向后移n位
            $dot_position += $length;
            $data = ltrim(substr($data, 0, $dot_position), "0").".".substr($data, $dot_position);
        } elseif($length < 0) {
            //当前是一个负数
            //获取要重复的0的个数
            $repeat_length = abs($length) - $dot_position;
            if($repeat_length > 0) {
                //这里的值可能是小于0的数，由于小数点过长
                $data = str_repeat('0', $repeat_length).$data;
            }

            $dot_position += $length;//此处length为负数，直接操作
            if($dot_position < 1) {
                //补充数据处理，如果当前位置小于0则表示无需处理，直接补小数点即可
                $data = ".{$data}";
            } else {
                $data = substr($data, 0, $dot_position).".".substr($data, $dot_position);
            }
        }

        if($data[0] == ".") {
            //数据补0
            $data = "0{$data}";
        }

        return trim($data, ".");
    }

    public function testgetWalletList(){
        $user_id = 1208;
        $currencies = Currency::select('*', DB::raw('0 as is_legal'))->where('is_display', 1)->orderBy('sort', 'desc')->get();
        $currencies = $currencies->filter(function ($item, $key) {
            $sum = array_sum([$item->is_legal, $item->is_lever, $item->is_match, $item->is_micro]);
            return $sum > 1;
        })->values();
        $currencies->transform(function ($item, $key) use ($user_id) {
            $wallet = UsersWallet::where('user_id', $user_id)->where('currency', $item->id)->first();
            $item->setVisible(['id', 'name', 'is_legal', 'is_lever', 'is_match', 'is_micro', 'wallet']);
            return $item->setAttribute('wallet', $wallet);
        });

        var_dump($currencies);
    }

}