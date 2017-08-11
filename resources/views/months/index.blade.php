@extends('layouts.app')

@php
    $monthlyCategories = App\Repositories\Entities\Month::$monthlyCategories;
    $subMonth = $date->copy()->subMonth();
    $addMonth = $date->copy()->addMonth();
@endphp

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="header">
                <div>
                    <a href="/months/{{ $subMonth->format('Y-m') }}"
                       class="btn btn-default">
                        前月へ
                    </a>
                </div>
                <div>
                    <h2>{{ $date->format('Y年m月') }}</h2>
                </div>
                <div>
                    <a href="/months/{{ $addMonth->format('Y-m') }}"
                       class="btn btn-default">
                        来月へ
                    </a>
                </div>
            </div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach ($monthlyCategories as $categoryJpName)
                                        <th>{{ $categoryJpName }}</th>
                                    @endforeach
                                    <th>支出合計</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($monthlyCategories as $categoryName => $categoryJpName)
                                        <th>{{ $month->$categoryName ?? '0' }}円</th>
                                    @endforeach
                                    <th>{{ $monthOfTotal + $daysOfTotal }}円</th>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-ms-2">
                            <a href="/months/detail/{{ $date->format('Y-m') }}"
                               class="btn btn-primary">
                                詳細情報
                            </a>
                            <a href="/months/edit/{{ $date->format('Y-m') }}"
                               class="btn btn-primary">
                                編集する
                            </a>
                        </div>
                    </div>
                </div>
                <table class="table table-bordered table-calendar">
                    <thead>
                    <tr>
                        <th>月</th>
                        <th>火</th>
                        <th>水</th>
                        <th>木</th>
                        <th>金</th>
                        <th>土</th>
                        <th>日</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @php($count = 0)
                            @foreach ($calendars as $calendar)
                                <td style="width: 99px; height: 71px;">
                                    @if ('' != $calendar['day'])
                                        @php($day = (int) $calendar['day']->format('d'))
                                        <a href="/days/{{ $calendar['day']->format('Y-m-d') }}">
                                            {{ $day }}
                                        </a>
                                        <br>
                                        {{ $days[$day]['total'] ?? '0' }}円
                                        <br>
                                        @if (isset($days[$day]['note']))
                                            <img src="/memo-icon.png">
                                        @endif
                                    @endif
                                    @php($count++)
                                </td>
                        @if ($count == 7)
                        </tr>
                        <tr>
                        @php($count = 0)
                        @endif
                            @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
