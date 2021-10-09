<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OfferProduct extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'offer_buy_product';

    public static function getProductList()
    {
        $list = DB::table('offer_buy_product')
            ->select('id',
                'name',
                'icon',
                DB::raw('format(now_price, 3) as now_price'),
                DB::raw('if(rise_fall_probability > 50, "1", "0") as rise_fall_symbol'),
                DB::raw('format((`max_increase` + `min_increase`)/2 * `rise_fall_probability`/100, 2) as rise_fall_probability_today')
            )
            ->where('status', 1)
            ->get()->toArray();
        return $list;
    }

    public static function getProducts()
    {
        $list = DB::table('offer_buy_product')->where('status', 1)
            ->get()->toArray();
        return $list;
    }

    public static function getProductById($id)
    {
        $list = DB::table('offer_buy_product')
            ->select(
                DB::raw('id'),
                DB::raw('"offeProductDetail.html" as toPage'),
                DB::raw('name as currency_name'),
                DB::raw('icon as logo'),
                DB::raw('now_price'),
                DB::raw('1 as is_offer_product'),
                DB::raw('concat(if(rise_fall_probability > 50, "+", "-"), format((`max_increase` + `min_increase`)/2 * `rise_fall_probability`/100, 2)) as `change`'),
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
            ->where('id', $id)->where('status', 1)
            ->first();
        return $list;
    }

    public static function getProductListForTimeLoad()
    {
        $list = DB::table('offer_buy_product')
            ->select(
                DB::raw('name as currency_name'),
                DB::raw('icon as logo'),
                DB::raw('concat(if(rise_fall_probability > 50, "+", "-"), format((`max_increase` + `min_increase`)/2 * `rise_fall_probability`/100, 2)) as `change`'),
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
            ->where('status', 1)
            ->get()->toArray();
        return $list;
    }

}