// Cutting Plan Permission Related Start
$(document).on('click', '.user-cutting-plan-permission-page .edit-button', function () {
    $(this).addClass('hide');
    $(this).parents('td').find('.submit-button').removeClass('hide');
    $(this).parents('td').find('.cancel-button').removeClass('hide');
    $(this).parents('tr').find('.email-view').addClass('hide');
    $(this).parents('tr').find('.email-input').removeClass('hide');
});

$(document).on('click', '.user-cutting-plan-permission-page .cancel-button', function () {
    $(this).addClass('hide');
    $(this).parents('td').find('.edit-button').removeClass('hide');
    $(this).parents('td').find('.submit-button').addClass('hide');
    $(this).parents('tr').find('.email-view').removeClass('hide');
    $(this).parents('tr').find('.email-input').addClass('hide');
});

$(document).on('click', '.user-cutting-plan-permission-page .submit-button', function () {
    var baseUrl = window.location.protocol + "//" + window.location.host + "/";
    var thisDom = $(this);
    var user_html = thisDom.parents('tr').find('select[name=user_id]');
    var user = user_html[0].options[user_html[0].selectedIndex].text;
    var cutting_floor_id = thisDom.parents('tr').find('input[name=cutting_floor_id]').val();
    var user_id = user_html.val();
    var messageArea = $('.js-response-message');
    var formData = {
        'cutting_floor_id': cutting_floor_id,
        'user_id': user_id,
        '_token': $('meta[name="csrf-token"]').attr('content')
    };
    if (user_id) {
        $.ajax({
            type: 'POST',
            url: baseUrl + 'user-cutting-floor-plan-permissions',
            data: formData
        }).done(function (response) {
            thisDom.addClass('hide');
            thisDom.parents('td').find('.cancel-button').addClass('hide');
            thisDom.parents('td').find('.edit-button').removeClass('hide');
            thisDom.parents('tr').find('.email-view').removeClass('hide');
            thisDom.parents('tr').find('.email-view').text(user);
            thisDom.parents('tr').find('.email-input').addClass('hide');
            let message = getMessage(response.message, response.type);
            messageArea.html(message);
            $('.js-response-message').fadeIn().delay(2000).fadeOut(2000);
        });
    } else {
        alert('Please select the user');
        return false;
    }

});

// Cutting Plan Permission Related End
function getMessage(message, type) {
    return '<div class="alert alert-' + type + '">' + message + '</div>';
}
