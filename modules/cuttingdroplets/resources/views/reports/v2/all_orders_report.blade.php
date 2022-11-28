@extends('cuttingdroplets::layout')

@section('title', 'Order Wise Cutting Report')
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header text-center">
						<h2>Order Wise Cutting Report || {{ date("jS F, Y") }}
							<span class="pull-right">
                <a href="{{ url('/v2/all-orders-cutting-report-download?type=pdf&'.request()->getQueryString()) }}">
                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a> |
                <a href="{{ url('/v2/all-orders-cutting-report-download?type=xls&'.request()->getQueryString()) }}">
                    <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
						</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body">
						<div class="flash-message print-delete">
							@foreach (['danger', 'warning', 'success', 'info'] as $msg)
								@if(Session::has('alert-' . $msg))
									<p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
								@endif
							@endforeach
						</div>
						{!! Form::open(['url' => '/v2/all-orders-cutting-report', 'method' => 'get', 'autocomplete' => 'off']) !!}
						<div class="form-group">
							<div class="row m-b">
								<div class="col-sm-3">
									<label>Style</label>
									{!! Form::select('order_id', $orders ?? [], request('order_id') ?? null, ['class' => 'form-control form-control-sm']) !!}
								</div>
								<div class="col-sm-3">
									<label>&nbsp;</label>
									<button type="submit" class="btn btn-sm btn-primary form-control form-control-sm">Submit</button>
								</div>
							</div>
						</div>
						{!! Form::close() !!}

						<div id="parentTableFixed" class="table-responsive">
							@include('cuttingdroplets::reports.tables.v2.order_wise_cutting_report_table')
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script>
		$(function () {
			$('.date-field').datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				clearBtn: true
			});

			$('[name="order_id"]').select2({
				ajax: {
					url: '/utility/get-styles-for-select2-search',
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
				placeholder: 'Select Style',
				allowClear: true
			});
		})
	</script>
@endsection
