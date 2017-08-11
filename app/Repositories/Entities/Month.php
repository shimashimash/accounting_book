<?php

namespace App\Repositories\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Month extends Model implements Transformable
{
    use TransformableTrait;

    //----------------------------------------------
    // Constants
    //----------------------------------------------

    public static $week = ['日', '月', '火', '水', '木', '金', '土'];

    // 曜日(月曜始まり)
    public static $dayOfWeek = [
        1 => '月',
        2 => '火',
        3 => '水',
        4 => '木',
        5 => '金',
        6 => '土',
        7 => '日',
    ];

    public static $monthlyCategories = [
        'house_rent'   => '家賃',
        'water_works'  => '水道代',
        'gas'          => 'ガス代',
        'electrical'   => '電気代',
        'mobile_phone' => '電話代',
        'saving'       => '貯金',
        'loan'         => 'ローン',
        'insurance'    => '保険',
        'credit_card'  => 'クレジットカード',
    ];

    //----------------------------------------------
    // Properties
    //----------------------------------------------

    protected $table = 'months';

    protected $fillable = [
        'id',
        'house_rent',
        'water_works',
        'gas',
        'electrical',
        'mobile_phone',
        'saving',
        'loan',
        'insurance',
        'credit_card',
        'user',
    ];

    public function days()
    {
        return $this->hasMany(Day::class);
    }
}