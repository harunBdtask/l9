<!DOCTYPE html>
<html>
<head>
    <title>Equity Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Equity Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.equity-ratio.table')
</main>
</body>
</html>



