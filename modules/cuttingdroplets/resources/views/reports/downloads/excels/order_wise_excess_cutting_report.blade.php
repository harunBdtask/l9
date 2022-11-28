<table>
    <thead>
        <tr>
            <th>SL</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>Order/Style</th>
            <th>PO</th>
            <th>Order Qty</th>
            <th>Today's Cutting</th>
            <th>Total Cutting</th>
            <th>Extra Qty</th>
            <th>Extra Cutting(%)</th>
        </tr>
    </thead>
    <tbody>
    @if(!$orders->getCollection()->isEmpty())
        @php
            $total_order_qty = 0;
            $todays_cutting_qty = 0;
            $total_cutting_qty = 0;
            $total_extra_qty = 0;
        @endphp
        @foreach($orders->getCollection() as $order)
            {{--                                    @if($order->total_cutting > $order->total_quantity)--}}
            @php
                $total_order_qty += $order->total_quantity;
                $todays_cutting_qty += $order->todays_cutting;
                $total_cutting_qty += $order->total_cutting;
                $extra_qty = $order->total_cutting - $order->total_quantity ?? 0;
                $extra_cutting_percent = ($order->total_quantity > 0) ? ((( $order->total_cutting - $order->total_quantity) * 100) / $order->total_quantity) : 0;
                $total_extra_qty += $order->total_cutting - $order->total_quantity ?? 0;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->buyer_name }}</td>
                <td>{{ $order->style_name }}</td>
                <td>{{ $order->order_style_no }}</td>
                <td>{{ $order->order_no }}</td>
                <td>{{ $order->total_quantity }}</td>
                <td>{{ $order->todays_cutting }}</td>
                <td>{{ $order->total_cutting }}</td>
                <td>{{ $extra_qty }}</td>
                <td>{{ $extra_cutting_percent }}</td>
            </tr>
            {{--@endif--}}
        @endforeach
        <tr>
            <th colspan="5">Total</th>
            <th>{{$total_order_qty}}</th>
            <th>{{$todays_cutting_qty}}</th>
            <th>{{$total_cutting_qty}}</th>
            <th>{{$total_extra_qty}}</th>
            <th></th>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="10" align="center" class="text-danger text-center">No Data
            <td>
        </tr>
    @endif
    </tbody>
    <tfoot>
    @if(!$print && $orders->total() > 15)
        <tr>
            <td colspan="10"
                align="center">{{ $orders->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
    </tfoot>
</table>