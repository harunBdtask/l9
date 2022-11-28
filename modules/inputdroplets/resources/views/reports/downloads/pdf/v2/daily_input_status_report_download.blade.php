<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')
    <style>
        .reportTable {
            border-collapse: collapse!important;
            font-size: 9px;
        }
    </style>
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">Daily Input Report ||
    <small class="text-muted text-center"> {{ date("jS F, Y", strtotime($date)) }}</small>
</h4>
<div>
  <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
    @include('inputdroplets::reports.v2.tables.daily_input_status_table')
  </table>
</div>
</main>
</body>
</html>
