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
		height: 40px;
	}
	
	.printViewOnly {
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
		
		.printViewOnly {
			display: block !important;
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
<table class="reportTable">
	<thead>
	<tr>
		<th colspan="19">The Faiyaz Limited<br>769 (New), Shewrapara, Rokeya Shoroni, Mirpur, Dhaka-1216</th>
	</tr>
	<tr>
		<th colspan="19">Pay Sheet {{ $pay_month ? 'For the month '. date('F-Y', strtotime($pay_month)): '' }}</th>
	</tr>
	<tr>
		<th rowspan="3">Sl No.</th>
		<th>Name</th>
		<th rowspan="2">Unique Id</th>
		<th rowspan="3">Basic</th>
		<th rowspan="3"><span class="vertical-align">House Rent 50&#37;</span></th>
		<th rowspan="3"><span class="vertical-align">Medical Allowance 10&#37;</span></th>
		<th rowspan="3"><span class="vertical-align">Conveyance Allowance</span></th>
		<th rowspan="3"><span class="vertical-align">Mobile Allowance</span></th>
		<th rowspan="3"><span class="vertical-align">Special Pay</span></th>
		<th rowspan="3">Gross</th>
		<th>Month Days</th>
		<th>Present</th>
		<th rowspan="2">Pay Holi</th>
		<th rowspan="3">Revenue</th>
		<th rowspan="2">Conveyance Use</th>
		<th rowspan="3"><span class="vertical-align">PF Amount Deducted</span></th>
		<th rowspan="3"><span class="vertical-align">Tax Deducted At Source</span></th>
		<th rowspan="3">Net Payable</th>
		<th rowspan="3" style="width: 100px;">Stamp</th>
	</tr>
	<tr>
		<th>Designation</th>
		<th>Working Days</th>
		<th>Leave</th>
	</tr>
	<tr>
		<th>Joining Date</th>
		<th>Code</th>
		<th>Holidays</th>
		<th>Absent</th>
		<th>Payable Days</th>
		<th>Amenity Use</th>
	</tr>
	@if($reports && $reports->count())
		<tr>
			<th colspan="19">Section : {{ $reports->first()->employeeOfficialInfo->sectionDetails->name }} Staff</th>
		</tr>
	@endif
	</thead>
	<tbody>
	@if($reports && $reports->count())
		@php
			$grand_total_net_payable = 0;
			$sub_total_net_payable = 0;
			$page_no = 0;
			$total_page_no = ceil($reports->count() / 5);
		@endphp
		@foreach($reports->sortBy('userid') as $report)
			@php
				$special_pay = 0;
				$conveyance_use = 0;
				$amenity_use = 0;
				$pf_amount_deducted = 0;
				$tax_deducted_at_source = 0;
				$sub_total_net_payable += $report->total_payable_amount;
				$grand_total_net_payable += $report->total_payable_amount;
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
					<table class="innerTable-two">
						<tr>
							<td><b>{{ $report->userid }}</b></td>
						</tr>
						<tr>
							<td>{{ $report->employeeOfficialInfo->code }}</td>
						</tr>
					</table>
				</td>
				<td>{{ $report->basic_salary }}</td>
				<td>{{ $report->house_rent }}</td>
				<td>{{ $report->medical_allowance }}</td>
				<td>{{ $report->transport_allowance }}</td>
				<td>{{ $report->employeeOfficialInfo->salary->mobile_allowence }}</td>
				<td>{{ $special_pay }}</td>
				<td>{{ $report->gross_salary }}</td>
				<td>
					<table class="innerTable">
						<tr>
							<td><b>{{ $report->total_working_day + $report->total_holiday }}</b></td>
						</tr>
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
							<td><b>{{ $report->total_present_day }}</b></td>
						</tr>
						<tr>
							<td>{{ $report->total_leave }}</td>
						</tr>
						<tr>
							<td>{{ $report->total_absent_day }}</td>
						</tr>
					</table>
				</td>
				<td>
					<table class="innerTable-two">
						<tr>
							<td><b>{{ $report->total_holiday }}</b></td>
						</tr>
						<tr>
							<td>{{ $report->total_payable_days }}</td>
						</tr>
					</table>
				</td>
				<td>{{ $report->revenue_stamp }}</td>
				<td>
					<table class="innerTable-two">
						<tr>
							<td><b>{{ $conveyance_use }}</b></td>
						</tr>
						<tr>
							<td>{{ $amenity_use }}</td>
						</tr>
					</table>
				</td>
				<td>{{ $pf_amount_deducted }}</td>
				<td>{{ $tax_deducted_at_source }}</td>
				<td><b>{{ $report->total_payable_amount }}</b></td>
				<td>&nbsp;</td>
			</tr>
			
			@if($loop->iteration > 0 && $loop->iteration % 5 == 0)
				<tr >
					<td colspan="17"><b>Sub Total</b></td>
					<td><b>{{ $sub_total_net_payable }}</b></td>
					<td><span class="printViewOnly">{!! ($loop->iteration > 1 && $loop->iteration % 5 == 0) ? 'Page-'.++$page_no.' /of '. $total_page_no : '' !!}</span></td>
				</tr>
				@if($loop->last)
					<tr>
						<td colspan="17" class="no-border"><b>Grand Total</b></td>
						<td class="no-border"><b>{{ $grand_total_net_payable }}</b></td>
						<td class="no-border"></td>
					</tr>
				@endif
				<tr class="spacer"></tr>
				<tr style="margin-top: 20px">
					<td colspan="3" class="sign">Prepared BY</td>
					<td colspan="7" class="sign">Checked BY</td>
					<td colspan="5" class="sign">Authenticated BY</td>
					<td colspan="4" class="sign">Approved BY</td>
				</tr>
				@php
					$sub_total_net_payable = 0;
				@endphp
			@endif
			@if($loop->last && $loop->iteration % 5 != 0)
				<tr>
					<td colspan="17"><b>Sub Total</b></td>
					<td><b>{{ $sub_total_net_payable }}</b></td>
					<td><span class="printViewOnly">{!! ($loop->iteration > 1 && $loop->iteration % 5 == 0) ? 'Page-'.++$page_no.' /of '. $total_page_no : '' !!}</span></td>
				</tr>
				<tr>
					<td colspan="17" class="no-border"><b>Grand Total</b></td>
					<td class="no-border"><b>{{ $grand_total_net_payable }}</b></td>
					<td class="no-border"></td>
				</tr>
				<tr class="spacer"></tr>
				<tr style="margin-top: 20px">
					<td colspan="3" class="sign">Prepared BY</td>
					<td colspan="7" class="sign">Checked BY</td>
					<td colspan="5" class="sign">Authenticated BY</td>
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
			<th colspan="19">No Data</th>
		</tr>
	@endif
	</tbody>
</table>