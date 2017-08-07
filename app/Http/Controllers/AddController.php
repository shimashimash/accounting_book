<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPriceRequest;
use App\Repositories\DayRepository;

class AddController extends Controller
{
    /**
     * @var DayRepository
     */
    protected $dayRepository;

    /**
     * Create a new controller instance.
     *
     * @param DayRepository $dayRepository
     */
    public function __construct(DayRepository $dayRepository)
    {
        $this->middleware('auth');
        $this->dayRepository = $dayRepository;
    }

    /**
     * 日の詳細画面を表示する
     *
     * @param $year
     * @param $month
     * @param $day
     * @return mixed
     */
    public function index($year, $month, $day)
    {
        $daysInfo = $this->dayRepository->firstOrCreate(['month_id' => $year. $month, 'day' => $day]);

        return view('add.index')->with([
            'daysInfo' => $daysInfo,
            'year' => $year,
            'month' => $month,
            'day' => $day
        ]);
    }

    /**
     * 更新処理
     *
     * @param PostPriceRequest $request
     * @param $id
     * @return mixed
     */
    public function edit(PostPriceRequest $request, $id)
    {
        $attr = $request->input('day');
        $this->dayRepository->update($attr, $id);
        session()->put('flash_message', 'データを更新しました');

        return redirect('/home/'.substr($attr['month_id'], 0, 4).'/'.substr($attr['month_id'], 4));
    }
}
