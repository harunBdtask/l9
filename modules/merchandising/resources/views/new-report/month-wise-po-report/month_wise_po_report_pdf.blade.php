<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>
    <h4 align="center">{{ 'Month Wise PO Report-'.\Carbon\Carbon::parse($reportData[0]['ex_factory_date'])->format('M-Y')}}</h4>
    @includeIf('merchandising::new-report.month-wise-po-report.month_wise_po_report_table')
    <div style="margin-top: 50px;">
    </div>
</main>
</body>
</html>
