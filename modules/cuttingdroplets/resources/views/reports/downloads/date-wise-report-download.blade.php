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
    <h4 align="center">Date Wise Cutting Production Summary ||
        <small class="text-muted text-center">{{ date("jS F, Y", strtotime($date)) }}</small>
    </h4>
@include('cuttingdroplets::reports.tables.date_wise_cutting_summary')
<!--table wise cutting summary-->
</main>
</body>
</html>
