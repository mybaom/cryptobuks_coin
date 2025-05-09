<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Currency extends Model
{
    protected $table = 'currency';
    public $timestamps = false;
    protected $appends = ['to_pb_price'];
    protected $hidden = ['key'];

    /**
     * 定义一对多关系
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function quotation()
    {
        return $this->hasMany(CurrencyMatch::class, 'legal_id', 'id');
    }

    public function microNumbers()
    {
        return $this->hasMany(MicroNumber::class)->orderBy('number', 'asc');
    }

    public static function getUsdtInfo()
    {
        $usdt = DB::table('currency')
            ->select(
                DB::raw('id as currency_id'),
                DB::raw('id'),
                DB::raw('name as currency_name'),
                DB::raw('logo'),
                DB::raw('price as now_price'),
                DB::raw('0 as `change`'),
                DB::raw('"0.00000000" as fluctuate_max'),
                DB::raw('"0.00000000" as fluctuate_min'),
                DB::raw('1 as is_display'),
                DB::raw('3 as legal_id'),
                DB::raw('"USDT" as legal_name'),
                DB::raw('name as legal_name2'),
                DB::raw('0 as lever_max_share'),
                DB::raw('1 as lever_min_share'),
                DB::raw('"0.00000000" as lever_share_num'),
                DB::raw('"0.0000" as lever_trade_fee'),
                DB::raw('2 as market_from'),
                DB::raw('"火币接口" as market_from_name'),
                DB::raw('"0.00" as micro_trade_fee'),
                DB::raw('1 as open_coin_trade'),
                DB::raw('1 as open_lever'),
                DB::raw('1 as open_microtrade'),
                DB::raw('0 as open_transaction'),
                DB::raw('"0.0000" as overnight'),
                DB::raw('1 as risk_group_result'),
                DB::raw('1 as rmb_relation'),
                DB::raw('0 as sort'),
                DB::raw('"0.0000" as spread'),
                DB::raw('0 as volume')
            )
            ->where('id', 3)
            ->first();
        return $usdt;
    }

    // public function getExRateAttribute()
    // {
    //     return Setting::getValueByKey('ExRate', 6.5);
    // }

    public function getCreateTimeAttribute()
    {
        return date('Y-m-d H:i:s', $this->attributes['create_time']);
    }

    public static function getNameById($currency_id)
    {
        $currency = self::find($currency_id);
        return $currency->name;
    }

    // public function getUsdtPriceAttribute()
    // {
    //     $last_price = 1;
    //     $currency_id = $this->attributes['id'];
    //     $last = TransactionComplete::orderBy('id', 'desc')
    //         ->where("currency", $currency_id)
    //         ->where("legal", 1)->first();//1是PB
    //     if (!empty($last)) {
    //         $last_price = $last->price;
    //     }
    //     if ($currency_id == 1) {
    //         $last_price = 1;
    //     }
    //     return $last_price;
    // }


    //获取币种相对于人民币的价格
    public static function getCnyPrice($currency_id)
    {
        $rate = Setting::getValueByKey('USDTRate', 7.08);
        $usdt = Currency::where('name', 'USDT')->select(['id'])->first();
        $last = MarketHour::orderBy('id', 'desc')
            ->where("currency_id", $currency_id)
            ->where("legal_id", $usdt->id)->first();
        if (!empty($last)) {
            $cny_Price = $last->highest * $rate; //行情表里面最近的数据的最高值
        } else {
            //$cny_Price = 1; //如果不存在交易对，默认为1
            //如果不存在行情，取币种默认价格
            $currency = Currency::where('id', $currency_id)->first();

            $cny_Price = @$currency->price * $rate;

        }
        if ($currency_id == $usdt->id) {
            $cny_Price = 1 * $rate;
        }

        return $cny_Price;
    }


    public function getRmbRelationAttribute()
    {
        $rate = Setting::getValueByKey('USDTRate', 7.08);
        return $rate;
    }

    public function getToPbPriceAttribute()
    {
        $currency_id = $this->id;

        $ptb = Currency::where('name', UsersWallet::CURRENCY_DEFAULT)->first();
        $last = MarketHour::orderBy('id', 'desc')
            ->where("currency_id", $currency_id)
            ->where("legal_id", $ptb->id)->first();
        if (!empty($last)) {
            $Price = $last->highest; //行情表里面最近的数据的最高值
        } else {
            $Price = $ptb->price; //如果不存在交易对，默认为1
        }
        if ($currency_id == $ptb->id) {
            $Price = 1;
        }
        if($Price>0){
            $to_pb_price = bcdiv($this->price, $Price, 8);
        }else{
            $to_pb_price = 0;
        }

        return $to_pb_price;
    }
    //获取币种相对于平台币的价格
    public static function getPbPrice($currency_id)
    {
        $ptb = Currency::where('name', UsersWallet::CURRENCY_DEFAULT)->first();
        $last = MarketHour::orderBy('id', 'desc')
            ->where("currency_id", $currency_id)
            ->where("legal_id", $ptb->id)->first();
        if (!empty($last)) {
            $Price = $last->highest; //行情表里面最近的数据的最高值
        } else {
            $Price = $ptb->price; //如果不存在交易对，默认为1
        }
        if ($currency_id == $ptb->id) {
            $Price = 1;
        }

        return $Price;
    }

    public function getOriginKeyAttribute($value)
    {
        $private_key = $this->attributes['key'] ?? '';
        return $private_key != '' ? decrypt($private_key) : '';
    }

    public function getKeyAttribute($value)
    {
        return $value == '' ?: '********';
    }

    public function setKeyAttribute($value)
    {
        if ($value != '') {
            return $this->attributes['key'] = encrypt($value);
        }
    }
}
