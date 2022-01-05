<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function testX(){
        $dataLen = 943;
        $z = (100-(10/($dataLen/100)));
        //echo ($dataLen / 30)*0.9;
        echo ($dataLen-30)/$dataLen*100;
    }

    public function testNumberDiffrent()
    {
        $a = 31321;
        $b = 31322;
        echo $this->convert_scientific_number_to_normal(abs($a-$b)/$a) > 0.01 ? 'gt' : 'lt';

    }

    public function testKModel(){
        // 起售价
        $startPrice = 10;
        // 涨幅概率
        $rade = 0.85;
        // 最小涨幅比例
        $minBl   = 0.6;
        // 最大涨幅比例
        $maxBl   = 0.84;
        // 比例转换整数值
        $blzh = 1000;
        $mins = 1440;
        // 随机涨幅比例
        $randBl = rand(($minBl * $blzh), ($maxBl * $blzh))/($blzh/100);
        echo "涨幅比例：$randBl% \n";
        echo "涨幅：" . (rand(0,100) < ($rade * 100) ? '涨': '跌')."\n";
        $totalIncreasePrice = $startPrice;
        $totalIncreasePrice += ($startPrice * $randBl / $blzh);
        echo "今日随机涨幅金额：$totalIncreasePrice \n";

        $increasePrice = $this->convert_scientific_number_to_normal($this->filter_money($this->convert_scientific_number_to_normal($totalIncreasePrice/$mins), 10));

        echo "涨幅金额：$increasePrice \n";
        echo "涨幅后金额：".($startPrice+$increasePrice)."\n";

        echo $this->getNowPrice();
    }

    /**
     * @param int $startPrice 行情数据起售价
     * @param float $rade 涨幅概率（小数）
     * @param float $minBl 最小涨幅比例 （小数）
     * @param float $maxBl 最大涨幅比例 （小数）
     */
    public function getNowPrice($startPrice = 10.00, $rade = 0.85, $minBl = 0.6, $maxBl = 0.84)
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
        return (rand(0,100) < ($rade * 100) ? ($startPrice + $increasePrice) : ($startPrice - $increasePrice));

    }

    public function getlModelList()
    {
        for ($i = 0; $i < 60; $i++){
            $this->testKModel();
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
