<?php
/**
 * Created by PhpStorm.
 * User: 果果爸
 * Date: 2020-10-01
 * Time: 22:17
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class MyBank extends Model
{
    protected $table="my_bank";

    protected $fillable = [
        'bank_name','bank_account','bank_address','user_id','name','type'
    ];


}