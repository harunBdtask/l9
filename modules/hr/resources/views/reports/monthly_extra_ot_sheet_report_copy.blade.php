<style>
	@import url('https://fonts.maateen.me/solaiman-lipi/font.css');
	
	* {
		font-family: 'SolaimanLipi', sans-serif;
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
	
	.spacer {
		height: .5rem;
	}
	
	.vertical-align {
		font-size: 12px;
		font-weight: 700;
		text-orientation: sideways;
		-webkit-writing-mode: vertical-rl;
		-ms-writing-mode: tb-rl;
		writing-mode: vertical-rl;
	}
	
	.text-center {
		text-align: center;
	}
	
	.list-style-none {
		list-style: none;
	}
	
	.salary-head {
		min-width: 400px;
		display: inline-block;
	}
	
	th, td, p, li {
		font-size: 12px !important;
	}
	
	.clearfix {
		overflow: auto;
	}
	
	.clearfix::after {
		content: "";
		clear: both;
		display: table;
	}
	.page-break {
		page-break-after: auto;
	}
	.no-page-break {
		page-break-inside: auto;
		height: 40px;
	}
	
	@media print {
		@page {
			size: landscape;
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
			-webkit-font-smoothing: antialiased;
		}
		
		.spacer {
			height: 5px!important;
		}
		
		table tbody tr {
			page-break-after: always!important;
		}
		.no-page-break {
			page-break-inside:avoid !important;
		}
	}
</style>
<table class="reportTable">
	<thead>
	<tr>
		<th colspan="14">The Faiyaz Limited</th>
	</tr>
	<tr>
		<th colspan="14">769 (New), Shewrapara, Rokeya Shoroni, Mirpur, Dhaka-1216</th>
	</tr>
	<tr>
		<th colspan="14">Extra Overtime Sheet and Others {{ $pay_month ? 'For the month '. date('F-Y', strtotime($pay_month)): '' }}</th>
	</tr>
	<tr>
		<th rowspan="3">Sl No.</th>
		<th>Name</th>
		<th>Unique Id</th>
		<th rowspan="3">Gross Salary</th>
		<th rowspan="3">Basic</th>
		<th rowspan="3"><span class="vertical-align">OT Rate (Basic x 2)/ 208</span></th>
		<th rowspan="3"><span class="vertical-align">OT Hour</span></th>
		<th rowspan="3"><span class="vertical-align">OT Amount</span></th>
		<th rowspan="3"><span class="vertical-align">Night Hour</span></th>
		<th rowspan="3"><span class="vertical-align">Night Amount</span></th>
		<th rowspan="3"><span class="vertical-align">Tiffin</span></th>
		<th rowspan="3"><span class="vertical-align">Others Amounts</span></th>
		<th rowspan="3">Net Payable</th>
		<th rowspan="3" style="width: 100px;">Signature</th>
	</tr>
	<tr>
		<th>Designation</th>
		<th>Grade</th>
	</tr>
	<tr>
		<th>Joining Date</th>
		<th>Code</th>
	</tr>
	@if($reports && $reports->count())
		<tr>
			<th colspan="14">Section : {{ $reports->first()->employeeOfficialInfo->sectionDetails->name }}</th>
		</tr>
	@endif
	</thead>
	<tbody>
	@php
		$sub_total_net_payable = 0;
	@endphp
	@if($reports && $reports->count())
		@php
			$total_net_payable = 0;
		@endphp
		@foreach($reports->sortBy('userid') as $report)
			@php
				$extra_ot_amount = round(($report->total_regular_extra_ot_hour * $report->ot_rate), 3);
				$tiffin = 0;
				$other_amount = 0;
				$net_payable = $extra_ot_amount + $tiffin + $other_amount + $report->night_ot_amount;
				$total_net_payable += $net_payable;
				$sub_total_net_payable += $net_payable;
			@endphp
			<tr class="spacer"></tr>
			<tr class="no-page-break">
				<td rowspan="3">{{ $loop->iteration }}</td>
				<th style="font-size: 16px!important;">{{ $report->employeeOfficialInfo->employeeBasicInfo->screen_name }}</th>
				<th style="font-size: 16px!important;">{{ $report->userid }}</th>
				<td rowspan="3">{{ $report->gross_salary }}</td>
				<td rowspan="3">{{ $report->basic_salary }}</td>
				<td rowspan="3">{{ $report->ot_rate }}</td>
				<td rowspan="3">{{ $report->total_regular_extra_ot_hour }}</td>
				<td rowspan="3">{{ $extra_ot_amount }}</td>
				<td rowspan="3">{{ $report->night_ot_hour }}</td>
				<td rowspan="3">{{ $report->night_ot_amount }}</td>
				<td rowspan="3">{{ $tiffin }}</td>
				<td rowspan="3">{{ $other_amount }}</td>
				<th rowspan="3" style="font-size: 16px!important;">{{ $net_payable }}</th>
				<td rowspan="3">&nbsp;</td>
			</tr>
			<tr class="no-page-break">
				<td>{{ $report->employeeOfficialInfo->designationDetails->name }}</td>
				<td>{{ $report->employeeOfficialInfo->grade->name }}</td>
			</tr>
			<tr class="no-page-break">
				<td>{{ $report->employeeOfficialInfo->date_of_joining ? \Carbon\Carbon::parse($report->employeeOfficialInfo->date_of_joining)->format('d/m/Y'): '' }}</td>
				<td>{{ $report->employeeOfficialInfo->code }}</td>
			</tr>
		@endforeach
		<tr>
			<th colspan="12">Sub Total</th>
			<th>{{ $sub_total_net_payable }}</th>
			<th>&nbsp;</th>
		</tr>
	@else
		<tr>
			<th colspan="14">No Data</th>
		</tr>
	@endif
	</tbody>
</table>