<table class="reportTable">
    <thead>
    <tr>
        <th>SI</th>
        <th>Date</th>
        <th>Party Name</th>
        <th>Challan No</th>
        <th>Item</th>
        <th>Bill No</th>
        <th>LC No</th>
        <th>PI No</th>
        <th>Received QTY</th>
        <th>Rate</th>
        <th>Total Value</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @php
        $index = 1;
    @endphp
    @foreach($dyesChemicalReceive as $key => $receive)
        @foreach($receive->details as $detail)
            <tr>
                <td>{{ $index++ }}</td>
                <td>{{ \Carbon\Carbon::parse($receive->receive_date)->toFormattedDateString() }}</td>
                <td>{{ $receive->supplier->name ?? '' }}</td>
                <td>{{ $receive->reference_no }}</td>
                <td>{{ $detail->item_name }}</td>
                <td></td>
                <td>{{ $receive->lc_no }}</td>
                <td></td>
                <td>{{ $detail->receive_qty ?? '' }}</td>
                <td>{{ $detail->rate ?? '' }}</td>
                <td>{{ ($detail->receive_qty * $detail->rate) ?? '' }}</td>
                <td></td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
