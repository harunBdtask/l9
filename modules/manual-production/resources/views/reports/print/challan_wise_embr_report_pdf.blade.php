@extends('manual-production::reports.layout')
@section('title', 'DATE WISE / CHALLAN WISE STYLE EMBROIDERY SEND RECEIVE DETAILS')
@section('content')
    @includeIf('manual-production::reports.print.includes.challan_wise_embr_report_include')
@endsection
