<!DOCTYPE html>
<html>
<head>
    <title>Return On Capital Employeed Ratio (ROCER) Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Return On Capital Employeed Ratio (ROCER) Report</h2>
    @includeIf('basic-finance::reports.ratio-report.return-on-capital-employeed-ratio.table')
</main>
</body>
</html>



