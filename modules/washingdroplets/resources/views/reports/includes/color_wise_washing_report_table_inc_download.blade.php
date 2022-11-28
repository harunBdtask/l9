<thead>
<tr>
    <th colspan="9">
        Buyer: {{ $buyer }} &nbsp;&nbsp;&nbsp;
        Style/Order No: {{ $order_style_no }} &nbsp;&nbsp;&nbsp;
        PO: {{ $po_no }} &nbsp;&nbsp;&nbsp;
    </th>
</tr>
<tr>
    <th>Color</th>
    <th>Cutting Qty</th>
    <th>Sewing Output</th>
    <th>Today Sent</th>
    <th>Total Sent</th>
    <th>Today Received</th>
    <th>Total Received</th>
    <th>Total Balance</th>
    <th>Washing Rejection</th>
</tr>
</thead>
<tbody>
@if($reportData)
    @php
        $sum_cuttingQty = 0;
        $sum_sewingOutputQty = 0;
        $sum_today_send = 0;
        $sum_total_send = 0;
        $sum_today_received = 0;
        $sum_total_received = 0;
        $sum_total_balance = 0;
        $sum_total_rejection = 0;
    @endphp
    @foreach($reportData as $report)
        @php
            $sum_cuttingQty += $report->total_cutting - $report->total_cutting_rejection ?? 0;
            $sum_sewingOutputQty += $report->total_sewing_output ?? 0;
            $sum_today_send += $report->todays_washing_sent ?? 0;
            $sum_total_send += $report->total_washing_sent ?? 0;
            $sum_today_received += $report->todays_washing_received ?? 0;
            $sum_total_received += $report->total_washing_received ?? 0;
            $sum_total_balance += $report->total_washing_sent - $report->total_washing_received ?? 0;
            $sum_total_rejection += $report->total_washing_rejection ?? 0;
        @endphp
        <tr>
            <td>{{$report->colors->name ?? 'Color'}}</td>
            <td>{{$report->total_cutting - $report->total_cutting_rejection ?? 0}}</td>
            <td>{{$report->total_sewing_output ?? 0}}</td>
            <td>{{$report->todays_washing_sent ?? 0}}</td>
            <td>{{$report->total_washing_sent ?? 0}}</td>
            <td>{{$report->todays_washing_received ?? 0}}</td>
            <td>{{$report->total_washing_received ?? 0}}</td>
            <td>{{$report->total_washing_sent - $report->total_washing_received ?? 0}}</td>
            <td>{{$report->total_washing_rejection ?? 0}}</td>
        </tr>
    @endforeach
    <tr>
        <th>Total</th>
        <th>{{ $sum_cuttingQty }}</th>
        <th>{{ $sum_sewingOutputQty }}</th>
        <th>{{ $sum_today_send }}</th>
        <th>{{ $sum_total_send }}</th>
        <th>{{ $sum_today_received }}</th>
        <th>{{ $sum_total_received }}</th>
        <th>{{ $sum_total_balance }}</th>
        <th>{{ $sum_total_rejection }}</th>
    </tr>
@else
    <tr>
        <th colspan="9">Data not found</th>
    </tr>
@endif
</tbody>