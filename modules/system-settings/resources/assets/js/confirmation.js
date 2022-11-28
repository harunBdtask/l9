var Confirmation = function () {
	$('.show-modal').on('click', function(evt) {
		var actionUrl = $(this).attr('data-url');

	    $('#confirmationModal').on('show.bs.modal', function () {
	    	$('#confirmationForm').attr('action', actionUrl);
		});
	});

	$('#confirmationModal').on('hide.bs.modal', function () {
		$('#confirmationForm').attr('action', '');
	});
}

new Confirmation();