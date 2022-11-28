<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Line Wise Input,Output & In-Line WIP Summary || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
    <thead>
    <tr>
        <th>Floor</th>
        <th>Line</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>Today's Input</th>
        <th>Total Input</th>
        <th>Today's Output</th>
        <th>Total Output</th>
        <th>Rejection</th>
        <th>In-Line WIP</th>
        <th>WIP (%)</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($line_wise_report))
        @php
            $total_today_input = 0;
            $total_total_input = 0;
            $total_today_output = 0;
            $total_total_output = 0;
            $total_rejection = 0;
            $total_line_wip = 0;
            $total_wip = 0;
        @endphp
        @foreach($line_wise_report as $report)
            @php
                $total_today_input += $report['today_input'];
                $total_total_input += $report['total_input'];
                $total_today_output += $report['today_output'];
                $total_total_output += $report['total_output'];
                $total_rejection += $report['rejection'];
                $total_line_wip += $report['line_wip'];
                $total_wip += $report['wip'];
            @endphp
            <tr>
                <td>{{ $report['floor'] }}</td>
                <td>{{ $report['line'] }}</td>
                <td>{{ $report['buyer'] }}</td>
                <td>{{ $report['style'] }}</td>
                <td>{{ $report['order'] }}</td>
                <td>{{ $report['today_input'] }}</td>
                <td>{{ $report['total_input'] }}</td>
                <td>{{ $report['today_output'] }}</td>
                <td>{{ $report['total_output'] }}</td>
                <td>{{ $report['rejection'] }}</td>
                <td>{{ $report['line_wip'] }}</td>
                <td>{{ $report['wip'] }}%</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="5">Total</td>
            <td>{{ $total_today_input }}</td>
            <td>{{ $total_total_input }}</td>
            <td>{{ $total_today_output }}</td>
            <td>{{ $total_total_output }}</td>
            <td>{{ $total_rejection }}</td>
            <td>{{ $total_line_wip }}</td>
            <td>{{ '' }}</td>
        </tr>
        </tr>
    @else
        <tr>
            <td colspan="13" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>