<div class="body-section table-responsive" style="margin-top: 0;">
    <table>
        <thead>
            <tr style="background-color: aliceblue;">
                <th style="text-align: center;">SL</th>
                <th style="text-align: center;">Employee Unique ID</th>
                <th style="text-align: center;">Punch Card ID</th>
                <th style="text-align: center;">Name</th>
                <th style="text-align: center;">Department</th>
                <th style="text-align: center;">Designation</th>
                <th style="text-align: center;">Section</th>
                <th style="text-align: center;">Type</th>
                <th style="text-align: center;">Punch Time</th>
                <th style="text-align: center;">Status</th>
                <th style="text-align: center;">Late Status</th>
            </tr>
        </thead>

        <tbody>
            @if (count($employees) > 0)
                @foreach ($employees as $employee)
                    <tr>
                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                        <td style="text-align: center;">{{ $employee['unique_id']}}</td>
                        <td style="text-align: center;">{{ $employee['punch_card_id']}}</td>
                        <td style="text-align: center;">{{ $employee['employee_basic_info']['first_name'] }}</td>
                        <td style="text-align: center;">{{ $employee['department_details']['name']}}</td>
                        <td style="text-align: center;">{{ $employee['designation_details']['name']}}</td>
                        <td style="text-align: center;">{{ $employee['section_details']['name']}}</td>
                        <td style="text-align: center;">{{ ucfirst($employee['type'])}}</td>
                        <td style="text-align: center;">{{ $employee['first_punch_time_in_day']}}</td>
                        @if ($employee['attendance_status'] == 'Present')
                        <td style="background: rgb(13, 196, 59);color: white;border: 1px solid #201c1c !important; text-align: center;">{{ $employee['attendance_status']}}</td>
                        @else
                        <td style="background: rgb(238, 53, 53);color: white;border: 1px solid #201c1c !important; text-align: center;">{{ $employee['attendance_status']}}</td>
                        @endif
                        <td style="text-align: center;">{{ $employee['late_status']}}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
    @if (count($employees) == 0)
            <p class="text-center" style="border: 1px solid black">No Data Found</p>
    @endif
</div>
