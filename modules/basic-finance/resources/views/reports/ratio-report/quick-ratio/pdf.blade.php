<!DOCTYPE html>
<html>
<head>
    <title>Quick Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Quick Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.quick-ratio.table')
</main>
</body>
</html>



