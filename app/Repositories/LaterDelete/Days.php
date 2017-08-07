<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Days extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'days';

    protected $primaryKey = 'id';

    protected $guarded = [
        'id',
    ];
}
