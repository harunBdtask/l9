<!DOCTYPE html>

<html>

<head>
    <title>MIS Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style>
        .reportTable th,
        .reportTable td {
            font-size: 8px;
        }
    </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <h4 align="center">Color Wise Production Summary Report || From - {{ date("jS F, Y", strtotime($from_date)) }} To - {{ date("jS F, Y", strtotime($to_date)) }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('misdroplets::reports.tables.color_wise_production_summary_report')
    </table>

</main>
</body>
</html>
