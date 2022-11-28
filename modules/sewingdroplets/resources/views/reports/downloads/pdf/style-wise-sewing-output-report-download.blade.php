<!DOCTYPE html>

<html>
<head>
  <title>Report</title>
  @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
  <h4 align="center">PO Wise Sewing Output Report || {{ date("jS F, Y") }}</h4>
  <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    @include('sewingdroplets::reports.tables.style-wise-sewing-output-report-download-table')
  </table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>