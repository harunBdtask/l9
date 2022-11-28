@include('hr::report-style')
@include('hr::reports.include.header')

<div class="row">
    <table class="reportTable">
        <tr>
            <th>SL</th>
            <th>Name</th>
            <th>Designation</th>
            <th>Department</th>
            <th>Section</th>
            <th>Amount</th>
        </tr>

        @foreach($salaries as $salary)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>{{ $salary->employee->screen_name }}</td>
                <td>{{ $salary->employeeOfficialInfo->designationDetails->name }}</td>
                <td>{{ $salary->employeeOfficialInfo->departmentDetails->name }}</td>
                <td>{{ $salary->employeeOfficialInfo->sectionDetails->name }}</td>
                <td>{{ $salary->total_payable_amount }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="5" class="text-right"><b>Total</b> &nbsp;	&nbsp;</td>
            <td>{{ $salaries->sum('total_payable_amount') }}</td>
        </tr>
    </table>
</div>
