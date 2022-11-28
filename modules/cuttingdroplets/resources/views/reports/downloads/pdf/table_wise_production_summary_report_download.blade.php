<!DOCTYPE html>
<html>
<head>
    <title>Table wise Cutting Report Summary</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('cuttingdroplets::reports.tables.table_wise_production_summary_table')
    </table>

</main>
</body>
</html>
