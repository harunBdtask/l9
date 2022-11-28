<!DOCTYPE html>

<html>

<head>

    <title>Report</title>

    @include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <h4 align="center">Date Wise Washing Report || {{ date("jS F, Y") }} </h4>

    @include('washingdroplets::reports.table.date_wise_washing_report')

</main>
</body>
</html>
