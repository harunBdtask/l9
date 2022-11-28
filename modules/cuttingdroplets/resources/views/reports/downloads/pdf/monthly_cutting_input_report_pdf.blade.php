<!DOCTYPE html>
<html>
<head>
    <title>Monthly Cutting Input Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Monthly Cutting Input Report</h4>

    @includeIf('cuttingdroplets::reports.tables.monthly_cutting_input_report_table')
</main>
</body>
</html>
