@extends('manual-production::reports.layout')
@section('title', 'Yearly Rejection Report')
@section('content')
    @includeIf('manual-production::reports.rejection.includes.yearly_rejection_report_include')
@endsection
