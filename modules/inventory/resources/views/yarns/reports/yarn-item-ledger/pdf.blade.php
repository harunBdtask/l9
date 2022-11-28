@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'Yarn Item Ledger'
])
@section('table')
    @include('inventory::yarns.reports.yarn-item-ledger.reportTable')
@endsection
