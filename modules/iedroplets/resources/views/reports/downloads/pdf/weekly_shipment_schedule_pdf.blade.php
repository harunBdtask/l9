<!DOCTYPE html>
<html>
<head>
    <title>Weekly Shipment Schedule</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Weekly Shipment Schedule</h4>

    @includeIf('iedroplets::reports.includes.weekly_shipment_schedule_table')
</main>
</body>
</html>
