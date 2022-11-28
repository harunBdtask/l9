<!DOCTYPE html>
<html>
<head>
    <title>Accounts Payable Turnover Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Accounts Payable Turnover Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.accounts-payable-turnover-ratio.table')
</main>
</body>
</html>



