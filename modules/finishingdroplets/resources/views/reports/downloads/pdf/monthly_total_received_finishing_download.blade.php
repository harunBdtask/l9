<!DOCTYPE html>

<html>

<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
  
</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">Monthly Total Received Finishing Report || {{ date("F, Y") }}</h4>
    @includeIf('finishingdroplets::reports.tables.monthly_total_received_finishing_report_table')
    
</main>
</body>
</html>
