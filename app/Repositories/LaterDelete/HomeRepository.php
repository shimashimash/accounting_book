<?php

namespace App\Repositories\Home;

use Carbon\Carbon;
use App\Month;
use App\Constants\DaysOfWeek;
use App\Constants\MonthlyCategories;
use Illuminate\Support\Facades\Auth;

class HomeRepository implements HomeRepositoryInterface
{
    //----------------------------------------------
    //
    // Constants
    //
    //----------------------------------------------

    public static $monthlyCategories = [
        'house_rent'   => '家賃',
        'water_works'  => '水道代',
        'gas'          => 'ガス代',
        'electrical'   => '電気代',
        'mobile_phone' => '電話代',
        'saving'       => '貯金',
        'loan'         => 'ローン',
        'insurance'    => 'ガス代',
        'credit_card'  => 'クレジットカード',
    ];

    //----------------------------------------------
    //
    // Public functions
    //
    //----------------------------------------------

    /**
     * 一ヶ月分の日と曜日の配列を取得する
     *
     * @param int $month
     * @param int $year
     * @return array
     */
    public function getMonth($year, $month)
    {
        if ($this->isNull($year, $month)) {
            list ($year, $month) = $this->createYearAndMonth();
        }
        $monthFromDB = Month::find($year. $month);
        if (is_null($monthFromDB)) {
            // 月の情報がなければ作成する
            $monthFromDB = Month::storeMonth($year . $month);
        }
        array_add($monthFromDB, 'total', $this->getTotal($monthFromDB));
        return $monthFromDB;
    }

    /**
     * 日々の情報を取得する
     *
     * @param int $monthFromDB
     * @return array
     */
    public function getDays($monthFromDB)
    {
        $daysInfo = [];
        if (is_null($monthFromDB)) {
            return $daysInfo;
        }
        foreach($monthFromDB->days as $day) {
            // TODO ここ気持ち悪いから修正したい
            $total = 0;
            $total += $day->food;
            $total += $day->clothes;
            $total += $day->medical;
            $total += $day->traffic;
            $total += $day->social_expenses;
            $total += $day->recreation;
            $daysInfo[$day->day] = [
                'note' => $day->note,
                'total' => $total,
            ];
        }
        return $daysInfo;
    }

    /**
     * 月の情報を取得する
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function createMonth($year, $month)
    {
        if ($this->isNull($year, $month)) {
            list ($year, $month) = $this->createYearAndMonth();
        }
        $lastDay = $this->getLastDay($year, $month);
        $daysOfWeek = DaysOfWeek::WEEK;
        for ($i=1; $i<=$lastDay; $i++) {
            $day[$i] = $daysOfWeek[date('w', mktime(0, 0, 0, $month, $i, $year))];
        }
        return $day;
    }

    /**
     * 日付情報を取得する
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    public function getDate($year, $month)
    {
        if ($this->isNull($year, $month)) {
            list ($year, $month) = $this->createYearAndMonth();
        }
        $date = [
            'year' => $year,
            'month' => $month,
            'last' => $this->getLast($year, $month),
            'next' => $this->getNext($year, $month),
        ];

        return $date;
    }

    /**
     * 一ヶ月の支出を更新する
     *
     * @param array $attributes
     */
    public function update($attributes)
    {
        Month::updateOrCreate(
            [
                'id' => $attributes['id']
            ],
            [
                'house_rent'   => $attributes['house_rent'],
                'water_works'  => $attributes['water_works'],
                'gas'          => $attributes['gas'],
                'electrical'   => $attributes['electrical'],
                'mobile_phone' => $attributes['mobile_phone'],
                'saving'       => $attributes['saving'],
                'loan'         => $attributes['loan'],
                'insurance'    => $attributes['insurance'],
                'credit_card'  => $attributes['credit_card'],
                'user'         => Auth::user()->name
            ]
        );
    }

    /**
     * 一ヶ月分の日々の支出合計を取得する
     *
     * @param $days
     * @return int
     */
    public function getTotalOfDays($days)
    {
        $totalOfDays = 0;
        foreach ($days as $day) {
            $totalOfDays += $day['total'];
        }
        return $totalOfDays;
    }

    //----------------------------------------------
    //
    // Protected functions
    //
    //----------------------------------------------


    /**
     * nullチェック
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    protected function isNull($year, $month)
    {
        if (is_null($year) | is_null($month)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 西暦と月を生成する
     *
     * @return array
     */
    protected function createYearAndMonth()
    {
        $now = Carbon::now();
        $year = $now->year;
        $month = sprintf('%02d', $now->month);
        return [$year, $month];
    }

    /**
     * 月の最終日を取得する
     *
     * @param int $year
     * @param int $month
     * @return int
     */
    protected function getLastDay($year, $month)
    {
        return (int) date('j', mktime(0, 0, 0, $month + 1, 0, $year));
    }


    /**
     * 前月と前月の西暦を取得する
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    protected function getLast($year, $month)
    {
        if ($month == 1) {
            $last['year'] = $year - 1;
            $last['month'] = 12;
        } else {
            $last['month'] = $month - 1;
            $last['year'] = $year;
        }
        return $last;
    }

    /**
     * 来月と来月の西暦を取得する
     *
     * @param int $year
     * @param int $month
     * @return array
     */
    protected function getNext($year, $month)
    {
        if ($month == 12) {
            $next['year'] = $year + 1;
            $next['month'] = 1;
        } else {
            $next['month'] = $month + 1;
            $next['year'] = $year;
        }
        return $next;
    }

    /**
     * 月の合計値を取得
     *
     * @param int $monthFromDB
     * @return int
     */
    protected function getTotal($monthFromDB)
    {
        $total = 0;
        if (is_null($monthFromDB)) {
            return $total;
        }
        foreach(self::$monthlyCategories as $key => $value) {
            $total += $monthFromDB->$key;
        }
        return $total;
    }
}