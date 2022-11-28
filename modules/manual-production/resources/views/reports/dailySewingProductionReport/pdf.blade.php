@extends('manual-production::reports.layout')
@section('title', 'Daily Swing Production Report')
@section('content')
    @includeIf('manual-production::reports.dailySewingProductionReport.data')
@endsection
