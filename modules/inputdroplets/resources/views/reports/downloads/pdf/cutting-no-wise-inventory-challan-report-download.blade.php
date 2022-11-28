<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Cutting no wise Inventory Challan Report || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
    @include('inputdroplets::reports.includes.cutting-no-wise-inventory-challan-report-table-inc-download')
</table>
</main>
</body>
</html>