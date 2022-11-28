<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style>
        .reportTable {
            border-collapse: collapse !important;
            font-size: 9px !important;
        }
    </style>
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Month Wise Cutting Production Report @if(isset($from_date) && isset($to_date))
            || {{ date("jS F, Y", strtotime($from_date)).' To '. date("jS F, Y", strtotime($to_date)) }} @endif
    </h4>
    @include('cuttingdroplets::reports.tables.month_wise_cutting_summary_table')
</main>
</body>
</html>
