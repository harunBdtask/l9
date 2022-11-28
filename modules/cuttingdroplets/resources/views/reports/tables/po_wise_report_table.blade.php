@php
	$tableHeadColorClass = 'tableHeadColor';
	if(isset($type) && $type == 'pdf') {
			$pdfStyle = 'border-collapse: collapse;font-size:9px !important;';
			$tableHeadColorClass = '';
	}
@endphp
<table class="reportTable" id="fixTable" style="{{ $pdfStyle ?? '' }}">
	<thead>
	<tr>
		<th colspan="15">Order Details</th>
	</tr>
	<tr>
		<th>Buyer</th>
		<th>Style</th>
		<th>Purchase Order</th>
		<th>Color</th>
		<th>Color Wise PO Qty</th>
		<th>Today's Cutting</th>
		<th>Total Cutting</th>
		<th>Left/Extra Qty</th>
		<th>Extra Cutting (%)</th>
		<th>T. Print Sent</th>
		<th>T. Print Rcv.</th>
		<th>T. Embr. Sent</th>
		<th>T. Embr. Rcv.</th>
		<th>T. Input</th>
		<th>T. Output</th>
	</tr>
	</thead>
	<tbody>
	@if($reports && $reports->count())
		@foreach($reports->getCollection()->groupBy('order_id') as $reportByOrder)
			@php
				if (!$reportByOrder->first()->buyer || !$reportByOrder->first()->order || !$reportByOrder->first()->purchaseOrder) {
						continue;
				}
				$buyer = $reportByOrder->first()->buyer->name ?? '';
				$style_name = $reportByOrder->first()->order->style_name ?? '';
				$purchase_order_row_span = 1;
				$reportByOrder->groupBy('purchase_order_id')->each(function($item, $key) use (&$purchase_order_row_span) {
						$purchase_order_row_span += $item->groupBy('color_id')->count();
				});
				$po_wise_po_qty_sum = 0;
				$po_wise_todays_cutting_sum = 0;
				$po_wise_total_cutting_sum = 0;
				$po_wise_left_extra_cutting_sum = 0;
			@endphp
			<tr>
				<td rowspan="{{ $purchase_order_row_span }}">{{ $buyer }}</td>
				<td rowspan="{{ $purchase_order_row_span }}">{{ $style_name }}</td>
			@foreach($reportByOrder->groupBy('purchase_order_id') as $reportByPurchaseOrder)
				@php
					$purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id;
					$purchase_order = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
					$color_row_span = $reportByPurchaseOrder->groupBy('color_id')->count();
				@endphp
				@if(!$loop->first)
					<tr>
						@endif
						<td rowspan="{{ $color_row_span }}">{{ $purchase_order }}</td>
					@foreach($reportByPurchaseOrder as $report)
						@php
							$color_id = $report->color_id;
							$color = $report->color->name ?? '';

							$color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);

							$todays_cutting = $report->todays_cutting_sum - $report->todays_cutting_rejection_sum;
							$total_cutting = $report->total_cutting_sum - $report->total_cutting_rejection_sum;
							$leftQty = $color_wise_po_qty - $total_cutting;
							$xtra = $color_wise_po_qty > 0 ? ((($total_cutting - $color_wise_po_qty) * 100) / $color_wise_po_qty) : 0;
							$xtra = $xtra > 0 ? $xtra : 0;

							$po_wise_po_qty_sum += $color_wise_po_qty;
							$po_wise_todays_cutting_sum += $todays_cutting;
							$po_wise_total_cutting_sum += $total_cutting;
							$po_wise_left_extra_cutting_sum += $leftQty;
						@endphp
								@if(!$loop->first)
									<tr>
								@endif
								<td>{{ $color }}</td>
								<td>{{ $color_wise_po_qty }}</td>
								<td>{{ $todays_cutting }}</td>
								<td>{{ $total_cutting }}</td>
								<td>{{ $leftQty }}</td>
								<td>{{ round($xtra, 2).'%' }}</td>
								<td>{{ $report->total_sent_sum }}</td>
								<td>{{ $report->total_received_sum }}</td>
								<td>{{ $report->total_embroidary_sent_sum }}</td>
								<td>{{ $report->total_embroidary_received_sum }}</td>
								<td>{{ $report->total_input_sum }}</td>
								<td>{{ $report->total_output_sum }}</td>
							</tr>
							@endforeach
							@if($loop->last)
								<tr>
									<th colspan="2">Total</th>
									<th>{{ $po_wise_po_qty_sum }}</th>
									<th>{{ $po_wise_todays_cutting_sum }}</th>
									<th>{{ $po_wise_total_cutting_sum }}</th>
									<th>{{ $po_wise_left_extra_cutting_sum }}</th>
									<th></th>
									<th>{{ $reportByOrder->sum('total_sent_sum') }}</th>
									<th>{{ $reportByOrder->sum('total_received_sum') }}</th>
									<th>{{ $reportByOrder->sum('total_embroidary_sent_sum') }}</th>
									<th>{{ $reportByOrder->sum('total_embroidary_received_sum') }}</th>
									<th>{{ $reportByOrder->sum('total_input_sum') }}</th>
									<th>{{ $reportByOrder->sum('total_output_sum') }}</th>
							@endif
						@endforeach
						</tr>
					@endforeach
				@else
				<tr>
					<td colspan="15">No Data</td>
				</tr>
				@endif
	</tbody>
	<tfoot>
	@if(!isset($type) && $reports->total() >= 30)
		<tr>
			<td colspan="15" align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
		</tr>
	@endif
	</tfoot>
</table>
