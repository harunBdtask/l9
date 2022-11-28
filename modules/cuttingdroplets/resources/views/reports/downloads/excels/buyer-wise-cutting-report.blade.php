<!DOCTYPE html>
<html>
<head></head>
<body>
<table>
    <thead>
        <tr>
            <th>Buyer</th>
            <th>Our Reference</th>
            <th>Order No.</th>
            <th>Order Quantity</th>
            <th>Today's Cutting</th>
            <th>Total Cutting</th>
            <th>Left Quantity</th>
            <th>Extra Cuttting (%)</th>
        </tr>
    </thead>
    <tbody class="color-wise-report">
    @include('cuttingdroplets::reports.includes.buyer_wise_report_inc_download')
    </tbody>
</table>
</body>
</html>