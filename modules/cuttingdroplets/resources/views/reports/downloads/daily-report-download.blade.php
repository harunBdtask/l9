<!DOCTYPE html>
<html>
<head>
    <title>Order Wise Cutting QC Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>
<body>
	@include('reports.downloads.includes.pdf-header')
	<main>
		<h4 align="center">Daily Cutting Production Report || {{ date("jS F, Y", strtotime($date)) }}</h4>
		@include('cuttingdroplets::reports.tables.daily-cutting-report-table')
	</main>
</body>
</html>
