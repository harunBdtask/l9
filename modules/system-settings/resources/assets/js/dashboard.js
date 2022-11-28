$('#print-sent').ready(function(){   
    $.ajax({
        url: '/utility/get-print-sent-data',
        type: 'get',
        success: function(response){
            $('.print-sent-today').html(response.today);
            $('.print-sent-last-day').html(response.lastDay);
            $('.print-sent-this-week').html(response.thisWeek);
            $('.print-sent-last-week').html(response.lastWeek);
            $('.print-sent-this-month').html(response.thisMonth);
            $('.print-sent-last-month').html(response.lastMonth);
        }
    });
});

$('#print-received').ready(function(){   
    $.ajax({
        url: '/utility/get-print-received-data',
        type: 'get',
        success: function(response){
            $('.print-received-today').html(response.today);
            $('.print-received-last-day').html(response.lastDay);
            $('.print-received-this-week').html(response.thisWeek);
            $('.print-received-last-week').html(response.lastWeek);
            $('.print-received-this-month').html(response.thisMonth);
            $('.print-received-last-month').html(response.lastMonth);
        }
    });
});

$('#sewing-input').ready(function(){   
    $.ajax({
        url: '/utility/get-sewing-input-data',
        type: 'get',
        success: function(response){
            $('.input-today').html(response.today);
            $('.input-last-day').html(response.lastDay);
            $('.input-this-week').html(response.thisWeek);
            $('.input-last-week').html(response.lastWeek);
            $('.input-this-month').html(response.thisMonth);
            $('.input-last-month').html(response.lastMonth);
        }
    });
});

$('#sewing-output').ready(function(){
    $.ajax({
        url: '/utility/get-sewing-output-data',
        type: 'get',
        success: function(response){
            $('.output-today').html(response.today);
            $('.output-last-day').html(response.lastDay);
            $('.output-this-week').html(response.thisWeek);
            $('.output-last-week').html(response.lastWeek);
            $('.output-this-month').html(response.thisMonth);
            $('.output-last-month').html(response.lastMonth);
        }
    });
});

$('.print-sent-received').ready(function(){
    $.ajax({
        url: '/utility/get-washing-sent-received-data',
        type: 'get',
        success: function(response){
            // washing sent data
            $('.washing-sent-today').html(response.today_sent);
            $('.washing-sent-last-day').html(response.last_day_sent);
            $('.washing-sent-this-week').html(response.this_week_sent);
            $('.washing-sent-last-week').html(response.last_week_sent);
            $('.washing-sent-this-month').html(response.this_month_sent);
            $('.washing-sent-last-month').html(response.last_month_sent);

            // washing received data
            $('.washing-received-today').html(response.today_received);
            $('.washing-received-last-day').html(response.last_day_received);
            $('.washing-received-this-week').html(response.this_week_received);
            $('.washing-received-last-week').html(response.last_week_received);
            $('.washing-received-this-month').html(response.this_month_received);
            $('.washing-received-last-month').html(response.last_month_received);
        }
    });
});


$('#allrejection').ready(function(){
    $.ajax({
        url: '/utility/get-all-rejection-data',
        type: 'get',
        success: function(response){
            var rejectionRow;
            $.each(response, function(index, report){
                rejectionRow += '<tr><td><b>'+report.title+'</b></td><td>'+report.tfab_rejection+'</td><td>'
                +report.tprint_rejection+'</td><td>'+report.tsewing_rejection+'</td><td>'+report.twashing_rejection+'</td></tr>';
            });          
            $('.all-rejection').html(rejectionRow);
        }
    });
});