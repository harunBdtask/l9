<!DOCTYPE html>
<html>
<head>
    <title>Gross Profit Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Gross Profit Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.gross-profit-ratio.table')
</main>
</body>
</html>



