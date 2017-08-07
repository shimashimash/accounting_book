/**
 * Created by shimadu on 2017/03/15.
 */
$(function() {

    /**
     * ajax
     *
     */
    ajaxForm = function(url, $year, $month) {
        //post送信するためにトークンを取得する
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        console.log(url);
        //ajaxで送信した結果を返す
        return $.ajax({
            url: url,
            type: 'POST',
            cache: false,
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify({
                year: $year,
                month: $month
            })
        });
    };

    // 先月へボタン
    // $('.prev').on('click', function() {
    //     $year = parseInt($('.year').text(), 10);
    //     $month = parseInt($('.month').text(), 10);
    //     if ($month === 1) {
    //         $month = 12;
    //         $year -= 1;
    //     } else {
    //         $month -= 1;
    //     }
    //     // ajaxを実行する
    //     ajaxForm('ajax/getMonthInfo', $year, $month);
    // });
    //
    // // 来月へボタン
    // $('.next').on('click', function() {
    //     $year = parseInt($('.year').text(), 10);
    //     $month = parseInt($('.month').text(), 10);
    //     if ($month === 12) {
    //         $month = 1;
    //         $year += 1;
    //     } else {
    //         $month += 1;
    //     }
    //     // ajaxを実行する
    //     ajaxForm('ajax/getMonthInfo', $year, $month);
    // });


});