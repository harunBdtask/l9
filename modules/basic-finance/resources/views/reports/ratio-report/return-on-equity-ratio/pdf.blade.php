<!DOCTYPE html>
<html>
<head>
    <title>Return On Equity (ROE) Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Return On Equity (ROE) Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.return-on-equity-ratio.table')
</main>
</body>
</html>



