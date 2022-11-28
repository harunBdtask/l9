<!DOCTYPE html>
<html>
<head>
    <title>Debt Service Coverage Ratio (DSCR)</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Debt Service Coverage Ratio (DSCR)</h2>
    @includeIf('basic-finance::reports.ratio-report.debt-to-equity-ratio.table')
</main>
</body>
</html>



