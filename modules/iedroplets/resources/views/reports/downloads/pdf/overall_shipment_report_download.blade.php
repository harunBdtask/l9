<!DOCTYPE html>

<html>

<head>

	<title>Report</title>

	@include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')

<main>

	<h4 align="center">Overall Shipment Report || {{ date("jS F, Y") }}</h4>

	@include('iedroplets::reports.includes.overall_shipment_report_table')

</main>
</body>
</html>
