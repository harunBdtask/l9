<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Buyer wise Sewing Line Input Report || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
        <thead>
        <tr>
            <th>Buyer</th>
            <th>Booking No</th>
            <th>Order</th>
            <th>PO</th>
            <th>Order Quantity</th>
            <th>Cutting Production</th>
            <th>Cutting WIP</th>
            <th>Print Send</th>
            <th>Print Recieve</th>
            <th>Total Print Reject</th>
            <th>Print/Embr.WIP</th>
            <th>Current Cutting Inventory</th>
            <th>Today's Input Qty</th>
            <th>Total Input Qty</th>
        </tr>
        </thead>
        <tbody>
        @include('inputdroplets::reports.includes.buyer_wise_report_inc_download')
        </tbody>
    </table>
</main>
</body>
</html>