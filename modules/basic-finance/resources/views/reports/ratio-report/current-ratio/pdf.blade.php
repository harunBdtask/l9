<!DOCTYPE html>
<html>
<head>
    <title>Current Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Current Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.current-ratio.table')
</main>
</body>
</html>



