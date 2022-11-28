<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">Date Wise Print Send Receive Report
    <small class="text-muted text-center">(From {{ date("jS F, Y", strtotime($from_date)) }} to {{ date("jS F, Y", strtotime($to_date)) }})</small>
</h4>
    @include('printembrdroplets::reports.tables.date_wise_report_modify_table')
</main>
</body>
</html>
