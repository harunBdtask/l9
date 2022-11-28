<!DOCTYPE html>

<html>
<head>
    <title>Profit Loss Statement Report</title>
    @include('merchandising::reports.downloads.includes.pdf_style')
    <style>
        table {
            border-collapse: collapse;
        }
    </style>
</head>

<body>
@include('merchandising::download.include.report-header')
@include('merchandising::download.include.report-footer')
<main>
    <h4 align="center">Profit Loss Statement Report</h4>
    @if(isset($order_data) && $order_data->count() > 0)
        <table class="reportTable">
            <thead>
            <tr>
                <th>Booking No</th>
                <th>Item</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total_order_qty = 0;
                $total_order_price = 0;
            @endphp
            @foreach($order_data as $order)
                <tr>
                    <td>{{$order->order->booking_no}}</td>
                    <td>{{$order->item->item_name}}</td>
                    <td>$ {{$order->unit_price}}</td>
                    <td>{{$order->quantity}} Pcs</td>
                    <td>$ {{($order->quantity * $order->unit_price)}}</td>
                </tr>
                @php
                    $total_order_qty += $order->quantity;
                    $total_order_price += ($order->quantity*$order->unit_price);
                @endphp
            @endforeach
            <tr>
                <td colspan="3"><b>Total</b></td>
                <td><b>{{$total_order_qty}} Pcs</b></td>
                <td><b>$ {{$total_order_price}}</b></td>
            </tr>
            </tbody>
        </table>
        <h5>Budget Details</h5>
        @php $grand_total_production_cost = 0 @endphp
        @foreach($budget_details as  $detail)
            @php
                $knitting_cost = $detail->budget_knitting->sum('knitting_part_knitting_total');
                $yarn_cost = $detail->budget_yarn->sum('yarn_part_total_yarn_value');
                $dyeing_cost = $detail->budget_dyeing->sum('dyeing_part_total_cost');
                $total_fab_cost = ($knitting_cost + $yarn_cost + $dyeing_cost);
                $total_trims_cost = $detail->budget_trims->sum('total_cost');
                $total_others_cost = $detail->budget_others->sum('total_cost');
                $total_production_cost = $total_fab_cost+ $total_trims_cost+$total_others_cost;
                $grand_total_production_cost += $total_production_cost;
            @endphp
            <table class="reportTable">
                <tbody>
                <tr>
                    <td colspan="2"><b>BUDGET NO : {{$detail->budget_number}}</b></td>
                </tr>
                <tr>
                    <td>Fabric Cost</td>
                    <td>$ {{$total_fab_cost}}</td>
                </tr>
                <tr>
                    <td>Trims Accessories Cost</td>
                    <td>$ {{$total_trims_cost}}</td>
                </tr>
                <tr>
                    <td>Others Cost</td>
                    <td>$ {{$total_others_cost}}</td>
                </tr>
                <tr>
                    <td><b>Total Production Cost</b></td>
                    <td><b>$ {{$total_production_cost}}</b></td>
                </tr>
                </tbody>
            </table>
            <br>
        @endforeach
        <h5>Profit Or Loss Statement</h5>
        <table class="reportTable">
            <tr>
                <td><b>Approx Cost</b></td>
                <td><b>$ {{$total_order_price}}</b></td>
            </tr>
            <tr>
                <td><b>Production Cost</b></td>
                <td><b>$ {{$grand_total_production_cost}}</b></td>
            </tr>
            <tr>
                <td><b>Profit Or Loss</b></td>
                @php $res = ($total_order_price - $grand_total_production_cost)  @endphp
                <td><b>$ {!! $res < 0 ? '<span style="background:red;padding:3px">'.$res.'</span>' : '<span style="background:green;padding:3px">'.$res.'</span>' !!}</b></td>
            </tr>
        </table>
    @endif
</main>
</body>
</html>