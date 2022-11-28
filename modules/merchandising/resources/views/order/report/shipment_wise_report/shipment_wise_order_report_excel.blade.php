<div class="row m-t">
    <div class="col-sm-12">
        <span><b>Total Buyer : {{ count($orders) }}</b></span>&nbsp;&nbsp;&nbsp;
        <span><b>Total Order Value : {{ $totalValue }}</b></span>
        <table class="reportTable" style="width: 100%">
            <thead style="background-color: aliceblue;">
            <tr>
                <th>Buyer</th>
                <th>Style</th>
                <th>Total No Style</th>
                <th>Order Qty (PCs)</th>
                <th>Older Value</th>
                <th>Ship Month</th>
                <th>Percentage</th>
            </tr>
            </thead>
            <tbody>
            @php
                $totalQty = 0;
            @endphp
            @if(count($orders))
                @foreach($orders as $key => $order)
                    @php
                        $totalQty += $order['order_qty'];
                    @endphp
                    <tr>
                        <td class="text-left">{{ $order['buyer_name'] }}</td>
                        <td class="text-left">{{ $order['style_name'] }}</td>
                        <td class="text-right">{{ $order['total_style'] }}</td>
                        <td class="text-right">{{ $order['order_qty'] }}</td>
                        <td class="text-right">{{ number_format($order['order_value'], 2) }}</td>
                        <td>{{ $order['ship_month'] }}</td>
                        <td class="text-right">{{ number_format($totalValue ? ($order['order_value'] / $totalValue) * 100 : 0, 2) }}%</td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7" class="text-danger">No Data Found</td>
                </tr>
            @endif
            </tbody>
            <tbody>
            <tr style="background-color: aliceblue;">
                <td colspan="3" class="text-right"><b>Total</b></td>
                <td class="text-right"><b>{{ $totalQty }}</b></td>
                <td class="text-right"><b>{{ number_format($totalValue, 2) }}</b></td>
                <td colspan="2"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
