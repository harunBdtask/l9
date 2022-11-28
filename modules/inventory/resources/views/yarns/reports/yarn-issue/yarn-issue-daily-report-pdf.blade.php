@extends('inventory::yarns.reports.pdf-master', ['report_name' => 'Daily Yarn Issue Statement'])
@section('table')
    @include('inventory::yarns.reports.yarn-issue.yarn-issue-daily-report-table', compact('data'))
@endsection
