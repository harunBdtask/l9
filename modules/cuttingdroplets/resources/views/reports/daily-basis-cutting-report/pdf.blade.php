<!DOCTYPE html>
<html>
<head>
    <title>Daily Basis Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
<div class="header-section">
    @include('reports.downloads.includes.pdf-header')
</div>
<main>
    @include('cuttingdroplets::reports.daily-basis-cutting-report.data-table')
</main>
</body>
</html>
