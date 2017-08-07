$(document).ready(function() {

    /**
     * 予約のチェック
     * ・チェックの決まり
     *  1. 選択できるのは日付ごと
     *  2. 3時間までなら範囲選択できる
     *  3. 3時間以上選択するとクリックしたもののみ選択される
     *  4. 予約可以外の項目を挟んで選択できない
     */
        // グローバル変数
    var selectedDay = '';
    var selectedRange = [];
    $(document).on('click', '.is-open', function() {
        if (selectedDay !== $(this).data('day')) {
            // 別の日付をクリックしたらその前にチェックした日のチェックを外す
            $('[data-day="' + selectedDay + '"]').removeClass('selected');
            selectedDay = $(this).data('day');
            selectedRange = [];
        }

        if ($(this).hasClass('selected')) {
            // クリックされたtdにチェックがついていたら何もしない
            return false;
        } else {
            selectedRange.push($(this).data('row-num'));
            var max = Math.max.apply(null, selectedRange);
            var min = Math.min.apply(null, selectedRange);
            if (5 >= max - min) {
                // 3h以下の範囲で選択した場合
                for (var i=min; i<=max; i++) {
                    var $td = $('[data-row-num="' + i + '"]' + '[data-day="' + selectedDay + '"]');
                    if (! $td.hasClass('is-open')) {
                        // 選択した範囲に予約可以外のクラスがある場合
                        resetSelected(this);
                        return false;
                    }
                    $td.addClass('selected');
                }
            } else {
                // 3hより多い範囲を選択した場合
                resetSelected(this);
            }
        }
        // 予約するボタン
        showReserveBtn(this);
    });

    /**
     * チェックの選択状態を初期化する
     */
    var resetSelected = function(selector) {
        $('.is-open').removeClass('selected');
        selectedRange = [];
        selectedRange.push($(selector).data('row-num'));
        $(selector).addClass('selected');
    };

    /**
     * 予約するボタンの表示・非表示
     */
    var showReserveBtn = function(selector) {
        var position = $(selector).position();
        var $reserveBtn = $('#reserveBtn');
        $reserveBtn.css({
            top: (position.top) - 40,
            left: (position.left) + 6,
            display: 'block'
        });
        if ($('.is-open.selected').length === 0) {
            $reserveBtn.css({
                display: 'none'
            });
        }
    };

    /**
     * 予約するボタン押下
     */
    $(document).on('click', '.date', function() {
        console.log('hogee');
    });

    /**
     * 企業が変更されたら応募者を取得する
     */
    $(document).on('change', '#reserve-company', function() {
        $('#reserve-company option[value="0"]').attr('disabled', true);
        var companyId = $(this).val(),
            url = '/cms/calendar_infos/applicant/' + companyId;
        $.ajax({
            "url": url,
            "type": "POST"
        }).done(function(data, textStatus, jqXHR) {
            var selectBox = '';
            selectBox += '<select name="reserve[applicant]" id="reserve-applicant" class="form-control" style="font-weight: normal;">';
            selectBox += '<option value="0" selected disabled>応募者を選択してください</option>';
            $.each(data, function(i, elem) {
                selectBox += '<option value="' + i + '">' + elem + '</option>';
            });
            selectBox += '</select>';
            $('#applicant').append(selectBox);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert('応募者の取得に失敗しました');
            console.error('ajax error ', errorThrown);
        });
    });

    /**
     * モーダルの初期化
     */
    var initModal = function() {
        $('#reserve-day').val('');
        $('#timeStart option, #timeEnd option').text('').val('');
        $('#timeStart, #timeEnd, #reserve-company, #bookRoom, #cancelRoom').attr('disabled', false);
        $('#reserve-applicant').remove();
        $('#reserve-company').val('0');
        $('#viewlistbooking tbody').empty();
        $('.delete').remove();
    };

    /**
     * 予約ボタン(利用予約モーダル)押下
     */
    $(document).on('click', '#bookRoom', function() {
        $('#reserve-form').submit();
    });

    /**
     * 面談が終了した td を押下
     */
    $(document).on('click', '.history-booked', function() {
        initModal();
        $('#timeStart, #timeEnd, #bookRoom, #cancelRoom').attr('disabled', true);
        getRoom(this);
    });

    /**
     * 予約済みの td を押下
     */
    $(document).on('click', '.cancel-booked', function() {
        var $form = $('#reserve-form');
        initModal();
        $('#timeStart, #timeEnd, #bookRoom').attr('disabled', true);
        getRoom(this);
        // form の action を変更する
        $form.attr('action', '/cms/calendar_infos/' + $(this).data('room-id'));
        $form.append('<input type="hidden" name="_method" class="delete" value="DELETE">');
        $('input[name="reserve[date]"]').val($(this).data('day'));
    });

    /**
     * 面談情報を取得し、モーダルの表示を変更する
     */
    var getRoom = function(selector) {
        var roomId = $(selector).data('room-id'),
            date = $(selector).data('day'),
            url = '/cms/calendar_infos/room/' + roomId;

        $.ajax({
            "url": url,
            "type": "POST"
        }).done(function(data, textStatus, jqXHR) {
            // htmlの表示を取得した面談情報に変更する
            var companyName = $('#reserve-company option[value="'+ data.companyId +'"]').text(),
                start = data.begin.match(/\d{2}:\d{2}/),
                end = data.end.match(/\d{2}:\d{2}/),
                selectBox = '',
                tr = '';
            $('#reserve-day').val(date);
            $('#timeStart option').text(start);
            $('#timeEnd option').text(end);
            $('#reserve-company').val(data.companyId);
            selectBox += '<select name="reserve[applicant]" id="reserve-applicant" class="form-control" style="font-weight: normal;">';
            selectBox += '<option selected>'+ data.applicantName +'</option>';
            selectBox += '</select>';
            $('#applicant').append(selectBox);
            $('#reserve-company, #reserve-applicant').attr('disabled', true);
            tr += '<tr>';
            tr += '<td>'+ companyName +'</td>';
            tr += '<td>'+ data.applicantName +'</td>';
            tr += '<td><a href="/cms/applicants/'+ data.applicantId +'">確認</a></td>';
            tr += '</tr>';
            $('#viewlistbooking tbody').append(tr);
        }).fail(function(jqXHR, textStatus, errorThrown) {
            alert('面談情報の取得に失敗しました');
            console.error('ajax error ', errorThrown);
        });
    };

    /**
     * 取り消しボタン押下
     */
    $(document).on('click', '#cancelRoom', function() {
        $('#reserve-form').submit();
    });

    (function() {
        //
    })();
});
