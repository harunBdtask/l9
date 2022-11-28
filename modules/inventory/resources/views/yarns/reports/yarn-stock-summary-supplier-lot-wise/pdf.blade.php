@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'Yarn Stock Summary Supplier-Lot Wise'
])

@section('table')
    @include('inventory::yarns.reports.yarn-stock-summary-supplier-lot-wise.data-table', [
        'reportData' => $reportData
    ])
@endsection
