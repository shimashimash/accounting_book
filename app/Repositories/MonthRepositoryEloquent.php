<?php

namespace App\Repositories;

use App\Days;
use Illuminate\Support\Facades\App;
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
     * 西暦と月を生成する
     *
     * @return int
     */
    public function getMonthId($year = null, $month = null)
    {
        if ($this->isNull($year, $month)) {
            $now = Carbon::now();
            $year = $now->year;
            $month = sprintf('%02d', $now->month);
        }
        return $year . $month;
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
        for ($i = 1; $i <= $lastDay; $i++) {
            $day[$i] = Month::$week[date('w', mktime(0, 0, 0, $month, $i, $year))];
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
        foreach(Month::$monthlyCategories as $key => $value) {
            $total += $monthFromDB->$key;
        }
        return $total;
    }
}