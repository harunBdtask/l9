<!DOCTYPE html>
<html>
<head>
    <title>Inventory Turnover Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Inventory Turnover Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.inventory-turnover-ratio.table')
</main>
</body>
</html>



