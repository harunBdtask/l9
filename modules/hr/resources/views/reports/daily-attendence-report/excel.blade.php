<div>
    <table style="border:1px solid; text-align: center;">
        <thead>
        <tr>
            <td colspan="12" style="height: 30px; text-align: center;">{{ factoryName() }}</td>
        </tr>
        <tr>
            <td colspan="12" style="height: 20px; text-align: center;">
                <b>Daily Attendance Report {{ $date }}</b>
            </td>
        </tr>
        </thead>
    </table>
    {{-- @includeIf('hr::reports.daily-attendence-report.view-body') --}}
    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Employee Unique ID</th>
                <th>Punch Card ID</th>
                <th>Name</th>
                <th>Department</th>
                <th>Designation</th>
                <th>Section</th>
                <th>Type</th>
                <th>Punch Time</th>
                <th>Status</th>
                <th>Late Status</th>
            </tr>
        </thead>

        <tbody>
            @if (count($employees) > 0)
                @foreach ($employees as $employee)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $employee['unique_id']}}</td>
                        <td>{{ $employee['punch_card_id']}}</td>
                        <td>{{ $employee['employee_basic_info']['first_name'] }}</td>
                        <td>{{ $employee['department_details']['name']}}</td>
                        <td>{{ $employee['designation_details']['name']}}</td>
                        <td>{{ $employee['section_details']['name']}}</td>
                        <td>{{ ucfirst($employee['type'])}}</td>
                        <td>{{ $employee['first_punch_time_in_day']}}</td>
                        @if ($employee['attendance_status'] == 'Present')
                        <td>{{ $employee['attendance_status']}}</td>
                        @else
                        <td>{{ $employee['attendance_status']}}</td>
                        @endif
                        <td>{{ $employee['late_status']}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
