<thead>
@if(request()->has('type'))
	<tr>
		<th colspan="7">Daily Table Wise Cutting And Input
			Summary {{ isset($cutting_floor_no) ? 'of Floor: '. $cutting_floor_no:'' }} {{ isset($cutting_table_no) ? ' Table: '. $cutting_table_no:'' }} </th>
	</tr>
@endif
<tr>
	<th>Date</th>
	<th>Table No</th>
	<th>Buyer</th>
	<th>Style</th>
	<th>PO</th>
	<th>Cut Qty</th>
	<th>T.Input Qty</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
  @php
    $g_total_sewing_input = 0;
  @endphp
	@foreach($reports->sortBy('cutting_table_id')->groupBy('order_id') as $reportByOrder)
    @php
      $order_total_sewing_input = 0;
    @endphp
    @foreach($reportByOrder->sortBy('cutting_table_id')->groupBy('purchase_order_id') as $reportByPurchaseOrder)
      @php
        $purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id;
        $sewing_input = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport::purchaseOrderWiseTotalInputQty($purchase_order_id);
        $order_total_sewing_input += $sewing_input;
        $g_total_sewing_input += $sewing_input;
      @endphp
      @foreach($reportByPurchaseOrder as $key => $report)
        <tr>
          <td>{{ date('d/m/Y', strtotime($report->production_date)) }}</td>
          <td>{{ $report->cuttingTable->table_no}}</td>
          <td>{{ $report->buyer->name }}</td>
          <td>{{ $report->order->style_name }}</td>
          <td>{{ $report->purchaseOrder->po_no }}</td>
          <td>{{ $report->cutting_qty_sum }}</td>
          @if($key == 0)
            <td rowspan="{{ $reportByPurchaseOrder->count() }}">{{ $sewing_input }}</td>
          @endif
        </tr>
      @endforeach
		@endforeach
		<tr>
			<th colspan="5">Total = {{ $reportByOrder->first()->order->style_name }}</th>
			<th>{{ $reportByOrder->sum('cutting_qty_sum')  }}</th>
			<th>{{ $order_total_sewing_input }}</th>
		</tr>
	@endforeach
	<tr>
		<th colspan="4">Total</th>
		<th>Cut. Qty:  {{ $reports->sum('cutting_qty_sum')  }}</th>
		<th>Input Qty:  {{ $g_total_sewing_input  }}</th>
		<th>Balance:  {{ $reports->sum('cutting_qty_sum') - $g_total_sewing_input }}</th>



	</tr>
@else
	<tr class="tr-height">
		<th colspan="7">No Data found</th>
	</tr>
@endif
</tbody>