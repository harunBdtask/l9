@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'Daily Party Wise Yarn Receive Statement'
])

@section('table')
    @include('inventory::yarns.reports.daily-yarn-receive-statement.data-table', [
        'reportData' => collect($reportData)->whereNotNull('lc_no')->groupBy('loan_party_id'),
        'title' => 'Goods Receive With LC'
    ])
    <br>
    @include('inventory::yarns.reports.daily-yarn-receive-statement.data-table', [
        'reportData' => collect($reportData)->whereNull('lc_no')->groupBy('loan_party_id'),
        'title' => 'Goods Receive Without LC'
    ])
@endsection
