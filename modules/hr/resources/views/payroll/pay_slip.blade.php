<style>
	@import url('https://fonts.maateen.me/solaiman-lipi/font.css');

	* {
		font-family: 'SolaimanLipi', sans-serif;
	}

	.row {
		margin-bottom: 1rem;
	}

	.basic-info-table {
		width: 100%;
		max-width: 100%;
		border-collapse: collapse;
	}

	.paymonth {
		font-size: 14px;
		font-weight: 700;
	}

	.font-bold {
		font-size: 13px;
		font-weight: 700;
	}

	p {
		margin-bottom: 0 !important;
	}

	.sl-no {
		width: 32% !important;
		text-align: left !important;
	}

	.company-name {
		width: 48% !important;
		text-align: left !important;
		vertical-align: top;
	}

	.print-copy {
		width: 20% !important;
		/*text-align: right !important;*/
	}

	.company-name-text {
		font-weight: 700;
		font-size: 17px;
	}

	.address {
		text-align: center;
		margin: 0 !important;
	}

	.pay-slip-heading {
		text-align: center;
		margin: 0 !important;
		font-weight: 700;
		text-decoration: underline;
	}

	.divTable {
		display: table;
		width: 100%;
		margin-bottom: 0.3rem !important;
	}

	.divTableRow {
		display: table-row;
	}

	.divTableHeading {
		background-color: #EEE;
		display: table-header-group;
	}

	.divTableCell {
		border: 1px solid #1d1d1d;
		border-collapse: collapse;
		display: table-cell;
		padding: 1px 10px;
	}

	.basicInfoTableCell {
		border: none !important;
		border-collapse: collapse;
		display: table-cell;
		padding: 1px 2px;
	}

	.basicInfoTableHead {
		border: none !important;
		border-collapse: collapse;
		display: table-cell;
		padding: 1px 2px;
		font-weight: bold;
	}

	.divTableHead {
		border: 1px solid #1d1d1d;
		border-collapse: collapse;
		display: table-cell;
		padding: 1px 10px;
		font-weight: bold;
	}

	.divTableHeading {
		background-color: #EEE;
		display: table-header-group;
		font-weight: bold;
	}

	.divTableFoot {
		background-color: #EEE;
		display: table-footer-group;
		font-weight: bold;
	}

	.divTableBody {
		display: table-row-group;
	}

	.signature {
		width: 50% !important;
		margin-left: 46% !important;
	}

	@media print {
		@page {
			size: landscape;
			-webkit-transform: rotate(-90deg);
			-moz-transform: rotate(-90deg);
			filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);
			-webkit-font-smoothing: antialiased;
		}

		.row {
			height: 383px !important;
			border: 1px solid white;
			margin-top: 6px;
			margin-left: 3px;
			margin-bottom: 0 !important;
			-webkit-print-color-adjust: exact;
		}

		.row:nth-child(odd) {
			page-break-after: always;
		}

		p {
			margin: 0 !important;
		}

		.divTable {
			margin-bottom: 3px;
		}

		.divCustomTable {
			margin-bottom: 12px !important;
		}

		.divTableHead {
			font-size: 12px !important;
            padding: 0 0 0 4px !important;
            height: 12px !important;
			text-align: center;
		}

		.divTableCell {
			font-size: 12px !important;
            padding: 0 0 0 4px !important;
            height: 12px !important;
			text-align: center;
		}

		.basicInfoTableHead {
			font-size: 12px !important;
            padding: 0 0 0 4px !important;
            height: 12px !important;
		}

		.basicInfoTableCell {
			font-size: 12px !important;
            padding: 0 0 0 4px !important;
            height: 12px !important;
		}

		.top-section {
			height: 18px !important;
			padding: 0 !important;
		}

		.address {
			height: 18px !important;
			padding: 0 !important;
		}

		.pay-slip-heading {
			height: 16px !important;
			padding: 0 !important;
			margin-bottom: 12px !important;
		}

		.divTableRow {
			height: 12px !important;
		}
	}
</style>
@if($monthly_payment_summaries && $monthly_payment_summaries->count())
	@foreach($monthly_payment_summaries as $monthly_payment_summary)
		@php
			$company_name = factoryName();
			$address = factoryAddress();
			$pay_month = $monthly_payment_summary->pay_month;
			$date = Carbon\Carbon::parse($pay_month);
			$month_bn = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bnMonth($date->copy()->startOfMonth()->format('F'));
			$year_bn = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($date->copy()->startOfMonth()->format('Y'));
			$bn_pay_month = '<span class="paymonth">'.$month_bn.'-</span>'.$year_bn;
			$start_of_month = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($date->copy()->startOfMonth()->format('d/m/Y'));
			$end_of_month = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($date->copy()->endOfMonth()->format('d/m/Y'));
			$department_bn = ($monthly_payment_summary->employeeOfficialInfo->departmentDetails->name ?? '').' '.($monthly_payment_summary->employeeOfficialInfo->departmentDetails->name_bn ?? '');
			$section_bn = ($monthly_payment_summary->employeeOfficialInfo->sectionDetails->name ?? '').' '.($monthly_payment_summary->employeeOfficialInfo->sectionDetails->name_bn ?? '');
			$employee_name = '<span class="font-bold">'.($monthly_payment_summary->employeeOfficialInfo->employeeBasicInfo->name_bn ?? '').'</span>';
			$designation_bn = $monthly_payment_summary->employeeOfficialInfo->designationDetails->name_bn ?? '';
			$grade_bn = $monthly_payment_summary->employeeOfficialInfo->grade->name_bn ?? '';
			$unique_id = '<span class="font-bold">'.\SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->employeeOfficialInfo->unique_id ?? '').'</span>';
			$month_woking_days = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_working_day);
			$monthly_attendance = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_present_day);
			$monthly_absent = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_absent_day);
			$total_leaves = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_leave);
			$total_holidays = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_holiday);
			$total_payable_days = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_payable_days);
			$basic_salary = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->basic_salary);
			$house_rent = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->house_rent);
			$medical_allowance = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->medical_allowance);
			$transport_allowance = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->transport_allowance);
			$food_allowance = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->food_allowance);
			$gross_salary = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->gross_salary);
			$attendance_bonus = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->attendance_bonus);
			$ot_hour = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->ot_hour);
			$ot_rate = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->ot_rate);
			$total_ot_amount = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_ot_amount);
			$absent_deduction = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->absent_deduction);
			$attendance_bonus_deduction = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->attendance_bonus_deduction);
			$revenue_stamp = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->revenue_stamp);
			$total_payable_amount = \SkylarkSoft\GoRMG\HR\Helpers\BanglaConverter::en2bn($monthly_payment_summary->total_payable_amount);
		@endphp
		<div class="row" style="">
			<div class="col-md-6">
				<div class="top-section d-flex justify-content-between">
					<div class="sl-no">
						<span>ক্রমিক নংঃ {{ str_pad($monthly_payment_summary->id, 8, 0, STR_PAD_LEFT) }}</span>
					</div>
					<div class="company-name">
						<span class="company-name-text">{{ $company_name }}</span>
					</div>
					<div class="print-copy">
						<span>অফিস কপি</span>
					</div>
				</div>
				<div class="address">
					<p>{{ $address }}</p>
				</div>
				<div class="pay-slip-heading">
					<p>বেতন ভাতা ও অতিরিক্ত কাজের মুজুরী পরিশোধ <span class="text-danger">(Trial Basis)</span></p>
				</div>
				<div class="divTable ">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="basicInfoTableHead" style="width: 14%;">মাসের নাম</div>
							<div class="basicInfoTableCell" style="width: 86%;">{!! $bn_pay_month !!} ({{ $start_of_month }}
								হইতে {{ $end_of_month }} তারিখ পর্যন্ত)
							</div>
						</div>
					</div>
				</div>
				<div class="divTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="basicInfoTableHead">বিভাগঃ</div>
							<div class="basicInfoTableCell">{{ $department_bn }}</div>
                            <div class="basicInfoTableHead">সেকশনঃ</div>
                            <div class="basicInfoTableCell">{{ $section_bn }}</div>
						</div>
						<div class="divTableRow divCustomTable">
							<div class="basicInfoTableHead">নামঃ</div>
							<div class="basicInfoTableCell">{!! $employee_name !!}</div>
							<div class="basicInfoTableHead">পদবীঃ</div>
							<div class="basicInfoTableCell">{{ $designation_bn }}</div>
							<div class="basicInfoTableHead">গ্রেডঃ</div>
							<div class="basicInfoTableCell">{{ $grade_bn }}</div>
						</div>
						<div class="divTableRow">
							<div class="basicInfoTableHead">আই. ডিঃ</div>
							<div class="basicInfoTableCell">{!! $unique_id !!}</div>
							<div class="basicInfoTableHead">মাসের কর্ম দিবসঃ</div>
							<div class="basicInfoTableCell">{{ $month_woking_days }}</div>
							<div class="basicInfoTableHead">উপস্থিতিঃ</div>
							<div class="basicInfoTableCell">{{ $monthly_attendance }}</div>
						</div>
						<div class="divTableRow">
							<div class="basicInfoTableHead">মোট ছুটিঃ</div>
							<div class="basicInfoTableCell">{{ $total_leaves }}</div>
							<div class="basicInfoTableHead">মোট হলিডেঃ</div>
							<div class="basicInfoTableCell">{{ $total_holidays }}</div>
							<div class="basicInfoTableHead">অনুপস্থিতঃ </div>
							<div class="basicInfoTableCell">{{ $monthly_absent }}</div>
						</div>
					</div>
				</div>

				<div class="divTable divCustomTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableHead">মুল বেতন</div>
							<div class="divTableHead">বাসা ভাড়া</div>
							<div class="divTableHead">চিকিৎসা</div>
							<div class="divTableHead">যাতায়াত</div>
							<div class="divTableHead">খাদ্য ভাতা</div>
							<div class="divTableHead">মোট বেতন</div>
							<div class="divTableHead">হাজিরা বোনাস</div>
						</div>
						<div class="divTableRow">
							<div class="divTableCell">{{ $basic_salary }}</div>
							<div class="divTableCell">{{ $house_rent }}</div>
							<div class="divTableCell">{{ $medical_allowance }}</div>
							<div class="divTableCell">{{ $transport_allowance }}</div>
							<div class="divTableCell">{{ $food_allowance }}</div>
							<div class="divTableCell">{{ $gross_salary }}</div>
							<div class="divTableCell">{{ $attendance_bonus }}</div>
						</div>
					</div>
				</div>

				<div class="divTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableHead">অতিঃ কাজের ঘণ্টা</div>
							<div class="divTableHead">অঃকাঃ রেট</div>
							<div class="divTableHead">অতিঃ কাজের টাকা</div>
							<div class="divTableHead">কর্তন {{ $monthly_absent }} দিন (অনুপস্থিতি)</div>
							<div class="divTableHead">হাজিরা বোনাস কর্তন</div>
							<div class="divTableHead">প্রাপ্য টাকা</div>
						</div>
						<div class="divTableRow">
							<div class="divTableCell">{{ $ot_hour }}</div>
							<div class="divTableCell">{{ $ot_rate }}</div>
							<div class="divTableCell">{{ $total_ot_amount }}</div>
							<div class="divTableCell">{{ $absent_deduction }}</div>
							<div class="divTableCell">{{ $attendance_bonus_deduction }}</div>
							<div class="divTableCell">{{ $total_payable_amount }} ৳</div>
						</div>
					</div>
				</div>
				<div class="divTable signature">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableHead" style="width: 33%;">শ্রমিকের স্বাক্ষর</div>
							<div class="divTableCell" style="width: 67%;">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<div class="top-section d-flex justify-content-between">
					<div class="sl-no">
						<span>ক্রমিক নংঃ {{ str_pad($monthly_payment_summary->id, 8, 0, STR_PAD_LEFT) }}</span>
					</div>
					<div class="company-name">
						<span class="company-name-text">{{ $company_name }}</span>
					</div>
					<div class="print-copy">
						<span>শ্রমিক কপি</span>
					</div>
				</div>
				<div class="address">
					<p>{{ $address }}</p>
				</div>
				<div class="pay-slip-heading">
					<p>বেতন ভাতা ও অতিরিক্ত কাজের মুজুরী পরিশোধ <span class="text-danger">(Trial Basis)</span></p>
				</div>
				<div class="divTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="basicInfoTableHead" style="width: 14%;">মাসের নাম</div>
							<div class="basicInfoTableCell" style="width: 86%;">{!! $bn_pay_month !!} ({{ $start_of_month }}
								হইতে {{ $end_of_month }} তারিখ পর্যন্ত)
							</div>
						</div>
					</div>
				</div>
				<div class="divTable divCustomTable">
					<div class="divTableBody">
						<div class="divTableRow">
                            <div class="basicInfoTableHead">বিভাগঃ</div>
                            <div class="basicInfoTableCell">{{ $department_bn }}</div>
                            <div class="basicInfoTableHead">সেকশনঃ</div>
                            <div class="basicInfoTableCell">{{ $section_bn }}</div>
						</div>
						<div class="divTableRow">
							<div class="basicInfoTableHead">নামঃ</div>
							<div class="basicInfoTableCell">{!! $employee_name !!}</div>
							<div class="basicInfoTableHead">পদবীঃ</div>
							<div class="basicInfoTableCell">{{ $designation_bn }}</div>
							<div class="basicInfoTableHead">গ্রেডঃ</div>
							<div class="basicInfoTableCell">{{ $grade_bn }}</div>
						</div>
						<div class="divTableRow">
							<div class="basicInfoTableHead">আই. ডিঃ</div>
							<div class="basicInfoTableCell">{!! $unique_id !!}</div>
							<div class="basicInfoTableHead">মাসের কর্ম দিবসঃ</div>
							<div class="basicInfoTableCell">{{ $month_woking_days }}</div>
							<div class="basicInfoTableHead">উপস্থিতিঃ</div>
							<div class="basicInfoTableCell">{{ $monthly_attendance }}</div>
						</div>
						<div class="divTableRow">
							<div class="basicInfoTableHead">মোট ছুটিঃ</div>
							<div class="basicInfoTableCell">{{ $total_leaves }}</div>
							<div class="basicInfoTableHead">মোট হলিডেঃ</div>
							<div class="basicInfoTableCell">{{ $total_holidays }}</div>
                            <div class="basicInfoTableHead">অনুপস্থিতঃ </div>
                            <div class="basicInfoTableCell">{{ $monthly_absent }}</div>
						</div>
					</div>
				</div>

				<div class="divTable divCustomTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableHead">মুল বেতন</div>
							<div class="divTableHead">বাসা ভাড়া</div>
							<div class="divTableHead">চিকিৎসা</div>
							<div class="divTableHead">যাতায়াত</div>
							<div class="divTableHead">খাদ্য ভাতা</div>
							<div class="divTableHead">মোট বেতন</div>
							<div class="divTableHead">হাজিরা বোনাস</div>
						</div>
						<div class="divTableRow">
							<div class="divTableCell">{{ $basic_salary }}</div>
							<div class="divTableCell">{{ $house_rent }}</div>
							<div class="divTableCell">{{ $medical_allowance }}</div>
							<div class="divTableCell">{{ $transport_allowance }}</div>
							<div class="divTableCell">{{ $food_allowance }}</div>
							<div class="divTableCell">{{ $gross_salary }}</div>
							<div class="divTableCell">{{ $attendance_bonus }}</div>
						</div>
					</div>
				</div>

				<div class="divTable">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableHead">অতিঃ কাজের ঘণ্টা</div>
							<div class="divTableHead">অঃকাঃ রেট</div>
							<div class="divTableHead">অতিঃ কাজের টাকা</div>
							<div class="divTableHead">কর্তন {{ $monthly_absent }} দিন (অনুপস্থিতি)</div>
							<div class="divTableHead">হাজিরা বোনাস কর্তন</div>
							<div class="divTableHead">প্রাপ্য টাকা</div>
						</div>
						<div class="divTableRow">
							<div class="divTableCell">{{ $ot_hour }}</div>
							<div class="divTableCell">{{ $ot_rate }}</div>
							<div class="divTableCell">{{ $total_ot_amount }}</div>
							<div class="divTableCell">{{ $absent_deduction }}</div>
							<div class="divTableCell">{{ $attendance_bonus_deduction }}</div>
							<div class="divTableCell">{{ $total_payable_amount }} ৳</div>
						</div>
					</div>
				</div>
				<div class="divTable signature">
					<div class="divTableBody">
						<div class="divTableRow">
							<div class="divTableHead" style="width: 33%;">শ্রমিকের স্বাক্ষর</div>
							<div class="divTableCell" style="width: 67%;">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	@endforeach
@else
	<div class="row">
		<h4>No Data Found</h4>
	</div>
@endif
