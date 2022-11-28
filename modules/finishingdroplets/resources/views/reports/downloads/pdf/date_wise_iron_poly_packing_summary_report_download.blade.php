<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
  @include('reports.downloads.includes.pdf-header')
  <main>
  <h4 align="center">Date Wise Iron Poly & Packing's Summary || Report Date: {{ $from_date ?? '' }} to {{ $to_date ?? '' }}</h4>

  @include('finishingdroplets::reports.tables.date_range_wise_poly_cartoon_report')

  </main>
</body>
</html>
