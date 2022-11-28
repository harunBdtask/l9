<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Buyer Wise Washing Report || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    @include('washingdroplets::reports.includes.buyer_wise_received_report_table_inc_download')
</table>

</main>
</body>
</html>