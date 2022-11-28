<!DOCTYPE html>
<html>
<head>
    <title>Yearly Summary Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Yearly Summary Report</h4>

    @includeIf('cuttingdroplets::reports.tables.yearly_summary_report_table')
</main>
</body>
</html>
