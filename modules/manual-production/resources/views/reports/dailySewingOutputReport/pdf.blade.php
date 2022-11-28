@extends('manual-production::reports.layout')
@section('title', 'Color Wise Date Wise Sewing Output in Pieces Report')
@section('content')
    @includeIf('manual-production::reports.dailySewingOutputReport.data')
@endsection
