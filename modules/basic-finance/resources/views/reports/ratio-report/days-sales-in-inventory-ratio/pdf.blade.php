<!DOCTYPE html>
<html>
<head>
    <title>Days Sales In Inventory Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Days Sales In Inventory Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.days-sales-in-inventory-ratio.table')
</main>
</body>
</html>



