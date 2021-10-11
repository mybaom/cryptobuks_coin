<?php

namespace App\Http\Controllers\Api;

use App\OfferProduct;
use App\Transaction;
use App\Users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class OfferProductController extends Controller
{

    public function getOfferProducts()
    {
        try {
            $result = OfferProduct::getProductList();
            return $this->success($result);
        }catch (\Exception $e)
        {
            return $this->success($e->getMessage());
        }
    }

    public function getOfferProductData()
    {
        try {
            $id = Input::get("id");
            $date = Input::get("date");
            $startDate = date('Y-m-d H:i') . ':00';
            // 如果有传日期过来则用传过来的日期
            $endDate = $date ? $date : date('Y-m-d H:i', time() - (3600 * 24 * 3)) . ':00';
            $endDateSymbol = $date ? '>' : '>=';
            $info = OfferProduct::select('*', DB::raw('FORMAT(now_price, 8) as now_price'), DB::raw('if(rise_fall_probability > 50, "1", "0") as rise_fall_symbol'), DB::raw('format((`max_increase` + `min_increase`)/2 * `rise_fall_probability`/100, 2) as rise_fall_probability'))->find($id);
            $getDbData = DB::table('offer_product_increase_record')
                ->select(
                    'minute',
                    DB::raw('FORMAT(open_price, 8) as open_price'),
                    DB::raw('FORMAT(close_price, 8) as close_price'),
                    DB::raw('FORMAT(lowest_price, 8) as lowest_price'),
                    DB::raw('FORMAT(highest_price, 8) as highest_price'),
                    'volume'
                )

                ->where('obp_id', $id)
                ->where('time_type', 1)
                ->where('minute', '<=', $startDate)
                ->where('minute', $endDateSymbol, $endDate)
                ->get()->toArray();

            $result = new \stdClass();
            $result->info = new \stdClass();
            $result->info->name = $info->name ?? '';
            $result->info->now_price = $info->now_price ?? '';
            $result->info->rise_fall_symbol = $info->rise_fall_symbol ?? 0;
            $result->info->rise_fall_probability = $info->rise_fall_probability;
            $result->min1 = new \stdClass();
            $result->min5 = new \stdClass();
            $result->min15 = new \stdClass();
            $result->min30 = new \stdClass();
            $result->hour = new \stdClass();
            $days = $this->getDates();
            $result->day = $this->getOfferProductDataByDays($id, $days['days']);
            $result->week = $this->getOfferProductDataByDays($id, $days['weeks']);
            $result->month = $this->getOfferProductDataByDays($id, $days['months']);
            foreach ($getDbData as $k => $v) {
                $simpleMin = substr($v->minute, 5, 11);

                $result->min1->date[] = $simpleMin;
                $result->min1->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price, $v->volume];
                $min = date('i', strtotime($v->minute));
                if (($min % 5) == 0) {
                    $result->min5->date[] = $simpleMin;
                    $result->min5->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price, $v->volume];
                }
                if (($min % 15) == 0) {
                    $result->min15->date[] = $simpleMin;
                    $result->min15->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price, $v->volume];
                }
                if (($min % 30) == 0) {
                    $result->min30->date[] = $simpleMin;
                    $result->min30->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price, $v->volume];
                }
                if (($min % 60) == 0) {
                    $result->hour->date[] = $simpleMin;
                    $result->hour->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price, $v->volume];
                }
            }
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        return $this->success($result);
    }

    public function getOfferProductDataByDays($id, $days)
    {
        $getDbData = DB::table('offer_product_increase_record')
            ->select(
                'minute',
                DB::raw('FORMAT(open_price, 8) as open_price'),
                DB::raw('FORMAT(close_price, 8) as close_price'),
                DB::raw('FORMAT(lowest_price, 8) as lowest_price'),
                DB::raw('FORMAT(highest_price, 8) as highest_price'),
                'volume'
            )

            ->where('obp_id', $id)
            ->where('time_type', 1)
            ->whereIn('minute', $days)
            ->get()
            ->toArray();

        $result = new \stdClass();
        foreach ($getDbData as $k => $v) {
            $simpleMin = substr($v->minute, 5, 11);
            $result->date[] = $simpleMin;
            $result->data[] = [$v->minute, $v->open_price, $v->close_price, $v->lowest_price, $v->highest_price, $v->volume];
        }

        return $result;

    }

    public function postOfferOrder()
    {
        try {
            $id = Input::get('id', 0);
            $currencyId = Input::get('currency_id', 0);
            $number = Input::get('number', 0);
            $totalPrice = Input::get('zj', 0);
            $balance = Input::get('ye', 0);
            $hznum = Input::get('hznum', 0);
            $nowPrice = Input::get('now_price', 0);
            $productInfo = DB::table('offer_buy_product')->where('id', $id)->where('status', 1)->first();
            if (!$productInfo) {
                throw new \Exception('product not found.');
            }
            $currencyInfo = DB::table('currency')->where('id', $currencyId)->first();
            if (!$currencyInfo) {
                throw new \Exception('currency not found.');
            }
            $user_id = Users::getUserId();
            $usersWalletInfo = DB::table('users_wallet')->where('user_id', $user_id)->where('currency', $currencyId)->first();
            if (!$usersWalletInfo) {
                throw new \Exception('wallet not found.');
            }
            if ($usersWalletInfo->change_balance < $hznum) {
                throw new \Exception('Sorry, your credit is running low.');
            }
            // 查询认购钱包是否存在
            $offerProductWallet = DB::table('offer_product_wallet')->where('user_id', $user_id)->where('obp_id', $id)->first();
            if (!$offerProductWallet) {
                $createWalletResult = DB::table('offer_product_wallet')->insert([
                    'obp_id' => $id,
                    'user_id' => $user_id,
                ]);
                if (! $createWalletResult) {
                    throw new \Exception('create wallet fail.');
                }
            }
        }catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        try {
            DB::beginTransaction();
            // 扣除币币账户内的余额
            $walletDecrement = DB::table('users_wallet')
                ->where('user_id', $user_id)
                ->where('currency', $currencyId)
                ->where('change_balance', '>=', $hznum)
                ->decrement('change_balance',$hznum);
            if(! $walletDecrement)
            {
                throw new \Exception('Account transfer failed.');
            }

            // 增加认购账户余额
            $offerWalletIncrement = DB::table('offer_product_wallet')
                ->where('user_id', $user_id)
                ->where('obp_id', $id)
                ->increment('balance', $number);
            if(! $offerWalletIncrement)
            {
                throw new \Exception('offer wallet Account transfer failed.');
            }
            // 增加认购记录
            $addRecordResult = DB::table('offer_product_order')->insert([
                'obp_id' => $id,
                'user_id' => $user_id,
                'price' => $nowPrice,
                'number' => $number,
                'total_price' => $totalPrice,
                'status' => '2'
            ]);
            if(! $addRecordResult)
            {
                throw new \Exception('create order failed.');
            }

            DB::commit();

            return $this->success('success');
        }catch (\Exception $e){
            DB::rollBack();
            return $this->error($e->getMessage());
        }
    }

    public function getMyOfferInfo()
    {
        try{
            $id     = Input::get('id', 0);
            $userId = Users::getUserId();
            $info   = DB::table('offer_product_wallet')->where('user_id', $userId)->where('obp_id', $id)->first();
            $list   = DB::table('offer_product_order')->where('obp_id', $id)->where('user_id', $userId)->get()->toArray();
            $std    = new \stdClass();
            $std->info = $info;
            $std->list = $list;
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }

        return $this->success($std);
    }

    public function getDates()
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

        return $datas;
    }

    public function getDateTime()
    {
        $startDate = date('Y-m-d H:i').':00';
        $endDate = date('Y-m-d H:i:s', strtotime($startDate)+60);
        $recordList = DB::table('offer_product_increase_record')
            ->select(
                'offer_product_increase_record.*',
                'today_price'
            )
            ->leftJoin('offer_buy_product', 'offer_buy_product.id', '=', 'offer_product_increase_record.obp_id')
            ->where('obp_id', 1)
            ->where('time_type', 1)
            ->where('minute', '>=', $startDate)
            ->where('minute', '<=', $endDate)
            ->get()->toArray();
        if(count($recordList) < 2){
            return $this->error('data error');
        }
        $now_price = rand($recordList[0]->open_price * 10000000000, $recordList[1]->open_price * 10000000000) / 10000000000;
        $res['now_price'] = $now_price;
        $res['change'] = $recordList[1]->today_price > $recordList[0]->open_price ? '-' : '+' . round(abs(($recordList[0]->open_price - $recordList[1]->today_price) / $recordList[1]->today_price), 2);

        return $this->success($res);
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


    public function getNewTimeData()
    {
        $id = Input::get('id');
        $redis = \Illuminate\Support\Facades\Redis::connection();
        $time = time();
        $minute = date('YmdHi', $time);
        $yesteDayMinute = date('YmdHi', $time - 3600 * 24);
        $searchMinute = date('Y-m-d H:i', $time) . ':00';
        $yestedaySearchMinute = date('Y-m-d H:i:s', strtotime($searchMinute) - 3600*24);

        $todayCacheKey = 'offer_product_data_' . $minute . '_' . $id;
        $yestedayCacheKey = 'offer_product_data_' . $yesteDayMinute . '_' . $id;
        $currentDataString = $redis->get($todayCacheKey);
        $yesteDayDataString = $redis->get($todayCacheKey);

        try {
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
            $proportion = substr($this->numberAddSubRand(round(abs(($currentData['open_price'] - $yesteDayData['close_price']) / $yesteDayData['close_price']) * 100, 2), $nowPrice > $yesteDayData['close_price']), 0, 5);
            $price1 = $this->subPrice($this->numberAddSubRand($this->subPrice( rand($currentData['lowest_price'] * 10000000000, $currentData['highest_price'] * 10000000000) / 10000000000), $nowPrice > $yesteDayData['close_price']));
            $price3 = $this->subPrice($this->numberAddSubRand($this->subPrice(rand($currentData['lowest_price'] * 10000000000, $currentData['highest_price'] * 10000000000) / 10000000000), $nowPrice > $yesteDayData['close_price']));
            list($highestPrice, $nowPrice, $lowestPrice) = $this->getSortList([$price1, $nowPrice, $price3]);

            $result = [
                'now_price' => $nowPrice,
                'proportion' => $proportion,
                'highest_price' => $highestPrice,
                'lowest_price' => $lowestPrice,
                'rise_fall_symbol' => $nowPrice > $yesteDayData['close_price'] ? '1' : '0'
            ];

            return $this->success($result);
        }catch (\Exception $e){
            return $this->error($e->getMessage());
        }
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
}