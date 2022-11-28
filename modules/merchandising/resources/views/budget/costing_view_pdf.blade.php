<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    @includeIf('merchandising::budget.costing_view_body_pdf')
</main>
</body>
</html>
