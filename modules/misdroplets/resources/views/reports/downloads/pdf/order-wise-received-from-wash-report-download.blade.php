<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">All Order's Washing Sent & Received Summary || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>Order</th>
        <th>Order Qty</th>
        <th>Cut. Production</th>
        <th>Today's Input</th>
        <th>Total Input</th>
        <th>Today's Output</th>
        <th>Total Output</th>
        <th>Today's Sent</th>
        <th>Total Sent</th>
        <th>Today's Received</th>
        <th>Total Received</th>
        <th>Total Rejection</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($order_wise_report))
        @foreach($order_wise_report as $report)
            <tr>
                <td>{{ $report->buyer }}</td>
                <td>{{ $report->style }}</td>
                <td>{{ $report->order }}</td>
                <td>{{ $report->order_qty }}</td>
                <td>{{ $report->cutting_qty }}</td>
                <td>{{ $report->today_input }}</td>
                <td>{{ $report->total_input }}</td>
                <td>{{ $report->today_output }}</td>
                <td>{{ $report->total_output }}</td>
                <td>{{ $report->today_wash_send }}</td>
                <td>{{ $report->total_wash_send }}</td>
                <td>{{ $report->today_wash_receive }}</td>
                <td>{{ $report->total_wash_receive }}</td>
                <td>{{ $report->total_rejection }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold">
            <td colspan="3">Total</td>
            <td>{{ $order_wise_report->sum('order_qty') }}</td>
            <td>{{ $order_wise_report->sum('cutting_qty') }}</td>
            <td>{{ $order_wise_report->sum('today_input') }}</td>
            <td>{{ $order_wise_report->sum('total_input') }}</td>
            <td>{{ $order_wise_report->sum('today_output') }}</td>
            <td>{{ $order_wise_report->sum('total_output') }}</td>
            <td>{{ $order_wise_report->sum('today_wash_send') }}</td>
            <td>{{ $order_wise_report->sum('total_wash_send') }}</td>
            <td>{{ $order_wise_report->sum('today_wash_receive') }}</td>
            <td>{{ $order_wise_report->sum('total_wash_receive') }}</td>
            <td>{{ $order_wise_report->sum('total_rejection') }}</td>
        </tr>
    @else
        <tr>
            <td colspan="14" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>

</main>
</body>
</html>
