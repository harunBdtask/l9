<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Order Wise Print Send Receive Report  || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
    @include('printembrdroplets::reports.includes.order-wise-print-send-receive-report-table-inc-download')
</table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>