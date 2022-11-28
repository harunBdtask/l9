<!DOCTYPE html>
<html>
<head>
    <title>Cut To Finish Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Cut To Finish | Report Date {{ date('d M, Y') }}</h4>

    <table class="reportTable" id="fixTable" style="border-collapse: collapse;font-size:9px !important;">
        @include('misdroplets::reports.tables.cut_to_finish_report_table')
    </table>
</main>
</body>
</html>
