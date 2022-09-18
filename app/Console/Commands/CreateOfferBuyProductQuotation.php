<?php
namespace App\Console\Commands;

use App\OfferProduct;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateOfferBuyProductQuotation extends Command{

    protected $signature = "createOfferBuyProductQuotation";
    protected $description = "生产认购产品行情数据";

    public function handle(){
        // 获取产品数据
        $products = OfferProduct::getProducts();

        foreach ($products as $k => $v) {
            $getData = true;
            while ($getData) {
                list($data, $insertData, $closePrice) = $this->createdData($v);
                // 0.0001384的时候0.003k线有点假，基本都是差不多长度
                if(
                    $this->convert_scientific_number_to_normal(abs($data['highest_price']-$data['lowest_price'])/$data['highest_price']) < 0.004
                    &&
                    $this->convert_scientific_number_to_normal(abs($data['close_price']-$data['open_price'])/$data['close_price']) < 0.004
                ){
                    $getData = false;
                }
            }

            DB::beginTransaction();
            try {
                $updateNowPriceResult = DB::table('offer_buy_product')->where('id', $v->id)->update(['now_price' => $closePrice]);
                // 如果更新失败则再更新一次
                if (!$updateNowPriceResult) {
                    DB::table('offer_buy_product')->where('id', $v->id)->update(['now_price' => $closePrice]);
                }
                if ($insertData) {
                    DB::table('offer_product_increase_record')->insert($insertData);
                }
                DB::commit();
            } catch (\Exception $e) {
                echo $e->getMessage();
                DB::rollBack();
            }
        }
    }

    public function createdData($v){
        // 获取时间
        $advanceMin = 2; // 提前预设两分钟后的数据
        $time = time() + 60 * $advanceMin;
        $currentMin = date('i', $time);
        $currentDate = date('Y-m-d H:i', $time).':00';
        $time = strtotime($currentDate);
        $isFiveMin = ($currentMin % 5) == 0 ? true : false;
        $isFifteenMin = ($currentMin % 15) == 0 ? true : false;
        $isThirtyMin = ($currentMin % 30) == 0 ? true : false;
        $isHourMin = ($currentMin % 60) == 0 ? true : false;
        $insertData = [];

        $todayPrice = $v->today_price;
        // 如果今日行情起售价为0或者为0点则更新今日起售现价为现价
        if ($v->today_price == 0 || $currentMin == '00') {
            $updateTodayPriceResult = DB::table('offer_buy_product')->where('id', $v->id)->update(['today_price' => $v->now_price]);
            // 如果更新失败则再更新一次
            if (!$updateTodayPriceResult) {
                DB::table('offer_buy_product')->where('id', $v->id)->update(['today_price' => $v->now_price]);
            }
            // 今日起售价也更换为现价
            $todayPrice = $v->now_price;
        }

        // 获得浮动价格
        $price1 = $this->getReasonablePrice(
            $this->getNowPrice(
                $todayPrice,
                $v->now_price,
                $v->rise_fall_probability / 100,
                $v->min_increase / 100,
                $v->max_increase / 100
            )
            , rand(0, 1));
//                $price2 = $this->getNowPrice(
//                        $todayPrice,
//                        $v->now_price,
//                        $v->rise_fall_probability / 100,
//                        $v->min_increase / 100,
//                        $v->max_increase / 100
//                    );
        $price2 = $this->getReasonablePrice(
            $this->getNowPrice(
                $todayPrice,
                $v->now_price,
                $v->rise_fall_probability / 100,
                $v->min_increase / 100,
                $v->max_increase / 100
            ),
            rand(0, 1));
        $price3 = $this->getReasonablePrice(
            $this->getNowPrice(
                $todayPrice,
                $v->now_price,
                $v->rise_fall_probability / 100,
                $v->min_increase / 100,
                $v->max_increase / 100
            )
            , rand(0, 1));

//                list($highestPrice, $lowestPrice) = $this->getSortList([$price1, $price3]);
        list($highestPrice, $closePrice, $lowestPrice) = $this->getSortList([$price1, $price2, $price3]);
        $openPrice = (float)$v->now_price;
        //$closePrice = $price2;

        $data = ['obp_id' => $v->id,
            'highest_price' => $highestPrice,
            'close_price' => $closePrice,
            'lowest_price' => $lowestPrice,
            'open_price' => $openPrice,
            'minute' => $currentDate,
            'time' => $time,
            'volume' => rand($v->min_volume, $v->max_volume)
        ];
        $insertData[] = array_merge($data, ['time_type' => 1]);
        if ($isFiveMin) {
            $insertData[] = array_merge($data, ['time_type' => 2]);
        }
        if ($isFifteenMin) {
            $insertData[] = array_merge($data, ['time_type' => 3]);
        }
        if ($isThirtyMin) {
            $insertData[] = array_merge($data, ['time_type' => 4]);
        }
        if ($isHourMin) {
            $insertData[] = array_merge($data, ['time_type' => 5]);
        }

        return [$data, $insertData, $closePrice];
    }

    /**
     * 获取合理价格并且后面加1的方法
     * @param $nowPrice
     * @return string
     */
    public function getReasonablePrice($nowPrice, $addOrSub = true)
    {
        $nowPrice = $this->convert_scientific_number_to_normal($nowPrice);
        $nowPrice = $this->subPrice($nowPrice);
        // 获取当前价格在什么价位
        $beforeInt = substr($nowPrice,0, strrpos($nowPrice,"."));
        // 如果价格大于0，则获得价格小数后两位的位置
        if($beforeInt > 0){
            $subLength = strlen($beforeInt) + 3;
        }else{
            // 如果价格小于0，则拿到价格的非零的第一位数在哪个位置
            $afterNumber = substr($nowPrice,strripos($nowPrice,".")+1);
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
                    $subLength = 7;
                    break;
                case 3:
                    $subLength = 7;
                    break;
                case 2:
                    $subLength = 7;
                    break;
                case 1:
                    $subLength = 7;
                    break;
                case 0:
                    $subLength = 6;
                    break;
            }
        }

//
//        // 裁剪出合理的显示价位
//        $subNowPrice = substr($nowPrice, 0, $subLength);
        // 计算增加的价格
        $addNumber = $this->convert_scientific_number_to_normal(pow(10, strlen($beforeInt) - $subLength + 1) * rand(0, 2));

        // 随机概率为增加或是减少
//        $addOrSub = rand(0, 1);
        if($addOrSub) {
            return $this->convert_scientific_number_to_normal($nowPrice + $addNumber);
        }else{
            return $this->convert_scientific_number_to_normal($nowPrice - $addNumber);
        }

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
                    $subLength = rand(7,8);
                    break;
                case 2:
                    $subLength = rand(6,8);
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

    private function getSortList($arr)
    {
        rsort($arr);
        return $arr;
    }

    /**
     * @param int $startPrice 行情数据起售价
     * @param float $nowPrice 行情现价
     * @param float $rade 涨幅概率（小数）
     * @param float $minBl 最小涨幅比例 （小数）
     * @param float $maxBl 最大涨幅比例 （小数）
     */
    public function getNowPrice($startPrice = 10.00, $nowPrice = 0, $rade = 0.85, $minBl = 0.6, $maxBl = 0.84)
    {
//        // 起售价
//        $startPrice = 10;
//        // 涨幅概率
//        $rade = 0.85;
//        // 最小涨幅比例
//        $minBl   = 0.6;
//        // 最大涨幅比例
//        $maxBl   = 0.84;
        // 比例转换整数值
        $blzh = 1000;
        $mins = 1440;
        // 随机涨幅比例
        $randBl = rand(($minBl * $blzh), ($maxBl * $blzh)) / ($blzh/100);
//        echo "涨幅比例：$randBl% \n";
//        echo "涨幅：" . (rand(0,100) < ($rade * 100) ? '涨': '跌')."\n";
        $totalIncreasePrice = $startPrice;
        $totalIncreasePrice += ($startPrice * $randBl / $blzh);
//        echo "今日随机涨幅金额：$totalIncreasePrice \n";

        $increasePrice = $this->convert_scientific_number_to_normal($this->filter_money($this->convert_scientific_number_to_normal($totalIncreasePrice/$mins), 10));

//        echo "涨幅金额：$increasePrice \n";
//        echo "涨幅后金额：".($startPrice+$increasePrice)."\n";
        return (rand(0,100) < ($rade * 100) ? ($nowPrice + $increasePrice) : ($nowPrice - $increasePrice));

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

    /**
     * 字符串截取, 默认小数点后2位
     * @param $money
     * @param int $accuracy
     * @return float
     */
    private function filter_money($money,$accuracy=2)
    {
        $str_ret = 0;
        if (empty($money) === false) {
            $str_ret = sprintf("%.".$accuracy."f", substr(sprintf("%.".($accuracy+1)."f", floatval($money)), 0, -1));
        }

        return floatval($str_ret);
    }
}