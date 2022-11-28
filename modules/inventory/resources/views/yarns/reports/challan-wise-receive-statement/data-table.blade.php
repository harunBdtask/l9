<table class="reportTable table">
    <thead style="background-color: aliceblue">
    <tr>
        <th colspan="20" style="text-align: left"><b>Goods Receive With LC</b></th>
    </tr>
    <tr>
        <td><b>CH RCV Date</b></td>
        <td><b>Party Name</b></td>
        <td><b>LC No</b></td>
        <td><b>LC Date</b></td>
        <td><b>LC Value$</b></td>
        <td><b>PI No</b></td>
        <td><b>PI Date</b></td>
        <td><b>PI Qty:</b></td>
        <td><b>PI Value$</b></td>
        <td><b>Challan No</b></td>
        <td><b>Yarn Comp.</b></td>
        <td><b>LOT No</b></td>
        <td><b>BAG</b></td>
        <td><b>TOTAL RCV Qty</b></td>
        <td><b>CH RCV Qty</b></td>
        <td><b>Bal. of SPI Mill</b></td>
        <td><b>Rate</b></td>
        <td><b>Bal. Value</b></td>
        <td><b>CH. Value</b></td>
        <td><b>Remarks</b></td>
    </tr>
    </thead>
    <tbody>
    @forelse($reportData as $key => $data)
        @php
            $rate = number_format($data->sum('rate') / $data->count('rate'), 2);
            $challanReceiveQty = $data->sum('receive_qty');
            $challanReceiveValue = $data->sum('receive_qty') * $rate;
            $total_lc_value = 0;
            $totalReceiveQty = 0;
            $total_receive_qty_value = 0;
            $balance_of_spi_mill = 0;
            $totalBalValue = 0;
            $partyWiseRow = true;
        @endphp
        @foreach($data->groupBy('pi_no') as $piGroup)
            @php
                $piRow = true;
                $sumTodayRCVQty = 0;
                $piQty = 0;
            @endphp
            @foreach(collect($piGroup)->groupBy('challan_no') as $group)
                @foreach($group as $value)
                    @php
                        $total_lc_value += $value['lc_value'];
                        $balance_of_spi_mill += $value['balance_of_spi_mill'];

                        $receiveQty = collect($group)->sum('receive_qty');
                        $balValue = $receiveQty * $rate;

                        if($loop->first) {
                            $totalReceiveQty += $receiveQty;
                            $totalBalValue += $balValue;
                            $sumTodayRCVQty += $receiveQty;
                        }
                        if ($piRow) {
                            $piQty = $piGroup->sum('pi_qty');
                        }
                        $spnMillQty = $piQty == 0 ? $sumTodayRCVQty : $piQty - $sumTodayRCVQty;
                    @endphp
                    <tr>
                        @if($partyWiseRow)
                            <td rowspan="{{ count($data) }}">
                                <div
                                    style="width: 65px;">{{ date("d-m-Y", strtotime($value['challan_receive_date'])) }}</div>
                            </td>
                            <td rowspan="{{ count($data) }}">
                                <div style="width: 85px;">
                                    {{ $value['party_name'] }}
                                </div>
                            </td>
                            <td rowspan="{{ count($data) }}" style="text-align: left;">{{ $value['lc_no'] }}</td>
                            <td rowspan="{{ count($data) }}">
                                <div style="width: 65px;">{{ date("d-m-Y", strtotime($value['lc_date'])) }}</div>
                            </td>
                            <td rowspan="{{ count($data) }}"
                                style="text-align: right;">{{ number_format($value['lc_value'], 2) }}</td>
                        @endif
                        @if($piRow)
                            <td rowspan="{{ count($piGroup) }}" style="text-align: left;">
                                <div>{{ $value['pi_no'] }}</div>
                            </td>
                            <td rowspan="{{ count($piGroup) }}" style="text-align: left;">
                                <div style="width: 65px;">{{ date("d-m-Y", strtotime($value['pi_date'])) }}</div>
                            </td>
                            <td rowspan="{{ count($piGroup) }}">{{ $piGroup->sum('pi_qty') }}</td>
                            <td rowspan="{{ count($piGroup) }}" style="text-align: right;">{{ number_format($piGroup->sum('pi_qty') * $value['pi_rate'], 2) }}</td>
                        @endif
                        @if($loop->first)
                            <td style="text-align: left;" rowspan="{{ count($group) }}">{{ $value['challan_no'] }}</td>
                        @endif
                        <td style="text-align: left;">
                            <div style="width: 160px; text-align: left">{{ $value['yarn_composition'] }}</div>
                        </td>
                        <td style="text-align: left;">{{ $value['lot_no'] }}</td>
                        <td>{{ $value['bag'] }}</td>
                        @if($loop->first)
                            <td rowspan="{{ count($group) }}">{{ $receiveQty }}</td>
                        @endif
                        @if($partyWiseRow)
                            <td rowspan="{{ count($data) }}">{{ $challanReceiveQty }}</td>
                        @endif
                        @if($loop->first)
                            <td rowspan="{{ count($group) }}">{{ number_format($spnMillQty, 2) }}</td>
                        @endif
                        <td style="text-align: right;">{{ $rate }}</td>
                        @if($loop->first)
                            <td rowspan="{{ count($group) }}"
                                style="text-align: right;">{{ number_format($receiveQty * $rate, 2) }}</td>
                        @endif
                        @if($partyWiseRow)
                            <td style="text-align: right;"
                                rowspan="{{ count($data) }}">{{ number_format($challanReceiveValue, 2) }}</td>
                        @endif
                        <td style="text-align: left;">{{ $value['remarks'] }}</td>
                    </tr>
                    @php
                        $partyWiseRow = false;
                        $piRow = false;
                    @endphp
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td colspan="4"><b>Subtotal</b></td>
            <td style="text-align: right;"><b>{{ number_format($total_lc_value, 2) }}</b></td>
            <td colspan="8"><b></b></td>
            <td><b>{{ $totalReceiveQty }}</b></td>
            <td><b>{{ $challanReceiveQty }}</b></td>
            <td><b>{{-- $balance_of_spi_mill --}}</b></td>
            <td><b></b></td>
            <td style="text-align: right;"><b>{{ number_format($totalBalValue, 2) }}</b></td>
            <td style="text-align: right;"><b>{{ number_format($challanReceiveValue, 2) }}</b></td>
            <td><b></b></td>
        </tr>
    @empty
        <tr>
            <th colspan="20">No data found!</th>
        </tr>
    @endforelse
    </tbody>
</table>
