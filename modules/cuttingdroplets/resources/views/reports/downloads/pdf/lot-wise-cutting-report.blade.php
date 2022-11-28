<!DOCTYPE html>
<html>
<head>
    <title>Production Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Lot Wise Cutting Production</h4>
    @include('cuttingdroplets::reports.includes.lot-wise-cutting-report-table-inc-download')
</main>
</body>
</html>
