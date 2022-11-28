<thead>
<tr>
  <th>Buyer : {{ $buyer_id ? $buyers[$buyer_id] : '' }}</th>
  <th>Daily Input Output Report Summary</th>
  <th>Style : {{ $order_id ? $orders[$order_id] : '' }}</th>
</tr>
<tr>
  <th>Date</th>
  <th>Total Input</th>
  <th>Total Output</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
  @foreach($reports as $report)
    <tr>
      <td>{{ $report->production_date ? date('d/m/Y', strtotime($report->production_date)) : '' }}</td>
      <td>{{ $report->sewing_input_sum }}</td>
      <td>{{ $report->sewing_output_sum }}</td>
    </tr>
  @endforeach
  <tr>
    <th>Total</th>
    <th>{{ $reports->sum('sewing_input_sum')  }}</th>
    <th>{{ $reports->sum('sewing_output_sum')  }}</th>
  </tr>
@else
  <tr>
    <th colspan="3">No Data Found</th>
  </tr>
@endif
</tbody>