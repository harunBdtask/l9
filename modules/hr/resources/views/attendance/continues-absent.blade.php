@include('hr::report-style')
@include('hr::reports.include.header')

<div class="row">
    <table class="reportTable">
        <tr>
            <th>Unique Id</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Name (Bengali)</th>
            <th>Department</th>
            <th>Section</th>
            <th>Designation</th>
            <th>Leaves</th>
        </tr>
        @foreach($employees as $employee)
            <tr>
                <td>{{ $employee->unique_id }}</td>
                <td>{{ $employee->first_name }}</td>
                <td>{{ $employee->last_name }}</td>
                <td>{{ $employee->name_bn }}</td>
                <td>{{ $employee->officialInfo->departmentDetails->name }}</td>
                <td>{{ $employee->officialInfo->sectionDetails->name }}</td>
                <td>{{ $employee->officialInfo->designationDetails->name }}</td>
                <td>{{ json_encode($leaves[$employee->unique_id]) }}</td>
            </tr>
        @endforeach

    </table>
</div>
