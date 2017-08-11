<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Entities\Month;
use App\Repositories\Entities\Day;
use Carbon\Carbon;

/**
 * Class MonthRepositoryEloquent
 * @package namespace App\Repositories;
 */
class MonthRepositoryEloquent extends BaseRepository implements MonthRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Month::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * 日々の情報を取得する
     *
     * @param array $monthInfo
     * @return array
     */
    public function getDays($monthInfo)
    {
        $daysInfo = [];
        if (is_null($monthInfo)) {
            return $daysInfo;
        }
        foreach ($monthInfo->days as $day) {
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
     * 一か月分のカレンダーを生成する
     *
     * @param $date
     * @return array
     */
    public function getMonthCalendar($date)
    {
        $j = 0;
        $calendar = [];
        for ($i = 1; $i < $date->daysInMonth + 1; $i++) {
            // 曜日を取得する
            $week = date('N', mktime(0, 0, 0, $date->month, $i, $date->year));
            // 一日(ついたち)の場合
            if ($i == 1) {
                for ($s = 1; $s < $week; $s++) {
                    $calendar[$j]['day'] = '';
                    $calendar[$j]['dayOfWeek'] = '';
                    $j++;
                }
            }
            $calendar[$j]['day'] = Carbon::create($date->year, $date->month, $i, 0, 0, 0);
            $calendar[$j]['dayOfWeek'] = Month::$dayOfWeek[$week];
            $j++;
            // 月末日の場合
            if ($i == $date->daysInMonth) {
                for ($e = 1; $e <= 7 - $week; $e++) {
                    $calendar[$j]['day'] = '';
                    $calendar[$j]['dayOfWeek'] = '';
                    $j++;
                }
            }
        }
        return $calendar;
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

    public function getSpendingDays($days)
    {
        list($food, $clothes, $medical, $traffic, $socialExpenses, $recreation) = [0, 0, 0, 0, 0, 0];
        foreach ($days as $day) {
            $food += $day['food'];
            $clothes += $day['clothes'];
            $medical += $day['medical'];
            $traffic += $day['traffic'];
            $socialExpenses += $day['$social_expenses'];
            $recreation += $day['recreation'];
        }
        foreach (Day::$dailyCategories as $categoryJpName) {
            $labels[] = $categoryJpName;
        }
        $data = [$food, $clothes, $medical, $traffic, $socialExpenses, $recreation];
        return [$data, $labels];
    }

    public function getSpendingMonth($monthInfo)
    {
        $data = [];
        $labels = [];
        foreach (Month::$monthlyCategories as $key => $value) {
            $labels[] = $value;
            $data[] = $monthInfo->$key;
        }
        return [$data, $labels];
    }

    /**
     * パイグラフを作成する
     *
     * @param $name
     * @param $data
     * @param $labels
     * @return array
     */
    public function getPieChart($name, $data, $labels)
    {
        $chart = app()->chartjs
            ->name($name)
            ->type('pie')
            ->size(['width' => 400, 'height' => 200])
            ->labels($labels)
            ->datasets([
                [
                    'backgroundColor' => ['red', 'purple', 'blue', 'green', 'yellow', 'orange', 'brown', 'olive', 'grey', 'fuchsia'],
                    'hoverBackgroundColor' => ['#FF6384', '#36A2EB'],
                    'data' => $data,
                ],
            ])
            ->options([]);
        return $chart;
    }

    /**
     * 月の決まっている支出合計を取得する
     *
     * @param $monthInfo
     * @return int
     */
    public function getTotalOfMonths($monthInfo)
    {
        $total = 0;
        foreach (Month::$monthlyCategories as $key => $value) {
            $total += $monthInfo->$key;
        }
        return $total;
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
        foreach(Month::$monthlyCategories as $key => $value) {
            $total += $monthFromDB->$key;
        }
        return $total;
    }
}