<table class="reportTable">
    <thead style="background: #0080002b">
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>Item</th>
        <th>O/Qty</th>
        <th>Unit Price</th>
        <th>Total Value</th>
        <th>Shipment date</th>
    </tr>
    </thead>
    <tbody>
    @php
        $sumPoQty = 0;
        $sumTotalValue = 0;
    @endphp
    @foreach($reports as  $buyer_id => $orderReport)
        @php
            $buyerTotalPoQty = 0;
            $buyerTotalValue = 0;
        @endphp
        @foreach($orderReport as $report)
            @php
                $totalPoQty = $report->sum('po_quantity');
                $unitPrice = $report->sum('avg_rate_pc_set');
                $totalValue = $totalPoQty*$unitPrice;
                $shipmentDate = \Carbon\Carbon::parse($report->sortByDesc('ex_factory_date')->first()['ex_factory_date'])->toFormattedDateString();
                $buyerTotalPoQty += $totalPoQty;
                $sumPoQty += $totalPoQty;
                $buyerTotalValue += $totalValue;
                $sumTotalValue += $totalValue;
            @endphp
            <tr>
                @if($loop->first)
                    <td rowspan="{{count($orderReport)}}">{{$buyers[$buyer_id] ?? null}}</td>
                @endif
                <td>{{$report->first()->order->style_name}}</td>
                <td>
                    {{$report->first()->order->item_details ?
                    collect($report->first()->order->item_details['details'])->implode('item_name',', ') : null}}
                    {{--                    {{Arr::get($report->first(), 'order.item_details.details.item_name')}}--}}
                </td>
                <td>{{$totalPoQty}}</td>
                <td>{{$unitPrice}}</td>
                <td>{{$totalValue}}</td>
                <td>{{$shipmentDate}}</td>
            </tr>
        @endforeach
        <tr style="background: #f0f5e1">
            <th colspan="3">Total</th>
            <th>{{$buyerTotalPoQty}}</th>
            <th></th>
            <th>{{$buyerTotalValue}}</th>
            <th></th>
        </tr>
    @endforeach
    <tr style="background: lightpink">
        <th colspan="3">Total</th>
        <th>{{$sumPoQty}}</th>
        <th></th>
        <th>{{$sumTotalValue}}</th>
        <th></th>
    </tr>
    </tbody>
</table>
