<!DOCTYPE html>
<html>
<head>
    <title>Buyer Style Wise Cutting Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 style="text-align: center">Buyer Style Wise Cutting Report</h4>

    @includeIf('cuttingdroplets::reports.includes.buyer_style_wise_cutting_report_include')
</main>
</body>
</html>
