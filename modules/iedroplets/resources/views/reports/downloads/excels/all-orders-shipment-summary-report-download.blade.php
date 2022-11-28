<table>
	<thead>
	<tr>
		<th>Buyer</th>
		<th>Style/Order</th>
		<th>PO</th>
		<th>PO Qty</th>
		<th>Shipout Qty</th>
		<th>Shipout Balance Qty</th>
		<th>Shipment Date</th>
		<th>Total Export Value</th>
		<th>Total Shipout Value</th>
		<th>Total Export Value Balance</th>
		<th>Reason</th>
	</tr>
	</thead>
	<tbody>
	@if(!$all_orders_summary->getCollection()->isEmpty())
		@php
			$t_po_qty = 0;
			$t_shipout_qty = 0;
			$t_shipout_balance_qty = 0;
			$g_total_export_value = 0;
			$g_total_shipout_value = 0;
			$g_total_export_balance_value = 0;
		@endphp
		@foreach($all_orders_summary->getCollection() as $shipment)
			@php
				$po_qty = $shipment->purchaseOrder->po_quantity;
				$shipout_qty = $shipment->ship_quantity;
				$shipout_balance_qty = $po_qty - $shipout_qty;
				$shipment_date = $shipment->purchaseOrder->inspection_date ? date('d/m/Y', strtotime($shipment->purchaseOrder->inspection_date)) : '';
				$unit_price = $shipment->purchaseOrder->unit_price ?? 0;
				$total_export_value = $unit_price * $po_qty;
				$total_shipout_value = $shipout_qty * $unit_price;
				$total_export_balance_value = $total_export_value - $total_shipout_value;

				$t_po_qty += $po_qty;
				$t_shipout_qty += $shipout_qty;
				$t_shipout_balance_qty += $shipout_balance_qty;
				$g_total_export_value += $total_export_value;
				$g_total_shipout_value += $total_shipout_value;
				$g_total_export_balance_value += $total_export_balance_value;
			@endphp
			<tr>
				<td>{{ $shipment->buyer->name }}</td>
				<td>{{ $shipment->order->booking_no }}</td>
				<td>{{ $shipment->purchaseOrder->po_no }}</td>
				<td>{{ $po_qty }}</td>
				<td>{{ $shipout_qty }}</td>
				<td>{{ $shipout_balance_qty }}</td>
				<td>{{ $shipment_date }}</td>
				<td>{{ $total_export_value }}</td>
				<td>{{ $total_shipout_value }}</td>
				<td>{{ $total_export_balance_value }}</td>
				<td>{{ $shipment->remarks }}</td>
			</tr>
		@endforeach
		<tr style="font-weight: bold">
			<td colspan="3">Total</td>
			<td>{{ $t_po_qty }}</td>
			<td>{{ $t_shipout_qty }}</td>
			<td>{{ $t_shipout_balance_qty }}</td>
			<td>&nbsp;</td>
			<td>{{ $g_total_export_value }}</td>
			<td>{{ $g_total_shipout_value }}</td>
			<td>{{ $g_total_export_balance_value }}</td>
			<td>&nbsp;</td>
		</tr>
	@else
		<tr>
			<td colspan="11" class="text-danger text-center">Not found</td>
		</tr>
	@endif
	</tbody>
</table>
