<!DOCTYPE html>
<html>
    <head>
        <title>Report</title>
        @include('reports.downloads.includes.pdf-styles')
    </head>
<body>
    @include('reports.downloads.includes.pdf-header')
    <main>
        <h4 align="center">Buyer Wise Print Send Receive Report</h4>
        <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Buyer</th>
                    <th>Style</th>
                    <th>PO</th>
                    <th>Order Qty</th>
                    <th>Cutting Qty</th>
                    <th>Cutting WIP</th>
                    <th>Total Send</th>
                    <th>Total Recieved</th>
                    <th>Fabric Rejection</th>
                    <th>Print Rejection</th>
                    <th>Print WIP/Short</th>
                </tr>
            </thead>
            <tbody>
            @include('printembrdroplets::reports.includes.buyer_wise_report_inc_download')
            </tbody>
        </table>
    </main>
</body>
</html>
