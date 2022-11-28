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
	
	@media print {
		@page {
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
			-webkit-font-smoothing: antialiased;
		}
		
		.sign {
			border: none !important;
			width: 25% !important;
		}
		
		.spacer {
			height: 3px !important;
		}
		
		.pageNo {
			display: block !important;
		}
		
		table tbody tr {
			page-break-after: always !important;
		}
		.footer {
			page-break-after: always !important;
		}
		
		.no-page-break {
			page-break-inside: avoid !important;
		}
		
		.main-content {
			margin: 0px 15px!important;
		}
	}
</style>
<div class="text-center">
	<h2>The Faiyaz Limited</h2>
	<h5>769 (New), Shewrapara, Rokeya Shoroni, Mirpur, Dhaka-1216</h5>
	<h5>Payment Summary of Month {{ date('F-Y', strtotime($date)) }}</h5>
</div>
<div class="main-content">
	<h5>Staff Data</h5>
	<table class="reportTable">
		<thead>
		<tr>
			<th>Department</th>
			<th>Section</th>
			<th>Total Person</th>
			<th>Tot. Sal. Amount</th>
			<th>Main Hours</th>
			<th>Main OT Amount</th>
			<th>Extra Hours</th>
			<th>Extra OT Amount</th>
			<th>Tiffin Amount</th>
			<th>Festival Amount</th>
			<th>PF</th>
		</tr>
		</thead>
		<tbody>
		@php
			$total_staff = 0;
			$total_staff_salary = 0;
			$main_staff_ot_salary = 0;
		@endphp
		@foreach($regular_reports_data->where('employeeOfficialInfo.type','staff')->groupBy('employeeOfficialInfo.section_id') as $val)
			@php
				$total_pay_amount = $val->sum('total_payable_amount');
				$total_staff += $val->count();
				$total_staff_salary += round($total_pay_amount);
				$main_staff_ot_salary += round($val->sum('total_ot_amount'));
			@endphp
			<tr>
				<td>{{$val->first()->employeeOfficialInfo->departmentDetails->name}}</td>
				<td>{{$val->first()->employeeOfficialInfo->sectionDetails->name}}</td>
				<td>{{$val->count()}}</td>
				<td>{{round($total_pay_amount)}}</td>
				<td>{{$val->sum('ot_hour')}}</td>
				<td>{{round($val->sum('total_ot_amount'))}}</td>
				<td></td>
				<td></td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
			</tr>
		@endforeach
		<tr>
			<td></td>
			<td></td>
			<td><b>{{$total_staff}}</b></td>
			<td><b>{{$total_staff_salary}}</b></td>
			<td></td>
			<td><b>{{$main_staff_ot_salary}}</b></td>
			<td colspan="5"></td>
		</tr>
		</tbody>
	</table>
	
	<h5>Worker Data</h5>
	<table class="reportTable">
		<thead>
		<tr>
			<th>Department</th>
			<th>Section</th>
			<th>Total Person</th>
			<th>Tot. Sal. Amount</th>
			<th>Main Hours</th>
			<th>Main OT Amount</th>
			<th>Extra Hours</th>
			<th>Extra OT Amount</th>
			<th>Tiffin Amount</th>
			<th>Festival Amount</th>
			<th>PF</th>
		</tr>
		</thead>
		<tbody>
		@php
			$total_worker = 0;
			$total_worker_salary = 0;
			$main_staff_ot_salary = 0;
			$total_ot_hours = 0;
			$main_ot_amount = 0;
			$extra_ot_amount = 0;
		@endphp
		@foreach($regular_reports_data->where('employeeOfficialInfo.type','worker')->groupBy('employeeOfficialInfo.section_id') as $val)
			@php
				$total_pay_amount = $val->sum('total_payable_amount');
				$total_worker += $val->count();
				$total_worker_salary +=round($total_pay_amount);
				$total_ot_hours += $val->sum('ot_hour');
			$main_ot_amount += round($val->sum('total_ot_amount'));
			$extra_ot_amount += round($val->sum('extra_ot_amount'));
			@endphp
			<tr>
				<td>{{$val->first()->employeeOfficialInfo->departmentDetails->name}}</td>
				<td>{{$val->first()->employeeOfficialInfo->sectionDetails->name}}</td>
				<td>{{$val->count()}}</td>
				<td>{{round($total_pay_amount)}}</td>
				<td>{{$val->sum('ot_hour')}}</td>
				<td>{{round($val->sum('total_ot_amount'))}}</td>
				<td>{{$val->sum('total_regular_extra_ot_hour')}}</td>
				<td>{{round($val->sum('extra_ot_amount'))}}</td>
				<td>0</td>
				<td>0</td>
				<td>0</td>
			</tr>
		@endforeach
		<tr>
			<td></td>
			<td></td>
			<td><b>{{$total_worker}}</b></td>
			<td><b>{{$total_worker_salary}}</b></td>
			<td><b>{{$total_ot_hours}}</b></td>
			<td><b>{{$main_ot_amount}}</b></td>
			<td></td>
			<td><b>{{$extra_ot_amount}}</b></td>
			<td colspan="4"></td>
		</tr>
		</tbody>
	</table>
	<div class="footer">
		<p><b>Total Person : {{$total_staff + $total_worker}}</b></p>
		<p><b>Total Salary Amount
				: {{ $total_staff_salary + $total_worker_salary + $extra_ot_amount }}</b>
		</p>
	</div>
</div>

