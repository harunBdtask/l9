<!DOCTYPE html>
<html>
<head>
    <title>Date Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Date Wise Cutting Report | Repoet Date {{ date('d M, Y', strtotime($date)) }}</h4>

    <table class="reportTable" id="fixTable" style="border-collapse: collapse;font-size:9px !important;">
      @include('cuttingdroplets::reports.tables.v2.date_wise_cutting_report_table')
    </table>
</main>
</body>
</html>
