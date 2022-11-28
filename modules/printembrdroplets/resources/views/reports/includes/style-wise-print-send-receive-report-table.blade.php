@if(!empty($result_report))
    @php
        $torder_qty = 0;
        $tcutting_qty = 0;
        $tcutting_wip = 0;
        $tbundle_sent = 0;
        $ttotal_sent = 0;
        $tbundle_received = 0;
        $ttotal_received = 0;
        $tfabric_rejection = 0;
        $tprint_rejection = 0;
        $ttotal_rejection = 0;
        $tprint_wip_short = 0;
        $sum_rejection = 0;
    @endphp
    @foreach($result_report as $report)
        @php
            $print_wip_short = 0;
            $wip = 0;
            $total_rej = 0;
            $style_name = $report['style'] ?? 'Style';
            $order_no = $report['po'] ?? '';
            $total_quantity = $report['order_quantity'] ?? 0;
            $total_rej = $report['total_cutting_rejection'] + $report['total_print_rejection'];

            $sum_rejection += $total_rej;
            $torder_qty += $total_quantity;
            $tcutting_qty += $report['total_cutting'];
            $wip = $report['total_cutting'] - $report['total_sent'];
            $tcutting_wip += $wip;
            $ttotal_sent += $report['total_sent'];
            $ttotal_received += $report['total_received'];
            $tprint_rejection += $report['total_print_rejection'];
            $ttotal_rejection += $report['total_cutting_rejection'];
            $print_wip_short = $report['total_sent'] - $report['total_received'];
            $tprint_wip_short += $print_wip_short;
        @endphp
        <tr>
            <td>{{ $order_no }}</td>
            <td>{{ $total_quantity }}</td>
            <td>{{ $report['total_cutting'] }}</td>
            <td>{{ $wip }}</td>
            <td>{{ $report['total_sent'] }}</td>
            <td>{{ $report['total_received'] }}</td>
            <td>{{ $report['total_cutting_rejection'] }}</td>
            <td>{{ $report['total_print_rejection'] }}</td>
            <td>{{ $total_rej }}</td>
            <td>{{ $print_wip_short }}</td>
        </tr>
    @endforeach
    <tr style="text-align: center;font-weight: bold">
        <td>Total</td>
        <td>{{$torder_qty}}</td>
        <td>{{$tcutting_qty}}</td>
        <td>{{$tcutting_wip}}</td>
        <td>{{$ttotal_sent}}</td>
        <td>{{$ttotal_received}}</td>
        <td>{{$ttotal_rejection}}</td>
        <td>{{$tprint_rejection}}</td>
        <td>{{$sum_rejection}}</td>
        <td>{{$tprint_wip_short}}</td>
    </tr>
@else
    <tr>
        <td colspan="12" style="text-align: center;font-weight: bold">Not found</td>
    </tr>
@endif