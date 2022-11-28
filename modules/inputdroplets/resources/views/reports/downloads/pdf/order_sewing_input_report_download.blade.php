<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Order Wise Input Report || {{ date("jS F, Y") }}</h4>

<table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
  @includeIf('inputdroplets::reports.tables.order_sewing_input_report_table')
</table>
</main>
</body>
</html>
