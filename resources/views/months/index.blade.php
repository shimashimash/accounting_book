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
                <table border="1" class="table table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10%;">日付</th>
                            <th style="width: 15%;">支出</th>
                            <th>メモ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dayAndWeek as $day => $daysOfWeek)
                            <tr>
                                <td>
                                    <a href="/days/{{ $date->format('Y-m'). '-'. $day }}">
                                        {{ $day }}({{ $daysOfWeek }})
                                    </a>
                                </td>
                                <td>{{ $days[$day]['total'] ?? '0' }}円</td>
                                <td>{{ $days[$day]['note'] ?? 'なし' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
