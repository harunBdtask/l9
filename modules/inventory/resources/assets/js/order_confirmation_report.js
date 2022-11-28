var table_body = $('.table-body').html();
$(document).on('change', '.order-confirmation-report #artwork_no', function () {
    $('.main-load-pagination .pagination').hide();
    var artwork_no = $(this).val();
    var buyer_id = $('#buyer_id').val();
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    if (artwork_no.length == 0) {
        artwork_no = 0;
    }
    if (buyer_id.length == 0) {
        buyer_id = 0;
    }
    if (date_from.length == 0) {
        alert('Date From must be selected!');
        return;
    }
    if (date_to.length == 0) {
        alert('Date To must be selected!');
        return;
    }
    var start_date_array = date_from.split('/');
    var end_date_array = date_to.split('/');
    date_from = start_date_array[2] + '_' + start_date_array[1] + '_' + start_date_array[0];
    date_to = end_date_array[2] + '_' + end_date_array[1] + '_' + end_date_array[0];
    if (artwork_no && date_from && date_to) {
        $.ajax({
            type: 'GET',
            url: '/get-order-confirmation-report-data?artwork_no=' + artwork_no + '&buyer_id=' + buyer_id + '&date_from=' + date_from + '&date_to=' + date_to,
            success: function (response) {
                var pdf_url = '/get-order-confirmation-report-data-download/pdf/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                var excel_url = '/get-order-confirmation-report-data-download/excel/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                if (response.status == 200 && response.order_info_data != null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', pdf_url);
                    $('#order-confirmation-report-xls').attr('href', excel_url);
                    $('#buyer_id').val(response.buyer).trigger('change');
                } else {
                    $('.table-body').html(table_body);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
                if (response.status == 500 && response.order_info_data == null) {
                    $('.table-body').html(response.html);
                    $('#buyer_id').val(response.buyer).trigger('change');
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
            }
        });
    } else {
        $('.table-body').html(table_body);
        $('#buyer_id').val('').select2();
        $('#order-confirmation-report-pdf').attr('href', '');
        $('#order-confirmation-report-xls').attr('href', '');
    }
});

$(document).on('change', '.order-confirmation-report #buyer_id', function () {
    $('.main-load-pagination .pagination').hide();
    var buyer_id = $(this).val();
    var artwork_no = $('#artwork_no').val();
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    if (artwork_no.length == 0) {
        artwork_no = 0;
    }
    if (buyer_id.length == 0) {
        buyer_id = 0;
    }
    if (date_from.length == 0) {
        alert('Date From must be selected!');
        return;
    }
    if (date_to.length == 0) {
        alert('Date To must be selected!');
        return;
    }
    var start_date_array = date_from.split('/');
    var end_date_array = date_to.split('/');
    date_from = start_date_array[2] + '_' + start_date_array[1] + '_' + start_date_array[0];
    date_to = end_date_array[2] + '_' + end_date_array[1] + '_' + end_date_array[0];
    if (buyer_id && date_from && date_to) {
        $.ajax({
            type: 'GET',
            url: '/get-order-confirmation-report-data?artwork_no=' + artwork_no + '&buyer_id=' + buyer_id + '&date_from=' + date_from + '&date_to=' + date_to,
            success: function (response) {
                var pdf_url = '/get-order-confirmation-report-data-download/pdf/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                var excel_url = '/get-order-confirmation-report-data-download/excel/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                if (response.status == 200 && response.order_info_data != null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', pdf_url);
                    $('#order-confirmation-report-xls').attr('href', excel_url);

                } else {
                    $('.table-body').html(table_body);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
                if (response.status == 500 && response.order_info_data == null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
            }
        });
    } else {
        $('.table-body').html(table_body);
        $('#order-confirmation-report-pdf').attr('href', '');
        $('#order-confirmation-report-xls').attr('href', '');
    }
});

$(document).on('change', '.order-confirmation-report #date_to', function () {
    $('.main-load-pagination .pagination').hide();
    var artwork_no = $('#artwork_no').val();
    var buyer_id = $('#buyer_id').val();
    var date_from = $('#date_from').val();
    var date_to = $(this).val();
    if (artwork_no.length == 0) {
        artwork_no = 0;
    }
    if (buyer_id.length == 0) {
        buyer_id = 0;
        alert('Buyer must be selected!');
        return;
    }
    if (date_from.length == 0) {
        alert('Date From must be selected!');
        return;
    }
    if (date_to.length == 0) {
        alert('Date To must be selected!');
        return;
    }
    var start_date_array = date_from.split('/');
    var end_date_array = date_to.split('/');
    date_from = start_date_array[2] + '_' + start_date_array[1] + '_' + start_date_array[0];
    date_to = end_date_array[2] + '_' + end_date_array[1] + '_' + end_date_array[0];
    if (date_from && date_to) {
        $.ajax({
            type: 'GET',
            url: '/get-order-confirmation-report-data?artwork_no=' + artwork_no + '&buyer_id=' + buyer_id + '&date_from=' + date_from + '&date_to=' + date_to,
            success: function (response) {
                var pdf_url = '/get-order-confirmation-report-data-download/pdf/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                var excel_url = '/get-order-confirmation-report-data-download/excel/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                if (response.status == 200 && response.order_info_data != null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', pdf_url);
                    $('#order-confirmation-report-xls').attr('href', excel_url);
                } else {
                    $('.table-body').html(table_body);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
                if (response.status == 500 && response.order_info_data == null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
            },
        });
    } else {
        $('.table-body').html(table_body);
        $('#order-confirmation-report-pdf').attr('href', '');
        $('#order-confirmation-report-xls').attr('href', '');
    }
});

$(document).on('click', '.order-confirmation-report .pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var myurl = $(this).attr('href');
    var page = $(this).attr('href').split('page=')[1];
    getData(page);
});

function getData(page) {
    var artwork_no = $('#artwork_no').val();
    var buyer_id = $('#buyer_id').val();
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    if (artwork_no.length == 0) {
        artwork_no = 0;
    }
    if (buyer_id.length == 0) {
        buyer_id = 0;
    }
    if (date_from.length == 0) {
        alert('Date From must be selected!');
        return;
    }
    if (date_to.length == 0) {
        alert('Date To must be selected!');
        return;
    }
    var start_date_array = date_from.split('/');
    var end_date_array = date_to.split('/');
    date_from = start_date_array[2] + '_' + start_date_array[1] + '_' + start_date_array[0];
    date_to = end_date_array[2] + '_' + end_date_array[1] + '_' + end_date_array[0];
    $.ajax(
        {
            url: '/get-order-confirmation-report-data?artwork_no=' + artwork_no + '&buyer_id=' + buyer_id + '&date_from=' + date_from + '&date_to=' + date_to + '&page=' + page,
            type: "GET",
            success: function (response) {
                var pdf_url = '/get-order-confirmation-report-data-download/pdf/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                var excel_url = '/get-order-confirmation-report-data-download/excel/' + artwork_no + '/' + buyer_id + '/' + date_from + '/' + date_to;
                if (response.status == 200 && response.order_info_data != null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', pdf_url);
                    $('#order-confirmation-report-xls').attr('href', excel_url);
                } else {
                    $('.table-body').html(table_body);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
                if (response.status == 500 && response.order_info_data == null) {
                    $('.table-body').html(response.html);
                    $('#order-confirmation-report-pdf').attr('href', '');
                    $('#order-confirmation-report-xls').attr('href', '');
                }
            },
        })
}
