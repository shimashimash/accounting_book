<?php

namespace App\Repositories\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Day extends Model implements Transformable
{
    use TransformableTrait;

    //----------------------------------------------
    // Constants
    //----------------------------------------------

    public static $dailyCategories = [
        'food'            => '食費',
        'clothes'         => '日用品',
        'medical'         => '医療費',
        'traffic'         => '交通費',
        'social_expenses' => '交際費',
        'recreation'      => '娯楽',
    ];

    //----------------------------------------------
    // Properties
    //----------------------------------------------

    protected $table = 'days';

    protected $guarded = [
        'id',
    ];
}