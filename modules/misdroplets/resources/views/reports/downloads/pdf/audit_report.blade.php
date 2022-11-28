<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    @include('misdroplets::reports.downloads.includes.pdf-styles')
</head>

<body>

@include('misdroplets::reports.downloads.includes.pdf-header')
<main>

<center><span style="font-size: 13px; font-weight: bold;" align="center">Audit Report</span></center>

<table class="reportTable" style="border: 1px solid black;border-collapse: collapse; font-size: 6px!important;">
  @include('misdroplets::reports.tables.audit_report_table_download')
</table>

</main>
@include('misdroplets::reports.downloads.includes.pdf-footer')
</body>
</html>