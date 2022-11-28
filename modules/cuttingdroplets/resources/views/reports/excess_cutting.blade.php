@extends('cuttingdroplets::layout')
@php
	$tableHeadColorClass = 'tableHeadColor';
	if (isset($type) || request()->has('type') || request()->route('type')) {
		$tableHeadColorClass = '';
	}
@endphp
@section('title', 'Order Wise Excess Cutting Production Report')
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header text-center">
						@php
							$currentPage = $reports ? $reports->currentPage() : 1;
						@endphp
						<h2>Order Wise Excess Cutting Production Report
							<span class="pull-right">
                                <a href="{{ url('/excess-cutting-report-download?type=pdf&'.request()->getQueryString()) }}">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                                </a>
                                |
                                <a href="{{ url('/excess-cutting-report-download?type=xls&'.request()->getQueryString()) }}">
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
						<div class="row form-group">
						{!! Form::open(['url' => 'excess-cutting-report', 'method' => 'GET', 'autocomplete' => 'off', 'id' => 'excess-cutting-report-form']) !!}
							<div class="col-sm-2">
								<label>Buyer</label>
								{!! Form::select('buyer_id', $buyers ?? [], $buyer_id ?? null, ['class' => 'form-control form-control-sm']) !!}
							</div>
							<div class="col-md-2">
								<label>Order/Style</label>
								{!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm']) !!}
							</div>
							<div class="col-sm-3">
								<label>From Date</label>
								{!! Form::text('from_date', request('from_date') ?? null, ['class' => 'form-control form-control-sm date-field', 'placeholder' => 'yyyy-mm-dd']) !!}
							</div>
							<div class="col-sm-3">
								<label>To Date <sub class="text-warn small">[Give max 6 month date range]</sub></label>
								{!! Form::text('to_date', request('to_date') ?? null, ['class' => 'form-control form-control-sm date-field', 'placeholder' => 'yyyy-mm-dd']) !!}
							</div>
							<div class="col-sm-2">
								<label>&nbsp;</label>
								<button type="submit" class="btn btn-sm btn-primary form-control form-control-sm">Submit</button>
							</div>
              {!! Form::close() !!}
						</div>
						<div id="parentTableFixed" class="table-responsive">
							<table class="reportTable" id="fixTable">
								<thead>
								<tr>
									<th>SL</th>
									<th>Buyer</th>
									<th>Order/Style</th>
									<th>PO</th>
									<th>PO Qty</th>
									<th>Today's Cutting</th>
									<th>Total Cutting</th>
									<th>Extra Qty</th>
									<th>Extra Cutting(%)</th>
								</tr>
								</thead>
								<tbody>
								@if(!$reports->getCollection()->isEmpty())
									@php
										$total_order_qty = 0;
										$todays_cutting_qty = 0;
										$total_cutting_qty = 0;
										$total_extra_qty = 0;
                    $sl = 0;
									@endphp
									@foreach($reports->getCollection()->groupBy('purchase_order_id') as $reportByPurchaseOrder)
										@php
											$buyer_name = $reportByPurchaseOrder->first()->buyer->name;
											$style_name = $reportByPurchaseOrder->first()->order->style_name ?? '';
											$po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
											$po_quantity = $reportByPurchaseOrder->first()->purchaseOrder->po_pc_quantity > 0 ? $reportByPurchaseOrder->first()->purchaseOrder->po_pc_quantity : $reportByPurchaseOrder->first()->purchaseOrder->po_quantity;
											$todays_cutting = $reportByPurchaseOrder->sum('todays_cutting') - $reportByPurchaseOrder->sum('todays_cutting_rejection');
											$total_cutting = $reportByPurchaseOrder->sum('total_cutting') - $reportByPurchaseOrder->sum('total_cutting_rejection');
											$extra_qty = $total_cutting - ($po_quantity ?? 0);
										@endphp
                    @if($po_quantity > $total_cutting)
                      @continue
                    @endif
                    @php
                      $total_order_qty += $po_quantity;
											$todays_cutting_qty += $todays_cutting;
											$total_cutting_qty += $total_cutting;
											$extra_cutting_percent = ($po_quantity > 0) ? ((($extra_qty) * 100) / $po_quantity) : 0;
											$total_extra_qty += $extra_qty ?? 0;
                    @endphp
										<tr>
											<td>{{ ++$sl }}</td>
											<td>{{ $buyer_name }}</td>
											<td>{{ $style_name }}</td>
											<td>{{ $po_no }}</td>
											<td>{{ $po_quantity }}</td>
											<td>{{ $todays_cutting }}</td>
											<td>{{ $total_cutting }}</td>
											<td>{{ $extra_qty }}</td>
											<td>{{ number_format($extra_cutting_percent,2) }}</td>
										</tr>
									@endforeach
									<tr>
										<th colspan="4">Total</th>
										<th>{{$total_order_qty}}</th>
										<th>{{$todays_cutting_qty}}</th>
										<th>{{$total_cutting_qty}}</th>
										<th>{{$total_extra_qty}}</th>
										<th></th>
									</tr>
								@else
									<tr class="tr-height">
										<td colspan="9" class="text-center text-danger">No Data</td>
									</tr>
								@endif
								</tbody>
								<tfoot>
								@if($reports->total() > 15)
									<tr>
										<td colspan="9" align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
									</tr>
								@endif
								</tfoot>
							</table>
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

			$('[name="buyer_id"]').select2({
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

			$('[name="order_id"]').select2({
				ajax: {
					url: function (params) {
						return `/utility/get-styles-for-select2-search`
					},
					data: function (params) {
            const buyerId = $('[name="buyer_id"]').val();
            return {
							search: params.term,
              buyer_id: buyerId,
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
				placeholder: 'Select Style',
				allowClear: true
			});

			$(document).on('change', '[name="buyer_id"]', function (e) {
				let buyerSelectDom = $(this);
				let orderSelectDom = $('[name="order_id"]');
				let buyerId = buyerSelectDom.val();
				orderSelectDom.val('').change();
			});

			$(document).on('change', '[name="order_id"]', function (e) {
				let buyerSelectDom = $('[name="buyer_id"]');
				let orderSelectDom = $(this);
				let orderId = orderSelectDom.val();
				let buyerId = buyerSelectDom.val();
			});
		})
	</script>
@endsection
