@extends('hr::layout')
@section("title", "Employee staffs List")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Employee Management List</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <a href="{{ url('/hr/employee/create') }}" class="btn btn-xs white m-b">
                            <em class="fa fa-plus"></em>
                            Add New
                        </a>
                        <button class="btn btn-xs teal m-b" data-toggle="modal" data-target="#import-file">
                            <em class="fa fa-arrow-circle-o-up"></em>
                            Import
                        </button>
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{ url('/hr/employee/sample-excel-download') }}" class="btn btn-xs btn-success m-b">
                            <em class="fa fa-file-excel-o"></em>
                            Sample File
                        </a>
                    </div>
                </div>
                <div class="row">
                    <form class="col-md-12">
                        <table class="reportTable">
                            <tr>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center" name="unique_id"
                                           placeholder="Search"
                                           value="{{ request('unique_id') }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center" name="name"
                                           placeholder="Search"
                                           value="{{ request('name') }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center" name="code"
                                           placeholder="Search"
                                           value="{{ request('code') }}">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm select2-input" name="department"
                                            id="department">
                                        <option value="">Select</option>
                                        @foreach($departments as $department)
                                            <option
                                                {{ request('department') == $department->id ? 'selected' : '' }} value="{{ $department->id }}">
                                                {{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm select2-input" name="section"
                                            id="section">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control form-control-sm select2-input" name="designation">
                                        <option value="">Select</option>
                                        @foreach($designations as $designation)
                                            <option
                                                {{ request('designation') == $designation->id ? 'selected' : '' }} value="{{ $designation->id }}">
                                                {{ $designation->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center" name="gender"
                                           placeholder="Search"
                                           value="{{ request('gender') }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control form-control-sm text-center" name="salary"
                                           placeholder="Search"
                                           value="{{ request('salary') }}">
                                </td>
                                <td>
                                    <select class="form-control form-control-sm select2-input" name="grade">
                                        <option value="">Select</option>
                                        @foreach($grades as $grade)
                                            <option
                                                {{ request('grade') == $grade->id ? 'selected' : '' }} value="{{ $grade->id }}">
                                                {{ $grade->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                    <a class="btn btn-xs btn-warning"
                                       href="{{ url('hr/employee-list') }}">
                                        <em class="fa fa-refresh"></em>
                                    </a>
                                </td>
                            </tr>
                            <thead>
                            <tr style="background: #0ab4e6;">
                                <th>Unique Id</th>
                                <th>Name</th>
                                <th>Code</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>Designation</th>
                                <th>Gender</th>
                                <th>Salary</th>
                                <th>Grade</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($employees as $employee)
                                <tr>
                                    <td>{{ $employee->unique_id }}</td>
                                    <td>{{ $employee->screen_name }}</td>
                                    <td>{{ $employee->employeeOfficialInfo->code ?? '' }}</td>
                                    <td>{{ $employee->employeeOfficialInfo->departmentDetails->name ?? '' }}</td>
                                    <td>{{ $employee->employeeOfficialInfo->sectionDetails->name ?? '' }}</td>
                                    <td>{{ $employee->employeeOfficialInfo->designationDetails->name ?? '' }}</td>
                                    <td>{{ $employee->sex }}</td>
                                    <td>{{ $employee->salary->gross ?? '' }}</td>
                                    <td>{{ $employee->employeeOfficialInfo->grade->name ?? '' }}</td>
                                    <td>
                                        <div class="pl-1">
                                            <a href="/hr/employee/{{ $employee->id }}/edit"
                                               class="btn btn-xs btn-info"
                                               target="_blank"
                                               title="Edit Employee">
                                                <em class="fa fa-edit"></em>
                                            </a>
                                            <button
                                                type="button"
                                                data-toggle="modal"
                                                ui-target="#animate"
                                                ui-toggle-class="flip-x"
                                                title="Delete Employee"
                                                data-target="#confirmationModal"
                                                class="btn btn-xs btn-danger show-modal"
                                                data-url="{{ url('/hr/api/v1/employees/'.$employee->id) }}">
                                                <em class="fa fa-trash"></em>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $employees->appends(request()->query())->links() }}
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="import-file" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import Employee</h4>
                    </div>
                    <form action="{{ url('/hr/employee-information-excel-upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <p>Import employee like sample files format.</p>
                            <input type="file" class="form-control form-control-sm" name="employee_excel">
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-success btn-sm" type="submit">Submit</button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).on('change', '#department', function (e) {
            const department = e.target.value;
            if (department) {
                $("#section").empty().append("<option value=''>Select</option>");
                $.ajax({
                    type: 'GET',
                    url: '/hr/api/v1/sections-list/' + department,
                    success: function (response) {
                        $.each(response.data, function (i, index) {
                            $("#section").append("<option value=" + response.data[i] + ">" + response.data[i].name + "</option>")
                        })
                    }
                })
            }
        });
    </script>
@endsection
