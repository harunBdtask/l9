<thead>
<tr>
    <th colspan="5">
        Buyer: {{$buyer}} &nbsp;&nbsp;&nbsp;
        Our Ref: {{$style}} &nbsp;&nbsp;&nbsp;
        PO: {{$order_no}} &nbsp;&nbsp;&nbsp;
        Color: {{$color}}
    </th>
</tr>
<tr style="text-align: center;">
    <th>Size Name</th>
    <th>Order Quantity</th>
    <th>Cutting Quantity</th>
    <th>Left Quantity</th>
    <th>Extra Quantity</th>
</tr>
</thead>
<tbody>
@php
    $total_size_order_qty = 0;
    $total_cutting_qty = 0;
    $total_left_qty = 0;
@endphp
@if(!empty($size_wize_report))
    @foreach($size_wize_report as $report)
        @php
            $total_size_order_qty += $report['size_order_qty'];
            $total_cutting_qty += $report['cutting_qty'] ;
            $total_left_qty += $report['left_qty'] ;
        @endphp
        <tr style="text-align: center;">
            <td>{{ $report['size_name'] }}</td>
            <td>{{ $report['size_order_qty'] }}</td>
            <td>{{ $report['cutting_qty'] }}</td>
            <td>{{ $report['left_qty'] }}</td>
            <td>{{ $report['extra_qty'].'%'}}</td>
        </tr>
    @endforeach
    <tr style="text-align: center;font-weight: bold">
        <td><b>Total</b></td>
        <td>{{ $total_size_order_qty }}</td>
        <td>{{ $total_cutting_qty }}</td>
        <td>{{ $total_left_qty }}</td>
        <td></td>
    </tr>
@else
    <tr>
        <td style="text-align: center;"><strong>No Data</strong></td>
    </tr>
@endif
</tbody>