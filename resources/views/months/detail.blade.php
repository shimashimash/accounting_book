@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="header">
                <h2>{{ substr($monthInfo->id, 0, 4) }}年{{ (int) substr($monthInfo->id, 4)}}月</h2>
            </div>
            <div class="panel-body">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div style="width: 50%; float: left;">
                            <p>今月の決まっている支出合計グラフ</p>
                            {!! $spending1->render() !!}
                        </div>
                        <div style="width: 50%; float: left;">
                            <p>日ごとの支出合計グラフ</p>
                            {!! $spending2->render() !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="form-group control-btn">
                    <a href="javascript:history.back();" class="btn btn-default">戻る</a>
                </div>
            </div>
        </div>
    </div>
@endsection
