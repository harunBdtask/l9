<style>
	@import url('https://fonts.maateen.me/solaiman-lipi/font.css');
	
	* {
		font-family: 'SolaimanLipi', sans-serif;
	}
	
	.innerTable {
		width: 100% !important;
		border: none !important;
	}
	
	.innerTable tr td {
		border: none !important;
	}
	
	.innerTable tr:nth-child(2) td {
		border-top: 1px solid #000 !important;
		border-bottom: 1px solid #000 !important;
	}
	
	.innerTable-two {
		width: 100% !important;
	}
	
	.innerTable-two tr:first-child td {
		border: none !important;
	}
	
	.innerTable-two tr:nth-child(2) td {
		border: none !important;
		border-top: 1px solid #000 !important;
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
			height: 18px !important;
		}
		
		table tbody tr {
			page-break-after: always !important;
		}
		
		.no-page-break {
			page-break-inside: avoid !important;
		}
		
		.pageNo {
			display: block !important;
		}
		
		.table-container {
			margin-top: 2px !important;
		}
		
		.sign {
			border:none !important;
			width: 25% !important;
		}
		
		.no-border {
			border: none!important;
		}
	}
</style>
<div class="">
	<table class="reportTable">
		<thead>
		<tr class="top-head">
			<th colspan="17" style="padding-top: 5px !important;">The Faiyaz Limited</th>
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
				$grand_total_net_payable = 0;
				$sub_total_net_payable = 0;
				$page_no = 0;
			@endphp
			@foreach($reports->sortBy('userid') as $report)
				@php
					$sub_total_net_payable += $report->total_payable_amount;
					$grand_total_net_payable += $report->total_payable_amount;
					$total_page_no = ceil($reports->count() / 5);
				@endphp

				<tr style="height: 90px;">
					<td>{{ $loop->iteration }}</td>
					<td>
						<table class="innerTable">
							<tr>
								<td><b>{{ $report->employeeOfficialInfo->employeeBasicInfo->screen_name }}</b></td>
							</tr>
							<tr>
								<td>{{ $report->employeeOfficialInfo->designationDetails->name }}</td>
							</tr>
							<tr>
								<td>{{ $report->employeeOfficialInfo->date_of_joining ? \Carbon\Carbon::parse($report->employeeOfficialInfo->date_of_joining)->format('d/m/Y'): '' }}</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="innerTable">
							<tr>
								<td><b>{{ $report->userid }}</b></td>
							</tr>
							<tr>
								<td>{{ $report->employeeOfficialInfo->grade->name }}</td>
							</tr>
							<tr>
								<td>{{ $report->employeeOfficialInfo->code }}</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="innerTable">
							<tr>
								<td>{{ ucfirst($report->employeeOfficialInfo->type) }}</td>
							</tr>
							<tr>
								<td>{{ $report->gross_salary }}</td>
							</tr>
							<tr>
								<td>{{ $report->basic_salary }}</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="innerTable-two">
							<tr>
								<td>{{ $report->total_working_day }}</td>
							</tr>
							<tr>
								<td>{{ $report->total_holiday }}</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="innerTable">
							<tr>
								<td>{{ $report->total_present_day }}</td>
							</tr>
							<tr>
								<td>{{ $report->total_leave }}</td>
							</tr>
							<tr>
								<td>{{ $report->total_absent_day }}</td>
							</tr>
						</table>
					</td>
					<td>{{ $report->house_rent }}</td>
					<td>{{ $report->medical_allowance }}</td>
					<td>{{ $report->transport_allowance }}</td>
					<td>{{ $report->food_allowance }}</td>
					<td>{{ $report->attendance_bonus }}</td>
					<td>
						<table class="innerTable">
							<tr>
								<td>{{ $report->absent_deduction }}</td>
							</tr>
							<tr>
								<td>{{ $report->attendance_bonus_deduction }}</td>
							</tr>
							<tr>
								<td>{{ $report->revenue_stamp }}</td>
							</tr>
						</table>
					</td>
					<td>
						<table class="innerTable-two">
							<tr>
								<td>{{ $report->ot_rate }}</td>
							</tr>
							<tr>
								<td>{{ $report->ot_hour }}</td>
							</tr>
						</table>
					</td>
					<td>{{ $report->total_ot_amount }}</td>
					<td>{{ $report->total_payable_amount }}</td>
					<td>&nbsp;</td>
				</tr>
				
				@if($loop->iteration > 0 && $loop->iteration % 5 == 0)
					<tr class="">
						<td colspan="14"><b>Sub Total</b></td>
						<td><b>{{ $sub_total_net_payable }}</b></td>
						<td><span class="pageNo">{!! ($loop->iteration > 1 && $loop->iteration % 5 == 0) ? 'Page-'.++$page_no.' /of '. $total_page_no : '' !!}</span></td>
					</tr>
					@if($loop->last)
					<tr>
						<td colspan="14" class="no-border"><b>Grand Total</b></td>
						<td class="no-border"><b>{{ $grand_total_net_payable }}</b></td>
						<td class="no-border"></td>
					</tr>
					@endif
					<tr class="spacer"></tr>
					<tr style="margin-top: 20px">
						<td colspan="4" class="sign">Prepared BY</td>
						<td colspan="4" class="sign">Checked BY</td>
						<td colspan="4" class="sign">Authenticated BY</td>
						<td colspan="4" class="sign">Approved BY</td>
					</tr>
					@php
						$sub_total_net_payable = 0;
					@endphp
				@endif
				@if($loop->last && $loop->iteration % 5 != 0)
					<tr class="">
						<td colspan="14"><b>Sub Total</b></td>
						<td><b>{{ $sub_total_net_payable }}</b></td>
						<td><span class="pageNo">{!! ($loop->iteration > 1 && $loop->iteration % 5 == 0) ? 'Page-'.++$page_no.' /of '. $total_page_no : '' !!}</span></td>
					</tr>
					<tr>
						<td colspan="14" class="no-border"><b>Grand Total</b></td>
						<td class="no-border"><b>{{ $grand_total_net_payable }}</b></td>
						<td class="no-border"></td>
					</tr>
					<tr class="spacer"></tr>
					<tr style="margin-top: 20px">
						<td colspan="4" class="sign">Prepared BY</td>
						<td colspan="4" class="sign">Checked BY</td>
						<td colspan="4" class="sign">Authenticated BY</td>
						<td colspan="4" class="sign">Approved BY</td>
					</tr>
					@php
						$sub_total_net_payable = 0;
					@endphp
				@endif
				<tr class="spacer"></tr>
			@endforeach
		@else
			<tr>
				<th colspan="17">No Data</th>
			</tr>
		@endif
		</tbody>
	</table>
</div>