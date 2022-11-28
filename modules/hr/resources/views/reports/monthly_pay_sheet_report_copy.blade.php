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
		height: 35px;
	}
	.pageNo {
		display: none;
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
			height: 18px!important;
		}
		
		table tbody tr {
			page-break-after: always!important;
		}
		.no-page-break {
			page-break-inside:avoid !important;
		}
		.pageNo {
			display: block!important;
		}
	}
</style>
<table class="reportTable">
	<thead>
	<tr>
		<th colspan="17">The Faiyaz Limited</th>
	</tr>
	<tr>
		<th colspan="17">769 (New), Shewrapara, Rokeya Shoroni, Mirpur, Dhaka-1216</th>
	</tr>
	<tr>
		<th colspan="17">Pay Sheet {{ $pay_month ? 'For the month '. date('F-Y', strtotime($pay_month)): '' }}</th>
	</tr>
	<tr>
		<th rowspan="3">Sl No.</th>
		<th>Name</th>
		<th>Unique Id</th>
		<th>Worker/Staff</th>
		<th rowspan="2">Working Days</th>
		<th>Present</th>
		<th rowspan="3"><span class="vertical-align">House Rent 50&#37;</span></th>
		<th rowspan="3"><span class="vertical-align">Medical Allowance</span></th>
		<th rowspan="3"><span class="vertical-align">Transport Allowance</span></th>
		<th rowspan="3"><span class="vertical-align">Food Allowance</span></th>
		<th rowspan="3"><span class="vertical-align">Att. Bonus</span></th>
		<th style="width: 25px;">Att. Deduction</th>
		<th rowspan="2">OT Rate</th>
		<th rowspan="3"><span class="vertical-align">OT Amount</span></th>
		<th rowspan="3">Net Payable</th>
		<th rowspan="3" style="width: 100px;">Stamp</th>
	</tr>
	<tr>
		<th>Designation</th>
		<th>Grade</th>
		<th>Gross</th>
		<th>Leave</th>
		<th>Bonus Deduction</th>
	</tr>
	<tr>
		<th>Joining Date</th>
		<th>Code</th>
		<th>Basic</th>
		<th>Holidays</th>
		<th>Absent</th>
		<th>Revenue</th>
		<th>OT Hour</th>
	</tr>
	@if($reports && $reports->count())
		<tr>
			<th colspan="17">Section : {{ $reports->first()->employeeOfficialInfo->sectionDetails->name }}</th>
		</tr>
	@endif
	</thead>
	<tbody>
	@if($reports && $reports->count())
		@php
			$sub_total_net_payable = 0;
			$page_no = 0;
		@endphp
		@foreach($reports->sortBy('userid') as $report)
			@php
				$sub_total_net_payable += $report->total_payable_amount;
			@endphp
			<tr class="spacer"><td colspan="17" style="border: none!important;"><span class="pageNo">{!! ($loop->iteration > 1 && $loop->iteration % 5 == 1) ? 'Page-'.++$page_no : '' !!}</span></td></tr>
			<tr class="no-page-break">
				<td rowspan="3">{{ $loop->iteration }}</td>
				<td><span style="font-weight:bold;">{{ $report->employeeOfficialInfo->employeeBasicInfo->screen_name }}</span></td>
				<td><span style="font-weight:bold;">{{ $report->userid }}</span></td>
				<td>{{ ucfirst($report->employeeOfficialInfo->type) }}</td>
				<td rowspan="2">{{ $report->total_working_day }}</td>
				<td>{{ $report->total_present_day }}</td>
				<td rowspan="3">{{ $report->house_rent }}</td>
				<td rowspan="3">{{ $report->medical_allowance }}</td>
				<td rowspan="3">{{ $report->transport_allowance }}</td>
				<td rowspan="3">{{ $report->food_allowance }}</td>
				<td rowspan="3">{{ $report->attendance_bonus }}</td>
				<td>{{ $report->absent_deduction }}</td>
				<td rowspan="2">{{ $report->ot_rate }}</td>
				<td rowspan="3">{{ $report->total_ot_amount }}</td>
				<td rowspan="3">{{ $report->total_payable_amount }}</td>
				<td rowspan="3">&nbsp;</td>
			</tr>
			<tr class="no-page-break">
				<td>{{ $report->employeeOfficialInfo->designationDetails->name }}</td>
				<td>{{ $report->employeeOfficialInfo->grade->name }}</td>
				<td>{{ $report->gross_salary }}</td>
				<td>{{ $report->total_leave }}</td>
				<td>{{ $report->attendance_bonus_deduction }}</td>
			</tr>
			<tr class="no-page-break">
				<td>{{ $report->employeeOfficialInfo->date_of_joining ? \Carbon\Carbon::parse($report->employeeOfficialInfo->date_of_joining)->format('d/m/Y'): '' }}</td>
				<td>{{ $report->employeeOfficialInfo->code }}</td>
				<td>{{ $report->basic_salary }}</td>
				<td>{{ $report->total_holiday }}</td>
				<td>{{ $report->total_absent_day }}</td>
				<td>{{ $report->revenue_stamp }}</td>
				<td>{{ $report->ot_hour }}</td>
			</tr>
		@endforeach
		<tr>
			<th colspan="14">Sub Total</th>
			<th>{{ $sub_total_net_payable }}</th>
			<th>&nbsp;</th>
		</tr>
		<tr class="spacer"><td colspan="17" style="border: none!important;"><span class="pageNo">{!! 'Page-'.$page_no++ !!}</span></td></tr>
	@else
		<tr>
			<th colspan="17">No Data</th>
		</tr>
	@endif
	</tbody>
</table>