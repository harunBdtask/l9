$(document).on('change', '.item-group-assign #select_factory_id', function () {
    var factory_id = $(this).val();
    if (factory_id) {
        $.ajax({
            type: "GET",
            url: "/get-item-groups-on-factory/" + factory_id,
            success: function (response) {
                $('#item_group_id').empty();
                if (response.status == 200 && response.item_groups != null) {
                    $('#item_group_id').append('<option value="">Please select one</option>');
                    $.each(response.item_groups, function (key, item_group) {
                        $('#item_group_id').append('<option value="' + item_group.id + '">' + item_group.item_group_name + '</option>');
                    });

                } else {
                    $('#item_group_id').html('<option value="">Item Group not found</option>');
                }
            },
        });
    }
});