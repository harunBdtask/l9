<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
    <style type="text/css">
        th, td {
            font-size: 9px !important;
            border: 1px solid black !important;
            border-collapse: collapse !important;
        }
    </style>
</head>
@include('reports.downloads.includes.pdf-header')
<body>

    <main>
     <h4 align="center">Line Wise Input Inhand Report(input)</h4>
       @include('inputdroplets::reports.tables.floor_line_wise_input_report_table')
    </main>
    @include('reports.downloads.includes.pdf-footer')
</body>
</html>