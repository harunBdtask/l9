@php
	$tableHeadColorClass = 'tableHeadColor';
	if (isset($type) || request()->has('type') || request()->route('type')) {
		$tableHeadColorClass = '';
	}
@endphp
@extends('cuttingdroplets::layout')

@section('styles')
	<style>
		#loader, .loader {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(226, 226, 226, 0.75) no-repeat center center;
			width: 100%;
			z-index: 1000;
		}

		.spin-loader {
			position: relative;
			top: 46%;
			left: 5%;
		}
	</style>
@endsection
@section('title', 'Buyer Wise Cutting Production Report')

@section('content')
	<div class="padding buyer-wise-cutting-report">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header text-center">
						<h2>Buyer Wise Cutting Production Report
							<span class="pull-right">
              <a href="" id="buyer-wise-pdf">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a id="buyer-wise-xls" href="">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
						</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body cutting-color-wise">
						@include('partials.response-message')
						<div class="form-group">
							<div class="row m-b">
								<div class="col-sm-3">
									<label>Buyer</label>
									{!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm']) !!}
								</div>
								<div class="col-sm-3">
									<label>From Date</label>
									{!! Form::text('from_date', request('from_date') ?? null, ['class' => 'form-control form-control-sm date-field', 'placeholder' => 'yyyy-mm-dd', 'autocomplete' => 'off']) !!}
								</div>
								<div class="col-sm-3">
									<label>To Date <sub class="text-warn">[Give max 6 month date range]</sub></label>
									{!! Form::text('to_date', request('to_date') ?? null, ['class' => 'form-control form-control-sm date-field', 'placeholder' => 'yyyy-mm-dd', 'autocomplete' => 'off']) !!}
									<span class="small text-danger to_date"></span>
								</div>
								<div class="col-sm-3">
									<label>&nbsp;</label>
									<button type="button" class="btn btn-sm btn-primary form-control form-control-sm" id="report-submit-btn">Submit</button>
								</div>
							</div>
						</div>

						<div id="parentTableFixed" class="table-responsive">
							<table class="reportTable" id="fixTable">
								<thead>
								<tr>
									<th>Style</th>
									<th>PO</th>
									<th>PO Quantity</th>
									<th>Today's Cutting</th>
									<th>Today's Cutting Rejection</th>
									<th>Today's OK Cutting</th>
									<th>Total Cutting</th>
									<th>Total Cutting Rejection</th>
									<th>Total OK Cutting</th>
									<th>Left/Extra Quantity</th>
									<th>Extra Cuttting (%)</th>
								</tr>
								</thead>
								<tbody class="color-wise-report">
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="loader">
			<div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		$(function () {
			var loaderHtml = $('#loader');

			$('.date-field').datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				clearBtn: true
			});

			$('[name="buyer_id"]').select2({
				ajax: {
					url: '/utility/get-buyers-for-select2-search',
					data: function (params) {
						return {
							search: params.term,
						}
					},
					processResults: function (data) {
						return {
							results: data.results,
							pagination: {
								more: false
							}
						}
					},
					delay: 250,
					cache: true,
				},
				placeholder: 'Select Buyer',
				allowClear: true
			});

			$(document).on('change', '[name="buyer_id"]', function (e) {
				e.preventDefault();
				$('.cutting-color-wise .color-wise-report').empty();
			});

			$(document).on('click', '#report-submit-btn', function (e) {
				e.preventDefault();
				$('.cutting-color-wise .color-wise-report').empty();
				getBuyerWiseCuttingData(1);
			});

			$(document).on('click', '.buyer-wise-cutting-report .pagination a', function (event) {
				event.preventDefault();
				$('li').removeClass('active');
				$(this).parent('li').addClass('active');
				let page = $(this).attr('href').split('page=')[1];
				getBuyerWiseCuttingData(page);
			});

			function getBuyerWiseCuttingData(page) {
				let buyer_id = $('.buyer-wise-cutting-report [name="buyer_id"]').val();
				let from_date = $('.buyer-wise-cutting-report [name="from_date"]').val();
				let to_date = $('.buyer-wise-cutting-report [name="to_date"]').val();
				$('span.text-danger').html('');
				if (buyer_id) {
					loaderHtml.show();
					$.ajax({
						type: 'GET',
						url: '/get-buyer-wise-cutting-report',
						data: {
							buyer_id: buyer_id,
							from_date: from_date,
							to_date: to_date,
							page: page,
						},
						success: function (response) {
							loaderHtml.hide();
							let pdf_url = `buyer-wise-cutting-report-download?type=pdf&buyer_id=${buyer_id}&from_date=${from_date}&to_date=${to_date}&page=${page}`;
							let excel_url = `buyer-wise-cutting-report-download?type=excel&buyer_id=${buyer_id}&from_date=${from_date}&to_date=${to_date}&page=${page}`;
							if (response.status == 200) {
								$('.cutting-color-wise .color-wise-report').html(response.html);
								$('.buyer-wise-cutting-report #buyer-wise-pdf').attr('href', pdf_url);
								$('.buyer-wise-cutting-report #buyer-wise-xls').attr('href', excel_url);
							}
							if (response.status == 500) {
								$('.cutting-color-wise .color-wise-report').html(response.html);
								$('.buyer-wise-cutting-report #buyer-wise-pdf').attr('href', '');
								$('.buyer-wise-cutting-report #buyer-wise-xls').attr('href', '');
								$('span.to_date').html(response.to_date);
							}
						},
						error: function (response) {
							loaderHtml.hide();
							console.log(response)
						}
					});
				} else {
					alert('Please select a buyer!');
				}
			}

		});
	</script>
@endsection
