<thead>
  <tr>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>PO</th>
    <th>PO Quantity</th>
    <th>Cutting Production</th>
    <th>Cutting WIP</th>
    <th>Days Go Shipment</th>
    <th>Print Send</th>
    <th>Print Recieve</th>
    <th>Total Print Reject</th>
    <th>Print/Embr. WIP</th>
    <th>Current Cutting Inventory</th>
    <th>Today's Input Qty</th>
    <th>Total Input Qty</th>
  </tr>
</thead>
<tbody>
@if($order_wise_input)
  @php
    $torder_qty = 0;
    $grand_total_cutting_qty = 0;
    $grand_total_sent = 0;
    $grand_total_received = 0;
    $grand_total_print_rejection = 0;
    $grand_total_todays_input = 0;
    $grand_total_input = 0;
    $twip = 0;
    $tpewip = 0;
    $tcurrnt_inventory = 0;
  @endphp
  @foreach($order_wise_input->groupBy('purchase_order_id') as $reportByOrder)
    @php
      if (!$reportByOrder->first()->purchaseOrder || !$reportByOrder->first()->order || !$reportByOrder->first()->buyer) {
        continue;
      }
      $wip = 0;
      $pewip = 0;
      $current_inventory = 0;

      $buyer_name = $reportByOrder->first()->buyer->name ?? '';
      $style_name = $reportByOrder->first()->order->style_name ?? 'Style';
      $order_no = $reportByOrder->first()->purchaseOrder->po_no ?? '';
      $order_qty = $reportByOrder->first()->purchaseOrder->po_quantity ?? 0;

      $dateInMs = strtotime($reportByOrder->first()->purchaseOrder->ex_factory_date) - strtotime($reportByOrder->first()->order->order_confirmation_date);
      $days_to_shipment = round($dateInMs / 86400);

      $torder_qty += $order_qty;

      $total_cutting_of_order = 0;
      $total_sent_of_order = 0;
      $total_received_of_order = 0;
      $total_print_rejection = 0;
      $todays_input_of_order = 0;
      $total_input_of_order = 0;
      foreach($reportByOrder as $report) {
        $total_cutting_of_order += $report->total_cutting ?? 0;
        $total_sent_of_order += $report->total_sent ?? 0;
        $total_received_of_order += $report->total_received ?? 0;
        $total_print_rejection += $report->total_print_rejection ?? 0;
        $todays_input_of_order += $report->todays_input ?? 0;
        $total_input_of_order += $report->total_input ?? 0;
      }
      $grand_total_cutting_qty += $total_cutting_of_order;
      $grand_total_sent += $total_sent_of_order;
      $grand_total_received += $total_received_of_order;
      $grand_total_print_rejection += $total_print_rejection;
      $grand_total_todays_input += $todays_input_of_order;
      $grand_total_input += $total_input_of_order;

      $wip = $total_cutting_of_order - $total_sent_of_order;
      $twip +=  $wip;
      $pewip = $total_sent_of_order - $total_received_of_order;
      $tpewip += $pewip;

      $current_inventory = $total_cutting_of_order - $total_sent_of_order;
      $tcurrnt_inventory += $current_inventory;
    @endphp
    <tr>
      <td>{{ $buyer_name ?? '' }}</td>
      <td>{{ $style_name ?? 'Style' }}</td>
      <td>{{ $order_no }}</td>
      <td>{{ $order_qty ?? 0 }}</td>
      <td>{{ $total_cutting_of_order }}</td>
      <td>{{ $wip }}</td>
      <td>{{ $days_to_shipment }}  D(s)</td>
      <td>{{ $total_sent_of_order }}</td>
      <td>{{ $total_received_of_order }}</td>
      <td>{{ $total_print_rejection }}</td>
      <td>{{ $pewip }}</td>
      <td>{{ $current_inventory }}</td>
      <td>{{ $todays_input_of_order }}</td>
      <td>{{ $total_input_of_order }}</td>
    </tr>
  @endforeach
  <tr style="font-weight: bold">
    <td colspan="3">{{ 'Total' }}</td>
    <td>{{ $torder_qty }}</td>
    <td>{{ $grand_total_cutting_qty }}</td>
    <td>{{ $twip }}</td>
    <td></td>
    <td>{{ $grand_total_sent }}</td>
    <td>{{ $grand_total_received }}</td>
    <td>{{ $grand_total_print_rejection }}</td>
    <td>{{ $tpewip }}</td>
    <td>{{ $tcurrnt_inventory }}</td>
    <td>{{ $grand_total_todays_input }}</td>
    <td>{{ $grand_total_input }}</td>
  </tr>
  @if($order_wise_input->total() > PAGINATION && $print == 0)
    <tr>
      <td colspan="14" align="center">{{ $order_wise_input->appends(request()->except('page'))->links() }}</td>
    </tr>
  @endif
@else
  <tr>
    <td colspan="14" class="text-danger text-center">Not found<td>
  </tr>
@endif
</tbody>