<!DOCTYPE html>
<html>
<head>
    <title>Return On Assets Ratio (ROAR) Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Return On Assets Ratio (ROAR) Report</h2>
    @includeIf('basic-finance::reports.ratio-report.return-on-assets-ratio.table')
</main>
</body>
</html>



