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
		font-size: 7px!important;
		font-style: italic;
		text-align: center!important;
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
			padding: 5px 15px!important;
		}

		.footer-section {
			display: block!important;
		}
	}
</style>
<div class="reportHeader">
	<h2>The Faiyaz Limited</h2>
	<h5>769 (New), Shewrapara, Rokeya Shoroni, Mirpur, Dhaka-1216</h5>
</div>
<div class="reportName">
	<h3>Leave Report (Individual)</h3>
</div>
<hr class="custom-hr">
<div class="main-section">
	<div class="top-section">
		<table class="basic-info-table">
			<tbody>
			<tr>
				<th colspan="2">
					Date of: {{ isset($from_date) ? date('d-M-Y', strtotime($from_date)) : "" }}
					To {{ isset($to_date) ? date('d-M-Y', strtotime($to_date)) : "" }}
				</th>
			</tr>
			<tr>
				<th>ID Number:</th>
				<td><span
							class="font-bold">{{ (isset($reports) && $reports->count()) ? $reports->first()->employeeOfficialInfo->unique_id : '' }}</span>
				</td>
			</tr>
			<tr>
				<th>Name:</th>
				<td>{{ (isset($reports) && $reports->count()) ? $reports->first()->employee->screen_name : '' }}</td>
			</tr>
			<tr>
				<th>Designation:</th>
				<td>{{ (isset($reports) && $reports->count()) ? $reports->first()->employeeOfficialInfo->designationDetails->name : '' }}</td>
			</tr>
			<tr>
				<th>Section:</th>
				<td>{{ (isset($reports) && $reports->count()) ? $reports->first()->employeeOfficialInfo->sectionDetails->name : '' }}</td>
			</tr>
			<tr>
				<th>Joining Date:</th>
				<td>{{ (isset($reports) && $reports->count()) ? ($reports->first()->employeeOfficialInfo->date_of_joining ? date('d/m/Y', strtotime($reports->first()->employeeOfficialInfo->date_of_joining)): '') : '' }}</td>
			</tr>
			</tbody>
		</table>
	</div>
	<div class="bottom-section">
		<table class="reportTable">
			<thead>
			<tr>
				<th>SL. No.</th>
				<th>Leave Date</th>
				<th>Leave Type</th>
			</tr>
			</thead>
			<tbody>
			@if($reports && $reports->count())
				@php
					$sl = 0;
				@endphp
				@foreach($reports->sortBy('type_id')->groupBy('type_id') as $reportByType)
					@foreach($reportByType as $report)
						<tr>
							<td>{!! ++$sl !!}</td>
							<td>{{ $report->leave_date ? date('d/m/Y', strtotime($report->leave_date)) : '' }}</td>
							<td>{{ $report->type->leaveType->name }}</td>
						</tr>
					@endforeach
					<tr>
						<th colspan="2">Total {{ $reportByType->first()->type->name }}</th>
						<th>({{ $reportByType->count() }})</th>
					</tr>
				@endforeach
				<tr>
					<th colspan="2">Total Leave</th>
					<th>({{ $reports->count() }})</th>
				</tr>
			@else
				<tr>
					<th colspan="3">No Data</th>
				</tr>
			@endif
			</tbody>
		</table>
	</div>
</div>
<div class="footer-section">
	<p class="footer-text">&copy; Skylark Soft Limited</p>
</div>
