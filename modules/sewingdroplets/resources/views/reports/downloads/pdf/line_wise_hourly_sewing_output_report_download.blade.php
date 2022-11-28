<!DOCTYPE html>
<html>
<head>
  <title>Report</title>
  @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@php
  error_reporting(0);
@endphp
@include('reports.downloads.includes.pdf-header')
<main>
  @include('sewingdroplets::reports.tables.date_wise_hourly_report_table_excel')
</main>
</body>
</html>
