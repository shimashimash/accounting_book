<?php

namespace App\Repositories\Add;

use App\Days;
use Illuminate\Support\Facades\Auth;

class AddRepository implements AddRepositoryInterface
{
    /**
     * ある日の詳細情報を取得する
     *
     * @param string $monthId
     * @param string $day
     * @return object $daysInfo
     */
    public function getDayInfo($monthId, $day)
    {
        $dayInfo = Days::where('month_id', $monthId)
            ->where('day', $day)
            ->first();
        return $dayInfo;
    }

    /**
     * DBにidがなければ新規登録、あれば更新する
     *
     * @param array $day
     */
    public function updateData($day)
    {
        Days::updateOrCreate(
            [
                'id' => $day['id']
            ],
            [
                'month_id'        => $day['year']. $day['month'],
                'day'             => $day['day'],
                'food'            => $day['food'],
                'clothes'         => $day['clothes'],
                'medical'         => $day['medical'],
                'traffic'         => $day['traffic'],
                'social_expenses' => $day['social_expenses'],
                'recreation'      => $day['recreation'],
                'note'            => $day['note'],
                'user'            => Auth::user()->name
            ]
        );
    }
}