<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Warehouse Daily Out Report || From {{ date("jS F, Y", strtotime($from_date)) }} - To {{ date("jS F, Y", strtotime($to_date)) }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('warehouse-management::reports.includes.daily_out_report_table')
    </table>

</main>

</body>
</html>