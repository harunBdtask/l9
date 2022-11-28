<thead>
<tr>
  <th colspan="5">
    Buyer: {{ $buyer ?? '' }} &nbsp;&nbsp;&nbsp;
    Style/Order: {{ $booking_no ?? '' }} &nbsp;&nbsp;&nbsp;
    Order/Style: {{ $style ?? '' }} &nbsp;&nbsp;&nbsp;
    PO: {{ $order_no ?? '' }}
  </th>
</tr>
<tr>
  <th>Floor No.</th>
  <th>Line No.</th>
  <th>Color</th>
  <th>Total Sewing Output</th>
  <th>Date</th>
</tr>
</thead>
<tbody>
@if(!empty($reports))
  @php
    $total_output = 0;
    $count = 0;
  @endphp
  @foreach($reports as $report)
    @php
      $total_output += $report['output_qty'];
      $count++;
    @endphp
    <tr>
      <td>{{ $report['floor'] }}</td>
      <td>{{ $report['line'] }}</td>
      <td>{{ $report['color'] }}</td>
      <td>{{ $total_output }}</td>
      <td>{{ $report['date'] }}</td>
    </tr>
  @endforeach
  @php
    $avg = $total_output / $count;
  @endphp
  <tr style="font-weight:bold">
    <td colspan="3">Total</td>
    <td>{{ $total_output }} <b>[ Avg : {{ number_format($avg,2) }} ]</b></td>
    <td></td>
  </tr>
@else
  <tr>
    <td colspan="4" style="text-align: center; font-weight: bold;">Not found
    <td>
  </tr>
@endif
</tbody>
