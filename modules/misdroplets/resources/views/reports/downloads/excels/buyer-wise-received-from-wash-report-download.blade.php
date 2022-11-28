<table>
    <thead>
    <tr style="text-align: center;">
        <th>Style</th>
        <th>Order</th>
        <th>Color</th>
        <th>Order Qty</th>
        <th>Cut. Production</th>
        <th>Today's Input</th>
        <th>Total Input</th>
        <th>Today's Output</th>
        <th>Total Output</th>
        <th>Total Rejection</th>
        <th>Today's Sent</th>
        <th>Total Sent</th>
        <th>Today's Received</th>
        <th>Total Received</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($report_color_wise))
        @foreach($report_color_wise as $report)
            <tr style="text-align: center;">
                <td>{{ $report['style'] }} </td>
                <td>{{ $report['order'] }} </td>
                <td>{{ $report['color'] }} </td>
                <td>{{ $report['order_color_qty'] }} </td>
                <td>{{ $report['cutting_qty'] }} </td>
                <td>{{ $report['today_input'] }} </td>
                <td>{{ $report['total_input'] }} </td>
                <td>{{ $report['today_output'] }} </td>
                <td>{{ $report['total_output'] }} </td>
                <td>{{ $report['rejection'] }} </td>
                <td>{{ $report['today_wash_send'] }} </td>
                <td>{{ $report['total_wash_send'] }} </td>
                <td>{{ $report['today_wash_received'] }} </td>
                <td>{{ $report['total_wash_received'] }} </td>
            </tr>
        @endforeach
        <tr style="text-align: center; font-weight:bold">
            <td colspan="3" style="text-align: center;">Total</td>
            <td>{{ $total_report['total_order_qty'] }}</td>
            <td>{{ $total_report['total_cutting_qty'] }}</td>
            <td>{{ $total_report['total_today_input'] }}</td>
            <td>{{ $total_report['total_total_input'] }}</td>
            <td>{{ $total_report['total_today_output'] }}</td>
            <td>{{ $total_report['total_total_output'] }}</td>
            <td>{{ $total_report['total_rejection'] }}</td>
            <td>{{ $total_report['total_total_today_sent'] }}</td>
            <td>{{ $total_report['total_total_sent'] }}</td>
            <td>{{ $total_report['total_today_received'] }}</td>
            <td>{{ $total_report['total_total_received'] }}</td>
        </tr>
    @else
        <tr>
            <td colspan="14" style="text-align: center; font-weight: bold;" >Not found</td>
        </tr>
    @endif

    </tbody>
</table>