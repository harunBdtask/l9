<table class="reportTable " id="fixTable">
    <thead style="background-color: aliceblue">
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Style Name</th>
        <th>Po No</th>
        <th>Ref. No.</th>
        <th>Order Qty</th>
        <th>Shipment Qty</th>
        <th>Sewing Rcvd. Qty</th>
        <th>Rcvd Balance</th>
        <th>Pack Qty</th>
        <th>Fin. Balance</th>
        <th>Input Balance</th>
        <th>Sewing Lines</th>
        <th>Remarks</th>
    </tr>
    </thead>
    @php
        $grandTotalShipmentQty = 0;
        $grandTotalSewingReceivedQty = 0;
        $grandTotalReceivedBalance = 0;
        $grandTotalPackQty = 0;
        $grandTotalFinalBalance = 0;
    @endphp
    <tbody>
    @if(count($data))
        @foreach($data as $key => $value)
            @php
                $grandTotalShipmentQty += $value['shipment_qty'];
                $grandTotalSewingReceivedQty += $value['total_sewing_receive_qty'];
                $grandTotalReceivedBalance += $value['receive_balance'];
                $grandTotalPackQty += $value['total_pack_qty'];
                $grandTotalFinalBalance += $value['final_balance'];
            @endphp
            <tr class="custom-hover-color">
                <td>{{ $key+1 }}</td>
                <td>{{ $value['buyer_name'] }}</td>
                <td>{{ $value['style_name'] }}</td>
                <td>{{ $value['po_no'] }}</td>
                <td>{{ $value['ref_no'] }}</td>
                <td class="text-right">{{ $value['order_qty'] }}</td>
                <td class="text-right">{{ $value['shipment_qty'] }}</td>
                <td class="text-right">{{ $value['total_sewing_receive_qty'] }}</td>
                <td class="text-right">{{ $value['receive_balance'] }}</td>
                <td class="text-right">{{ $value['total_pack_qty'] }}</td>
                <td class="text-right">{{ $value['final_balance'] }}</td>
                <td class="text-right">{{ $value['input_balance'] }}</td>
                <td>{{ $value['sewing_lines'] }}</td>
                <td>{{ $value['remarks'] }}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="15" class="text-danger"><b>No Data Found!</b></td>
        </tr>
    @endif
    </tbody>
    <tbody>
        <tr>
            <td colspan="6"><b>Total</b></td>
            <td class="text-right"><b>{{ $grandTotalShipmentQty }}</b></td>
            <td class="text-right"><b>{{ $grandTotalSewingReceivedQty }}</b></td>
            <td class="text-right"><b>{{ $grandTotalReceivedBalance }}</b></td>
            <td class="text-right"><b>{{ $grandTotalPackQty }}</b></td>
            <td class="text-right"><b>{{ $grandTotalFinalBalance }}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
