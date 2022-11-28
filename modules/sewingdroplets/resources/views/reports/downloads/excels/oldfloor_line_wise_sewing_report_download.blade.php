<table class="reportTable">
  <thead>
  <tr>
    <th>Floor</th>
    <th>Line</th>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>PO</th>
    <th>Today's Input</th>
    <th>Total Input</th>
    <th>Today's Output</th>
    <th>Total Output</th>
    <th>Rejection</th>
    <th>Rejection(%)</th>
    <th>In-Line WIP</th>
    <th>WIP (%)</th>
  </tr>
  </thead>
  <tbody>
  @if($lineReport)
    @foreach($lineReport->getCollection() as $report)
      <tr>
        <td>{{ $report->line->floor->floor_no ?? '' }}</td>
        <td>{{ $report->line->line_no ?? '' }}</td>
        <td>{{ $report->buyer->name ?? '' }}</td>
        <td>{{ $report->purchaseOrder->order->order_style_no ?? '' }}</td>
        <td>{{ $report->purchaseOrder->po_no ?? '' }}</td>
        <td>{{ $report->todays_input ?? 0 }}</td>
        <td>{{ $report->total_input ?? 0 }}</td>
        <td>{{ $report->todays_output ?? 0 }}</td>
        <td>{{ $report->total_output ?? 0 }}</td>
        <td>{{ $report->rejection ?? 0 }}</td>
        <td>{{ ($report->rejection > 0 && $report->total_input > 0)  ? number_format((($report->rejection * 100) / $report->total_input) ?? 0, 2) : 0 }}</td>
        <td>{{ $report->total_input - $report->total_output }}</td>
        <td>{{ (($report->total_input - $report->total_output) > 0 && $report->total_input > 0) ? number_format((($report->total_input - $report->total_output) / $report->total_input) * 100, 2) : 0 }}
          %
        </td>
      </tr>
    @endforeach
  @endif
  </tbody>
</table>