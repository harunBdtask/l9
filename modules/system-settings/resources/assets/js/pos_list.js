var color_size_modal_table_body = $('.color_size_modal_table_body').html();
$(document).on('click','.po-list .po_breakdown_button',function () {
    var po_id = $(this).attr('po_id');
    if(po_id){
        $.ajax({
            type: "GET",
            url: "/get-po-color-size-breakdown/" + po_id,
            success: function (response) {

                if (response.status == 200 && response.html != null) {
                    $('.color_size_modal_table_body').html(response.html);
                } else if(response.status == 200 && response.html != null) {
                    $('.color_size_modal_table_body').html(response.html);
                }else {
                    $('.color_size_modal_table_body').html(color_size_modal_table_body);
                }
            }
        });
    } else {
        $('.color_size_modal_table_body').html(color_size_modal_table_body);
    }
});