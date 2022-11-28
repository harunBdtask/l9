@extends('cuttingdroplets::layout')
@php
	$tableHeadColorClass = 'tableHeadColor';
	if (isset($type) || request()->has('type') || request()->route('type')) {
		$tableHeadColorClass = '';
	}
@endphp
@section('title', 'Buyer Booking Wise Fabric Consumption Report')

@section('content')
	<div class="padding">
		<div class="box">
			<div class="box-header">
				@php
					$currentPage = $reports ? $reports->currentPage() : 1;
				@endphp
				<h2>Buyer Booking Wise Fabric Consumption Report
					<span class="pull-right">
                        <a href="{{url('/buyer-style-wise-fabric-consumption-report-download?type=pdf&current_page='.$currentPage.'&buyer_id='.$buyer_id.'&order_id='.$order_id)}}">
                            <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                        </a>
                        |
                        <a href="{{url('/buyer-style-wise-fabric-consumption-report-download?type=xls&current_page='.$currentPage.'&buyer_id='.$buyer_id.'&order_id='.$order_id)}}">
                            <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                        </a>
                    </span>
				</h2>
			</div>
			<div class="box-divider m-a-0"></div>
			<div class="box-body">
				{!! Form::open(['url' => 'buyer-style-wise-fabric-consumption-report', 'method' => 'GET']) !!}
				<div class="form-group">
					<div class="col-sm-3">
						{!! Form::select('buyer_id', $buyers ?? [], $buyer_id ?? null, ['class' => 'form-control form-control-sm']) !!}
					</div>
					<div class="col-md-3">
						{!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm']) !!}
					</div>
					<div class="col-md-2">
						<button class="btn btn-sm btn-primary m-b form-control form-control-sm" type="submit" style="height: 28px; line-height: 14px">GO
						</button>
					</div>
				</div>
				{!! Form::close() !!}
				<div id="parentTableFixed" class="table-responsive">
					<table class='reportTable' id="fixTable">
						<thead>
						<tr>
							<th>Buyer</th>
							<th>Style</th>
							<th>Color</th>
							<th>SID</th>
							<th>Cutting No</th>
							<th>Fabric Save/Loss</th>
							<th>Cutting Date</th>
						</tr>
						</thead>
						<tbody>
						@if($reports && $reports->count())
							@foreach($reports as $report)
								@php
									$colors = $report->allColors ?? '';
									$cuttingNo = $report->cutting_no;

									if ($report->colors) {
											$cuttingNosWithColor = explode('; ', $cuttingNo);

											$cuttingNo = '';
											foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
													$cutting = explode(': ', $cuttingNoWithColor);
													$cuttingNo .= \SkylarkSoft\GoRMG\SystemSettings\Models\Color::findOrFail($cutting[0])->name . ': ' . $cutting[1] . '; ';
											}
											$cuttingNo = rtrim($cuttingNo, '; ');
									}

								@endphp
								<tr>
									<td>{{ $report->buyer->name }}</td>
									<td>{{ $report->order->style_name }}</td>
									<td>{{ $colors }}</td>
									<td>{{ $report->sid }}</td>
									<td>{{ $cuttingNo }}</td>
									<td>{{ number_format($report->fabric_save, 2) }} KGs</td>
									<td>{{ $report->bundleCards()->first()->cutting_date ? date('d/m/Y', strtotime($report->bundleCards()->first()->cutting_date)) : '' }}</td>
								</tr>
							@endforeach
							<tr>
								<th colspan="5">Total</th>
								<th>{{ number_format($reports->sum('fabric_save'), 2) }} KGs</th>
								<th>&nbsp;</th>
							</tr>
							@if($reports->currentPage() == $reports->lastPage() )
								<tr>
									<th colspan="5" align="center">Grand Total</th>
									<th>{{ number_format(\SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCardGenerationDetail::where('order_id', $order_id)->where(['is_regenerated' => 0,'is_manual' => 0])->get()->sum('fabric_save'), 2) }}
										KGs
									</th>
									<th>&nbsp;</th>
								</tr>
							@endif
							@if($reports->total() > 15)
								<tr>
									<td colspan="7"
									    align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
								</tr>
							@endif
						@else
							<tr>
								<th colspan="7" align="center">No Data</th>
							</tr>
						@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
	<script>
		$(function () {
			let buyerSelectDom = $('[name="buyer_id"]');
			let orderSelectDom = $('[name="order_id"]');
			let orders;
			$("#fixTable").tableHeadFixer();

			$('.date-field').datepicker({
				format: 'yyyy-mm-dd',
				autoclose: true,
				clearBtn: true
			});

			buyerSelectDom.select2({
				ajax: {
					url: '/utility/get-buyers-for-select2-search',
					data: function (params) {
						return {
							search: params.term,
						}
					},
					processResults: function (data, params) {
						return {
							results: data.results,
							pagination: {
								more: false
							}
						}
					},
					cache: true,
					delay: 250
				},
				placeholder: 'Select Buyer',
				allowClear: true
			});

			orderSelectDom.select2({
				ajax: {
					url: function (params) {
						return `/utility/get-styles-for-select2-search`
					},
					data: function (params) {
            const buyerId = buyerSelectDom.val();
            return {
							search: params.term,
              buyer_id: buyerId
						}
					},
					processResults: function (data, params) {
						orders = data;
						return {
							results: data.results,
							pagination: {
								more: false
							}
						}
					},
					cache: true,
					delay: 250
				},
				placeholder: 'Select Style',
				allowClear: true
			});

			$(document).on('change', 'select[name="buyer_id"]', function () {
				let orderId = orderSelectDom.val();
				if (orderId) {
					orderSelectDom.val('').change();
				}
			});
		});
	</script>
@endsection
