<table class="reportTable table">
    <thead style="background-color: aliceblue !important;">
    <tr>
        <th style="background-color: aliceblue !important;" colspan="9"><b>Particular</b></th>
        <th style="background-color: aliceblue !important;" colspan="3"><b>PI/Procurement Details</b></th>
        <th style="background-color: aliceblue !important;" colspan="5"><b>Spinning Mill Yarn Status</b></th>
    </tr>
    <tr>
        <td><b>SL No.</b></td>
        <td><b>Party Name</b></td>
        <td><b>Yarn Count</b></td>
        <td><b>Yarn Type</b></td>
        <td><b>Yarn Brand</b></td>
        <td><b>Yarn Comp.</b></td>
        <td><b>Yarn Color</b></td>
        <td><b>Certification</b></td>
        <td><b>Lot No</b></td>
        <td><b>PI No</b></td>
        <td><b>PI Date</b></td>
        <td><b>PI Qty</b></td>
        <td><b>RCV Qty</b></td>
        <td><b>Bal. Qty</b></td>
        <td><b>Rate</b></td>
        <td><b>Receive Value</b></td>
        <td><b>Balance Value</b></td>
    </tr>
    </thead>
    <tbody>
    @php
        $i = 1;
        $receiveQty = 0;
    @endphp
    @foreach($reportData->groupBy('pi_no') as $piGroup)
        @php
            $piRow = true;
            $spiMillQty = 0;
            $receiveQty = 0;
        @endphp
        @foreach($piGroup->groupBy('party_id') as $partyGroup)
            @foreach($partyGroup as $value)
                @php
                    if ($piRow) {
                        $spiMillQty = numberFormat($value['pi_qty'] - $value['receive_qty']);
                    } else {
                        $spiMillQty -= numberFormat($value['receive_qty']);
                    }
                    $receiveQty = numberFormat($value['receive_qty']);
                @endphp
                <tr>
                    @if($loop->first)
                        <td rowspan="{{ count($partyGroup) }}">{{ $i }}</td>
                        <td rowspan="{{ count($partyGroup) }}">{{ $value['party_name'] }}</td>
                    @endif
                    <td>{{ $value['yarn_count'] }}</td>
                    <td>{{ $value['yarn_type'] }}</td>
                    <td>{{ $value['yarn_brand'] }}</td>
                    <td>{{ $value['yarn_composition'] }}</td>
                    <td>{{ $value['yarn_color'] }}</td>
                    <td>{{ $value['certification'] }}</td>
                    <td>{{ $value['yarn_lot'] }}</td>
                    @if($piRow)
                        <td rowspan="{{ count($piGroup) }}">{{ $value['pi_no'] ? $value['pi_no'] : '' }}</td>
                        <td rowspan="{{ count($piGroup) }}">{{ $value['pi_no'] ? date('d-m-Y', strtotime($value['pi_date'])) : '' }}</td>
                        <td rowspan="{{ count($piGroup) }}">{{ $value['pi_no'] ? $value['pi_qty'] : '' }}</td>
                    @endif
                    <td>{{ numberFormat($value['receive_qty']) }}</td>
                    <td>
                        @if($value['pi_no'])
                            {{ numberFormat($spiMillQty) }}
                        @endif
                    </td>
                    <td style="text-align: right">${{ numberFormat($value['rate']) }}</td>
                    <td style="text-align: right">${{ numberFormat($value['receive_value']) }}</td>
                    <td style="text-align: right">
                        @if($value['pi_no'])
                            ${{ numberFormat($spiMillQty * $value['rate']) }}
                        @endif
                    </td>
                </tr>
                @php
                    $piRow = false;
                    if ($loop->first) {
                        $i++;
                    }
                @endphp
            @endforeach
        @endforeach
    @endforeach
    <tr>
        <th style="background-color: aliceblue !important;"></th>
        <th style="background-color: aliceblue !important;" colspan="10"></th>
        <th style="background-color: aliceblue !important;"></th>
        <th style="background-color: aliceblue !important;">{{ numberFormat(collect($reportData)->sum('receive_qty')) }}</th>
        <th style="background-color: aliceblue !important;"></th>
        <th style="background-color: aliceblue !important;"></th>
        <th style="background-color: aliceblue !important; text-align: right">${{ numberFormat(collect($reportData)->sum('receive_value')) }}</th>
        <th style="background-color: aliceblue !important;"></th>
    </tr>
    </tbody>
</table>
