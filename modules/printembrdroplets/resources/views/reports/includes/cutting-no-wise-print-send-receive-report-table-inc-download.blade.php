<thead>
<tr>
    <th colspan="13">
        Buyer: {{ $buyer ?? '' }} &nbsp;&nbsp;&nbsp;
        Style: {{ $style_name ?? '' }} &nbsp;&nbsp;&nbsp;
        PO: {{ $po_no ?? '' }} &nbsp;&nbsp;&nbsp;
        Color: {{ $color ?? '' }} &nbsp;&nbsp;&nbsp;
        Cutting No: {{ $cutting_no ?? '' }} &nbsp;&nbsp;&nbsp;
    </th>
</tr>
<tr>
    <th>Color Name</th>
    <th>Size Name</th>
    <th>PO Quantity</th>
    <th>Cutting Production</th>
    <th>Cutting WIP</th>
    <th>Bundle Send</th>
    <th>Total Send</th>
    <th>Bundle Recieved</th>
    <th>Total Recieved</th>
    <th>Fabric Rejection</th>
    <th>Print Rejection</th>
    <th>Total Rejection</th>
    <th>Print WIP/Short</th>
</tr>
</thead>
<tbody>
@if(!empty($result_report))
    @php
        $torder_qty = 0;
        $tcutting_qty = 0;
        $tcutting_wip = 0;
        $tbundle_send = 0;
        $ttotal_send = 0;
        $tbundle_received = 0;
        $ttotal_received = 0;
        $tfabric_rejection = 0;
        $tprint_rejection = 0;
        $ttotal_rejection = 0;
        $tprint_wip_short = 0;
    @endphp
    @foreach($result_report as $report)
        @php
            $torder_qty += $report['order_qty'];
            $tcutting_qty += $report['cutting_qty'];
            $tcutting_wip += $report['cutting_wip'];
            $tbundle_send += $report['bundle_send'];
            $ttotal_send += $report['total_send'];
            $tbundle_received += $report['bundle_received'];
            $ttotal_received += $report['total_received'];
            $tfabric_rejection += $report['fabric_rejection'];
            $tprint_rejection += $report['print_rejection'];
            $ttotal_rejection += $report['total_rejection'];
            $tprint_wip_short += $report['print_wip_short'];
        @endphp
        <tr style="text-align: center;">
            <td>{{ $report['color_name'] }}</td>
            <td>{{ $report['size_name'] }}</td>
            <td>{{ $report['order_qty'] }}</td>
            <td>{{ $report['cutting_qty'] }}</td>
            <td>{{ $report['cutting_wip'] }}</td>
            <td>{{ $report['bundle_send'] }}</td>
            <td>{{ $report['total_send'] }}</td>
            <td>{{ $report['bundle_received'] }}</td>
            <td>{{ $report['total_received'] }}</td>
            <td>{{ $report['fabric_rejection'] }}</td>
            <td>{{ $report['print_rejection'] }}</td>
            <td>{{ $report['total_rejection'] }}</td>
            <td>{{ $report['print_wip_short'] }}</td>
        </tr>
    @endforeach
    <tr style="text-align: center;font-weight: bold">
        <td colspan="2"><b>Total</b></td>
        <td>{{ $torder_qty }}</td>
        <td>{{ $tcutting_qty }}</td>
        <td>{{ $tcutting_wip }}</td>
        <td>{{ $tbundle_send }}</td>
        <td>{{ $ttotal_send }}</td>
        <td>{{ $tbundle_received }}</td>
        <td>{{ $ttotal_received }}</td>
        <td>{{ $tfabric_rejection }}</td>
        <td>{{ $tprint_rejection }}</td>
        <td>{{ $ttotal_rejection }}</td>
        <td>{{ $tprint_wip_short }}</td>
    </tr>
@else
    <tr>
        <td colspan="14" style="text-align: center; font-weight: bold;">Not found</td>
    </tr>
@endif
</tbody>