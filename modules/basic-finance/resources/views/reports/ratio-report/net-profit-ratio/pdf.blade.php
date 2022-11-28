<!DOCTYPE html>
<html>
<head>
    <title>Net Profit Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Net Profit Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.net-profit-ratio.table')
</main>
</body>
</html>



