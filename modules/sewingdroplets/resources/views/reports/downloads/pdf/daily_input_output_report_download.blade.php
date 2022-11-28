<!DOCTYPE html>

<html>
<head>
  <title>Report</title>
  @include('reports.downloads.includes.pdf-styles')
  <style>
    .reportTable {
      border-collapse: collapse !important;
      font-size: 9px !important;
    }
  </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

  <h4 align="center">Daily Input Output Report Summary</h4>

  <table class="reportTable">
    @include('sewingdroplets::reports.tables.daily_input_output_report_table')
  </table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>