@extends('cuttingdroplets::layout')
@push('style')
	<style>
		#parentTableFixed {
			height: 400px !important;
		}

		.box-header {
			padding-top: .60rem !important;
			padding-bottom: .60rem !important;
		}

		@media screen and (-webkit-min-device-pixel-ratio: 0) {
			input[type=date].form-control form-control-sm {
				line-height: 1;
			}
		}
	</style>
@endpush
@section('title', 'Line Wise Input Inhand Report')
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header text-center">
						<h2> Line Wise Input Inhand Report
							<span class="pull-right">
                                {{--  <a href="{{
                                    $floor_id
                                    ? url("floor-line-wise-cutting-report-download?type=pdf&floor_id=$floor_id&from_date=$fromDate&to_date=$toDate")
                                    : '#'}}">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                  </a>
                                  |--}}
								<a href="{{ url("floor-line-wise-cutting-report-download?type=xls&buyer_id=".($buyer_id ?? null)."&order_id=".($order_id ?? null)."&date=".($date ?? null)) }}">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
						</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body">
						{!! Form::open(['url' => '/floor-line-wise-cutting-report', 'method' => 'GET', 'autocomplete' => 'off']) !!}
						<div class="form-group">
							<div class="row m-b">
								<div class="col-sm-2">
									<label>Date</label>
									{!! Form::text('date', $date ?? null, ['class' => 'datepicker form-control form-control-sm', 'placeholder' => 'yyyy-mm-dd', 'id' => 'custom-datepicker']) !!}
								</div>
								<div class="col-sm-2">
									<label>Buyer</label>
									{!! Form::select('buyer_id', $buyers, $buyer_id ?? null, ['class' => 'select2-input form-control form-control-sm', 'placeholder' => 'Select Buyer']) !!}
								</div>
								<div class="col-sm-2">
									<label>Style</label>
									{!! Form::select('order_id', $orders, $order_id ?? null, ['class' => 'select2-input form-control form-control-sm', 'placeholder' => 'Select Booking']) !!}
								</div>
								<div class="col-sm-2">
									<label>&nbsp;</label>
									<button type="submit" class="btn btn-primary btn-sm form-control form-control-sm"><i class="fa fa-search"></i> Search</button>
								</div>
							</div>
						</div>
						{!! Form::close() !!}
						<div id="parentTableFixed" class="table-responsive">
							@include('cuttingdroplets::reports.tables.floor_line_wise_cutting_report_table_for_view')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
	<script>
		$(document).ready(function () {
			$("#fixTable").tableHeadFixer();
			$('#custom-datepicker').datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true
			});
		});

		$(document).on('change', '[name="buyer_id"]', function (e) {
			e.preventDefault();
			var bookingSelectDom = $('[name="order_id"]');
			bookingSelectDom.empty();
			bookingSelectDom.val('').select2();
			var buyer_id = $(this).val();
			if (buyer_id) {
				$.ajax({
					type: 'GET',
					url: '/get-orders-with-booking-no/' + buyer_id,
					success: function (response) {
						var bookingNoDropdown = '<option value="">Select Booking</option>';
						if (Object.keys(response).length > 0) {
							$.each(response, function (index, data) {
								bookingNoDropdown += '<option value="' + data.id + '">' + data.booking_no + '</option>';
							});
						}
						bookingSelectDom.html(bookingNoDropdown);
						bookingSelectDom.val('').select2();
					}
				});
			}
		});

	</script>
@endsection
