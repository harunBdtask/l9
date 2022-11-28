@extends('manual-production::reports.layout')
@section('title', 'Style Wise Rejection Report')
@section('content')
    @includeIf('manual-production::reports.rejection.includes.style_wise_rejection_report_include')
@endsection
