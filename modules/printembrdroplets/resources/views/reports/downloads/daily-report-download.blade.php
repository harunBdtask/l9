<!DOCTYPE html>

<html>
<head>
    <title>PO Wise Cutting QC Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">Daily Cutting Cutting Production || {{ date("jS F, Y") }}</h4>

@include('bundlecard::reports.tables.daily-cutting-report-table')

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>