var StatusUpdateConfirmation = function () {
  $('.status-update-modal').on('click', function (evt) {
    var actionUrl = $(this).attr('data-url');
    var alertMessage = $(this).attr('data-alertMessage');
    $('#statusUpdateModal').on('show.bs.modal', function () {
      $('#statusUpdateForm').attr('action', actionUrl);
      alertMessage = alertMessage ? alertMessage : 'Are you sure to execute this action?';
      $('#status-modal-alert-message').html(alertMessage);
    });
  });

  $('#statusUpdateModal').on('hide.bs.modal', function () {
    $('#statusUpdateForm').attr('action', '');
    alertMessage = 'Are you sure to execute this action?';
    $('#status-modal-alert-message').html(alertMessage);
  });
}

new StatusUpdateConfirmation();