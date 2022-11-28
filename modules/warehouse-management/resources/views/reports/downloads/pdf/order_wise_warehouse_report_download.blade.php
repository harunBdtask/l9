<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Buyer-Style Wise Report ||{{ date("jS F, Y") }}</h4>

    @include('warehouse-management::reports.includes.order_wise_warehouse_report')

</main>

</body>
</html>