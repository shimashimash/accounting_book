<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPriceRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\MonthRepository;
use Carbon\Carbon;

class MonthsController extends Controller
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
     * @param $date
     * @return mixed
     */
    public function index($date = null)
    {
        $date = is_null($date) ? Carbon::today() : Carbon::parse($date);
        $monthInfo = $this->monthRepository->firstOrCreate(['id' => $date->format('Ym')]);
        $daysInfo = $this->monthRepository->getDays($monthInfo);

        return view('months.index')->with([
            'month' => $monthInfo,
            'dayAndWeek' => $this->monthRepository->createMonth($date),
            'days' => $daysInfo,
            'date' => Carbon::create($date->year, $date->month, 1, 0, 0, 0),
            'monthOfTotal' => $this->monthRepository->getTotalOfMonths($monthInfo),
            'daysOfTotal' => $this->monthRepository->getTotalOfDays($daysInfo),
        ]);
    }

    /**
     * 月の支出を編集する画面
     *
     * @param $date
     * @return mixed
     */
    public function edit($date)
    {
        return view('months.edit')->with([
            'date' => $date,
            'month' => $this->monthRepository->find(str_replace('-', '', $date)),
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
        $date = Carbon::parse($request->input('date'));
        $attributes['user'] = Auth::user()->name;
        $this->monthRepository->update($attributes, $monthId);
        session()->put('flash_message', 'データを更新しました');

        return redirect('/months/'. $date->format('Y-m'));
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
        return view('months.detail')->with([
            'monthInfo' => $monthInfo,
            'spending1' => $this->monthRepository->getPieChart('spending1', $data1, $labels1),
            'spending2' => $this->monthRepository->getPieChart('spending2', $data2, $labels2),
        ]);
    }
}