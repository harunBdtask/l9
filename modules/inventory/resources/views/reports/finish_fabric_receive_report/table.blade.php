<table class="reportTable">
    <thead>
    <tr>
        <th style="background-color: aliceblue;">SL No</th>
        <th style="background-color: aliceblue;">Receive Unique ID</th>
        <th style="background-color: aliceblue;">Challan No</th>
        <th style="background-color: aliceblue;">CH Rcv Date</th>
        <th style="background-color: aliceblue;">Buyer</th>
        <th style="background-color: aliceblue;">Style / Order NO</th>
        <th style="background-color: aliceblue;">PO NO</th>
        <th style="background-color: aliceblue;">Batch No</th>
        <th style="background-color: aliceblue;">FIN Dia</th>
        <th style="background-color: aliceblue;">GSM</th>
        <th style="background-color: aliceblue;">Feb Type</th>
        <th style="background-color: aliceblue;">Color</th>
        <th style="background-color: aliceblue;">Booking ACT REQ QTY (KG)</th>
        <th style="background-color: aliceblue;">NO of Roll</th>
        <th style="background-color: aliceblue;">RCV FIN FAB (KG)</th>
        <th style="background-color: aliceblue;">Excess QTY Fin Fab Rcv (KG)</th>
        <th style="background-color: aliceblue;">Rate</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;">LOCATION</th>
        <th style="background-color: aliceblue;">PI NO</th>
        <th style="background-color: aliceblue;">PI Offer Date</th>
        <th style="background-color: aliceblue;">Remarks</th>
    </tr>
    </thead>
    <tbody>
            @php
                $index = 1;
                $totalSumBookingReqActQty = 0;
                $totalSumNoOffRoll = 0;
                $totalSumRcvFinFab = 0;
                $totalSumExcessQtyFinFabRcv = 0;
                $totalSumRate = 0;
                $totalSumAmount = 0;
            @endphp
            @foreach($fabricReceives as $receive)
                @php
                    $sumBookingReqActQty = 0;
                    $sumNoOffRoll = 0;
                    $sumRcvFinFab = 0;
                    $sumExcessQtyFinFabRcv = 0;
                    $sumRate = 0;
                    $sumAmount = 0;
                @endphp

                @foreach($receive as $value)
                <tr>
                    <td>{{ $index++ }}</td>
                    <td>{{ $value['receive_unique_id'] }}</td>
                    <td>{{ $value['challan_no'] }}</td>
                    <td>{{ $value['ch_rcv_date'] }}</td>
                    <td>{{ $value['buyer'] }}</td>
                    <td>{{ $value['style_order_no'] }}</td>
                    <td>{{ $value['po_no'] }}</td>
                    <td>{{ $value['batch_no'] }}</td>
                    <td>{{ $value['fin_dia'] }}</td>
                    <td>{{ $value['gsm'] }}</td>
                    <td>{{ $value['feb_type'] }}</td>
                    <td>{{ $value['color'] }}</td>
                    <td>{{ $value['booking_act_req_qty'] }}</td>
                    <td>{{ $value['no_of_roll'] }}</td>
                    <td>{{ $value['rcv_fin_fab'] }}</td>
                    @if($value['rcv_fin_fab'] > $value['booking_act_req_qty'])
                        <td>{{ $value['rcv_fin_fab'] - $value['booking_act_req_qty'] }}</td>
                    @else
                        <td>0</td>
                    @endif
                    <td>{{ $value['rate'] }}</td>
                    <td>{{ $value['value'] }}</td>
                    <td>{{ $value['location'] }}</td>
                    <td>{{ $value['pi_no'] }}</td>
                    <td>{{ $value['pi_offer_date'] }}</td>
                    <td>{{ $value['remarks'] }}</td>
                </tr>
                @php
                    $sumBookingReqActQty += $value['booking_act_req_qty'];
                    $sumNoOffRoll += $value['no_of_roll'];
                    $sumRcvFinFab += $value['rcv_fin_fab'];
                    if($value['rcv_fin_fab'] > $value['booking_act_req_qty']){
                        $sumExcessQtyFinFabRcv += $value['rcv_fin_fab'] - $value['booking_act_req_qty'];
                    } else {
                        $sumExcessQtyFinFabRcv += 0;
                    }
                    $sumRate += $value['rate'];
                    $sumAmount += $value['value'];

                    $totalSumBookingReqActQty += $value['booking_act_req_qty'];
                    $totalSumNoOffRoll += $value['no_of_roll'];
                    $totalSumRcvFinFab += $value['rcv_fin_fab'];
                    if($value['rcv_fin_fab'] > $value['booking_act_req_qty']){
                        $totalSumExcessQtyFinFabRcv += $value['rcv_fin_fab'] - $value['booking_act_req_qty'];
                    } else {
                        $totalSumExcessQtyFinFabRcv += 0;
                    }
                    $totalSumRate += $value['rate'];
                    $totalSumAmount += $value['value'];
                @endphp
               @endforeach
                <tr>
                    <td colspan="12"> <b>Sub Total</b> </td>
                    <td> <b>{{ $sumBookingReqActQty }}</b> </td>
                    <td> <b>{{ $sumNoOffRoll }}</b> </td>
                    <td> <b>{{ $sumRcvFinFab }}</b> </td>
                    <td> <b>{{ $sumExcessQtyFinFabRcv }}</b> </td>
                    <td> <b>{{ $sumRate }}</b> </td>
                    <td> <b>{{ $sumAmount }}</b> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
                <tr>
                    <td colspan="12"> <b>Total</b> </td>
                    <td> <b>{{ $totalSumBookingReqActQty }}</b> </td>
                    <td> <b>{{ $totalSumNoOffRoll }}</b> </td>
                    <td> <b>{{ $totalSumRcvFinFab }}</b> </td>
                    <td> <b>{{ $totalSumExcessQtyFinFabRcv }}</b> </td>
                    <td> <b>{{ $totalSumRate }}</b> </td>
                    <td> <b>{{ $totalSumAmount }}</b> </td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

    </tbody>
</table>
