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

  <h4 align="center">Date Wise Sewing Output Report
    <small class="text-muted text-center">(From {{ date("jS F, Y", strtotime($from_date)) }}
      to {{ date("jS F, Y", strtotime($to_date)) }})
    </small>
  </h4>

  @include('sewingdroplets::reports.tables.date_range_wise_report_download')

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>