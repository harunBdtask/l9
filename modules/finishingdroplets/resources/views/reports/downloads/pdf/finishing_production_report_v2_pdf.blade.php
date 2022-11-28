<!DOCTYPE html>
<html>
<head>
    <title>Finishing Production Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Finishing Production Report</h4>

    <table class="reportTable">
        <thead>
        <tr>
            <td style="background-color: #a1c9ed;"><strong>Buyer</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Date</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Style Name</strong></td>
            <td style="background-color: #a1c9ed;"><strong>PO No</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Country</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Color</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Order Qty</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Order Qty + 1%</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Daily Rcvd</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Pre Rcvd</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Total Rcvd</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Daily Iron</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Pre Iron</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Total Iron</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Daily Finish</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Pre Finish</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Total Finish</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Balance Qty</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Finish Floor</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Sewing Floor</strong></td>
            <td style="background-color: #a1c9ed;"><strong>Remarks</strong></td>
        </tr>
        </thead>
        <tbody class="report-div">
        @includeIf('finishingdroplets::reports.tables.finishing_production_report_v2_table')
        </tbody>
    </table>
</main>
</body>
</html>
