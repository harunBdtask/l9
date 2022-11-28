<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style type="text/css">
        th, td {
            font-size: 9px !important;
            border: 1px solid black !important;
            border-collapse: collapse !important;
        }
    </style>
</head>
@include('reports.downloads.includes.pdf-header')
<body>
<main>
    <h4 align="center">Line Wise Input Inhand Report</h4>
    @include('cuttingdroplets::reports.tables.floor_line_wise_cutting_report_table')
</main>
</body>
</html>
