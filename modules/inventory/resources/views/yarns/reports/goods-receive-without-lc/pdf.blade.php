@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'Goods Received Without LC'
])

@section('table')
    @include('inventory::yarns.reports.goods-receive-without-lc.data-table')
@endsection
