<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPriceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MonthRepository;

class HomeController extends Controller
{
    /**
     * @var MonthRepository
     */
    protected $monthRepository;

    /**
     * Create a new controller instance.
     *
     * @param MonthRepository $monthRepository
     */
    public function __construct(MonthRepository $monthRepository)
    {
        $this->middleware('auth');
        $this->monthRepository = $monthRepository;
    }

    /**
     * 一ヶ月の画面を表示する
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function index($year = null, $month = null)
    {
        $monthId = $this->monthRepository->getMonthId($year, $month);
        $monthInfo = $this->monthRepository->firstOrCreate(['id' => $monthId]);
        $daysInfo = $this->monthRepository->getDays($monthInfo);

        return view('home.index')->with([
            'month' => $monthInfo,
            'dayAndWeek' => $this->monthRepository->createMonth($year, $month),
            'days' => $daysInfo,
            'date' => $this->monthRepository->getDate($year, $month),
            'monthOfTotal' => $this->monthRepository->getTotalOfMonths($monthInfo),
            'daysOfTotal' => $this->monthRepository->getTotalOfDays($daysInfo),
        ]);
    }

    /**
     * 月の支出を編集する画面
     *
     * @param $year
     * @param $month
     * @return mixed
     */
    public function edit($year, $month)
    {
        return view('home.edit')->with([
            'month' => $this->monthRepository->find($year. $month),
        ]);
    }

    /**
     * 月の支出を更新する
     *
     * @param PostPriceRequest $request
     * @param $monthId
     * @return mixed
     */
    public function update(PostPriceRequest $request, $monthId)
    {
        $attributes = $request->input('month');
        $attributes['user'] = Auth::user()->name;
        $this->monthRepository->update($attributes, $monthId);
        session()->put('flash_message', 'データを更新しました');

        return redirect('/home/'.substr($monthId, 0, 4).'/'.substr($monthId, 4));
    }

    /**
     * 詳細画面
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $monthInfo = $this->monthRepository->find($id);
        list ($data1, $labels1) = $this->monthRepository->getSpendingMonth($monthInfo);
        list ($data2, $labels2) = $this->monthRepository->getSpendingDays($monthInfo->days);
        return view('home.detail')->with([
            'monthInfo' => $monthInfo,
            'spending1' => $this->monthRepository->getPieChart('spending1', $data1, $labels1),
            'spending2' => $this->monthRepository->getPieChart('spending2', $data2, $labels2),
        ]);
    }
}