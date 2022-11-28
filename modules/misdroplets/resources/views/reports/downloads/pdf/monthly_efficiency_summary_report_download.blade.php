<!DOCTYPE html>
<html>
<head>
    <title>MIS Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style>
        .reportTable th,
        .reportTable td {
            font-size: 9px !important;
        }
    </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <h4 align="center">Efficiency Summary Report || {{ $monthName ."-". $year }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">    
        @include('misdroplets::reports.tables.monthly_efficiency_summary_table')
    </table>

</main>
</body>
</html>