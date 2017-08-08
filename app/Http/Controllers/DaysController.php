<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostPriceRequest;
use App\Repositories\DayRepository;
use Carbon\Carbon;

class DaysController extends Controller
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
     * @param $date
     * @return mixed
     */
    public function index($date)
    {
        $date = Carbon::parse($date);
        $daysInfo = $this->dayRepository->firstOrCreate(
            [
                'month_id' => $date->format('Ym'),
                'day' => $date->format('d'),
            ]
        );
        return view('days.index')->with([
            'date' => $date,
            'daysInfo' => $daysInfo,
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
        $date = Carbon::parse($request->input('date'));
        $this->dayRepository->update($attr, $id);
        session()->put('flash_message', 'データを更新しました');

        return redirect('/months/'. $date->format('Y-m'));
    }
}
