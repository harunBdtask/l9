@extends('hr::layout')
@section("title","Absent List")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Daily Attendence Report</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                {{-- <div class="row">
                    <div class="col-md-12 m-b">
                    </div>
                </div> --}}
                {{-- <div class="row">
                    <div class="col-md-10">

                    </div>
                    <div class="col-md-2 text-right">
                        <button class="btn btn-xs btn-primary m-b" id="exportButton">Export Excel</button>
                        <button class="btn btn-xs btn-primary m-b" id="printButton">Export Pdf</button>

                    </div>
                </div> --}}
                <div class="row">
                    <div class="col-md-8" id="searchForm">
                        <form action="{{ '/hr/attendance/report/daily-attendence-report' }}" method="get">
                            <table class="table borderless">
                                <thead>
                                <tr>
                                    <th>Date</th>
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
                                    <td>
                                        <select class="form-control form-control-sm select2-input" name="type">
                                            <option value="">Select</option>
                                            @foreach($types as $type)
                                                <option
                                                    value="{{ $type['name'] }}" {{ request('type') == $type['name'] ? 'selected' : '' }}>{{ $type['name'] }}</option>
                                            @endforeach
                                        </select>
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
                    <div>
                        <div class="col-md-2"></div>
                    </div>
                    <div class="col-md-2">
                        <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th scope="col" class="border-color" style="font-size: 17px;">{{$present_count}}</th>
                                <th scope="col" class="border-color" style="font-size: 17px;">{{$absent_count}}</th>
                                <th scope="col" class="border-color" style="font-size: 17px;">{{$late_count}}</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td class="border-color" >
                                    <span class="text-success">
                                      <span>Present</span>
                                    </span>
                                  </td>
                                <td class="border-color">
                                  <span class="text-danger">
                                    <span>Absent</span>
                                  </span>
                                </td>
                                <td class="border-color">
                                  <span class="text-info">
                                    <span>late</span>
                                  </span>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                    </div>
                    <div class="col-md-12" id="attendenceReportPdf">
                        <table class="reportTable" id="attendenceReport" style="border: 1px solid #201c1c !important;">
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
                                            <td style="background: rgb(13, 196, 59);color: white;border: 1px solid #201c1c !important;">{{ $employee['attendance_status']}}</td>
                                            @else
                                            <td style="background: rgb(238, 53, 53);color: white;border: 1px solid #201c1c !important;">{{ $employee['attendance_status']}}</td>
                                            @endif
                                            <td>{{ $employee['late_status']}}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if (count($employees) == 0)
                                <p class="text-center" style="border: 1px solid black">No Data Found</p>
                        @endif
                        {{-- <div class="text-center">
                            {{ $employees->appends(request()->query())->links() }}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($){
                $("#exportButton").click (function () {
                    console.log('exporting')
                    $("#attendenceReport").table2excel({
                        exclude: ".noExl",
                        name: "AbsentReport",
                        filename: "Absent Report",
                        fileext: ".xls",
                        });
                });
        });

    </script>
    <script type="text/javascript">
    $("#printButton").click (function () {
        window.print()
    });
        // $scope.printWindow = function () {
        //  window.print()
        // }
    </script>
    <style>
    @media print {
        #printButton{
            display: none !important; // To hide
        }
        #exportButton{
            display: none !important; // To hide
        }
        #searchForm{
            display: none !important; // To hide
        }

        #attendenceReportPdf * {
            visibility: visible; // Print only required part
            text-align: left;
            -webkit-print-color-adjust: exact !important;
        }
    }
    .border-color
    {
        border: 1px solid #201c1c !important;
        font-size: 17px;
        font-weight: 700;
    }
    </style>

@endsection
