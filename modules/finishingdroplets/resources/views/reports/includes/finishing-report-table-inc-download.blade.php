<thead>
<tr>
    <th colspan="8">
        Buyer: {{ $buyer ?? '' }} &nbsp;&nbsp;&nbsp;
        Style/Order No: {{ $order_style_no ?? '' }} &nbsp;&nbsp;&nbsp;
        PO: {{ $po_no ?? '' }} &nbsp;&nbsp;&nbsp;
    </th>
</tr>
<tr>
    <th>Colour</th>
    <th>Size</th>
    <th>Order Qty</th>
    <th>Total Cutting</th>
    <th>Total Input</th>
    <th>Total Output</th>
    <th>Total Finishing Received</th>
    <th>In_line WIP</th>
</tr>
</thead>
<tbody>
@if(!empty($report_size_wise))
    @foreach($report_size_wise as $report)
        <tr>
            <td>{{  $report['color']  }}</td>
            <td>{{  $report['size']  }}</td>
            <td>{{ $report['size_order_qty'] }}</td>
            <td>{{ $report['size_cutting_qty'] }}</td>
            <td>{{ $report['total_input'] }}</td>
            <td>{{ $report['total_output'] }}</td>
            <td>{{ $report['finished_qty'] }}</td>
            <td>{{ $report['in_line_wip'] }}</td>
        </tr>
    @endforeach
    <tr style="font-weight:bold">
        <td colspan="2">Total</td>
        <td>{{ $total_report['total_order_qty'] }}</td>
        <td>{{ $total_report['total_cutting_qty'] }}</td>
        <td>{{ $total_report['total_total_input'] }}</td>
        <td>{{ $total_report['total_total_output'] }}</td>
        <td>{{ $total_report['total_finished_qty'] }}</td>
        <td>{{ $total_report['total_in_line_wip'] }}</td>
    </tr>
@else
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold;">No Reports
        <td>
    </tr>
@endif
</tbody>