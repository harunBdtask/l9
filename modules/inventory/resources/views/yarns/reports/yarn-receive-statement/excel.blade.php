@include('inventory::yarns.reports.daily-yarn-receive-statement.data-table', [
        'reportData' => collect($reportData)->whereNotNull('lc_no')->groupBy('loan_party_id')
    ])
<table>
    <tr>

    </tr>
</table>
@include('inventory::yarns.reports.daily-yarn-receive-statement.data-table', [
    'reportData' => collect($reportData)->whereNull('lc_no')->groupBy('loan_party_id')
])
