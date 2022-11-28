<!DOCTYPE html>
<html>
<head>
  <title>Report</title>
  @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
@include('reports.downloads.includes.pdf-footer')
<main>
  <table class="reportTable" style="border-collapse: collapse;font-size: 9px!important;">
    @includeIf('sewingdroplets::reports.tables.sewing_line_plan_report_table')
  </table>
</main>
</body>
</html>