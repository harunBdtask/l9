<!DOCTYPE html>
<html>
<head>
    <title>Account Receivable Turnover Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Account Receivable Turnover Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.account-receivable-turnover-ratio.table')
</main>
</body>
</html>



