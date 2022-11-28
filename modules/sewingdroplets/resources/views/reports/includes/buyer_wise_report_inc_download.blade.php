@if($total_production_report)
  @php
    $grand_total_order_qty = 0;
    $grand_total_cutting = 0;
    $grand_cutting_wip = 0;
    $grand_total_sent = 0;
    $grand_total_received = 0;
    $grand_total_cutting_rejection = 0;
    $grand_total_print_rejection = 0;
    $grand_print_wip = 0;
    $grand_todays_input = 0;
    $grand_total_input = 0;
    $grand_todays_sewing_output = 0;
    $grand_total_sewing_output = 0;
    $grand_total_sewing_rejection = 0;
    $grand_total_rejection = 0;
    $grand_in_line_wip = 0;
  @endphp
  @foreach($total_production_report->groupBy('order_id') as $key => $reportByOrder)
    @php
      $buyer = $reportByOrder->first()->buyer->name ?? 'Buyer';
      $style_name = $reportByOrder->first()->order->style_name ?? 'Style';
      $po = $reportByOrder->first()->purchaseOrder->po_no ?? '';
      $order_total_qty = $reportByOrder->first()->order->total_quantity ?? 0;
      $total_cutting = 0;
      $total_cutting_rejection = 0;
      $total_sent = 0;
      $total_received = 0;
      $total_print_rejection = 0;
      $todays_input = 0;
      $total_input = 0;
      $todays_sewing_output = 0;
      $total_sewing_output = 0;
      $total_sewing_rejection = 0;
      foreach ($reportByOrder as $key2 => $report) {
          $total_cutting += $report->total_cutting ?? 0;
          $total_cutting_rejection += $report->total_cutting_rejection ?? 0;
          $total_sent += $report->total_sent ?? 0;
          $total_received += $report->total_received ?? 0;
          $total_print_rejection += $report->total_print_rejection ?? 0;
          $todays_input += $report->todays_input ?? 0;
          $total_input += $report->total_input ?? 0;
          $todays_sewing_output += $report->todays_sewing_output ?? 0;
          $total_sewing_output += $report->total_sewing_output ?? 0;
          $total_sewing_rejection += $report->total_sewing_rejection ?? 0;
      }
      $cutting_wip = $total_cutting - $total_sent ?? 0;
      $print_wip = $total_sent - $total_received ?? 0;
      $in_line_wip = $total_input - $total_sewing_output;
      $total_rejection = $total_cutting_rejection + $total_print_rejection + $total_sewing_rejection;
      $cut_to_sewing_ratio = 0;
      if($total_cutting > 0 && $total_sewing_output) {
          $cut_to_sewing_ratio = round(($total_sewing_output/$total_cutting)*100, 2);
      }

      $grand_total_order_qty += $order_total_qty;
      $grand_total_cutting += $total_cutting;
      $grand_cutting_wip += $cutting_wip;
      $grand_total_sent += $total_sent;
      $grand_total_received += $total_received;
      $grand_total_cutting_rejection += $total_cutting_rejection;
      $grand_total_print_rejection += $total_print_rejection;
      $grand_print_wip += $print_wip;
      $grand_todays_input += $todays_input;
      $grand_total_input += $total_input;
      $grand_todays_sewing_output += $todays_sewing_output;
      $grand_total_sewing_output += $total_sewing_output;
      $grand_total_sewing_rejection += $total_sewing_rejection;
      $grand_total_rejection += $total_rejection;
      $grand_in_line_wip += $in_line_wip;
    @endphp
    <tr>
      <td>{{ $buyer }}</td>
      <td>{{ $style_name }}</td>
      <td>{{ $po }}</td>
      <td>{{ $order_total_qty }}</td>
      <td>{{ $total_cutting }}</td>
      <td>{{ $cutting_wip }}</td>
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
    <th colspan="3"> Total</th>
    <th>{{ $grand_total_order_qty }}</th>
    <th>{{ $grand_total_cutting }}</th>
    <th>{{ $grand_cutting_wip }}</th>
    <th>{{ $grand_todays_input }}</th>
    <th>{{ $grand_total_input }}</th>
    <th>{{ $grand_todays_sewing_output }}</th>
    <th>{{ $grand_total_sewing_output }}</th>
    <th>{{ $grand_total_sewing_rejection }}</th>
    <th>{{ $grand_total_rejection }}</th>
    <th>{{ $grand_in_line_wip }}</th>
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
