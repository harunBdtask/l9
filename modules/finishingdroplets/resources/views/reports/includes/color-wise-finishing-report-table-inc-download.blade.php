<thead>
<tr>
    <th colspan="16">
        Buyer:: {{ $buyer ?? '' }}  &nbsp;&nbsp;&nbsp;
        Style/Order No:: {{ $order_style_no ?? '' }}  &nbsp;&nbsp;&nbsp;
        PO:: {{ $po_no ?? '' }}  &nbsp;&nbsp;&nbsp;
    </th>
</tr>
<tr>
    <th>Colour</th>
    <th>Size</th>
    <th>Order Qty</th>
    <th>Cutting Prod.</th>
    <th>WIP in Cutting/Print/Embr.</th>
    <th>Total Input</th>
    <th>Total Output</th>
    <th>Finishing Received</th>
    <th>Total Rejection</th>
    <th>In_line WIP</th>
    <th>Order 2 Cut(%)</th>
    <th>Order 2 Input(%)</th>
    <th>Order 2 Sewing(%)</th>
    <th>Get up</th>
    <th>+/- To Order</th>
    <th>Order 2 Getup (%)</th>
</tr>
</thead>
<tbody>
@if(!empty($report_color_wise))
    @foreach($report_color_wise as $report)
        <tr>
            <td>{{ $report['color'] }}</td>
            <td>{{ $report['size'] }}</td>
            <td>{{ $report['size_order_qty'] }}</td>
            <td>{{ $report['size_cutting_qty'] }}</td>
            <td>{{ $report['wip'] }}</td>
            <td>{{ $report['total_input'] }}</td>
            <td>{{ $report['total_output'] }}</td>
            <td>{{ $report['finished_qty'] }}</td>
            <td>{{ $report['rejection'] }}</td>
            <td>{{ $report['in_line_wip'] }}</td>
            <td>{{ $report['total_cutt_order'] }}</td>
            <td>{{ $report['order_to_input'] }}</td>
            <td>{{ $report['ratio'] }}</td>
            <td>{{ $report['goq'] }}</td>
            <td>{{ $report['balance'] }}</td>
            <td>{{ $report['gpercent'] }}</td>
        </tr>
    @endforeach
    <tr style="font-weight:bold">
        <td colspan="2">Total</td>
        <td>{{ $total_report['total_order_qty'] }}</td>
        <td>{{ $total_report['total_cutting_qty'] }}</td>
        <td>{{ $total_report['total_wip'] }}</td>
        <td>{{ $total_report['total_total_input'] }}</td>
        <td>{{ $total_report['total_total_output'] }}</td>
        <td>{{ $total_report['total_finished_qty'] }}</td>
        <td>{{ $total_report['total_rejection'] }}</td>
        <td>{{ $total_report['total_in_line_wip'] }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
@else
    <tr>
        <td colspan="7" style="text-align: center; font-weight: bold;">No Reports
        <td>
    </tr>
@endif
</tbody>