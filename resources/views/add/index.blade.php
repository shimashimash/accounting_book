@extends('layouts.app')

@php($dailyCategories = App\Repositories\Entities\Day::$dailyCategories)

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="date">
                <h3>{{ (int) $month }}月{{ $day }}日</h3>
            </div>
            <form action="/add/{{ $daysInfo->id }}" method="POST">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    @foreach ($dailyCategories as $categoryJpName)
                                        <th>{{ $categoryJpName }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach ($dailyCategories as $categoryName => $categoryJpName)
                                        <th>
                                            {!! Form::text('day['. $categoryName. ']',$daysInfo[$categoryName] ?? 0, ['class' => 'form-control']) !!}
                                        </th>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                        <div class="form-group">
                            {!! Form::textarea('day[note]', $daysInfo->note ?? '',['class' => 'form-control', 'placeholder' => 'メモ的なことを']) !!}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
                    {{ Form::hidden('day[month_id]', $year. $month) }}
                    {{ Form::hidden('day[day]', $day) }}
                    {{ csrf_field() }}
                    <div class="form-group control-btn">
                        {!! Form::submit('OK', ['class' => 'btn btn-primary']) !!}
                        <a href="javascript:history.back();" class="btn btn-default">戻る</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
