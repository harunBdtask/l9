
<p><b>Summary: </b></p>
<table class="reportTable" style="width: 100%">
    <thead style="background-color: #c1e2e7">
    <tr>
        <td style="background-color: lightblue; padding: 0px;"><b>Buyer</b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Factory Name</b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Order Qty</b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Shipped Qty</b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Over / Shortage</b></td>
    </tr>
    </thead>
    <tbody>
    @foreach($summaryData as $buyerSummary)
        @foreach($buyerSummary['assigning_factory_data'] as $summary)
            <tr>
                @if($loop->first)
                    <td rowspan="{{count($buyerSummary['assigning_factory_data'])}}">{{$buyerSummary['buyer']}}</td>
                @endif
                <td>{{$summary['assigning_factory']}}</td>
                <td>{{$summary['order_qty']}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
<p><b>Details: </b></p>
<table class="reportTable" style="width: 100%">
    <thead style="background-color: #c1e2e7">
    <tr>
        <td style="background-color: lightblue; padding: 0px;"><b>SL </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Season </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Factory </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Buyer </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Customer </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Brand </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Style </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>VPO </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Description </b></td>
        <td style="background-color: lightblue; padding: 0px;"><b>Order Qty(Pcs) </b></td>

        @if (!$type == 'qc')
            <td style="background-color: lightblue; padding: 0px;"><b>FTY FOB (US$/Pc)</b></td>
            <td style="background-color: lightblue; padding: 0px;"><b>PO FOB (US$/Pc)</b></td>
            <td style="background-color: lightblue; padding: 0px;"><b>PO Delivery Date</b></td>
        @endif

        <td style="background-color: lightblue; padding: 0px;"><b>FTY Delivery Date</b></td>

        @if (!$type == 'qc')
            <td style="background-color: lightblue; padding: 0px;"><b>FTY FOB Value(US$)</b></td>
            <td style="background-color: lightblue; padding: 0px;"><b>PO FOB Value(US$)</b></td>
        @else
            <td style="background-color: lightblue; padding: 0px;"><b>Shipped Qty</b></td>
            <td style="background-color: lightblue; padding: 0px;"><b>Over / Shortage</b></td>
        @endif

        <td style="background-color: lightblue; padding: 0px;"><b>REMARKS</b></td>
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
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{str_pad($loop->parent->iteration, 2, '0', STR_PAD_LEFT)}}</td>
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{Arr::get($poData,'order.season.season_name')}}</td>
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{Arr::get($poData, 'order.assignFactory.name')}}</td>
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{Arr::get($poData, 'buyer.name')}}</td>
                    @endif
                    <td style="text-align: left;" >{{$poData->customer}}</td>
                    @if($loop->first)
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{Arr::get($poData,'order.remarks')}}</td>
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{Arr::get($poData,'order.style_name')}}</td>
                    @endif
                    <td style="text-align: left;" >{{$poData->po_no}}</td>
                    @if($loop->first)
                        <td  style="text-align: left;" rowspan="{{count($buyerWise)}}">{{collect(Arr::get($poData,'order.item_details.details'))->implode('item_name', ',')}}</td>
                    @endif
                    <td style="text-align: left;" >{{$poData->po_quantity}}</td>

                    @if (!$type == 'qc')
                        <td style="text-align: right;">${{number_format($finalCostPcSet ?? 0, 2)}}</td>
                        <td style="text-align: right;">${{number_format($poData->avg_rate_pc_set ?? 0, 2)}}</td>
                        <td>{{$poData->ex_factory_date}}</td>
                    @endif

                    <td>{{$poData->country_ship_date}}</td>

                    @if (!$type == 'qc')
                        <td style="text-align: right;">${{number_format($ftyFobValue,2)}}</td>
                        <td style="text-align: right;">${{number_format($poFobValue,2)}}</td>
                    @else
                        <td></td>
                        <td></td>
                    @endif

                    <td>{{$poData->remarks}}</td>
                </tr>
            @endforeach
        @endforeach
        <tr style="background-color: lavender">
            <td colspan="8"></td>
            <td><b>Total:</b></td>
            <td style="text-align: left;"><b>{{$styleTotalOrderQty}}</b></td>
            <td colspan="{{ $type == 'qc' ? '1' : '4' }}"></td>

            @if (!$type == 'qc')
                <td style="text-align: right;"><b>${{number_format($styleTotalFtyFobValue,2)}}</b></td>
                <td style="text-align: right;"><b>${{number_format($styleTotalPoFobValue,2)}}</b></td>
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
    <tr style="background-color: powderblue">
        <td colspan="8"></td>
        <td><b>Total Qty:</b></td>
        <td style="text-align: left;"><b>{{$TotalOrderQty}}</b></td>
        <td colspan="{{ $type == 'qc' ? '1' : '4' }}"></td>

        @if (!$type == 'qc')
            <td style="text-align: right;"><b>${{number_format($TotalFtyFobValue,2)}}</b></td>
            <td style="text-align: right;"><b>${{number_format($TotalPoFobValue,2)}}</b></td>
        @else
            <td><b></b></td>
            <td><b></b></td>
        @endif

        <td></td>
    </tr>
    </tbody>
</table>
