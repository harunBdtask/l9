<!DOCTYPE html>
<html>
<head>
    <title>Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
<div class="header-section">
    @include('reports.downloads.includes.pdf-header')
</div>
<main>
    <h4 align="center">All PO's Cutting Production Report</h4>
    <div style="width: 100%">
        @include('cuttingdroplets::reports.tables.po_wise_report_table')
    </div>
</main>
</body>
</html>


