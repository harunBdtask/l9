<thead>
@if(request()->has('type'))
  <tr>
    <th colspan="6">Monthly Line Wise Production
      Summary {{ isset($floor_no) ? 'of Floor: '. $floor_no:'' }} {{ isset($line_no) ? ' Line: '. $line_no:'' }} </th>
    <th>{{request()->month ? date('F', mktime(0, 0, 0, request()->month, 10)) : ''}}</th>
  </tr>
@endif
<tr>
  <th>Date</th>
  <th>Buyer</th>
  <th>Style/Order</th>
  <th>PO</th>
  <th>Input</th>
  <th>Output</th>
  <th>Efficiency(%)</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
  @php
    $total_earned_minutes = 0;
    $total_available_minutes = 0;
  @endphp
  @foreach($reports as $report)
    @php
      $earned_minutes = $report->sewing_output_sum * ($report->purchaseOrder->smv ?? 0);
      $available_minutes = \SkylarkSoft\GoRMG\Sewingdroplets\Models\HourlySewingProductionReport::calculateDailyPurchaseOrderFloorLineWiseAvailableMinutes($report->production_date, $report->purchase_order_id, $floor_id, $line_id);
      $efficiency = $available_minutes > 0 ? round((($earned_minutes / $available_minutes) * 100), 2) : 0;

      $total_earned_minutes += $earned_minutes;
      $total_available_minutes += $available_minutes;
    @endphp
    <tr>
      <td>{{ date('d/m/Y', strtotime($report->production_date)) }}</td>
      <td>{{ $report->buyer->name }}</td>
      <td>{{ $report->order->style_name }}</td>
      <td>{{ $report->purchaseOrder->po_no }}</td>
      <td>{{ $report->sewing_input_sum }}</td>
      <td>{{ $report->sewing_output_sum }}</td>
      <td>{{ $efficiency }}</td>
    </tr>
  @endforeach
  @php
    $total_efficiency = $total_available_minutes > 0 ? round((($total_earned_minutes / $total_available_minutes) * 100), 2) : 0;
  @endphp
  <tr>
    <th colspan="4">Total</th>
    <th>{{ $reports->sum('sewing_input_sum')  }}</th>
    <th>{{ $reports->sum('sewing_output_sum')  }}</th>
    <th>{{ $total_efficiency }}</th>
  </tr>
@else
  <tr class="tr-height">
    <th colspan="7">No Data found</th>
  </tr>
@endif
</tbody>