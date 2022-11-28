var table_body = $('.table_body').html();
$(document).on('change', '.sample-approval-report #date_to', function () {
    var date_from = $('#date_from').val();
    var date_to = $(this).val();
    if(date_from.length == 0){
        alert('Date From must be selected!');
        return;
    }
    if(date_to.length == 0){
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
            url: '/get-sample-approval-report-data?date_from=' + date_from + '&date_to=' + date_to,
            success: function (response) {
                if (response.status == 200 && response.sample_approval_report_data != null) {
                    $('.table_body').html(response.html);
                } else {
                    $('.table_body').html(table_body);
                }
            }
        });
    } else {
        $('.table_body').html(table_body);
    }
});

$(document).on('click', '.sample-approval-report .pagination a', function (event) {
    event.preventDefault();
    $('li').removeClass('active');
    $(this).parent('li').addClass('active');
    var myurl = $(this).attr('href');
    var page = $(this).attr('href').split('page=')[1];
    getSampleData(page);
});

function getSampleData(page) {
    var date_from = $('#date_from').val();
    var date_to = $('#date_to').val();
    var start_date_array = date_from.split('/');
    var end_date_array = date_to.split('/');

    date_from = start_date_array[2] + '_' + start_date_array[1] + '_' + start_date_array[0];
    date_to = end_date_array[2] + '_' + end_date_array[1] + '_' + end_date_array[0];
    if (date_from && date_to) {
        $.ajax({
            type: 'GET',
            url: '/get-sample-approval-report-data?date_from=' + date_from + '&date_to=' + date_to +'&page=' + page,
            success: function (response) {
                if (response.status == 200 && response.sample_approval_report_data != null) {
                    $('.table_body').html(response.html);
                } else {
                    $('.table_body').html(table_body);
                }
            }
        });
    } else {
        $('.table_body').html(table_body);
    }
}