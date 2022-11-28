@if($total_production_report)
  @php
    $grand_total_order_qty = 0;
  @endphp
  @foreach($total_production_report->groupBy('purchase_order_id') as $key => $reportByPurchaseOrder)
    @php
      $orderInfo = $reportByPurchaseOrder->first()->order;
      $style_name = $orderInfo->style_name ?? 'Style';
      $po = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
      $order_total_qty = $reportByPurchaseOrder->first()->purchaseOrder->po_quantity ?? 0;
      $total_cutting = $reportByPurchaseOrder->sum('total_cutting');
      $total_cutting_rejection = $reportByPurchaseOrder->sum('total_cutting_rejection');
      $total_sent = $reportByPurchaseOrder->sum('total_sent');
      $total_received = $reportByPurchaseOrder->sum('total_received');
      $total_print_rejection = $reportByPurchaseOrder->sum('total_print_rejection');
      $todays_input = $reportByPurchaseOrder->sum('todays_input');
      $total_input = $reportByPurchaseOrder->sum('total_input');
      $todays_sewing_output = $reportByPurchaseOrder->sum('todays_sewing_output');
      $total_sewing_output = $reportByPurchaseOrder->sum('total_sewing_output');
      $total_sewing_rejection = $reportByPurchaseOrder->sum('total_sewing_rejection');

      $cutting_wip = $total_cutting - $total_sent;
      $print_wip = $total_sent - $total_received;
      $in_line_wip = $total_input - $total_sewing_output;
      $total_rejection = $total_cutting_rejection + $total_print_rejection + $total_sewing_rejection;
      $cut_to_sewing_ratio = 0;
      if($total_cutting > 0 && $total_sewing_output) {
          $cut_to_sewing_ratio = round(($total_sewing_output/$total_cutting)*100, 2);
      }

      $grand_total_order_qty += $order_total_qty;
    @endphp
    <tr>
      <td>{{ $style_name }}</td>
      <td>{{ $po }}</td>
      <td>{{ $order_total_qty }}</td>
      <td>{{ $total_cutting }}</td>
      <td>{{ $cutting_wip }}</td>
      <td>{{ $total_sent }}</td>
      <td>{{ $total_received }}</td>
      <td>{{ $print_wip }}</td>
      <td>{{ $todays_input }}</td>
      <td>{{ $total_input }}</td>
      <td>{{ $todays_sewing_output }}</td>
      <td>{{ $total_sewing_output }}</td>
      <td>{{ $total_sewing_rejection }}</td>
      <td>{{ $total_rejection }}</td>
      <td>{{ $in_line_wip }}</td>
      <td>{{ $cut_to_sewing_ratio }}</td>
    </tr>
  @endforeach
  <tr>
    <th colspan="2"> Total</th>
    <th>{{ $grand_total_order_qty }}</th>
    <th>{{ $total_production_report->sum('total_cutting') }}</th>
    <th>{{ $total_production_report->sum('total_cutting') - $total_production_report->sum('total_sent') }}</th>
    <th>{{ $total_production_report->sum('total_sent') }}</th>
    <th>{{ $total_production_report->sum('total_received') }}</th>
    <th>{{ $total_production_report->sum('total_sent') - $total_production_report->sum('total_received') }}</th>
    <th>{{ $total_production_report->sum('todays_input') }}</th>
    <th>{{ $total_production_report->sum('total_input') }}</th>
    <th>{{ $total_production_report->sum('todays_sewing_output') }}</th>
    <th>{{ $total_production_report->sum('total_sewing_output') }}</th>
    <th>{{ $total_production_report->sum('total_sewing_rejection') }}</th>
    <th>
      {{
          $total_production_report->sum('total_cutting_rejection')
          + $total_production_report->sum('total_print_rejection')
          + $total_production_report->sum('total_sewing_rejection')
      }}
    </th>
    <th>{{ $total_production_report->sum('total_input') - $total_production_report->sum('total_sewing_output') }}</th>
    <th></th>
  </tr>
  @if($total_production_report && $total_production_report->total() > 18 && $print == 0)
    <tr>
      <td colspan="16" align="center">{{ $total_production_report->appends(request()->except('page'))->links() }}</td>
    </tr>
  @endif
@else
  <tr>
    <td colspan="16" align="center">No data</td>
  </tr>
@endif
