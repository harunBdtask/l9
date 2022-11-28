<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <h4 align="center">Style Wise Input Report  || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
        @include('inputdroplets::reports.tables.get-style-wise-sewing-input-report-download-table')
    </table>
</main>
</body>
</html>
