<!DOCTYPE html>

<html>

<head>

  <title>Report</title>

  @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

  <h4 align="center">Line & Date Wise Sewing Output Average || {{ date("jS F, Y") }}</h4>

  <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;font-size: 8px;">
    @include('sewingdroplets::reports.tables.line-date-wise-avg-report-table')
  </table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>