@extends('iedroplets::layout')
@section('title', 'Shipment Date &amp; Unit Price Update')
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header text-center">
						<h2>Shipment Date &amp; Unit Price Update</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body">
						<div class="js-response-message text-center"></div>
						<form id="shipmentDateAndInspectionUpdateForm">
							<div class="form-group">
								<div class="row m-b">
									<div class="col-sm-2">
										<label>Buyer</label>
										{!! Form::select('buyer_id', $buyers, null, ['class' => 'si-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
									</div>
									<div class="col-sm-2">
										<label>Style/Order</label>
										{!! Form::select('order_id', [], null, ['class' => 'si-booking-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Booking']) !!}
									</div>
									<div class="col-sm-2">
										<label>Style/Order No</label>
										{!! Form::select('order_id', [], null, ['class' => 'si-style-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Style', 'disabled' => true]) !!}
									</div>
								</div>
							</div>

							<table class="reportTable">
								<thead class="text-center">
								<tr>
									<th>Purchase Order</th>
									<th>Shipment Date</th>
									<th>Unit Price/Dz</th>
								</tr>
								</thead>
								<tbody class="price-inspection-date-update">

								</tbody>
							</table>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		$(document).on('change', '.si-buyer-select', function () {
			$('.si-booking-select').empty().select2();
			$('.si-style-select').empty().select2();
			$('.price-inspection-date-update').empty();
			$('.price-inspection-update-btn').hide();
			var buyer_id = $(this).val();
			if (buyer_id) {
				$.ajax({
					type: 'GET',
					url: '/get-orders-with-booking-no/' + buyer_id,
					success: function (response) {
						var bookingNoDropdown = '<option value="">Select a Booking</option>';
						var styleDropdown = '<option value="">Select a Style</option>';
						if (Object.keys(response).length > 0) {
							$.each(response, function (index, data) {
								bookingNoDropdown += '<option value="' + data.id + '">' + data.booking_no + '</option>';
								styleDropdown += '<option value="' + data.id + '">' + data.order_style_no + '</option>';
							});
							$('.si-booking-select').html(bookingNoDropdown);
							$('.si-style-select').html(styleDropdown);
						}
					}
				});
			}
		});

		$(document).on('change', '.si-booking-select', function () {
			$('.price-inspection-date-update').empty();
			$('.price-inspection-update-btn').hide();
			var order_id = $(this).val();
			$('.si-style-select').val(order_id).select2();
			if (order_id) {
				var result;
				$('.price-inspection-date-update').empty();
				$.ajax({
					type: 'GET',
					url: '/get-purchase-orders/' + order_id,
					success: function (response) {
						if (Object.keys(response).length > 0) {
							$.each(response, function(index, data) {
								result += [
			                        '<tr>',
			                            '<td>' + data.po_no + '<input type="hidden" name="ids[]" value="'+ data.id +'"></td>',
			                            '<td>' + '<input type="date" name="ex_factory_date[]" class="ex-factory-date" value="' + data.ex_factory_date + '"><p><span class="ex_factory_date text-danger"></span></p>' + '</td>',

			                            '<td>' + '<input type="number" name="unit_price[]" class="unit-price number-right" value="' + data.unit_price + '"><p><span class="unit_price text-danger"></span></p>' + '</td>',
			                        '</tr>',
			                    ].join();
							});

							result += [
								'<tr class="hide-button" style="height:45px !important;">',
									'<td colspan="3" class="text-center">'+
										'<button type="button" class="btn btn-sm btn-success update-btn">Submit</button>'+
									'</td></tr>'].join();

							$('.price-inspection-date-update').append(result);
							$('.price-inspection-update-btn').show();
						}
					}
				});
			}
		});

		$(document).on('click', '.update-btn', function () {
			var input = $("#shipmentDateAndInspectionUpdateForm").serialize();

			$.ajax({
				type: 'POST',
				url: '/inspection-date-and-unit-price-update-post',
				data: input,
				success: function (response) {
					if (response == 200) {
						$('.js-response-message').html(getMessage(U_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);
					} else {
						var message = response.message ? response.message : U_FAIL;
						$('.js-response-message').html(getMessage(message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
					}
					$(window).scrollTop(0);
				},
				error: function (response) {
					console.log(response.responseJSON)
					$.each(response.responseJSON.errors, function (errorIndex, errorValue) {
						let errorDomElement, error_index, errorMessage;
						errorDomElement = '' + errorIndex;
						errorDomIndexArray = errorDomElement.split(".");
						errorDomElement = 'span.' + errorDomIndexArray[0];
						error_index = errorDomIndexArray[1];
						errorMessage = errorValue[0];
						if (errorDomIndexArray.length == 1) {
							$(errorDomElement).html(errorMessage);
						}
					});
				}
			});
		});
	</script>
@endsection
