<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Month extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'month';

    protected $primaryKey = 'id';

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
        return $this->hasMany(Days::class);
    }

    public static function storeMonth($id)
    {
        $month = new Month;
        $month->id = $id;
        $month->save();
    }
}
