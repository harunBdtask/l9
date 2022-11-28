<--DOCTYPE html>
<html>
<head>
    <title>Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Buyer Wise Cutting Production Report || {{ date("D\ - F d- Y") }} </h4>
    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        <thead>
        <tr>
            <th>Style/Order No</th>
            <th>PO</th>
            <th>Order Quantity</th>
            <th>Today's Cutting</th>
            <th>Total Cutting</th>
            <th>Left Quantity</th>
            <th>Extra Cuttting (%)</th>
        </tr>
        </thead>
        <tbody class="color-wise-report">
        @if($buyer_wise_data)
            @foreach($buyer_wise_data as $buyer)                
                <tr>
                    <td>{{ $buyer->style_name }}</td>
                    <td>{{ $buyer->order_no }}</td>
                    <td>{{ $buyer->order_qty }}</td>
                    <td>{{ $buyer->cutting_qty }}</td>
                    <td>{{ $buyer->todays_qty }}</td>
                    <td>{{ $buyer->left_qty }}</td>
                    <td>{{ $buyer->extra }} %</td>
                </tr>      
            @endforeach
            <tr style="font-weight: bold">
                <td colspan="2"><b>Total</b></td>
                <td>{{ $buyer_wise_data->sum('order_qty') }}</td>
                <td>{{ $buyer_wise_data->sum('cutting_qty') }}</td>
                <td>{{ $buyer_wise_data->sum('todays_qty')}}</td>
                <td>{{ $buyer_wise_data->sum('left_qty') }}</td>
                <td></td>
            </tr>;
        @else
            <tr>
                <td colspan="7" class="text-danger text-center">Not found</td>
            </tr>
        @endif
        </tbody>
    </table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>