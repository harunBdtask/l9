<style>
	@import url('https://fonts.maateen.me/solaiman-lipi/font.css');
	
	* {
		font-family: 'SolaimanLipi', sans-serif;
	}
	
	.reportHeader,
	.reportName {
		text-align: center;
	}
	
	h2 {
		margin-bottom: 0px !important;
	}
	
	h5, h3 {
		margin: 2px 0px !important;
	}
	
	.basic-info-table {
		margin-bottom: 1rem;
		width: 50%;
		max-width: 80%;
		font-size: 12px;
		border-collapse: collapse;
	}
	
	.basic-info-table tbody,
	.basic-info-table th {
		padding: 3px;
		font-size: 12px;
		text-align: center;
	}
	
	.basic-info-table th,
	.basic-info-table td {
		border: 1px solid #000;
	}
	
	.font-bold {
		font-weight: bold;
	}
	
	.reportTable {
		margin-bottom: 1rem;
		width: 100%;
		max-width: 100%;
		font-size: 12px;
		border-collapse: collapse;
	}
	
	.reportTable thead,
	.reportTable tbody,
	.reportTable th {
		padding: 3px;
		font-size: 12px;
		text-align: center;
	}
	
	.reportTable th,
	.reportTable td {
		border: 1px solid #000;
	}
	
	.table td, .table th {
		padding: 0.1rem;
		vertical-align: middle;
	}
	
	th, td, p, li {
		font-size: 12px !important;
	}
	
	.footer-section {
		display: none;
		position: fixed;
		bottom: 0;
		left: 43%;
		font-size: 7px !important;
		font-style: italic;
		text-align: center !important;
	}
	
	hr.custom-hr {
		border: 0;
		height: 1px;
		background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.75), rgba(0, 0, 0, 0));
		-webkit-print-color-adjust: exact;
	}
	
	@media print {
		@page {
			size: portrait;
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
			-webkit-font-smoothing: antialiased;
			page-break-after: always;
		}
		
		.main {
			margin: 10px;
		}
		
		tr {
			page-break-inside: avoid;
			page-break-after: auto
		}
		
		table {
			page-break-inside: auto
		}
		
		.main-section {
			padding: 5px 15px !important;
		}
		
		.footer-section {
			display: block !important;
		}
	}
</style>
<div class="reportHeader">
	<h2>The Faiyaz Limited</h2>
	<h5>769 (New), Shewrapara, Rokeya Shoroni, Mirpur, Dhaka-1216</h5>
</div>
<div class="reportName">
	<h3>Yearly Leave Report</h3>
</div>
<hr class="custom-hr">
<div class="main-section">
	<div class="bottom-section">
		<table class="reportTable">
			<thead>
			<tr>
				<th colspan="6">Date of: {{ isset($year) ? Carbon\Carbon::parse('first day of January '.$year)->format('d-M-Y') : '' }} To {{ isset($year) ? Carbon\Carbon::parse('last day of December '.$year)->format('d-M-Y') : '' }}</th>
			</tr>
			<tr>
				<th>SL. No.</th>
				<th>Unique ID</th>
				<th>Name</th>
				<th>Joining Date</th>
				<th>Department</th>
				<th>Total Leave</th>
			</tr>
			</thead>
			<tbody>
			@if($reports && $reports->count())
				@foreach($reports->sortBy('employeeOfficialInfo.unique_id') as $report)
					<tr>
						<td>{{ $loop->iteration }}</td>
						<td>{{ $report->employeeOfficialInfo->unique_id }}</td>
						<td>{{ $report->employeeOfficialInfo->employeeBasicInfo->screen_name }}</td>
						<td>{{ isset($report->employeeOfficialInfo->date_of_joining) ? date('d/m/Y', strtotime($report->employeeOfficialInfo->date_of_joining)) : '' }}</td>
						<td>{{ $report->employeeOfficialInfo->departmentDetails->name }}</td>
						<td>{{ $report->total_leave }}</td>
					</tr>
				@endforeach
			@else
				<tr>
					<th colspan="6">No Data</th>
				</tr>
			@endif
			</tbody>
		</table>
	</div>
</div>
<div class="footer-section">
	<p class="footer-text">HRKIT &copy; Skylark Soft Limited</p>
</div>