@extends('manual-production::reports.layout')
@section('title', 'DATE WISE / CHALLAN WISE STYLE PRINT SEND RECEIVE DETAILS')
@section('content')
    @includeIf('manual-production::reports.print.includes.challan_wise_print_report_include')
@endsection
