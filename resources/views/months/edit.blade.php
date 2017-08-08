@extends('layouts.app')

@php
    $monthlyCategories = App\Repositories\Entities\Month::$monthlyCategories;
    $date = \Carbon\Carbon::parse($date);
@endphp

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="date">
                <h3>{{ $date->format('Y年m月') }}</h3>
            </div>
            <form action="/home/edit/{{ $month->id }}" method="POST">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach ($monthlyCategories as $categoryJpName)
                                        <th>{{ $categoryJpName }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($monthlyCategories as $categoryName => $categoryJpName)
                                        <th>
                                            {!! Form::text('month['. $categoryName. ']', $month[$categoryName] ?? 0, ['class' => 'form-control']) !!}
                                        </th>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="panel-body">
                    {{ Form::hidden('_method', 'PUT') }}
                    {{ Form::hidden('date', $date) }}
                    {{ csrf_field() }}
                    <div class="form-group control-btn">
                        {!! Form::submit('確定する', ['class' => 'btn btn-primary']) !!}
                        <a href="/home" class="btn btn-default">戻る</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
