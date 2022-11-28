<!DOCTYPE html>
<html>
<head>
    <title>Working Capital Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Working Capital Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.working-capital-ratio.table')
</main>
</body>
</html>



