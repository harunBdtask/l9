<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style>
        .reportTable thead,
        .reportTable tbody,
        .reportTable th {
            font-size: 8px;
        }

        .reportTable th,
        .reportTable td {
            font-size: 8px;
        }

        .table td, .table th {
            font-size: 8px;

        }
    </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Hourly Finishing Production Report || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('finishingdroplets::reports.tables.hourly_finishing_production_table')
    </table>
</main>
</body>
</html>
