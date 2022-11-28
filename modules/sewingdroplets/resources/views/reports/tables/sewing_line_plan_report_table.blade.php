<thead>
@if(request()->has('type') || request()->route('type'))
  <tr>
    <th colspan="10">Sewing Line Plan Report</th>
  </tr>
@endif
<tr>
  <th>Floor</th>
  <th>Line</th>
  <th>Buyer</th>
  <th>Booking</th>
  <th>Purchase Orders</th>
  <th>Order Qty</th>
  <th>Plan Qty</th>
  <th>Shipment Date</th>
  <th>Sewing Start Date</th>
  <th>Sewing End Date</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
  @foreach($reports as $report)
    @php
      $purchase_orders = $report->sewingPlanDetails ? implode(', ', $report->sewingPlanDetails->pluck('purchaseOrder.po_no')->toArray()) : '';
      $order_qty = $report->sewingPlanDetails ? $report->sewingPlanDetails->sum('purchaseOrder.po_quantity') : 0;
      $shipment_date = $report->sewingPlanDetails ? $report->sewingPlanDetails->first()->purchaseOrder->ex_factory_date : '';
    @endphp
    <tr>
      <td>{{ $report->floor->floor_no }}</td>
      <td>{{ $report->line->line_no }}</td>
      <td>{{ $report->buyer->name }}</td>
      <td>{{ $report->order->style_name }} {{ $report->order->reference_no }}</td>
      <td>{{ $purchase_orders }}</td>
      <td>{{ $order_qty }}</td>
      <td>{{ $report->allocated_qty }}</td>
      <td>{{ $shipment_date ? date('d/m/Y', strtotime($shipment_date)) : '' }}</td>
      <td>{{ date('d/m/Y h:i a', strtotime($report->start_date)) }}</td>
      <td>{{ date('d/m/Y h:i a', strtotime($report->end_date)) }}</td>
    </tr>
  @endforeach
@else
  <tr>
    <th colspan="10">No Data</th>
  </tr>
@endif
</tbody>