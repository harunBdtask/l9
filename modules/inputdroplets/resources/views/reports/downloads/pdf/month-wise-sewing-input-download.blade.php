<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <h4 align="center">Month wise Sewing Input Report || From: {{ $from_date }} To: {{ $to_date }}</h4>

    <table class="reportTable" style="border-collapse: collapse;">
        <thead>
        <tr>
            <th colspan="6">Section-1 : Challan No. Wise Sewing Input Status</th>
        </tr>
        <tr>
            <th>Line</th>
            <th>Challan No.</th>
            <th>Buyer</th>
            <th>Order/Style</th>
            <th>PO</th>
            <th>Input Qty</th>
        </tr>
        </thead>
        <tbody class="color-wise-report">
        @if(!empty($date_wise_input))
            @php
                $total_input = 0;
            @endphp
            @foreach($date_wise_input as $report)
                @php
                    $po_wise_input = $report->quantity_sum - $report->total_rejection_sum - $report->print_rejection_sum;
                    $total_input += $po_wise_input;
                @endphp
                <tr>
                    <td>{{ $report->line->line_no ?? '' }}</td>
                    <td>{{ $report->challan_no ?? '' }}</td>
                    <td>{{ $report->order->buyer->name ?? '' }}</td>
                    <td>{{ $report->order->style_name ?? '' }}</td>
                    <td>{{ $report->purchaseOrder->po_no ?? '' }}</td>
                    <td>{{  $po_wise_input }}</td>
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="5">Total</td>
                <td>{{ $total_input }}</td>
            </tr>
        @else
            <tr>
                <td colspan="5" class="text-danger text-center">Not found
                <td>
            </tr>
        @endif
        </tbody>
    </table>

    <!-- line wise -->
    <table class="reportTable" style="border-collapse: collapse;">
        <thead>
        <tr>
            <th colspan="2">Section-2 : Line Wise Input Status</th>
        </tr>
        <tr>
            <th>Line</th>
            <th>Input Quantity</th>
        </tr>
        </thead>
        <tbody class="color-wise-report">
        @if(!empty($date_wise_input))
            @php
                $total_input_line = 0;
            @endphp
            @foreach($date_wise_input->groupBy('line_id') as $report_line_wise)
                @php
                    $line_unique = $report_line_wise->first();
                    $input_line = $report_line_wise->sum('quantity_sum') - $report_line_wise->sum('total_rejection_sum') - $report_line_wise->sum('print_rejection_sum');
                    $total_input_line += $input_line;
                @endphp
                <tr>
                    <td>{{ $line_unique->line->line_no ?? '' }}</td>
                    <td>{{ $input_line }}</td>
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td>Total</td>
                <td>{{ $total_input_line }}</td>
            </tr>
        @else
            <tr>
                <td colspan="2" class="text-danger text-center">Not found
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</main>
</body>
</html>
