<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
<h4 align="center">All Order's Input Summary || {{ date("jS F, Y") }}</h4>

    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        @include('inputdroplets::reports.tables.order-wise-input-tables')
    </table>

</main>
</body>
</html>
