<!DOCTYPE html>
<html>
<head>
    <title>Daily Size Wise Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Daily Size Wise Cutting Report</h4>

    @includeIf('cuttingdroplets::reports.includes.daily_size_wise_cutting_report_include')
</main>
</body>
</html>
