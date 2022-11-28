<p><b>Summary: </b></p>
<table class="reportTable" style="width: 100%">
    <thead style="background-color: #c1e2e7">
    <tr>
        <th>Buyer</th>
        <th>Factory Name</th>
        <th>Order Qty</th>
        <th>Shipped Qty</th>
        <th>Over / Shortage</th>
    </tr>
    </thead>
    <tbody>
    @foreach($summaryData as $key=>$summary)
        <tr>
            @if($loop->first)
                <td rowspan="{{count($summaryData)}}">{{$summary['factory']}}</td>
            @endif
            <td>{{$summary['buyer']}}</td>
            <td>{{$summary['order_qty']}}</td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    </tbody>
</table>
<p><b>Details: </b></p>
<table class="reportTable" style="width: 100%">
    <thead style="background-color: #c1e2e7">
    <tr>
        <th>SL</th>
        <th>Season</th>
        <th>Factory</th>
        <th>Buyer</th>
        <th>Customer</th>
        <th>Brand</th>
        <th>Style</th>
        <th>VPO</th>
        <th>Description</th>
        <th>Order Qty(Pcs)</th>

        @if (!$type == 'qc')
            <th>FTY FOB (US$/Pc)</th>
            <th>PO FOB (US$/Pc)</th>
            <th>PO Delivery Date</th>
        @endif

        <th>FTY Delivery Date</th>

        @if (!$type == 'qc')
            <th>FTY FOB Value(US$)</th>
            <th>PO FOB Value(US$)</th>
        @else
            <th>Shipped Qty</th>
            <th>Over / Shortage</th>
        @endif

        <th>REMARKS</th>
    </tr>
    </thead>
    <tbody>
    @php
        $TotalOrderQty=0;
        $TotalFtyFobValue=0;
        $TotalPoFobValue=0;
    @endphp
    @foreach($orderData as $key=>$order)
        @php
            $styleTotalOrderQty=0;
            $styleTotalFtyFobValue=0;
            $styleTotalPoFobValue=0;
        @endphp
        @foreach($order as $buyer_key=>$buyerWise)
            @foreach($buyerWise as $_key=>$poData)
                @php
                    $finalCostPcSet=Arr::get($poData, 'order.orderPriceQuotation.final_cost_pc_set');
                    $ftyFobValue=$finalCostPcSet*$poData->po_quantity;
                    $poFobValue=$poData->po_quantity*$poData->avg_rate_pc_set;

                    $styleTotalOrderQty += $poData->po_quantity;
                    $styleTotalFtyFobValue += $ftyFobValue;
                    $styleTotalPoFobValue += $poFobValue;
                    $TotalOrderQty +=  $poData->po_quantity;
                    $TotalFtyFobValue += $ftyFobValue;
                    $TotalPoFobValue += $poFobValue;
                @endphp
                <tr>
                    @if($loop->first)
                        <td rowspan="{{count($buyerWise)}}">{{$key}}</td>
                        <td rowspan="{{count($buyerWise)}}">{{Arr::get($poData,'order.season.season_name')}}</td>
                        <td rowspan="{{count($buyerWise)}}">{{Arr::get($poData, 'order.factory.factory_name')}}</td>
                        <td rowspan="{{count($buyerWise)}}">{{Arr::get($poData, 'buyer.name')}}</td>
                    @endif
                    <td>{{$poData->customer}}</td>
                    @if($loop->first)
                        <td rowspan="{{count($buyerWise)}}">{{Arr::get($poData,'order.remarks')}}</td>
                        <td rowspan="{{count($buyerWise)}}">{{Arr::get($poData,'order.style_name')}}</td>
                    @endif
                    <td>{{$poData->po_no}}</td>
                    @if($loop->first)
                        <td rowspan="{{count($buyerWise)}}">{{collect(Arr::get($poData,'order.item_details.details'))->implode('item_name', ',')}}</td>
                    @endif
                    <td>{{$poData->po_quantity}}</td>

                    @if (!$type == 'qc')
                        <td>${{$finalCostPcSet ?? 0}}</td>
                        <td>${{$poData->avg_rate_pc_set ?? 0}}</td>
                        <td>{{$poData->ex_factory_date}}</td>
                    @endif

                    <td>{{$poData->country_ship_date}}</td>

                    @if (!$type == 'qc')
                        <td>${{number_format($ftyFobValue,2)}}</td>
                        <td>${{number_format($poFobValue,2)}}</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif

                    <td>{{$poData->remarks}}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="8"></td>
            <td><b>Total:</b></td>
            <td><b>{{$styleTotalOrderQty}}</b></td>
            <td colspan="{{ $type == 'qc' ? '1' : '4' }}"></td>

            @if (!$type == 'qc')
                <td><b>${{number_format($styleTotalFtyFobValue,2)}}</b></td>
                <td><b>${{number_format($styleTotalPoFobValue,2)}}</b></td>
            @else
                <td><b></b></td>
                <td><b></b></td>
            @endif

            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="17"></td>
    </tr>
    <tr>
        <td colspan="8"></td>
        <td><b>Total Qty:</b></td>
        <td><b>{{$TotalOrderQty}}</b></td>
        <td colspan="{{ $type == 'qc' ? '1' : '4' }}"></td>

        @if (!$type == 'qc')
            <td><b>{{number_format($TotalFtyFobValue,2)}}</b></td>
            <td><b>{{number_format($TotalPoFobValue,2)}}</b></td>
        @else
            <td><b></b></td>
            <td><b></b></td>
        @endif

        <td></td>
    </tr>
    </tbody>
</table>
