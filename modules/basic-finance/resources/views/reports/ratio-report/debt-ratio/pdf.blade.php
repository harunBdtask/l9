<!DOCTYPE html>
<html>
<head>
    <title>Debt Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Debt Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.debt-ratio.table')
</main>
</body>
</html>



