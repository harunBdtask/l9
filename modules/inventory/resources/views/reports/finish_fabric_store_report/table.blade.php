<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue;" colspan="8"><b>Order Details</b></td>
        <td style="background-color: lightcyan;" colspan="6"><b>Fabric Receive Status</b></td>
        <td style="background-color: aliceblue;" colspan="5"><b>Fabric Delivery Status</b></td>
        <td style="background-color: aliceblue;" rowspan="2"><b>UOM</b></td>
        <td style="background-color: aliceblue;" rowspan="2"><b>Remarks</b></td>
    </tr>
    <tr>
        <td style="background-color: aliceblue;"><b>SL</b></td>
        <td style="background-color: aliceblue;"><b>Batch No</b></td>
        <td style="background-color: aliceblue;"><b>Buyer</b></td>
        <td style="background-color: aliceblue;"><b>Job No</b></td>
        <td style="background-color: aliceblue;"><b>Style</b></td>
        <td style="background-color: aliceblue;"><b>Fabric Type</b></td>
        <td style="background-color: aliceblue;"><b>Color</b></td>
        <td style="background-color: aliceblue;"><b>Part</b></td>
        <td style="background-color: lightcyan;"><b>Booking Qty</b></td>
        <td style="background-color: lightcyan;"><b>Prev Rcv.</b></td>
        <td style="background-color: lightcyan;"><b>Prev Rcv. Return</b></td>
        <td style="background-color: lightcyan;"><b>Today Rcv.</b></td>
        <td style="background-color: lightcyan;"><b>TTL Rcv.</b></td>
        <td style="background-color: lightcyan;"><b>Fab Blnc.</b></td>
        <td style="background-color: aliceblue;"><b>Prev Del.</b></td>
        <td style="background-color: aliceblue;"><b>Prev Del. Return</b></td>
        <td style="background-color: aliceblue;"><b>Today Del.</b></td>
        <td style="background-color: aliceblue;"><b>Total Del.</b></td>
        <td style="background-color: aliceblue;"><b>Store Blnc</b></td>
    </tr>
    </thead>
    <tbody>
    @php
        $total_booking_qty = 0;
    @endphp
    @foreach($reportData as $key => $data)
        @php

            $total_booking_qty += $data['booking_qty'];
//        @endphp
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ collect($data)->first()['batch_no'] }}</td>
            <td>{{ collect($data)->first()['buyer'] }}</td>
            <td>{{ collect($data)->first()['uniq_id'] }}</td>
            <td>{{ collect($data)->first()['style'] }}</td>
            <td>{{ collect($data)->first()['fabric_type'] }}</td>
            <td>{{ collect($data)->first()['color'] }}</td>
            <td>{{ collect($data)->first()['part'] }}</td>
            <td style="text-align: right;background-color: #d9f3f3">{{ number_format($data['booking_qty'], 2) }}</td>
            <td style="text-align: right;background-color: #d9f3f3">{{ number_format($data['prev_receive_qty'], 2) }}</td>
            <td style="text-align: right;background-color: #d9f3f3">{{ number_format($data['prev_receive_return_qty'], 2) }}</td>
            <td style="text-align: right;background-color: #d9f3f3">{{ number_format($data['today_receive_qty'], 2) }}</td>
            <td style="text-align: right;background-color: #d9f3f3">{{ number_format($data['total_receive_qty'], 2) }}</td>
            <td style="text-align: right;background-color: #d9f3f3">{{ number_format($data['balance_receive_qty'], 2) }}</td>
            <td style="text-align: right">{{ number_format($data['prev_issue_qty'], 2) }}</td>
            <td style="text-align: right">{{ number_format($data['prev_issue_return_qty'], 2) }}</td>
            <td style="text-align: right">{{ number_format($data['today_issue_qty'], 2) }}</td>
            <td style="text-align: right">{{ number_format($data['total_issue_qty'], 2) }}</td>
            <td style="text-align: right">{{ number_format($data['balance_issue_qty'], 2) }}</td>
            <td>{{ collect($data)->first()['uom'] }}</td>
            <td></td>
        </tr>
    @endforeach

    <tr>
        <td style="text-align: right;background-color: gainsboro" colspan="8"><b>Total</b></td>
        <td style="text-align: right;background-color: gainsboro"><b> {{ number_format($total_booking_qty, 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('prev_receive_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('prev_receive_return_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('today_receive_qty'), 2) }} </b></td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('total_receive_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('balance_receive_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('prev_issue_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('prev_issue_return_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('today_issue_qty'), 2) }} </b></td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('total_issue_qty'), 2) }} </b>
        </td>
        <td style="text-align: right;background-color: gainsboro">
            <b> {{ number_format(collect($reportData)->sum('balance_issue_qty'), 2) }} </b>
        </td>
        <td colspan="2" style="text-align: right;background-color: gainsboro"></td>
    </tr>
    </tbody>
</table>
