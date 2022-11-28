<thead>
@if(request()->has('type'))
	<tr>
		<th colspan="5">Monthly Table Wise Production
			Summary {{ isset($cutting_floor_no) ? 'of Floor: '. $cutting_floor_no:'' }} {{ isset($cutting_table_no) ? ' Table: '. $cutting_table_no:'' }} </th>
	</tr>
@endif
<tr>
	<th>Date</th>
	<th>Buyer</th>
	<th>Style</th>
	<th>PO</th>
	<th>Cut Qty</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
	@foreach($reports->groupBy('order_id') as $reportByOrder)
		@foreach($reportByOrder as $report)
			<tr>
				<td>{{ date('d/m/Y', strtotime($report->production_date)) }}</td>
				<td>{{ $report->buyer->name }}</td>
				<td>{{ $report->order->style_name }}</td>
				<td>{{ $report->purchaseOrder->po_no }}</td>
				<td>{{ $report->cutting_qty_sum }}</td>
			</tr>
		@endforeach
		<tr>
			<th colspan="4">Total = {{ $reportByOrder->first()->order->style_name }}</th>
			<th>{{ $reportByOrder->sum('cutting_qty_sum')  }}</th>
		</tr>
	@endforeach
	<tr>
		<th colspan="4">Total</th>
		<th>{{ $reports->sum('cutting_qty_sum')  }}</th>
	</tr>
@else
	<tr class="tr-height">
		<th colspan="5">No Data found</th>
	</tr>
@endif
</tbody>