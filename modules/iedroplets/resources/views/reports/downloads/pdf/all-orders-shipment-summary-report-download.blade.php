<!DOCTYPE html>

<html>

<head>

	<title>Report</title>

	@include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')

<main>

	<h4 align="center">All Order's Shipment Summary || {{ date("jS F, Y") }}</h4>

	@include('iedroplets::reports.order_wise_shipment_report_table')

</main>
</body>
</html>
