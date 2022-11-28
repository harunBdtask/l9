<table class="reportTable">
    <thead style="background-color: aliceblue">
    <tr>
        <th colspan="21" style="text-align: left"><b>Goods Receive With LC</b></th>
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
        <td><b>PI Yarn Composition</b></td>
        <td><b>Receive Yarn Composition</b></td>
        <td><b>LOT No</b></td>
        <td><b>BAG</b></td>
        <td><b>TODAY RCV Qty</b></td>
        <td><b>TOTAL RCV Qty</b></td>
        <td><b>Bal. of SPI Mill</b></td>
        <td><b>Rate</b></td>
        <td><b>RCV Value</b></td>
        <td><b>Bal. Value</b></td>
        <td><b>Remarks</b></td>
    </tr>
    </thead>
    <tbody>
    @forelse($reportData as $key => $data)
        @php
            $rate = number_format($data->sum('rate') / $data->count('rate'), 4);
            $total_lc_value = 0;
            $total_receive_qty = 0;
            $today_receive_qty = 0;
            $balance_of_spi_mill = 0;
            $total_receive_value = 0;
            $total_bal_value = 0;
            $partyWiseRow = true;
        @endphp
        @foreach($data->groupBy('lot_no') as $group)
            @foreach($group as $value)
                @php
                    $total_lc_value += $value['lc_value'];
                    $today_receive_qty += $value['today_receive_qty'];
                    $balance_of_spi_mill += $value['balance_of_spi_mill'];
                    $total_receive_value += $value['receive_value'];
                    if ($loop->first && count($group)) {
                        $total_receive_qty += $group->sum('total_receive_qty');
                        $total_bal_value += $group->sum('bal_value');
                    }
                @endphp
                <tr>
                    <td>{{ date("d-m-Y", strtotime($value['challan_receive_date'])) }}</td>
                    @if($partyWiseRow)
                        <td rowspan="{{ count($data) }}">{{ $value['party_name'] }}</td>
                    @endif
                    <td>{{ $value['lc_no'] }}</td>
                    <td>{{ $value['lc_date'] }}</td>
                    <td>{{ $value['lc_value'] }}</td>
                    <td>{{ $value['pi_no'] }}</td>
                    <td>{{ $value['pi_date'] }}</td>
                    <td>{{ $value['pi_qty'] }}</td>
                    <td>{{ $value['pi_value'] }}</td>
                    <td>{{ $value['challan_no'] }}</td>
                    <td>{{ $value['pi_yarn_composition'] }}</td>
                    <td>{{ $value['yarn_composition'] }}</td>
                    @if($loop->first && count($group) > 0)
                        <td rowspan="{{ count($group) }}">{{ $value['lot_no'] }}</td>
                    @endif
                    <td>{{ $value['bag'] }}</td>
                    <td>{{ $value['today_receive_qty'] }}</td>
                    @if($loop->first && count($group) > 0)
                        <td rowspan="{{ count($group) }}">{{ $group->sum('total_receive_qty') }}</td>
                    @endif
                    @if(count($group) < 0)
                        <td>{{ $value['total_receive_qty'] }}</td>
                    @endif
                    <td>{{ $value['balance_of_spi_mill'] }}</td>
                    <td>{{ $value['rate'] }}</td>
                    <td>{{ $value['receive_value'] }}</td>
                    @if($loop->first && count($group) > 0)
                        <td rowspan="{{ count($group) }}">{{ $group->sum('bal_value') }}</td>
                    @endif
                    @if(count($group) < 0)
                        <td>{{ $value['bal_value'] }}</td>
                    @endif
                    <td>{{ $value['remarks'] }}</td>
                </tr>
                @php
                    $partyWiseRow = false;
                @endphp
            @endforeach
        @endforeach
        <tr>
            <td colspan="4"><b>Subtotal</b></td>
            <td><b>{{ $total_lc_value }}</b></td>
            <td colspan="9"><b></b></td>
            <td><b>{{ $today_receive_qty }}</b></td>
            <td><b>{{ $total_receive_qty }}</b></td>
            <td><b>{{ $balance_of_spi_mill }}</b></td>
            <td><b></b></td>
            <td><b>{{ $total_receive_value }}</b></td>
            <td><b>{{ $total_bal_value }}</b></td>
            <td><b></b></td>
        </tr>
    @empty
        <tr>
            <th colspan="21">No data found!</th>
        </tr>
    @endforelse
    </tbody>
</table>
