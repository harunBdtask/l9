<!DOCTYPE html>
<html>
<head>
    <title>Price Earning Ratio Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h2 align="center">Price Earning Ratio Report</h2>
    @includeIf('basic-finance::reports.ratio-report.price-earning-ratio.table')
</main>
</body>
</html>



