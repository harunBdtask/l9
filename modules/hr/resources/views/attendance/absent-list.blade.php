@extends('hr::layout')
@section("title","Absent List")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Daily Absent Report</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                    </div>
                    <div class="col-md-2 text-right">
                        {{-- <button class="btn btn-xs btn-primary m-b" id="exportButton">Export</button> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ '/hr/attendance/absent-list' }}" method="get">
                            <table class="table borderless">
                                <thead>
                                <tr>
                                    <th>From</th>
                                    <th>Unique Id</th>
                                    <th>Type</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <input type="date" class="form-control form-control-sm" name="date"
                                               value="{{ $date }}">
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="unique_id">
                                            <option value="">Select</option>
                                            @foreach($uids as $uid)
                                                <option
                                                    value="{{$uid->unique_id}}" {{ request('unique_id') == $uid['unique_id'] ? 'selected' : '' }}>{{ $uid->unique_id }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    {{-- {{ request('uid') == $employee->unique_id ? 'selected' : '' }} --}}
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="type">
                                            <option value="">Select</option>
                                            @foreach($types as $type)
                                                <option
                                                    value="{{ $type['name'] }}" {{ request('type') == $type['name'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    {{-- {{ request('type') == $type['id'] ? 'selected' : '' }} --}}
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
                        <table class="reportTable" id="absentTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Unique ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Section</th>
                                <th>Designation</th>
                                <th>Employee Type</th>
                                <th>Last Present</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($absent_employees as $absent_employee)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $absent_employee['unique_id']}}</td>
                                    <td>{{ $absent_employee['employee_basic_info']['first_name']}}</td>
                                    <td>{{ $absent_employee['department_details']['name'] }}</td>
                                    <td>{{ $absent_employee['section_details']['name'] }}</td>
                                    <td>{{ $absent_employee['designation_details']['name'] }}</td>
                                    <td>{{ ucfirst($absent_employee['type'])}}</td>
                                    @if ($absent_employee['last_attendance_date'] != null)
                                    <td>{{ $absent_employee['last_attendance_date']['attendance_date']}}</td>
                                    @else
                                    <td>N/A</td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        @if (count($absent_employees) == 0)
                                <p class="text-center" style="border: 1px solid black">No Data Found</p>
                        @endif
                        {{-- <div class="text-center">
                            {{ $attendances->appends(request()->query())->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($){
                $("#exportButton").click (function () {
                    console.log('exporting')
                    $("#absentTable").table2excel({
                        exclude: ".noExl",
                        name: "AbsentReport",
                        filename: "Absent Report",
                        fileext: ".xls",
                        });
                });
        });

    </script>

@endsection
