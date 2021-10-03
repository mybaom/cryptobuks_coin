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

}