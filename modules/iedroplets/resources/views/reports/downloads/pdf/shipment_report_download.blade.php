<!DOCTYPE html>

<html>

<head>
	
	<title>Report</title>
	
	@include('reports.downloads.includes.pdf-styles')

</head>

<body>
@include('reports.downloads.includes.pdf-header')
@include('reports.downloads.includes.pdf-footer')

<main>
	
	<h4 align="center">Daily Shipment Report || {{ date("jS F, Y") }}</h4>

	@include('iedroplets::reports.includes.shipment_report_table')

</main>
</body>
</html>