@extends('manual-production::reports.layout')
@section('title', 'Daily Input Unit Wise Report')
@section('content')
    @includeIf('manual-production::reports.sewing.includes.daily_input_unit_wise_report_inlcude')
@endsection
