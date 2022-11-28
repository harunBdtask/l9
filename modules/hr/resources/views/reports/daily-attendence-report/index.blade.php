@extends('skeleton::layout')
@section('title','Daily Attendence Report')
@section('styles')
    <style>
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
        .border-color
        {
            border: 1px solid #201c1c !important;
            font-size: 17px;
            font-weight: 700;
        }
    </style>
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Daily Attendance Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="{{ url('/hr/attendance/report/daily-attendence-report') }}" method="get">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-6">
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
                                </div>
                                {{-- <div class="col-sm-1" style="display:flex;">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            title="Search" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="/hr/daily-knitting-report" style="margin-top: 30px; margin-left: 5px;" class="btn btn-sm btn-primary"
                                            title="Refresh">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </div> --}}
                                <div class="col-sm-6">
                                    <div class="col-sm-10">
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
                                    <div class="col-sm-2 text-right" style="margin-top: 2%;">
                                        {{-- <a class="btn" href="{{ url('/hr/attendance/daily-attendence-report/pdf?'.Request::getQueryString()) }}">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </a> --}}
                                        <a class="btn" href="{{ url('/hr/attendance/daily-attendence-report/excel?'.Request::getQueryString()) }}">
                                            <i class="fa fa-file-excel-o"></i>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <br>
                    @if($employees)
                        @includeIf('hr::reports.daily-attendence-report.view-body')
                    @endif
                </div>
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection
