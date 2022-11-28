<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Floor Wise Status Report ||{{ date("jS F, Y") }} -  Floor {{ $warehouse_floor ?? '' }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('warehouse-management::reports.includes.floor_wise_status_report_table')
    </table>

</main>

</body>
</html>