<!DOCTYPE html>
<html>
<head>
    <title>Fixed Charge Coverage Ratio (FCCR) Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Fixed Charge Coverage Ratio (FCCR) Report</h2>
    @includeIf('basic-finance::reports.ratio-report.fixed-charge-coverage-ratio.table')
</main>
</body>
</html>



