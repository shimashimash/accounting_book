@extends('layouts.app')

@php($monthlyCategories = App\Repositories\Entities\Month::$monthlyCategories)

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="header">
                <div>
                    <a href="/home/{{ $date['last']['year'] }}/{{ sprintf('%02d', $date['last']['month']) }}"
                       class="btn btn-default">
                        前月へ
                    </a>
                </div>
                <div>
                    <h2>{{ $date['year'] }}年{{ (int) $date['month'] }}月</h2>
                </div>
                <div>
                    <a href="/home/{{ $date['next']['year'] }}/{{ sprintf('%02d', $date['next']['month']) }}"
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
                            <a href="/home/detail/{{ $date['year']. sprintf('%02d', $date['month']) }}"
                               class="btn btn-primary">
                                詳細情報
                            </a>
                            <a href="/home/{{ $date['year'] }}/{{ sprintf('%02d', $date['month']) }}/edit"
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
                                <td><a href="/add/{{ $date['year'] }}/{{ $date['month'] }}/{{ $day }}">{{ $day }}({{ $daysOfWeek }})</a></td>
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
