$(document).on('click', '.team-member-assign .add-more-row', function () {
    var table_row = $('.table-row');
    console.log(table_row.length);
    var innerHtml = $(this).parents('.table-row').html();
    var html = '<tr class="table-row">' + innerHtml + '</tr>';
    $(this).parents('.table-body').append(html);
    if (table_row.length > 0) {
        $(".remove").removeClass('hide');
    }
});

$(document).on('click', '.team-member-assign .remove', function () {
    var table_row = $('.table-row');
    console.log(table_row.length);
    $(this).parents('.table-row').remove();
    if (table_row.length === 2) {
        $(".remove").addClass('hide');
    }
});

$(document).on('click', '.team-member-assign .is_team_lead_radio', function () {
    var is_team_lead_radio = $(this).val();
    $(this).parents('td').find('.is_team_lead').val(is_team_lead_radio);
    $(this).parents('tr').siblings('tr').find('.is_team_lead').val(0);
});

$(document).on('click', '.team-member-assign .is_team_lead_radio_new', function () {
    var is_team_lead_radio = $(this).val();
    $(this).parents('td').find('.is_team_lead_new').val(is_team_lead_radio);
    $(this).parents('tr').siblings('tr').find('.is_team_lead_new').val(0);
    var old_team_lead = $('.is_team_lead_radio').val();
    if (old_team_lead == 1) {
        alert("Team Leader Exist");
        $(this).parents('tr').siblings('tr').find('.is_team_lead_new').val(0);
        $(this).parents('td').find('.is_team_lead_new').val(0);
        $(this).prop("checked", false);
    }
});

$(document).on('click', '.team-member-assign #add_new_members', function () {
    $('#team-assign-table').removeClass('hide');
    $('#team-assign-table-old').addClass('hide');
});

$(document).on('click', '.team-member-assign #cancel_new_members', function () {
    $('#team-assign-table').addClass('hide');
    $('#team-assign-table-old').removeClass('hide');

});