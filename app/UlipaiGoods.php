<?php
/**
 * Created by PhpStorm.
 * User: 杨圣新
 * Date: 2018/10/26
 * Time: 16:45
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UlipaiGoods extends Model
{
    public $timestamps = false;

    protected $guarded = [];

    protected $table = 'ulipai_goods';


    const STOP = 0;
    const START = 1;


  

    public function getStatusNameAttribute()
    {
        $value                 = $this->attributes['status'];
        $status[static::STOP]  = '已停止';
        $status[static::START] = '已开启';
        return $status[$value];
    }


}