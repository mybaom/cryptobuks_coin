<?php

namespace App\Http\Controllers\Api;

use App\OfferProduct;
use App\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use App\Utils\RPC;
use App\Currency;
use App\TransactionComplete;
use App\Users;
use App\MarketHour;
use App\CurrencyQuotation;
use App\AreaCode;
use App\UsersWallet;
use Illuminate\Support\Facades\Redis;

class CurrencyController extends Controller
{
    public function area_code()
    {
        $AreaCode = AreaCode::get()->toArray();
        return $this->success($AreaCode);
    }

    public function lists()
    {
        $currency = Currency::where('is_display', 1)->orderBy('sort', 'asc')->get()->toArray();
        $legal = array();
        foreach ($currency as $c) {
            if ($c["is_legal"]) {
                array_push($legal, $c);
            }
        }

        return $this->success(array(
            "currency" => $currency,
            "legal" => $legal
        ));
    }

    public function lever()
    {
        $currency = Currency::where('is_display', 1)->orderBy('sort', 'asc')->get()->toArray();
        $legal = array();
        foreach ($currency as $c) {
            if ($c["is_lever"]) {
                array_push($legal, $c);
            }
        }
        $time = strtotime(date("Y-m-d"));

        foreach ($legal as &$l) {
            $quotation = array();

            foreach ($currency as $cc) {
                if ($cc["id"] != $l["id"]) {
                    $last_price = 0;
                    $yesterday_last_price = 0;
                    $last = "";
                    $yesterday_last = "";
                    $proportion = 0.00;

                    $last = TransactionComplete::orderBy('create_time', 'desc')
                        ->where("currency", $cc["id"])
                        ->where("legal", $l["id"])
                        ->first();
                    $yesterday_last = TransactionComplete::orderBy('create_time', 'desc')
                        ->where("create_time", '<', $time)
                        ->where("currency", $cc["id"])
                        ->where("legal", $l["id"])
                        ->first();
                    !empty($last) && $last_price = $last->price;
                    !empty($yesterday_last) && $yesterday_last_price = $yesterday_last->price;

                    if (empty($last_price)) {
                        if ($yesterday_last_price) {
                            $proportion = -100.00;
                        }
                    } else {
                        if ($yesterday_last_price) {
                            $proportion = ($last_price - $yesterday_last_price) / $yesterday_last_price;
                        } else {
                            $proportion = +100.00;
                        }
                    }

                    array_push($quotation, array(
                        "id" => $cc["id"],
                        "name" => $cc["name"],
                        "last_price" => $last_price,
                        "proportion" => $proportion,
                        "yesterday_last_price" => $yesterday_last_price
                    ));
                }
            }
            $l["quotation"] = $quotation;
        }

        return $this->success($legal);
    }

    //BY tiandongliang
    public function quotation_tian()
    {
        $currency = Currency::where('is_display', 1)->orderBy('sort', 'asc')->get()->toArray();
        $legal = array();
        foreach ($currency as $c) {
            if ($c["is_legal"]) {
                array_push($legal, $c);
            }
        }
        $time = strtotime(date("Y-m-d"));
        foreach ($legal as &$l) {
            $quotation = array();
            foreach ($currency as $key => $cc) {
                $l['quotation'] = CurrencyQuotation::orderBy('add_time', 'desc')->where("legal_id", $l["id"])->get()->toArray();
            }
            // $l["quotation"] = $cc;
            // var_dump($legal);die;
        }
        return $this->success($legal);
    }

    /**
     * 新行情for tradingview
     *
     * @return void
     */
    public function newTimeshars(Request $request)
    {
//    	echo 123;exit;
        $symbol = $request->get('symbol');
        $period = $request->get('period');
        $start = $request->get('from', null);
        $end = $request->get('to', null);

        $symbol = strtoupper($symbol);
        //类型，1=15分钟，2=1小时，3=4小时,4=一天,5=分时,6=5分钟，7=30分钟,8=一周，9=一月,10=一年
        $period_list = [
            '1min' => 5,
            '5min' => 6,
            '15min' => 1,
            '30min' => 7,
            '60min' => 2,
            '1D' => 4,
            '1W' => 8,
            '1M' => 9,
            '1day' => 4,
            '1week' => 8,
            '1mon' => 9,
            '1year' => 10,
        ];
        $periods = array_keys($period_list);
        $types = array_values($period_list);
        if ($start == null || $end == null) {
            return [
                'code' => -1,
                'msg' => 'error: start time or end time must be filled in',
                'data' => null
            ];
        }

        if ($start > $end) {
            return [
                'code' => -1,
                'msg' => 'error: start time should not exceed the end time.',
                'data' => null
            ];
        }
        if ($symbol == '' || stripos($symbol, '/') === false) {
            return [
                'code' => -1,
                'msg' => 'error: symbol invalid',
                'data' => null
            ];
        }

        if ($period == '' || !in_array($period, $periods)) {
            return [
                'code' => -1,
                'msg' => 'error: period invalid',
                'data' => null
            ];
        }
        $now = strtotime(date('Y-m-d H:i'));
        if ($period == '1min' && $end >= $now) {
            //最后一分钟数据不能使用
            $end = $now - 1;
        }
        $type = $period_list[$period];
        $symbol = explode('/', $symbol);
        list($base_currency, $quote_currency) = $symbol;
        $base_currency = Currency::where('name', $base_currency)
            ->where("is_display", 1)
            ->first();

        $quote_currency = Currency::where('name', $quote_currency)
            ->where("is_display", 1)
            ->where("is_legal", 1)
            ->first();
        if (!$base_currency || !$quote_currency) {
            return [
                'code' => -1,
                'msg' => 'error: symbol not exist',
                'data' => null
            ];
        }
        $legal_id = $quote_currency->id;
        $currency_id = $base_currency->id;
        //1分钟数据
        $minutes_quotation = MarketHour::orderBy('day_time', 'asc')
            ->where("currency_id", $currency_id)
            ->where("legal_id", $legal_id)->where('type', $type)
            ->where('day_time', '>=', $start)
            ->where('day_time', '<=', $end)
            ->get();
        $return = array();
        if ($minutes_quotation) {
            foreach ($minutes_quotation as $k => $v) {
                $arr = array(
                    "open" => $v->start_price,
                    "close" => $v->end_price,
                    "high" => $v->highest,
                    "low" => $v->mminimum,
                    "volume" => $v->number,
                    "time" => $v->day_time * 1000,
                );
                array_push($return, $arr);
            }
        } else {
            foreach ($minutes_quotation as $k => $v) {
                $arr = null;
                array_push($return, $arr);
            }
        }
        return [
            "code" => 1,
            "msg" => 'success:)',
            "data" => $return,
        ];
    }

    public function klineMarket(Request $request)
    {

        $symbol = $request->input('symbol');
        $period = $request->input('period');
        $from = $request->input('from', null);
        $to = $request->input('to', null);
        $symbol = strtoupper($symbol);
        $result = [];
        //类型，1=15分钟，2=1小时，3=4小时,4=一天,5=分时,6=5分钟，7=30分钟,8=一周，9=一月,10=一年
        $period_list = [
            '1min' => '1min',
            '5min' => '5min',
            '15min' => '15min',
            '30min' => '30min',
            '60min' => '60min',
            '1H' => '60min',
            '1D' => '1day',
            '1W' => '1week',
            '1M' => '1mon',
            '1Y' => '1year',
            '1day' => '1day',
            '1week' => '1week',
            '1mon' => '1mon',
            '1year' => '1year',
        ];
        if ($from == null || $to == null) {
            return [
                'code' => -1,
                'msg' => 'error: from time or to time must be filled in',
                'data' => $result,
            ];
        }
        if ($from > $to) {
            return [
                'code' => -1,
                'msg' => 'error: from time should not exceed the to time.',
                'data' => $result,
            ];
        }
        $periods = array_keys($period_list);

        if ($period == '' || !in_array($period, $periods)) {
            return [
                'code' => -1,
                'msg' => 'error: period invalid',
                'data' => $result,
            ];
        }
        if ($symbol == '' || stripos($symbol, '/') === false) {
            return [
                'code' => -1,
                'msg' => 'error: symbol invalid',
                'data' => $result,
            ];
        }
        $period = $period_list[$period];
        list($base_currency, $quote_currency) = explode('/', $symbol);
        $base_currency_model = Currency::where('name', $base_currency)
            ->where("is_display", 1)
            ->first();
        $quote_currency_model = Currency::where('name', $quote_currency)
            ->where("is_display", 1)
            ->where("is_legal", 1)
            ->first();
        if (!$base_currency_model || !$quote_currency_model) {
            return [
                'code' => -1,
                'msg' => 'error: symbol not exist',
                'data' => null
            ];
        }

        $result = MarketHour::getEsearchMarket($base_currency, $quote_currency, $period, $from, $to);

        $result = array_map(function ($value) {
            $value['time'] = $value['id'] * 1000;
            $value['volume'] = $value['amount'] ?? 0;
            return $value;
        }, $result);



        return [
            'code' => 1,
            'msg' => 'success',
            'data' => $result
        ];
    }



    public function newQuotation()
    {
        $needOfferProduct = Input::get('need_offer_product', 1);
        $currency = Currency::with('quotation')
            ->whereHas('quotation', function ($query) {
                $query->where('is_display', 1);
            })
            ->where('is_display', 1)
            ->where('is_legal', 1)
            ->get();

        $currency = json_decode(json_encode($currency, JSON_UNESCAPED_UNICODE),true);

        $cbv = OfferProduct::getProductById(1);

        if($needOfferProduct == 2)
        {
            $usdt = Currency::getUsdtInfo();
            if (count($currency[0]['quotation']) < 3){
                $currency[0]['quotation'][] = $usdt;
            }else{
                $result = [];
                foreach ($currency[0]['quotation'] as $k => $v) {
                    if($k == 2){
                        $result[] = $usdt;
                    }
                    $result[] = $v;
                }

                $currency[0]['quotation'] = $result;
            }
        }

        if($cbv && $needOfferProduct == 1) {
            try{
                $cbv = json_decode(json_encode($cbv, JSON_UNESCAPED_UNICODE), true);
                $newData = $this->getNewTimeData(1);
                $cbv['change'] = $newData['proportion'];
                $cbv['now_price'] = $newData['now_price'];
            }catch (\Exception $e){

            }


            if (count($currency[0]['quotation']) < 3){
                $currency[0]['quotation'][] = $cbv;
            }else{
                $result = [];
                foreach ($currency[0]['quotation'] as $k => $v) {
                    if($k == 2){
                        $result[] = $cbv;
                    }
                    $result[] = $v;
                }

                $currency[0]['quotation'] = $result;
            }
        }

        return $this->success($currency);
    }

    public function getNewTimeData($id)
    {
        $redis = \Illuminate\Support\Facades\Redis::connection();
        $time = time();
        $minute = date('YmdHi', $time);
        $yesteDayMinute = date('YmdHi', ($time - 3600 * 24));
        $searchMinute = date('Y-m-d H:i', $time) . ':00';
        $yestedaySearchMinute = date('Y-m-d H:i:s', strtotime($searchMinute) - 3600*24);

        $todayCacheKey = 'offer_product_data_' . $minute . '_' . $id;
        $yestedayCacheKey = 'offer_product_data_' . $yesteDayMinute . '_' . $id;
        $getOfferNewDataCacheKey = 'offer_product_new_data_' . $id;
        $currentDataString = $redis->get($todayCacheKey);
        $yesteDayDataString = $redis->get($yestedayCacheKey);
        $offerNewDataString = $redis->get($getOfferNewDataCacheKey);

        if(!empty($offerNewDataString)){
            $result = json_decode($offerNewDataString, true);
            return $result;
        }
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
        $nowPrice = $this->subPrice($this->numberAddSubRand($nowPrice, $currentData['open_price'] < $currentData['close_price'] ? true : false));
        // 涨幅百分比
        $proportion = substr($this->numberAddSubRand(round(abs(($currentData['open_price'] - $yesteDayData['close_price']) / $yesteDayData['close_price']) * 100, 2), $nowPrice > $yesteDayData['close_price']), 0, 5);
        $rise_fall_symbol = $nowPrice > $yesteDayData['close_price'] ? '+' : '-';
        $result = [
            'now_price' => $nowPrice,
            'proportion' => $rise_fall_symbol.$proportion,
        ];
        // 设置当前数据缓存，为了多处展示的数据同步问题，不然都是随机变化的
        $setCacheResult = $redis->set($getOfferNewDataCacheKey, json_encode($result, JSON_UNESCAPED_UNICODE));
        $redis->expire($getOfferNewDataCacheKey, 5);
        if(! $setCacheResult)
        {
            throw new \Exception('set redis fail.');
        }

        return $result;
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
                case 0:
                    $subLength = 5;
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


    public function getChargeCoinAddress()
    {
        $getRechargeAddress = [
            'BTC' => Setting::getValueByKey('recharge_btc_address', ''),
            'ETH' => Setting::getValueByKey('recharge_eth_address', ''),
            'USDT' => Setting::getValueByKey('recharge_usdt_address', '')
        ];
//        $getRechargeAddress = [
//            'BTC' => '37uLL3Jn8zc81MT5UGwAoUx2Za8yoUC4cT',
//            'ETH' => '0xeFc84e679d5A18870d8C7445cC2F2508aECA2C8d',
//            'USDT' => '0xeFc84e679d5A18870d8C7445cC2F2508aECA2C8d'
//        ];
        return $this->success($getRechargeAddress);
    }

    public function dealInfo()
    {
        $legal_id = Input::get("legal_id");
        $currency_id = Input::get("currency_id");

        if (empty($legal_id) || empty($currency_id))
            return $this->error("参数错误");

        $legal = Currency::where("is_display", 1)
            ->where("id", $legal_id)
            ->where("is_legal", 1)
            ->first();
        $currency = Currency::where("is_display", 1)
            ->where("id", $currency_id)
            ->first();
        if (empty($legal) || empty($currency)) {
            return $this->error("币未找到");
        }
        $type = Input::get("type", "1");
        $seconds = 60;
        switch ($type) {
            case 2:
                $seconds = 15 * 60;
                break;
            case 3:
                $seconds = 60 * 60;
                break;
            case 4:
                $seconds = 4 * 60 * 60;
                break;
            case 5:
                $seconds = 24 * 60 * 60;
                break;
            default:
                $seconds = 60;
        }
        $time = time();
        $last_price = 0;
        $last = TransactionComplete::orderBy('create_time', 'desc')
            ->where("currency", $currency_id)
            ->where("legal", $legal_id)
            ->first();
        $last && $last_price = $last->price;

        $now_quotation = TransactionComplete::getQuotation($legal_id, $currency_id, ($time - $seconds), $time);
        //$now_quotation = TransactionComplete::getQuotation_two($currency->name,$legal->name,$type);
        $quotation = array();
        for ($i = 0; $i < 10; $i++) {
            $end_time = $time - $i * $seconds;
            $start_time = $end_time - $seconds;

            $data = array();
            $data = $now_quotation = TransactionComplete::getQuotation($legal_id, $currency_id, $start_time, $end_time);
            array_push($quotation, $data);
        }
        return $this->success(array(
            "legal" => $legal,
            "currency" => $currency,
            "last_price" => $last_price,
            "now_quotation" => $now_quotation,
            "quotation" => $quotation
        ));
    }

    public function userCurrencyList()
    {
        $user_id = Users::getUserId();
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
        return $this->success($currencies);
    }
}
