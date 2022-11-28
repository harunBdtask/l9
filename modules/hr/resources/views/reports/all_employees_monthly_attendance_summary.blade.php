@include('hr::report-style')
@include('hr::reports.include.header')

<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <tr>
                <td>Id</td>
                <td>Unique Id</td>
                <td>
                    <div class="divTable">
                        <div class="divTableBody">
                            <div class="divTableRow">
                                <div class="divTableCell">Name</div>
                            </div>
                            <div class="divTableRow">
                                <div class="divTableCell">Joining date</div>
                            </div>
                        </div>
                    </div>
                </td>
                <td>Designation</td>
                <td>Code</td>
                <td>Month Days</td>
                <td>Present</td>
                <td>Mnth Holi</td>
                <td>Leave</td>
                <td>Late</td>
                <td>Absent</td>
                <td>Tot. Pay. Day</td>
                <td>Main OT</td>
                <td>Extra Tot.</td>
                <td>Holi OT</td>
                <td>Tot. OT Hour</td>
                <td>Signature</td>
            </tr>
            @php
                $total_ot_hour = 0;
            @endphp
            @foreach($summaries as $summary)
                <tr>
                    <td>{{$loop->iteration}}</td>
                    <td>{{$summary->userid}}</td>
                    <td>
                        <div class="divTable">
                            <div class="divTableBody">
                                <div class="divTableRow">
                                    <div
                                        class="divTableCell">{{$summary->employee->first_name.' '.$summary->employee->last_name}}</div>
                                </div>
                                <div class="divTableRow">
                                    <div
                                        class="divTableCell">{{ $summary->employeeOfficialInfo->date_of_joining ?  date('d-M-Y',strtotime($summary->employeeOfficialInfo->date_of_joining)) : ''}}</div>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>{{$summary->employeeOfficialInfo->designationDetails->name}}</td>
                    <td>{{$summary->employeeOfficialInfo->code}}</td>
                    <td>{{$no_of_days}}</td>
                    <td>{{$summary->total_present_day}}</td>
                    <td>{{$summary->total_holiday}}</td>
                    <td>{{$summary->total_leave}}</td>
                    <td>{{$summary->total_late ?? 0}}</td>
                    <td>{{$summary->total_absent_day ?? 0}}</td>
                    <td>{{$summary->total_payable_days}}</td>
                    <td>{{$summary->ot_hour}}</td>
                    <td>{{$summary->total_regular_extra_ot_hour ?? 0}}</td>
                    <td>
                        @php
                            $totalHolidayOt =\SkylarkSoft\GoRMG\HR\Models\HrHolidayPaymentSummary::where('userid', $summary->userid)->whereMonth('pay_month', date('m',strtotime($summary->pay_month)))->whereYear('pay_month', date('Y',strtotime($summary->pay_month)))->get()->sum('total_working_hour');
                            $total_ot_hour = ($summary->ot_hour + $summary->total_regular_extra_ot_hour + $totalHolidayOt);
                        @endphp
                        {{$totalHolidayOt}}
                    </td>
                    <td>{{$total_ot_hour}}</td>
                    <td></td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
