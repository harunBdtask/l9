<thead>
<tr>
    <th colspan="11">
        Buyer: {{ $buyer }} &nbsp;&nbsp;&nbsp;
        Style/Order No: {{ $order_style_no ??'' }} &nbsp;&nbsp;&nbsp;
        PO: {{ $po_no ??'' }}
    </th>
</tr>
<tr>
    <th>Color Name</th>
    <th>Size Name</th>
    <th>PO Quantity</th>
    <th>Cutting Production</th>
    <th>Cutting WIP</th>
    <th>Total Send</th>
    <th>Total Recieved</th>
    <th>Fabric Rejection</th>
    <th>Print Rejection</th>
    <th>Total Rejection</th>
    <th>Print WIP/Short</th>
</tr>
</thead>
<tbody>
@if(!empty($result_report['order_wise_data']))
    @foreach($result_report['order_wise_data'] as $report)
        <tr style="text-align: center;">
            <td>{{  $report['color']  }} </td>
            <td>{{  $report['size']  }} </td>
            <td>{{ $report['size_order_qty'] }} </td>
            <td>{{ $report['cutting_qty'] }} </td>
            <td>{{ $report['cutting_wip'] }} </td>
            <td>{{ $report['print_sent_qty'] }} </td>
            <td>{{ $report['print_received_qty'] }} </td>
            <td>{{  $report['fabric_rejection']  }} </td>
            <td>{{ $report['print_rejection'] }} </td>
            <td>{{ $report['total_rejection'] }} </td>
            <td>{{ $report['print_wip_short'] }} </td>
        </tr>
    @endforeach
    <tr style="text-align: center;font-weight: bold">
        <td colspan="2"><b>Total</b></td>
        <td>{{ $result_report['total_data']['total_order_qty'] }}</td>
        <td>{{ $result_report['total_data']['total_cutting_qty'] }}</td>
        <td>{{ $result_report['total_data']['total_wip_qty'] }}</td>
        <td>{{ $result_report['total_data']['total_sent_qty'] }}</td>
        <td>{{ $result_report['total_data']['total_received_qty'] }}</td>
        <td>{{ $result_report['total_data']['total_fabric_rejection'] }}</td>
        <td>{{ $result_report['total_data']['total_print_rejection'] }}</td>
        <td>{{ $result_report['total_data']['total_rejections'] }}</td>
        <td>{{ $result_report['total_data']['total_print_wip'] }}</td>
    </tr>
@else
    <tr>
        <td colspan="12" style="text-align: center;font-weight: bold">Not found</td>
    </tr>
@endif
</tbody>