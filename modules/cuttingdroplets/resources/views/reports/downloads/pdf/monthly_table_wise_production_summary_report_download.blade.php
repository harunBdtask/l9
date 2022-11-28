<!DOCTYPE html>
<html>
<head>
    <title>Order Wise Cutting QC Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Monthly Line Wise Production Summary || {{ date("F",  mktime(0, 0, 0, $month, 10)). ', ' .date("Y", strtotime($year)) }}</h4>
    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @includeIf('cuttingdroplets::reports.tables.monthly_table_wise_production_summary_table')
    </table>
</main>
</body>
</html>
