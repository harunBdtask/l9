<thead>
<tr style="background-color: #cbffb5;">
  <th>PO</th>
  <th>Floor No.</th>
  <th>Line No.</th>
  <th>Color</th>
  <th>Total Sewing Input</th>
  <th>Total Sewing Output</th>
  <th>Date</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
  @php
    $grand_total_sewing_input = 0;
    $grand_total_sewing_output = 0;
    $g_total_no_of_sewing_input = 0;
    $g_total_no_of_sewing_output = 0;
  @endphp
  @foreach($reports->groupBy('purchase_order_id') as $reportByPo)
    @php
      $po_no = $reportByPo->first()->purchaseOrder->po_no;
      $po_row_span = $reportByPo->count();

      $total_no_of_sewing_input = $reportByPo->where('sewing_input', '>', 0)->count();
      $total_no_of_sewing_output = $reportByPo->where('sewing_output', '>', 0)->count();
      $po_wise_input_avg = $total_no_of_sewing_input > 0 ? round(($reportByPo->sum('sewing_input') / $total_no_of_sewing_input), 2) : 0;
      $po_wise_output_avg = $total_no_of_sewing_output > 0 ? round(($reportByPo->sum('sewing_output') / $total_no_of_sewing_output), 2) : 0;

      $grand_total_sewing_input += $reportByPo->sum('sewing_input');
      $grand_total_sewing_output += $reportByPo->sum('sewing_output');
      $g_total_no_of_sewing_input += $total_no_of_sewing_input;
      $g_total_no_of_sewing_output += $total_no_of_sewing_output;
    @endphp
    <tr>
      <td rowspan="{{ $po_row_span }}">{{ $po_no }}</td>
    @foreach($reportByPo as $report)
      @if(!$loop->first)
        <tr>
          @endif
          <td>{{ $report->floor->floor_no }}</td>
          <td>{{ $report->line->line_no }}</td>
          <td>{{ $report->color->name }}</td>
          <td>{{ $report->sewing_input }}</td>
          <td>{{ $report->sewing_output }}</td>
          <td>{{ $report->production_date }}</td>
        </tr>
        @endforeach
        <tr style="background-color: #c2c5e7;">
          <th colspan="4">TOTAL : {{ $po_no }}</th>
          <th>{{ $reportByPo->sum('sewing_input').' ['.$po_wise_input_avg.']' }}</th>
          <th>{{ $reportByPo->sum('sewing_output').' ['.$po_wise_output_avg.']' }}</th>
          <th></th>
        </tr>
        @endforeach
        @php
          $g_total_input_avg = $g_total_no_of_sewing_input > 0 ? round(($grand_total_sewing_input / $g_total_no_of_sewing_input), 2) : 0;
          $g_total_output_avg = $g_total_no_of_sewing_output > 0 ? round(($grand_total_sewing_output / $g_total_no_of_sewing_output), 2) : 0;
        @endphp
        <tr style="background-color: #fbf6de;">
          <th colspan="4">GRAND TOTAL</th>
          <th>{{ $grand_total_sewing_input.' ['.$g_total_input_avg.']' }}</th>
          <th>{{ $grand_total_sewing_output.' ['.$g_total_output_avg.']' }}</th>
          <th></th>
        </tr>
      @else
        <tr>
          <th class="text-center text-danger" colspan="7">No Data Found</th>
        </tr>
      @endif
</tbody>