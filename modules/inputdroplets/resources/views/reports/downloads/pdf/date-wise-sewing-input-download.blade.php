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

<h4  align="center">Date wise Sewing Input Report ||
    <small class="text-muted text-center"> {{ date("jS F, Y") }}</small>
</h4>
<div style="font-size: 10px!important;">
    @include('inputdroplets::reports.tables.date_wise_input_report_modify_table')
</div>
</main>
</body>
</html>
