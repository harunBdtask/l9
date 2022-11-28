@extends('manual-production::reports.layout')
@section('title', 'Hourly Production Report')
@section('content')
    @includeIf('manual-production::reports.sewing.includes.date_floor_wise_hourly_sewing_output_inlcude')
@endsection
