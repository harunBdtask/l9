<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Size Wise Get Up Finished Report || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    @include('finishingdroplets::reports.includes.size-wise-finishing-report-table-inc-download')
</table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>