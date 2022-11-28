

<table class="reportTable">
    <thead>
    <tr>
        <td colspan="14" class="text-center">
            <span style="font-size: 10pt; font-weight: bold;">FINISH FABRIC RECEIVE STATEMENT</span>
        </td>
        <td></td>
        <td colspan="6" class="text-center">
            <span style="font-size: 10pt; font-weight: bold;">DELIVERY STATEMENT</span>
        </td>
        <td></td>
        <td colspan="4" class="text-center">
            <span style="font-size: 10pt; font-weight: bold;">BALANCE STATEMENT</span>
        </td>
        <td></td>
        <td colspan="5" class="text-center">
            <span style="font-size: 10pt; font-weight: bold;">LOCATION TRACE</span>
        </td>
    </tr>
    <tr>
        <th style="background-color: aliceblue;">Merchandiser</th>
        <th style="background-color: aliceblue;">Buyer</th>
        <th style="background-color: aliceblue;">Style/Order No</th>
        <th style="background-color: aliceblue;">Po No</th>
        <th style="background-color: aliceblue;">Color</th>
        <th style="background-color: aliceblue;">System Unique Id</th>
        <th style="background-color: aliceblue;">RCV Challan NO</th>
        <th style="background-color: aliceblue;">RCV Challan Date</th>
        <th style="background-color: aliceblue;">PI Commitment Date</th>
        <th style="background-color: aliceblue;">Booking Order Qty(KG)</th>
        <th style="background-color: aliceblue;">Finish Rcv Qty</th>
        <th style="background-color: aliceblue;">Excess QTY Fin Fab Rcv (KG)</th>
        <th style="background-color: aliceblue;">Rate</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;"></th>
        <th style="background-color: aliceblue;">Party Name</th>
        <th style="background-color: aliceblue;">Sys unique Id</th>
        <th style="background-color: aliceblue;">DLV CH No</th>
        <th style="background-color: aliceblue;">DLV CH Date</th>
        <th style="background-color: aliceblue;">Finish Delivery QTY</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;"></th>
        <th style="background-color: aliceblue;">Fab Bal Qty</th>
        <th style="background-color: aliceblue;">Stock Qty(KG)</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;">Remark</th>
        <th style="background-color: aliceblue;"></th>
        <th style="background-color: aliceblue;">In House Ageing</th>
        <th style="background-color: aliceblue;">Location</th>
        <th style="background-color: aliceblue;">BOM Status</th>
        <th style="background-color: aliceblue;">BOM Age</th>
        <th style="background-color: aliceblue;">Floor</th>
    </tr>
    </thead>
    <tbody>

    @php
        $totalBookingOrderQty = 0;
        $totalFinishRCVQty = 0;
        $totalExcessQtyInFabRCV = 0;
        $totalReceiveRate = 0;
        $totalReceiveValue = 0;
        $totalFinishDLVQty = 0;
        $totalFinishDLVValue = 0;
        $totalFabValQty = 0;
        $totalStockQty = 0;
        $totalIssueValue = 0;
    @endphp

    @foreach($fabricReceives as $receive)
        @php
            $bookingOrderQty = 0;
            $finishRCVQty = 0;
            $excessQtyInFabRCV = 0;
            $receiveRate = 0;
            $receiveValue = 0;
            $finishDLVQty = 0;
            $finishDLVValue = 0;
            $fabValQty = 0;
            $stockQty = 0;
            $issueValue = 0;
        @endphp

        @foreach($receive as $value)

            @php
                $deliveryStatementCount = collect($value['delivery_statement'])->count();
                $receiveDate = Carbon\Carbon::parse($value['ch_rcv_date']);
                $todayDate = Carbon\Carbon::now();
                $bookingDate = Carbon\Carbon::parse($value['bom_status']);
                $issueDate = '';
            @endphp

            @forelse($value['delivery_statement'] as $delivery)
                <tr>
                    @php
                        $issueDate = Carbon\Carbon::parse($delivery['dlv_challan_date']);
                    @endphp

                    @if($loop->first)
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['merchandiser'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['buyer'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['style_order_no'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['po_no'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['color'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['receive_unique_id'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['challan_no'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['ch_rcv_date'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['pi_offer_date'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['booking_act_req_qty'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['rcv_fin_fab'] }}</td>
                        @if($value['rcv_fin_fab'] > $value['booking_act_req_qty'])
                            <td rowspan="{{ $deliveryStatementCount }}">{{ $value['rcv_fin_fab'] - $value['booking_act_req_qty'] }}</td>
                        @else
                            <td rowspan="{{ $deliveryStatementCount }}">0</td>
                        @endif
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['rate'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['rcv_fin_fab'] * $value['rate'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}"></td>
                    @endif
                    <td>{{ $delivery['supplier_name'] }} </td>
                    <td>{{ $delivery['sys_unique_id'] }}</td>
                    <td>{{ $delivery['dlv_ch_no'] }}</td>
                    <td>{{ $delivery['dlv_challan_date'] }}</td>
                    <td>{{ $delivery['finish_delivery_qty'] }}</td>
                    <td>{{ $value['booking_act_req_qty'] - $delivery['finish_delivery_qty'] }}</td>
                    <td></td>
                    <td>{{ $value['rcv_fin_fab'] * $delivery['finish_delivery_qty'] }}</td>
                    <td>{{ $value['rcv_fin_fab'] - $delivery['finish_delivery_qty'] }}</td>
                    <td>{{ ($value['rcv_fin_fab'] - $delivery['finish_delivery_qty']) * $value['rate'] }}</td>
                    <td>{{ $value['remarks'] }}</td>
                    <td></td>
                    @if($loop->first)
{{--                        @if($issueDate)--}}
{{--                            <td rowspan="{{ $deliveryStatementCount }}">{{ $receiveDate->diffInDays($issueDate) }}</td>--}}
{{--                        @else--}}
{{--                            <td rowspan="{{ $deliveryStatementCount }}">{{ $receiveDate->diffInDays($todayDate) }}</td>--}}
{{--                        @endif--}}
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['in_house_ageing'] }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ collect($value['location'])->implode(' ,') }}</td>
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['bom_status'] }}</td>
                        @if($issueDate)
                            <td rowspan="{{ $deliveryStatementCount }}">{{ $bookingDate->diffInDays($issueDate) }}</td>
                        @else
                            <td rowspan="{{ $deliveryStatementCount }}">{{ $bookingDate->diffInDays($todayDate) }}</td>
                        @endif
                        <td rowspan="{{ $deliveryStatementCount }}">{{ $value['floor'] }}</td>
                    @endif
                </tr>
                @php
                    $finishDLVQty += $delivery['finish_delivery_qty'];
                    $finishDLVValue += $value['rate'] * $delivery['finish_delivery_qty'];
                    $fabValQty += $value['rcv_fin_fab'] * $delivery['finish_delivery_qty'];
                    $stockQty += $value['rcv_fin_fab'] - $delivery['finish_delivery_qty'];
                    $issueValue += ($value['rcv_fin_fab'] - $delivery['finish_delivery_qty']) * $value['rate'];

                    $totalFinishDLVQty += $delivery['finish_delivery_qty'];
                    $totalFinishDLVValue += $value['rate'] * $delivery['finish_delivery_qty'];
                    $totalFabValQty += $value['rcv_fin_fab'] * $delivery['finish_delivery_qty'];
                    $totalStockQty += $value['rcv_fin_fab'] - $delivery['finish_delivery_qty'];
                    $totalIssueValue += ($value['rcv_fin_fab'] - $delivery['finish_delivery_qty']) * $value['rate'];
                @endphp
            @empty
                <tr>
                    <td>{{ $value['merchandiser'] }}</td>
                    <td>{{ $value['buyer'] }}</td>
                    <td>{{ $value['style_order_no'] }}</td>
                    <td>{{ $value['po_no'] }}</td>
                    <td>{{ $value['color'] }}</td>
                    <td>{{ $value['receive_unique_id'] }}</td>
                    <td>{{ $value['challan_no'] }}</td>
                    <td>{{ $value['ch_rcv_date'] }}</td>
                    <td>{{ $value['pi_offer_date'] }}</td>
                    <td>{{ $value['booking_act_req_qty'] }}</td>
                    <td>{{ $value['rcv_fin_fab'] }}</td>
                    @if($value['rcv_fin_fab'] > $value['booking_act_req_qty'])
                        <td>{{ $value['rcv_fin_fab'] - $value['booking_act_req_qty'] }}</td>
                    @else
                        <td>0</td>
                    @endif
                    <td>{{ $value['rate'] }}</td>
                    <td>{{ $value['rcv_fin_fab'] * $value['rate'] }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>

                    <td>{{ $value['rcv_fin_fab'] * ($delivery['finish_delivery_qty'] ?? 0) }}</td>
                    <td>{{ $value['rcv_fin_fab'] * ($delivery['finish_delivery_qty'] ?? 0) }}</td>
                    <td>{{ ($value['rcv_fin_fab'] * ($delivery['finish_delivery_qty'] ?? 0)) * $value['rate'] }}</td>
                    <td>{{ $value['remarks'] }}</td>
                    <td></td>

{{--                    @if(!$issueDate)--}}
{{--                        <td>{{ $receiveDate->diffInDays($todayDate) }}</td>--}}
{{--                    @else--}}
{{--                        <td>{{ $receiveDate->diffInDays($issueDate) }}</td>--}}
{{--                    @endif--}}
                    <td>{{ $value['in_house_ageing'] }}</td>
                    <td>{{ collect($value['location'])->implode(' ,') }}</td>
                    <td>{{ $value['bom_status'] }}</td>
                    @if(!$issueDate)
                        <td>{{ $bookingDate->diffInDays($todayDate) }}</td>
                    @else
                        <td>{{ $bookingDate->diffInDays($issueDate) }}</td>
                    @endif
                    <td>{{ $value['floor'] }}</td>

                </tr>

            @endforelse
            @php
                $bookingOrderQty += $value['booking_act_req_qty'];
                $finishRCVQty += $value['rcv_fin_fab'];
                $receiveRate += $value['rate'];
                $receiveValue += $value['rcv_fin_fab'] * $value['rate'];

                $totalBookingOrderQty += $value['booking_act_req_qty'];
                $totalFinishRCVQty += $value['rcv_fin_fab'];
                $totalReceiveRate += $value['rate'];
                $totalReceiveValue += $value['rcv_fin_fab'] * $value['rate'];

            @endphp
        @endforeach
        <tr>
            <td colspan="9"><b>Sub Total</b></td>
            <td><b></b> {{ $bookingOrderQty }} </td>
            <td><b></b> {{ $finishRCVQty }} </td>
            <td><b></b></td>
            <td><b></b> {{ $receiveRate }} </td>
            <td><b></b> {{ $receiveValue }} </td>
            <td><b></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td> {{ $finishDLVQty }}</td>
            <td> {{ $finishDLVValue }}</td>
            <td></td>
            <td> {{ $fabValQty }}</td>
            <td>{{ $stockQty }}</td>
            <td>{{ $issueValue  }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="9"><b>Total</b></td>
        <td><b></b> {{ $totalBookingOrderQty }} </td>
        <td><b></b> {{ $totalFinishRCVQty }} </td>
        <td><b></b></td>
        <td><b></b> {{ $totalReceiveRate }} </td>
        <td><b></b> {{ $totalReceiveValue }} </td>
        <td><b></b></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td> {{ $totalFinishDLVQty }}</td>
        <td> {{ $totalFinishDLVValue }}</td>
        <td></td>
        <td> {{ $totalFabValQty }}</td>
        <td>{{ $totalStockQty }}</td>
        <td>{{ $totalIssueValue }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
