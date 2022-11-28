<table>
    <thead>
    <tr>
        <th rowspan="2">Buyer</th>
        <th colspan="7">Order Details</th>
    </tr>
    <tr>
        <th></th>
        <th>Our Reference</th>
        <th>Order No</th>
        <th>Order Qty</th>
        <th>Today's Cutting</th>
        <th>Total Cutting</th>
        <th>Left Qty</th>
        <th>Extra Cutting (%)</th>
    </tr>
    </thead>
    <tbody>
    @foreach($orders->getCollection()->groupBy('buyer_id') as $ordersByBuyer)
        @if($loop->first)
            <tr>
                <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('style_id')->count() + 1}}">{{ $ordersByBuyer->first()->buyer->name ?? '' }}</td>
                @foreach($ordersByBuyer->groupBy('style_id') as $ordersByStyle)
                    <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('style_id')->count() + 1}}">{{ $ordersByStyle->first()->style->name ?? '' }}</td>
                @endforeach
            </tr>
            @foreach($ordersByBuyer->groupBy('style_id') as $ordersByStyle)

                @php
                    $totalOrderQty = 0;
                    $totalTodaysCutting = 0;
                    $totalCuttingForStyle = 0;
                    $totalLeftQty = 0;
                    $totalXtra = 0;
                @endphp

                @foreach($ordersByStyle as $order)
                    <?php
                    $todaysCutting = $order->todays_cutting ?? 0;
                    $totalCutting = $order->total_cutting ?? 0;

                    $xtra = (($totalCutting - $order->order->total_quantity) * 100) / $order->order->total_quantity ?? 0;
                    $xtra = $xtra > 0 ? $xtra : 0;
                    $leftQty = $order->order->total_quantity - $totalCutting;

                    $totalOrderQty += $order->order->total_quantity;
                    $totalTodaysCutting += $todaysCutting;
                    $totalCuttingForStyle += $totalCutting;
                    $totalLeftQty += $leftQty;
                    $totalXtra += $xtra;
                    ?>
                    @if($loop->first)
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $order->order->order_no }}</td>
                            <td>{{ $order->order->total_quantity }}</td>
                            <td>{{ $todaysCutting }}</td>
                            <td>{{ $totalCutting }}</td>
                            <td>{{ $leftQty }}</td>
                            <td>{{ number_format($xtra, 2).'%' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $order->order->order_no }}</td>
                            <td>{{ $order->order->total_quantity }}</td>
                            <td>{{ $todaysCutting }}</td>
                            <td>{{ $totalCutting }}</td>
                            <td>{{ $leftQty }}</td>
                            <td>{{ number_format($xtra, 2).'%' }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>{{ 'TOTAL' }}</strong></td>
                    <td><strong>{{ $totalOrderQty }}</strong></td>
                    <td><strong>{{ $totalTodaysCutting }}</strong></td>
                    <td><strong>{{ $totalCuttingForStyle }}</strong></td>
                    <td><strong>{{ $totalLeftQty }}</strong></td>
                    <td><strong>{{ number_format($totalXtra, 2).'%' }}</strong></td>
                </tr>
            @endforeach
        @endif
        @if(!$loop->first)
            <tr>
                <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('style_id')->count() + 1}}">{{ $ordersByBuyer->first()->buyer->name ?? '' }}</td>
                @foreach($ordersByBuyer->groupBy('style_id') as $ordersByStyle)
                    <td rowspan="{{ $ordersByBuyer->count() + $ordersByBuyer->groupBy('style_id')->count() + 1}}">{{ $ordersByStyle->first()->style->name ?? '' }}</td>
                @endforeach
            </tr>
            @foreach($ordersByBuyer->groupBy('style_id') as $ordersByStyle)

                @php
                    $totalOrderQty = 0;
                    $totalTodaysCutting = 0;
                    $totalCuttingForStyle = 0;
                    $totalLeftQty = 0;
                    $totalXtra = 0;
                @endphp

                @foreach($ordersByStyle as $order)
                    <?php
                    $todaysCutting = $order->todays_cutting ?? 0;
                    $totalCutting = $order->total_cutting ?? 0;
                    $xtra = (($totalCutting - $order->order->total_quantity) * 100) / $order->order->total_quantity ?? 0;
                    $xtra = $xtra > 0 ? $xtra : 0;
                    $leftQty = $order->order->total_quantity - $totalCutting;

                    $totalOrderQty += $order->order->total_quantity;
                    $totalTodaysCutting += $todaysCutting;
                    $totalCuttingForStyle += $totalCutting;
                    $totalLeftQty += $leftQty;
                    $totalXtra += $xtra;
                    ?>
                    @if($loop->first)
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $order->order->order_no }}</td>
                            <td>{{ $order->order->total_quantity }}</td>
                            <td>{{ $todaysCutting }}</td>
                            <td>{{ $totalCutting }}</td>
                            <td>{{ $leftQty }}</td>
                            <td>{{ number_format($xtra, 2).'%' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $order->order->order_no }}</td>
                            <td>{{ $order->order->total_quantity }}</td>
                            <td>{{ $todaysCutting }}</td>
                            <td>{{ $totalCutting }}</td>
                            <td>{{ $leftQty }}</td>
                            <td>{{ number_format($xtra, 2).'%' }}</td>
                        </tr>
                    @endif
                @endforeach
                <tr>
                    <td></td>
                    <td></td>
                    <td><strong>{{ 'TOTAL' }}</strong></td>
                    <td><strong>{{ $totalOrderQty }}</strong></td>
                    <td><strong>{{ $totalTodaysCutting }}</strong></td>
                    <td><strong>{{ $totalCuttingForStyle }}</strong></td>
                    <td><strong>{{ $totalLeftQty }}</strong></td>
                    <td><strong>{{ number_format($totalXtra, 2).'%' }}</strong></td>
                </tr>
            @endforeach
        @endif
    @endforeach
    </tbody>
</table>