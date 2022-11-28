<table class="reportTable table">
    <thead style="background-color: aliceblue">
    <tr>
        <th colspan="29" style="text-align: left"><b>{{ $title }}</b></th>
    </tr>
    <tr>
        <td><b>Party Name</b></td>
        <td><b>LC No</b></td>
        <td><b>LC Date</b></td>
        <td><b>LC Value$</b></td>
        <td><b>PI No</b></td>
        <td><b>PI Date</b></td>
        <td><b>PI Qty:</b></td>
        <td><b>PI Value$</b></td>
        <td><b>CH RCV Date</b></td>
        <td><b>Challan No</b></td>
        <td><b>PI Yarn Comp.</b></td>
        <td><b>RCV Yarn Comp.</b></td>
        <td><b>LOT No</b></td>
        <td><b>BAG</b></td>
        <td><b>Cone</b></td>
        <td><b>Weight per bag</b></td>
        <td><b>Ref. No</b></td>
        <td><b>Today RCV Qty</b></td>
        <td><b>Return Qty</b></td>
        <td><b>Act. Bal. Qty</b></td>
        <td><b>Total RCV Qty</b></td>
        <td><b>Bal. of SPI Mill</b></td>
        <td><b>Rate</b></td>
        <td><b>RCV Value</b></td>
        <td><b>Total Bal. Value</b></td>
        <td><b>SPI Mill Bal. Value</b></td>
        <td><b>Num of carton/Box </b></td>
        <td><b>Yarn Brand</b></td>
        <td><b>Remarks</b></td>
    </tr>
    </thead>
    <tbody>
    @php
        $grand_total_lc_value = 0;
        $grand_today_receive_qty = 0;
        $grand_total_receive_qty = 0;
        $grand_balance_of_spi_mill = 0;
        $grand_total_receive_value = 0;
        $grand_total_bal_value = 0;
        $grand_total_bal_qty = 0;
        $grand_balance_value_of_spi_mill = 0;
    @endphp
    @forelse($reportData as $key => $data)
        @php
            $rate = number_format($data->sum('rate') / $data->count('rate'), 2);
            $total_lc_value = 0;
            $total_receive_qty = 0;
            $today_receive_qty = 0;
            $total_receive_value = 0;
            $total_bal_value = 0;
            $total_bal_qty = 0;
            $total_balance_value_of_spi_mill = 0;
            $partyWiseRow = true;
        @endphp

        @foreach($data->groupBy('pi_no') as $piGroup)
            @php
                $piRow = true;
                $sumActualRCVQty = 0;
                $piQty = 0;
                $piValue = 0;
            @endphp
            @foreach($piGroup->groupBy('lot_no') as $group)
                @foreach($group as $value)
                    @php
                        $total_lc_value += $value['lc_value'];
                        $today_receive_qty += $value['today_receive_qty'];
                        $total_receive_value += $value['receive_value'];
                        $actualBalQty = $value['today_receive_qty'] - $value['return_qty'];
                        $sumActualRCVQty += $actualBalQty;
                        if ($piRow) {
                            $piQty = $piGroup->keyBy('pi_unique_id')->sum('pi_qty');
                            $piValue = $piGroup->keyBy('pi_unique_id')->sum('pi_value');
                            $total_receive_qty += $piGroup->sum('today_receive_qty');
                        }
                        $spnMillQty = $piQty == 0 ? $sumActualRCVQty : $piQty - $sumActualRCVQty;
                        $total_bal_value += $actualBalQty * $value['rate'];
                        $total_bal_qty += $actualBalQty;
                        $total_balance_value_of_spi_mill += $spnMillQty * $value['rate'];
                    @endphp
                    <tr>
                        @if($partyWiseRow)
                            <td rowspan="{{ count($data) }}">
                                <div style="width: 85px;"> {{ $value['party_name'] }} </div>
                            </td>
                        @endif
                            <td>{{ $value['lc_no'] }}</td>
                            <td>
                                <div style="width: 65px;">{{ date("d-m-Y", strtotime($value['lc_date'])) }}</div>
                            </td>
                        @if($partyWiseRow)
                            <td rowspan="{{ count($data) }}">{{ $value['lc_value'] }}</td>
                        @endif
                        @if($piRow)
                            <td rowspan="{{ count($piGroup) }}">{{ $value['pi_no'] }}</td>
                            <td rowspan="{{ count($piGroup) }}">
                                <div style="width: 65px;">{{ date("d-m-Y", strtotime($value['pi_date'])) }}</div>
                            </td>
                            <td rowspan="{{ count($piGroup) }}">{{ $piQty }}</td>
                            <td rowspan="{{ count($piGroup) }}" style="text-align: right;">{{ $piValue }}</td>
                        @endif
                        <td>
                            <div style="width: 65px;">{{ $value['challan_receive_date'] }}</div>
                        </td>
                        <td>{{ $value['challan_no'] }}</td>
                        <td>
                            <div style="width: 160px; text-align: left">{{ $value['pi_yarn_composition'] }}</div>
                        </td>
                        <td>
                            <div style="width: 160px; text-align: left">{{ $value['yarn_composition'] }}</div>
                        </td>
                        @if($loop->first && count($group) > 0)
                            <td rowspan="{{ count($group) }}">{{ $value['lot_no'] }}</td>
                        @endif
                        <td>{{ $value['bag'] }}</td>
                        <td>{{ $value['cone'] }}</td>
                        <td>{{ $value['weight_per_bag'] }}</td>
                        <td> <div style="width: 160px;">{{ $value['product_code'] }}</div> </td>
                        <td>{{ $value['today_receive_qty'] }}</td>
                        <td>{{ $value['return_qty'] }}</td>
                        <td>{{ $actualBalQty }}</td>
                        @if($piRow)
                            <td rowspan="{{ count($piGroup) }}">{{ $piGroup->sum('today_receive_qty') }}</td>
                        @endif
                        <td>{{ number_format($spnMillQty, 2) }}</td>
                        <td>{{ $value['rate'] }}</td>
                        <td style="text-align: right;">{{ number_format($value['receive_value'], 2) }}</td>
                        <td style="text-align: right;">{{ number_format($actualBalQty * $value['rate'], 2) }}</td>
                        <td style="text-align: right;">{{ number_format($spnMillQty * $value['rate'], 2) }}</td>
                        <td>{{ $value['no_of_box'] }}</td>
                        <td>{{ $value['yarn_band'] }}</td>
                        <td>{{ $value['remarks'] }}</td>
                    </tr>
                    @php
                        $partyWiseRow = false;
                        $piRow = false;
                    @endphp
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td colspan="3"><b>Subtotal</b></td>
            <td><b>{{ $total_lc_value }}</b></td>
            <td colspan="13"><b></b></td>
            <td><b>{{ $today_receive_qty }}</b></td>
            <td><b></b></td>
            <td><b>{{ $total_bal_qty }}</b></td>
            <td><b>{{ $total_receive_qty }}</b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td style="text-align: right;"><b>{{ number_format($total_receive_value, 2) }}</b></td>
            <td><b>{{ number_format($total_bal_value, 2) }}</b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td><b></b></td>
        </tr>
        @php
            $grand_total_lc_value += $total_lc_value;
            $grand_today_receive_qty += $today_receive_qty;
            $grand_total_receive_qty += $total_receive_qty;
            $grand_total_receive_value += $total_receive_value;
            $grand_total_bal_value += $total_bal_value;
            $grand_total_bal_qty += $total_bal_qty;
            $grand_balance_value_of_spi_mill += $total_balance_value_of_spi_mill;
        @endphp
    @empty
        <tr>
            <th colspan="29">No data found!</th>
        </tr>
    @endforelse

    <tr>
        <td colspan="3"><b>Grand Total</b></td>
        <td><b>{{ $grand_total_lc_value }}</b></td>
        <td colspan="13"><b></b></td>
        <td><b>{{ $grand_today_receive_qty }}</b></td>
        <td><b></b></td>
        <td><b>{{ $grand_total_bal_qty }}</b></td>
        <td><b>{{ $grand_total_receive_qty }}</b></td>
        <td><b></b></td>
        <td><b></b></td>
        <td style="text-align: right;"><b>{{ number_format($grand_total_receive_value, 2) }}</b></td>
        <td><b>{{ number_format($grand_total_bal_value, 2) }}</b></td>
        <td><b></b></td>
        <td><b></b></td>
        <td><b></b></td>
        <td><b></b></td>
    </tr>
    </tbody>
</table>
