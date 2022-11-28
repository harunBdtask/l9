<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">Date Wise Finishing Report
    <small class="text-muted text-center">(From {{ date("jS F, Y", strtotime($from_date)) }} to {{ date("jS F, Y", strtotime($to_date)) }})</small>
</h4>

<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="2" style="font-size: 14px; font-weight: bold">Section-1 : Buyer Wise Report</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Finishing Received</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if(!empty($buyer_wise_report))
        @php
            $total_finished_qty = 0;
        @endphp
        @foreach($buyer_wise_report as $buyer)
            @php
                $total_finished_qty += $buyer['finished_qty'];
            @endphp
            <tr>
                <td>{{ $buyer['buyer'] }}</td>
                <td>{{ $buyer['finished_qty'] }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td>Total</td>
            <td>{{ $total_finished_qty }}</td>
        </tr>
    @else
        <tr>
            <td colspan="1" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>

<!-- line wise report -->
<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="4" style="font-size: 14px; font-weight: bold">Section-2 : Order Wise Report</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Finishing Received</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($order_wise_report))
        @php
            $total_order_finished_qty = 0;
        @endphp
        @foreach($order_wise_report as $order_wise)
            @php
                $total_order_finished_qty += $order_wise['order_finished_qty'];
            @endphp
            <tr>
                <td>{{ $order_wise['buyer'] }}</td>
                <td>{{ $order_wise['style'] }}</td>
                <td>{{ $order_wise['order'] }}</td>
                <td>{{ $order_wise['order_finished_qty'] }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="3">Total</td>
            <td>{{ $total_order_finished_qty }}</td>
        </tr>
    @else
        <tr>
            <td colspan="3" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>
<!-- buyer order wise report -->
<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="5" style="font-size: 14px; font-weight: bold">Section-3 : Color Wise Report</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Color</th>
        <th>Finishing Received</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($color_wise_report))
        @php
            $total_color_finished_qty = 0;
        @endphp
        @foreach($color_wise_report as $color_wise)
            @php
                $total_color_finished_qty += $color_wise['color_finished_qty'];
            @endphp
            <tr>
                <td>{{ $color_wise['buyer'] }}</td>
                <td>{{ $color_wise['style'] }}</td>
                <td>{{ $color_wise['order'] }}</td>
                <td>{{ $color_wise['color'] }}</td>
                <td>{{ $color_wise['color_finished_qty'] }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="4">Total</td>
            <td>{{ $total_color_finished_qty }}</td>
        </tr>
    @else
        <tr>
            <td colspan="4" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>

<!-- color order wise report -->
<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="6" style="font-size: 14px; font-weight: bold">Section-4 : Size Wise Report</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Color</th>
        <th>Size</th>
        <th>Finishing Received</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($size_wise_report))
        @php
            $total_size_finished_qty = 0;
        @endphp
        @foreach($size_wise_report as $size_wise)
            @php
                $total_size_finished_qty += $size_wise['size_finished_qty'];
            @endphp
            <tr>
                <td>{{ $size_wise['buyer'] }}</td>
                <td>{{ $size_wise['style'] }}</td>
                <td>{{ $size_wise['order'] }}</td>
                <td>{{ $size_wise['color'] }}</td>
                <td>{{ $size_wise['size'] }}</td>
                <td>{{ $size_wise['size_finished_qty'] }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="5">Total</td>
            <td>{{ $total_size_finished_qty }}</td>
        </tr>
    @else
        <tr>
            <td colspan="5" style="text-align: center; font-weight: bold;">Not found<td>
        </tr>
    @endif
    </tbody>
</table>
</main>
</body>
</html>
