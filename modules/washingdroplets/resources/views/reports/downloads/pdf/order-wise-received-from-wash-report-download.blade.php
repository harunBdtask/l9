<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

<h4 align="center">All Order's Washing Sent & Received Summary || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="font-size : 9px!important; border: 1px solid black;border-collapse: collapse;">
        @include('washingdroplets::reports.table.order_wise_wasing_received_summary_table')
    </table>

</main>
</body>
</html>
