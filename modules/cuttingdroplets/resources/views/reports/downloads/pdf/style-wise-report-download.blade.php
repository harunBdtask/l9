<!DOCTYPE html>
<html>
<head>
    <title>Order Wise Cutting QC Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center"> Our Reference Wise Cutting Production Report || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('cuttingdroplets::reports.includes.style-wise-report-download_table')
    </table>
</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>