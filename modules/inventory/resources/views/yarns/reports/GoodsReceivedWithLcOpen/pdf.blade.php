@extends('inventory::yarns.reports.pdf-master', [
    'report_name' => 'GOOD RECEIVED WITH LC OPEN'
])

@section('table')
    @include('inventory::yarns.reports.GoodsReceivedWithLcOpen.table')
@endsection
