<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <table class="reportTable" style="border-collapse: collapse; font-size: 5px!important;">
        @includeIf('finishingdroplets::reports.tables.date_wise_finishing_summary_report_table')
    </table>
</main>
</body>
</html>
