<thead>
<tr>
  <th>Buyer</th>
  <th>Style</th>
  <th>Order</th>
  <th>Order Qty</th>
  <th>Cutting Qty</th>
  <th>WIP In Cutting/Print/Embr.</th>
  <th>Today's Input to Line</th>
  <th>Total Input to Line</th>
  <th>Today's Output</th>
  <th>Total Sewing Output</th>
  <th>Sewing Rejection</th>
  <th>Total Rejection</th>
  <th>In_line WIP</th>
  <th>Cut 2 Sewing Ratio(%)</th>
</tr>
</thead>
<tbody>
@if($data)
  @php
    $total_order_qty = 0;
    $total_cutting = 0;
    $total_wip_in_cut = 0;
    $total_todays_input = 0;
    $total_input = 0;
    $total_todays_sewing_output = 0;
    $total_sewing_output = 0;
    $total_sewing_rejection = 0;
    $total_rejection = 0;
    $total_in_line_wip = 0;
  @endphp
  @foreach($data as $report)
    @php
      $total_order_qty += $report['order_quantity'] ?? 0;
      $total_cutting += $report['total_cutting'] ?? 0;
      $total_wip_in_cut += $report['total_cutting'] - $report['total_sent'] ?? 0;
      $total_todays_input += $report['todays_input'] ?? 0;
      $total_input += $report['total_input'] ?? 0;
      $total_todays_sewing_output += $report['todays_sewing_output'] ?? 0;
      $total_sewing_output += $report['total_sewing_output'] ?? 0;
      $total_sewing_rejection += $report['total_sewing_rejection'] ?? 0;
      $total_rejection = $report['total_cutting_rejection'] + $report['total_print_rejection'] + $report['total_sewing_rejection'] ?? 0;
      $total_in_line_wip += $report['total_input'] - $report['total_sewing_output'] ?? 0;
    @endphp
    <tr>
      <td>{{ $buyer ?? 'Buyer' }}</td>
      <td>{{ $report['style'] ?? 'Style' }}</td>
      <td>{{ $report['order_no'] ?? '' }}</td>
      <td>{{ $report['order_quantity'] ?? '' }}</td>
      <td>{{ $report['total_cutting'] }}</td>
      <td>{{ $report['total_cutting'] - $report['total_sent'] }}</td>
      <td>{{ $report['todays_input'] }}</td>
      <td>{{ $report['total_input'] }}</td>
      <td>{{ $report['todays_sewing_output'] }}</td>
      <td>{{ $report['total_sewing_output'] }}</td>
      <td>{{ $report['total_sewing_rejection'] }}</td>
      <td>{{ $report['total_cutting_rejection'] + $report['total_print_rejection'] + $report['total_sewing_rejection'] }}</td>
      <td>{{ $report['total_input'] - $report['total_sewing_output'] }}</td>
      <td>
        @if($report['total_sewing_output'] > 0 && $report['total_cutting'] > 0)
          {{ number_format(($report['total_sewing_output'] / $report['total_cutting'])*100,2) }}%
        @endif
      </td>
    </tr>
  @endforeach
  <tr style="font-weight: bold">
    <td colspan="3">Total</td>
    <td>{{ $total_order_qty  }}</td>
    <td>{{ $total_cutting }}</td>
    <td>{{ $total_wip_in_cut }}</td>
    <td>{{ $total_todays_input }}</td>
    <td>{{ $total_input }}</td>
    <td>{{ $total_todays_sewing_output }}</td>
    <td>{{ $total_sewing_output }}</td>
    <td>{{ $total_sewing_rejection }}</td>
    <td>{{ $total_rejection }}</td>
    <td>{{ $total_in_line_wip }}</td>
    <td></td>
  </tr>
@else
  <tr>
    <td colspan="12" class="text-danger text-center">Not found
    <td>
  </tr>
@endif
</tbody>