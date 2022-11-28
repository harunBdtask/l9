@extends('hr::layout')
@section("title", "Employee Salary History")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Employee Salary History</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('/hr/employee/salary-history-entry') }}" class="btn btn-sm white m-b"><i class="fa fa-plus"></i>
                            Add New
                        </a>
                    </div>
                </div>
                <div class="row">
                    <form class="col-md-12">
                        <table class="reportTable">
                            <tr>
                                <td style="width: 20%;">
                                    <select class="form-control form-control-sm select2-input" name="employee_id" id="employee">
                                        <option value="">Select</option>
                                        @foreach($employees as $employee)
                                            <option {{ request('employee_id') == $employee->id ? 'selected' : '' }} value="{{ $employee->id }}">
                                                {{ $employee->screen_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 20%;">
                                    <select class="form-control form-control-sm select2-input" name="department_id" id="department">
                                        <option value="">Select</option>
                                        @foreach($departments as $department)
                                            <option {{ request('department_id') == $department->id ? 'selected' : '' }} value="{{ $department->id }}">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 20%;">
                                    <select class="form-control form-control-sm select2-input" name="designation_id" id="designation">
                                        <option value="">Select</option>
                                        @foreach($designations as $designation)
                                            <option {{ request('designation_id') == $designation->id ? 'selected' : '' }} value="{{ $designation->id }}">
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td style="width: 15%;">
                                    <input type="text" class="form-control form-control-sm text-center" name="year"
                                    placeholder="Search"
                                    value="{{ request('year') }}">
                                </td>
                                <td style="width: 15%;">
                                <input type="text" class="form-control form-control-sm text-center" name="gross_salary"
                                    placeholder="Search"
                                    value="{{ request('gross_salary') }}">
                                </td>
                                <td style="width: 10%;">
                                    <button class="btn btn-xs white" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a class="btn btn-xs btn-warning"
                                        href="{{ url('hr/employee/salary-history') }}">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </td>
                            </tr>
                            <thead>
                            <tr style="background: #0ab4e6;">
                                <th>Name</th>
                                <th>Department</th>
                                <th>Designation</th>
                                <th>Year</th>
                                <th>Gross Salary</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($histories as $history)
                                <tr>
                                    <td>{{ $history->employee->screen_name ?? '' }}</td>
                                    <td>{{ $history->department->name ?? '' }}</td>
                                    <td>{{ $history->designation->name ?? '' }}</td>
                                    <td>{{ $history->year }}</td>
                                    <td>{{ $history->gross_salary }}</td>
                                    <td>
                                        <div class="pl-1">
                                            <a href="/hr/employee/salary-history-entry/{{ $history->id }}/edit"
                                                class="btn btn-xs btn-info"
                                                title="Edit Salary History">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button
                                                type="button"
                                                data-toggle="modal"
                                                ui-target="#animate"
                                                ui-toggle-class="flip-x"
                                                title="Delete Employee"
                                                data-target="#confirmationModal"
                                                data-url="{{ url('/hr/api/v1/salary-histories/'.$history->id) }}"
                                                class="btn btn-xs btn-danger show-modal">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $histories->appends(request()->query())->links() }}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection