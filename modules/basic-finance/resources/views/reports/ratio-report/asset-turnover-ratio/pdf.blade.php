<!DOCTYPE html>
<html>
<head>
    <title>Asset Turnover Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Asset Turnover Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.asset-turnover-ratio.table')
</main>
</body>
</html>



