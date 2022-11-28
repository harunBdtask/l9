<table class="reportTable">
    <thead>
    <tr>
        <th colspan="13" style="text-align: center">
            DHU Report ({{ $data['type'] ?? '' }}) <br>
            {{ date('F j, Y', strtotime($data['fromDate'])) }} - {{ date('F j, Y', strtotime($data['toDate'])) }}
        </th>
    </tr>
    <tr style="background-color: #dbdbdb">
        <th style="text-align: left;">
            {{ $data['type'] == 'Sewing' ? 'Line' : 'Table' }}
        </th>
        <th style="text-align: left;">Buyer</th>
        <th style="text-align: left;">Style</th>
        <th style="text-align: left;">PO</th>
        <th style="text-align: right;">Checked</th>
        <th style="text-align: right;">QC Pass</th>
        <th style="text-align: right;">Defects</th>
        <th style="text-align: right;">Reject</th>
        <th style="text-align: right;">DHU Level</th>
    </tr>
    </thead>

    <tbody>
    @foreach($data['reportData'] as $value)
        <tr>
            <td style="text-align: left;">{{ $value['table_no'] ?? $value['line_no'] ?? '' }}</td>
            <td style="text-align: left;">{{ $value['buyer_name'] }}</td>
            <td style="text-align: left;">{{ $value['style_name'] }}</td>
            <td style="text-align: left;">{{ $value['po_no'] }}</td>
            <td style="text-align: right;">{{ $value['checked'] }}</td>
            <td style="text-align: right;">{{ $value['qc_pass'] }}</td>
            <td style="text-align: right;">{{ $value['defects'] }}</td>
            <td style="text-align: right;">{{ $value['reject'] }}</td>
            <td style="text-align: right;">{{ number_format($value['dhu_level'], 2) }}</td>
        </tr>
    @endforeach
    @php
        $reportDataCollection = collect($data['reportData']);
        $totalCollectionChecked = $reportDataCollection->sum('checked');
        $totalCollectionDefects = $reportDataCollection->sum('defects');
    @endphp
    <tr style="background-color: #dbdbdb; font-weight: bold">
        <td style="text-align: right;" colspan="4">Total</td>
        <td style="text-align: right;">{{ $totalCollectionChecked }}</td>
        <td style="text-align: right;">{{ $reportDataCollection->sum('qc_pass') }}</td>
        <td style="text-align: right;">{{ $totalCollectionDefects }}</td>
        <td style="text-align: right;">{{ $reportDataCollection->sum('reject') }}</td>
        <td style="text-align: right;">
            {{ $totalCollectionChecked ? number_format((($totalCollectionDefects*100)/$totalCollectionChecked), 2) : 0.00 }}
        </td>
    </tr>
    </tbody>
</table>
