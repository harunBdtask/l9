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
    <h4 align="center">Order Wise Cutting Report</h4>
    <div style="width: 100%">
      @include('cuttingdroplets::reports.tables.v2.order_wise_cutting_report_table')
    </div>
</main>
</body>
</html>


