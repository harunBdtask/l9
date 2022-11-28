<!DOCTYPE html>

<html>

<head>

  <title>Report</title>

  @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">All Order's Inventory Report || {{ date("D\ - F d- Y") }}</h4>
  <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;font-size:9px !important;">
    @include('inputdroplets::reports.tables.order_wise_cutting_inventory_summary_table')
  </table>

</main>
</body>
</html>
