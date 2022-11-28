var report_div = $('.report-div').html();
var first_table_body = $('.first_table_body').html();
var second_table_head = $('.second_table_head').html();
var second_table_body = $('.second_table_body').html();
$(document).on('change','.sample-development-report #artwork_no',function () {
    var artwork_no = $(this).val();
    $('.report-div').addClass('hide');
    $('.first_table_body').html(first_table_body);
    $('.second_table_head').html(second_table_head);
    $('.second_table_body').html(second_table_body);
    $('.report-no-data-div').addClass('hide');
    if(artwork_no){
        $.ajax({
            type: 'GET',
            url: '/get-artwork-reference-list/'+artwork_no,
            success: function (response) {
                $("#artwork_ref_no").empty();
                if(response.status == 200 && response.artwork_reference_list != null){
                    $('#artwork_ref_no').append('<option value="">Please select one</option>');
                    $.each(response.artwork_reference_list, function (key, artwork_reference) {
                        $('#artwork_ref_no').append('<option value="' + artwork_reference.id + '">' + artwork_reference.reference + '</option>');
                    });
                    $('#buyer_id').val(response.buyer).select2();
                } else {
                    $('#artwork_ref_no').append('<option value="">No Data Found</option>');
                    $('#buyer_id').val('').select2();
                }
            }
        });
    } else {
        $('#artwork_ref_no').empty();
        $('#artwork_ref_no').append('<option value="">Please select one</option>');
        $('#buyer_id').val('').select2();
    }
});

$(document).on('change','.sample-development-report #artwork_ref_no',function () {
    var artwork_ref_no = $(this).val();
    var artwork_no = $('#artwork_no').val();
    var buyer_id = $('#buyer_id').val();
    if(buyer_id.length == 0){
        buyer_id = 0;
    }
    if(artwork_ref_no && artwork_no){
        $.ajax({
            type: 'GET',
            url: '/get-sample-development-report-data/'+artwork_no + '/'+ artwork_ref_no+'/'+buyer_id,
            success: function (response) {
                if(response.status == 200 && response.sample_development_report_data != null){
                    $('.report-no-data-div').addClass('hide');
                    $('.report-div').removeClass('hide');
                    $('.first_table_body').html(response.first_table_body);
                    $('.second_table_head').html(response.second_table_head);
                    $('.second_table_body').html(response.second_table_body);
                } else {
                    $('.report-no-data-div').removeClass('hide');
                    $('.report-div').addClass('hide');
                    $('.first_table_body').html(first_table_body);
                    $('.second_table_head').html(second_table_head);
                    $('.second_table_body').html(second_table_body);
                }
            }
        });
    } else {
        $('.report-no-data-div').removeClass('hide');
        $('.report-div').addClass('hide');
        $('.first_table_body').html(first_table_body);
        $('.second_table_head').html(second_table_head);
        $('.second_table_body').html(second_table_body);
    }
});

