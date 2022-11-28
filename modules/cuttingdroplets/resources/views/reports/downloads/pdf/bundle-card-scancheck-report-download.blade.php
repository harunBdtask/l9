<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Bundle Card Scan Check Report || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse; font-size: 9px !important">
        @include('cuttingdroplets::reports.includes.bundle_card_scancheck_report_table_inc_download')
    </table>
</main>
</body>
</html>
