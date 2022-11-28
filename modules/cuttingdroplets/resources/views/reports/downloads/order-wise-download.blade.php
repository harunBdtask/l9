<!DOCTYPE html>

<html>
<head>
    <title>Order Wise Cutting QC Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">Order Wise Cutting Production Report</h4>
<table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
  @include('cuttingdroplets::reports.includes.order-wise-table-inc-download')
</table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>