@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'Yarn Stock Summary Report'
])

@section('table')
    @include('inventory::yarns.reports.yarn-stock-summary.data-table', [
        'reportData' => $reportData
    ])
@endsection
