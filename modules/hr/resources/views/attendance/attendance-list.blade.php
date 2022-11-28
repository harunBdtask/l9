@extends('hr::layout')
@section("title","Attendance List")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header row">
                <div class="col-md-6">
                    <h2>Attendance List</h2>
                </div>
                <div class="col-md-6">
                    <button type="button" class="btn btn-sm btn-primary pull-right" data-toggle="modal"
                            data-target="#exampleModal">
                        Pull Attendance
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Pull Attendance</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <input type="date" id="date" value="{{ date('Y-m-d') }}" class="form form-control" required> <br>
                                </div>
                                {{--                                href="{{ config('app.attendance_host_address') }}"--}}
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <a id="url" class="btn btn-sm btn-primary pull-right">Pull Attendance</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
{{--                        <button class="btn btn-xs teal m-b" data-toggle="modal" data-target="#import-file">--}}
{{--                            <em class="fa fa-arrow-circle-o-up"></em>--}}
{{--                            Import--}}
{{--                        </button>--}}
{{--                        <a href="{{ url('/hr/attendance/attendance-list-sample-excel-download') }}" class="btn btn-xs btn-success m-b">--}}
{{--                            <em class="fa fa-file-excel-o"></em>--}}
{{--                            Sample File--}}
{{--                        </a>--}}
                    </div>
                    <div class="col-md-2 text-right">
                        <a href="{{ url('/hr/attendance/attendance-list-excel-export') }}" class="btn btn-xs btn-primary m-b">
                            <em class="fa fa-file-excel-o"></em>
                            Export File
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ '/hr/attendance' }}" method="get">
                            <table class="table borderless">
                                <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Designation</th>
                                    <th>Section</th>
                                    <th>Type</th>
                                    <th style="width: 15%">Date</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="department">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                                <option
                                                    value="{{ $department->id }}" {{ request('department') == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="designation">
                                            <option value="">Select Designation</option>
                                            @foreach($designations as $designation)
                                                <option
                                                    value="{{ $designation->id }}" {{ request('designation') == $designation->id ? 'selected' : '' }}>{{ $designation->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="section">
                                            <option value="">Select Section</option>
                                            @foreach($sections as $section)
                                                <option
                                                    value="{{ $section->id }}" {{ request('section') == $section->id ? 'selected' : '' }}>{{ $section->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="type">
                                            <option value="">Select Type</option>
                                            @foreach($types as $type)
                                                <option
                                                    value="{{ $type['id'] }}" {{ request('type') == $type['id'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input required type="date" class="form-control form-control-sm" name="date"
                                               value="{{ request('date') }}">
                                    </td>
                                    <td>
                                        <button class="btn btn-success btn-xs">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <table class="reportTable">
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
                                <th>Date</th>
                                <th>Punch Time</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $attendance->employeeOfficialInfo->unique_id }}</td>
                                    <td>{{ $attendance->userid }}</td>
                                    <td>{{ $attendance->employeeOfficialInfo->employeeBasicInfo->first_name ." ". $attendance->employeeOfficialInfo->employeeBasicInfo->last_name }}</td>
                                    <td>{{ $attendance->employeeOfficialInfo->departmentDetails->name }}</td>
                                    <td>{{ $attendance->employeeOfficialInfo->designationDetails->name }}</td>
                                    <td>{{ $attendance->employeeOfficialInfo->sectionDetails->name }}</td>
                                    <td>{{ ucfirst($attendance->employeeOfficialInfo->type) }}</td>
                                    <td>{{ \Carbon\Carbon::parse($attendance->attendance_date)->format('d-m-Y') }}</td>
                                    <td>{{ $attendance->punch_time ?? 'N/A' }}</td>
                                    <td>
                                        @if(strtotime($attendance->punch_time) <= strtotime('8:05'))
                                            <span class="badge badge-success">In Time</span>
                                        @elseif(strtotime($attendance->punch_time) > strtotime('8:05'))
                                            <span class="badge badge-danger">Late</span>
                                        @else
                                            <span class="badge badge-light">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                        <div class="text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="import-file" class="modal fade" role="dialog">
            <div class="modal-dialog modal-sm">

                <!-- Modal content-->
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Import Attendance</h4>
                    </div>
                    <form action="{{ url('/hr/attendance/attendance-list-excel-upload') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <p>Import attendance like sample files format.</p>
                            <input type="file" class="form-control form-control-sm" name="attendance_list_excel">
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

@push('script-head')
    <script type="text/javascript">

        $(document).ready(function (e) {
            $("#url").click(function (e) {
                e.preventDefault();
                let date = $("#date").val();
                let button = $("#url");
                button.attr("href", "{{ config('app.attendance_host_address') }}" + "?date=" + date);
                let link = button.attr('href');
                if(date) {
                    window.open(link, "_blank");
                }
                else {
                    alert("Select date to import data");
                }
                $("#date").val("");
            });
        });


    </script>
@endpush


