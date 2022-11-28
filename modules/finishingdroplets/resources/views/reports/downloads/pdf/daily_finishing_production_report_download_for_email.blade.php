<!DOCTYPE html>

<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style type="text/css">
        /* table style */
        .reportTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }
        .reportTable thead,
        .reportTable tbody,
        .reportTable th {
            padding: 2px;
            font-size: 8px !important;;
            text-align: center;
        }
        .reportTable th,
        .reportTable td {
            font-size: 8px !important;
            border: 1px solid #a2a2a2;
        }
        .table td, .table th {
            padding: 0.1rem;
            vertical-align: middle;
        }
    </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">Production Report || {{ date("jS F, Y", strtotime($date)) }}</h4>

<table class="reportTable" style="border-collapse: collapse;">
    @include('finishingdroplets::reports.includes.daily_finishing_production_report_table_for_email')
</table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>