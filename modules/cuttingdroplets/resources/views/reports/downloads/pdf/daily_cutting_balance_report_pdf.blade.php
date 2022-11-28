<!DOCTYPE html>
<html>
<head>
    <title>Daily Cutting Balance Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Daily Cutting Balance Report</h4>

    @includeIf('cuttingdroplets::reports.tables.daily_cutting_balance_report_table')
</main>
</body>
</html>
