
<table class="reportTable">
    <thead>
    <tr>
        <th colspan="9">Particulars</th>
        <th colspan="3">PI/Procurement Details</th>
        <th colspan="2">Bank/Commercial</th>
        <th colspan="5">Spinning Mills Yarn Status</th>
    </tr>
        <tr>
            <th>SL No</th>
            <th>Party Name</th>
            <th> PI Date</th>
            <th>Type</th>
            <th>Brand</th>
            <th>Composition</th>
            <th>Color</th>
            <th>Certification</th>
            <th>Lot No</th>
            <th>PI No</th>
            <th>Count</th>
            <th>PI Quantity</th>
            <th>L/C No</th>
            <th>L/C Date</th>
            <th>Receive Quantity</th>
            <th>Balance Quantity</th>
            <th>Rate</th>
            <th>Receive Value</th>
            <th>Balance Value</th>
        </tr>
    </thead>

    <tbody>
    @php
        $index = 1;
        $totalPiQtySum = 0;
        $totalRcvQtySum = 0;
        $totalBalanceOfSPLMillSum = 0;
        $totalRateSum = 0;
        $totalRcvValueSum = 0;
        $totalBalValueSum = 0;
    @endphp
    @foreach($reportData->groupBy('pi_no') as $key => $piGroup)
        @php
            $piQtySum = 0;
            $rcvQtySum = 0;
            $balanceOfSPLMillSum = 0;
            $rateSum = 0;
            $rcvValueSum = 0;
            $balValueSum = 0;
            $spiMill = 0;
            $rowSpan = $piGroup->count();
            $piRow = true;
        @endphp

        @foreach($piGroup->groupBy('pi_unique_id') as  $uniqueGroup)

            @foreach($uniqueGroup as $data)
            @php
                $spiMill = $data['pi_qty'] - $rcvQtySum;

                $piQtySum += $data['pi_qty'];
                $rcvQtySum += $data['rec_qty'];
                $balanceOfSPLMillSum += $spiMill;
                $rateSum += $data['rate'];
                $rcvValueSum += $data['rcv_value'];
                $balValueSum += ($data['pi_qty'] - $data['rec_qty']) * $data['rate'];

                $totalPiQtySum += $data['pi_qty'];
                $totalRcvQtySum += $data['rec_qty'];
                $totalBalanceOfSPLMillSum += $spiMill;
                $totalRateSum += $data['rate'];
                $totalRcvValueSum +=  $data['rcv_value'];
                $totalBalValueSum += ($data['pi_qty'] - $data['rec_qty']) * $data['rate'];

            @endphp
            <tr>
                @if ($piRow)
                    <td rowspan="{{ $rowSpan }}">{{ $index++ }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['party_name'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['pi_date'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['type'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['brand'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['composition'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['color'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['certification'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['lot_no'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['pi_no'] }}</td>
                @endif
                @if ($loop->first)
                    <td rowspan="{{ $uniqueGroup->count() }}">{{ $data['count'] }}</td>
                    <td rowspan="{{ $uniqueGroup->count() }}">{{ $data['pi_qty'] }}</td>
                @endif
                @if ($piRow)
                    <td rowspan="{{ $rowSpan }}">{{ $data['lc_no'] }}</td>
                    <td rowspan="{{ $rowSpan }}">{{ $data['lc_date'] }}</td>
                @endif
                <td>{{ $data['rec_qty'] }}</td>
                <td>{{ $spiMill }}</td>
                <td>{{ $data['rate'] }}</td>
                <td>{{ $data['rcv_value'] }}</td>
                <td>{{ $spiMill * $data['rate'] }}</td>
            </tr>
                 @php
                     $piRow = false;
                 @endphp
            @endforeach

        @endforeach
        <tr>
            <td colspan="11"><b>Sub Total</b></td>
            <td><b>{{ $piQtySum }}</b></td>
            <td><b></b></td>
            <td><b></b></td>
            <td><b>{{ $rcvQtySum }}</b></td>
            <td><b>{{ $balanceOfSPLMillSum }}</b></td>
            <td><b>{{ $rateSum }}</b></td>
            <td><b>{{ $rcvValueSum }}</b></td>
            <td><b>{{ $balValueSum }}</b></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="11"><b>Total</b></td>
        <td><b>{{ $totalPiQtySum }}</b></td>
        <td><b></b></td>
        <td><b></b></td>
        <td><b>{{ $totalRcvQtySum }}</b></td>
        <td><b>{{ $totalBalanceOfSPLMillSum }}</b></td>
        <td><b>{{ $totalRateSum }}</b></td>
        <td><b>{{ $totalRcvValueSum }}</b></td>
        <td><b>{{ $totalBalValueSum }}</b></td>
    </tr>
    </tbody>

</table>
