@extends('cuttingdroplets::layout')
@section('title', 'Booking Balance Bundle Check Report')
@section('content')
	<div class="padding">
		<div class="row">
			<div class="col-md-12">
				<div class="box">
					<div class="box-header text-center">
						<h2>Style Balance Bundle Check</h2>
					</div>
					<div class="box-divider m-a-0"></div>
					<div class="box-body">
						{!! Form::open(['url' => 'booking-balance-bundle-scan-check', 'method' => 'GET']) !!}
						<div class="form-group">
							<div class="row m-b">
								<div class="col-sm-2">
									<label>Buyer</label>
									{!! Form::select('buyer_id', $buyers ?? [], $buyer_id ?? null, ['class' => 'form-control form-control-sm']) !!}
								</div>
								<div class="col-sm-2">
									<label>Order/Style</label>
									{!! Form::select('order_id', $orders ?? [], $order_id ?? null, ['class' => 'form-control form-control-sm']) !!}
								</div>
								<div class="col-sm-2">
									<label>&nbsp;</label>
									<button type="submit" class="btn btn-sm btn-info form-control form-control-sm"> Search</button>
								</div>
							</div>
						</div>
						{!! Form::close() !!}
						<div class="table-responsive">
							<table class="reportTable">
								<thead style="font-size: 12px !important">
								<tr style="background-color: #9bdeac;">
									<th>Total Cutting</th>
									<th>Total Input</th>
									<th>Total Output</th>
									<th>Total Sewing Rejection</th>
								</tr>
								</thead>
								<tbody>
								@php
									$total_cutting_bundles = $reports ? (array_key_exists('total_cutting_bundles', $reports) ? $reports['total_cutting_bundles'] : 0) : 0;
									$total_cutting_qty = $reports ? (array_key_exists('total_cutting_qty', $reports) ? $reports['total_cutting_qty'] : 0) : 0;
									$total_input_bundles = $reports ? (array_key_exists('total_input_bundles', $reports) ? $reports['total_input_bundles'] : 0) : 0;
									$total_input_qty = $reports ? (array_key_exists('total_input_qty', $reports) ? $reports['total_input_qty'] : 0) : 0;
									$total_output_bundles = $reports ? (array_key_exists('total_output_bundles', $reports) ? $reports['total_output_bundles'] : 0) : 0;
									$total_output_qty = $reports ? (array_key_exists('total_output_qty', $reports) ? $reports['total_output_qty'] : 0) : 0;
									$total_output_rejection = $reports ? (array_key_exists('total_output_rejection', $reports) ? $reports['total_output_rejection'] : 0) : 0;
									$balance = $total_input_qty - ($total_output_qty + $total_output_rejection);
									$cutting_balance = $total_cutting_qty - $total_input_qty;
								@endphp
								<tr>
									<td>{!! ($total_cutting_bundles > 0 && $total_cutting_qty > 0) ? '<b>'.$total_cutting_bundles.'</b> Bundles<br><b>'. $total_cutting_qty.'</b>pcs' : '<b>0</b> Bundles<br><b>0</b>pcs' !!}</td>
									<td>{!! ($total_input_bundles > 0 && $total_input_qty > 0) ? '<b>'.$total_input_bundles.'</b>  Bundles<br><b>'. $total_input_qty.'</b>pcs' : '<b>0</b> Bundles<br><b>0</b>pcs' !!}</td>
									<td>{!! ($total_output_bundles > 0 && $total_output_qty > 0) ? '<b>'.$total_output_bundles.'</b>  Bundles<br><b>'. $total_output_qty.'</b>pcs' : '<b>0</b> Bundles<br><b>0</b>pcs' !!}</td>
									<td>{!! $total_output_rejection > 0 ? '<b>'.$total_output_rejection.'</b> pcs' : '<b>0</b> Bundles<br><b>0</b>pcs' !!}</td>
								</tr>
								<tr style="background-color: #f6f4bc;">
									<th colspan="2">Cutting/Input WIP</th>
									<th colspan="2">{!! $cutting_balance > 0 ? '<b>'.$cutting_balance.'</b> pcs' : '<b>0</b> pcs' !!}</th>
								</tr>
								<tr style="background-color: #f6f4bc;">
									<th colspan="2">Line Balance</th>
									<th colspan="2">{!! $balance > 0 ? '<b>'.$balance.'</b> pcs' : '<b>0</b> pcs' !!}</th>
								</tr>
								</tbody>
							</table>
						</div>
						<div class="col-md-12">
							<h5>Cutting/Input WIP</h5>
						</div>
						<div class="table-responsive parentTableFixed">
							<table class="fixTable reportTable">
								<thead style="font-size: 12px !important">
								<tr>
									<th>SL</th>
									<th>OP Barcode</th>
									<th>RP Barcode</th>
									<th>Size</th>
									<th title="Bundle No">B. No</th>
									<th>Cutting Qty</th>
								</tr>
								</thead>
								<tbody class="bundle-checked-list" style="font-size: 12px !important">
								@php
									$cutting_bundle_lists = $reports ? (array_key_exists('cutting_wip_list', $reports) ? $reports['cutting_wip_list'] : []) : [];
								@endphp
								@if($cutting_bundle_lists && count($cutting_bundle_lists))
									@foreach($cutting_bundle_lists as $cutting_bundle_list)
										<tr>
											<td>{{ $loop->iteration }}</td>
											<td>{!! '0'.str_pad($cutting_bundle_list->id, 8, '0', STR_PAD_LEFT) !!}</td>
											<td>{!! '1'.str_pad($cutting_bundle_list->id, 8, '0', STR_PAD_LEFT) !!}</td>
											<td>{{ $cutting_bundle_list->size->name ?? '' }}</td>
											<td>{{ $cutting_bundle_list->bundle_no ?? '' }}</td>
											<td>{{ $cutting_bundle_list->quantity - $cutting_bundle_list->total_rejection }}</td>
										</tr>
									@endforeach
									<tr>
										<th colspan="5">Total Cutting Qty</th>
										<th>{{ $cutting_bundle_lists->sum('quantity') - $cutting_bundle_lists->sum('total_rejection') }}</th>
									</tr>
								@else
									<tr>
										<th colspan="6">No Data Found</th>
									</tr>
								@endif
								</tbody>
							</table>
						</div>
						<div class="col-md-12">
							<h5>Sewing Balance</h5>
						</div>
						<div class="table-responsive parentTableFixed">
							<table class="fixTable reportTable">
								<thead style="font-size: 12px !important">
								<tr>
									<th>SL</th>
									<th>OP Barcode</th>
									<th>RP Barcode</th>
									<th>Size</th>
									<th title="Bundle No">B. No</th>
									<th>Input Qty</th>
								</tr>
								</thead>
								<tbody class="bundle-checked-list" style="font-size: 12px !important">
								@php
									$bundle_lists = $reports ? (array_key_exists('bundle_card_list', $reports) ? $reports['bundle_card_list'] : []) : [];
								@endphp
								@if($bundle_lists && count($bundle_lists))
									@foreach($bundle_lists as $bundle_list)
										<tr>
											<td>{{ $loop->iteration }}</td>
											<td>{!! '0'.str_pad($bundle_list->id, 8, '0', STR_PAD_LEFT) !!}</td>
											<td>{!! '1'.str_pad($bundle_list->id, 8, '0', STR_PAD_LEFT) !!}</td>
											<td>{{ $bundle_list->size->name ?? '' }}</td>
											<td>{{ $bundle_list->bundle_no ?? '' }}</td>
											<td>{{ $bundle_list->quantity - $bundle_list->total_rejection - $bundle_list->print_rejection - $bundle_list->embroidary_rejection }}</td>
										</tr>
									@endforeach
									<tr>
										<th colspan="5">Total Input Qty</th>
										<th>{{ $bundle_lists->sum('quantity') - $bundle_lists->sum('total_rejection') - $bundle_lists->sum('print_rejection') - $bundle_lists->sum('embroidary_rejection') }}</th>
									</tr>
								@else
									<tr>
										<th colspan="6">No Data Found</th>
									</tr>
								@endif
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="loader"></div>
	</div>
@endsection
@section('scripts')
  <script src="{{ asset('protracker/custom.js') }}"></script>
	<script type="text/javascript">
    $(function() {
      const buyerSelectDom = $('[name="buyer_id"]');
      const orderSelectDom = $('[name="order_id"]');
      const reportDom = $('bundle-checked-list');

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
        let orderId = orderSelectDom.val();
        if (orderId) {
          orderSelectDom.val('').change();
        }
        reportDom.empty();
      });

      $(document).on('change', '[name="order_id"]', function (e) {
        let orderId = $(this).val();
        reportDom.empty();
      });
    })

	</script>
@endsection
