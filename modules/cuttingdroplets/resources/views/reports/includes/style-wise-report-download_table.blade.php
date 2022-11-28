<thead>
<tr>
    <th colspan="5">
        Buyer: {{$buyer ?? ''}} &nbsp;&nbsp;&nbsp;
        Our Reference: {{$style ?? ''}}
    </th>
</tr>
<tr>
    <th>PO</th>
    <th>PO Quantity</th>
    <th>Today's Cutting</th>
    <th>Total Cutting</th>
    <th>Left Quantity</th>
</tr>
</thead>
<tbody>
@php
    error_reporting(0);
    $order_total_qty = 0;
    $todays_cutting_qty = 0;
    $total_cutting_qty = 0;
    $left_qty = 0;
@endphp
@if(isset($result_data))
    @foreach($result_data as $key => $report)
        @php
            $order_total_qty += $report['order_quantity'];
            $todays_cutting_qty += $report['todays_cutting'];
            $total_cutting_qty += $report['total_cutting'];
            $left_qty += $report['order_quantity'] - $report['total_cutting'];
        @endphp
        <tr>
            <td>{{ $report['po'] ?? '' }}</td>
            <td>{{ $report['order_quantity'] ?? 0 }}</td>
            <td>{{ $report['todays_cutting'] ?? 0 }}</td>
            <td>{{ $report['total_cutting'] ?? 0 }}</td>
            <td>{{ $report['order_quantity'] - $report['total_cutting'] ?? 0 }}</td>
        </tr>
    @endforeach
    <tr style="font-weight:bold">
        <td>Total</td>
        <td>{{ $order_total_qty }}</td>
        <td>{{ $todays_cutting_qty }}</td>
        <td>{{ $total_cutting_qty }}</td>
        <td>{{ $left_qty }}</td>
    </tr>
@endif
</tbody>