@include('hr::report-style')
<table class="reportTable">
    <thead>
    <tr>
        <th colspan="14">Daily Worker Attendance Report - {{ date('d-l-F-Y', strtotime($date)) }}</th>
    </tr>
    <tr>
        <th>Sl No.</th>
        <th>Unique Id</th>
        <th>Name</th>
        <th>Designation</th>
        <th>Joining Date</th>
        <th>Code</th>
        <th>In Time</th>
        <th>Out Time</th>
        <th>Lunch Start</th>
        <th>Lunch End</th>
        <th>Total OT</th>
        <th>Total Hour</th>
        <th>Machine</th>
        <th>Late</th>
    </tr>
    </thead>
    <tbody>
    @if($reports && $reports->count())
        <tr>
            <th colspan="14">Section : {{ $reports->first()->employeeOfficialInfo->sectionDetails->name }}</th>
        </tr>
        @foreach($reports->sortBy('userid') as $report)
            @php
                $ot_hours = round((($report->regular_ot_minute + $report->extra_ot_minute_same_day) / 60));
              $in_time_reference = \Carbon\Carbon::parse($date.'T08:00:00');
              $att_in = \Carbon\Carbon::parse($date.'T'.$report->att_in);
              $att_out = \Carbon\Carbon::parse($date.'T'.$report->att_out);

                $work_hour = $att_in < $in_time_reference ? $in_time_reference->diffInHours($att_out) : $att_in->diffInHours($att_out);
                $late = $report->status == 'late' ? 1 : 0;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $report->userid }}</td>
                <td>{{ $report->employeeOfficialInfo->employeeBasicInfo->screen_name }}</td>
                <td>{{ $report->employeeOfficialInfo->designationDetails->name }}</td>
                <td>{{ $report->employeeOfficialInfo->date_of_joining ? date('d/m/Y', strtotime($report->employeeOfficialInfo->date_of_joining)) : '' }}</td>
                <td>{{ $report->employeeOfficialInfo->code }}</td>
                <td>{{ $report->att_in ? \Carbon\Carbon::parse($date.'T'.$report->att_in)->format('h:i A') : '' }}</td>
                <td>{{ $report->att_out ? \Carbon\Carbon::parse($date.'T'.$report->att_out)->format('h:i A') : '' }}</td>
                <td>{{ $report->lunch_in ? \Carbon\Carbon::parse($date.'T'.$report->lunch_in)->format('h:i A') : '' }}</td>
                <td>{{ $report->lunch_out ? \Carbon\Carbon::parse($date.'T'.$report->lunch_out)->format('h:i A') : '' }}</td>
                <td>{{ $ot_hours }}</td>
                <td>{{ $work_hour }}</td>
                <td>&nbsp;</td>
                <td>{{ $late }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="14">{{ $reports->first()->employeeOfficialInfo->sectionDetails->name }} Section
                Present: {{ $reports->count() }}</th>
        </tr>
    @else
        <tr>
            <th colspan="14">No Data</th>
        </tr>
    @endif
    </tbody>
</table>
