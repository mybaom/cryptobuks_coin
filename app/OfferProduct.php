<?php

namespace App;


use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'offer_buy_product';

}