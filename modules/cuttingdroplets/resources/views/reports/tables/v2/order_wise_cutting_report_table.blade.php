@php
	$tableHeadColorClass = 'tableHeadColor';
	if(isset($type) && $type == 'pdf') {
			$pdfStyle = 'border-collapse: collapse;font-size:9px !important;';
			$tableHeadColorClass = '';
	}
@endphp
<table class="reportTable" id="fixTable" style="{{ $pdfStyle ?? '' }}">
	<thead>
    @if(isset($type) && $type == 'xls')
      <tr>
        <th colspan="10">{{ sessionFactoryName() }}</th>
      </tr>
      <tr>
        <th colspan="10">{{ sessionFactoryAddress() }}</th>
      </tr>
    @endif
    <tr>
      <th colspan="10">Order Wise Cutting Report</th>
    </tr>
    <tr>
      <th>Order/Style</th>
      <th>Buyer</th>
      <th>Garments Item</th>
      <th>PO</th>
      <th>EX-Factory Date</th>
      <th>Order Qty</th>
      <th>Cut Qty</th>
      <th>Ttl. Cut Qty</th>
      <th>Cut &#37;</th>
      <th>Cut Balance</th>
    </tr>
	</thead>
	<tbody>
	@if($reports && $reports->count())
    @php
      $gtCutQty = 0;
    @endphp
		@foreach($reports->getCollection()->groupBy('order_id') as $reportByOrder)
			@php
        if (!$reportByOrder->first()->buyer || !$reportByOrder->first()->order || !$reportByOrder->first()->purchaseOrder) {
						continue;
				}
				$buyer = $reportByOrder->first()->buyer->name ?? '';
				$styleName = $reportByOrder->first()->order->style_name ?? '';
        $garmentsItems = $reportByOrder->unique('garments_item_id')->pluck('garmentsItem.name')->implode(', ');
				$rowSpan = $reportByOrder->groupBy('purchase_order_id')->count();
        $orderQty = 0;
        $reportByOrder->groupBy('purchase_order_id')->each(function($item) use (&$orderQty) {
          $orderQty += $item->first()->purchaseOrder->po_pc_quantity ?? 0;
        });
        $orderWiseCutQty = $reportByOrder->sum('total_cutting');
        $gtCutQty += $orderWiseCutQty;
        $cutPercentage = $orderQty > 0 ? round(($orderWiseCutQty * 100) / $orderQty) : 0;
        $cutBalance = $orderQty - $orderWiseCutQty;
			@endphp
			<tr>
				<td rowspan="{{ $rowSpan }}">{{ $styleName }}</td>
				<td rowspan="{{ $rowSpan }}">{{ $buyer }}</td>
				<td rowspan="{{ $rowSpan }}">{{ $garmentsItems }}</td>
			@foreach($reportByOrder->groupBy('purchase_order_id') as $reportByPurchaseOrder)
        @php
          $poNo = $reportByPurchaseOrder->first()->purchaseOrder->po_no;
          $exFactoryDate = $reportByPurchaseOrder->first()->purchaseOrder->ex_factory_date;
          $poQty = $reportByPurchaseOrder->first()->purchaseOrder->po_pc_quantity;
          $cutQty = $reportByPurchaseOrder->sum('total_cutting');
        @endphp
				@if(!$loop->first)
					<tr>
        @endif
          <td>{{ $poNo }}</td>
          <td>{{ $exFactoryDate ? date('d-m-Y', strtotime($exFactoryDate)) : '' }}</td>
          <td>{{ $poQty }}</td>
          <td>{{ $cutQty }}</td>
        @if($loop->first)
        <td rowspan="{{ $rowSpan }}">{{ $orderWiseCutQty }}</td>
        <td rowspan="{{ $rowSpan }}">{{ $cutPercentage }}</td>
        <td rowspan="{{ $rowSpan }}">{{ $cutBalance }}</td>
        @endif
      @endforeach
    @endforeach
    <tr>
      <th colspan="6">Grand Total</th>
      <th colspan="2">{{ $gtCutQty }}</th>
      <th colspan="2">&nbsp;</th>
    </tr>
    @else
    <tr>
      <td colspan="10">No Data</td>
    </tr>
    @endif
	</tbody>
	<tfoot>
	@if(!isset($type) && $reports->total() >= 30)
		<tr>
			<td colspan="10" align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
		</tr>
	@endif
	</tfoot>
</table>
