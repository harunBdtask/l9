<!DOCTYPE html>
<html>
<head>
    <title>Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
<div class="header-section">
    @include('reports.downloads.includes.pdf-header')
</div>
<main>
    <h4 align="center">Buyer Wise Cutting Production Report</h4>
    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        <thead>
        <tr>
            <th colspan="11">Buyer Name: {{ $buyer_name }}</th>
        </tr>
        <tr>
          <th>Style</th>
          <th>PO</th>
          <th>PO Quantity</th>
          <th>Today's Cutting</th>
          <th>Today's Cutting Rejection</th>
          <th>Today's OK Cutting</th>
          <th>Total Cutting</th>
          <th>Total Cutting Rejection</th>
          <th>Total OK Cutting</th>
          <th>Left/Extra Quantity</th>
          <th>Extra Cuttting (&#37;)</th>
        </tr>
        </thead>
        <tbody class="color-wise-report">
        @include('cuttingdroplets::reports.includes.buyer_wise_report_inc')
        </tbody>
    </table>
</main>
</body>
</html>
