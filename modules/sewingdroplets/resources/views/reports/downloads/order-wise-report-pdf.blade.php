<!DOCTYPE html>

<html>

<head>

  <title>Report</title>

  @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
  <h4 align="center">All PO's Sewing Output Summary || {{ date("D\ - F d- Y") }}</h4>

  <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;font-size: 8px!important;">
    @include('sewingdroplets::reports.tables.order-wise-report-table')
  </table>
</main>
</body>
</html>
