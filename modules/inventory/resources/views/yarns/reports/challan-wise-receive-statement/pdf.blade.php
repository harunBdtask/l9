@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'Challan Wise Yarn Receive Statement'
])

@section('table')
    @include('inventory::yarns.reports.challan-wise-receive-statement.data-table', [
        'reportData' => collect($reportData)->whereNotNull('lc_no')->groupBy('loan_party_id')
    ])
    <br>
    @include('inventory::yarns.reports.challan-wise-receive-statement.data-table', [
        'reportData' => collect($reportData)->whereNull('lc_no')->groupBy('loan_party_id')
    ])
@endsection
