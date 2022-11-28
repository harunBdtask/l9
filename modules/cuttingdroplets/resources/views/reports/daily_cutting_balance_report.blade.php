@extends('cuttingdroplets::layout')
@section('title', 'Daily Cutting Balance Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Daily Cutting Balance Report
                            <span class="pull-right">
                              <a href="{{ url('daily-cutting-balance-report/pdf') }}">
                                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                              </a>
                              |
                              <a href="{{ url('daily-cutting-balance-report/xls') }}">
                                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                              </a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div style="float: right">
                            <h5>Date: {{ date('d-m-Y') }}</h5>
                        </div>
                        @include('cuttingdroplets::reports.tables.daily_cutting_balance_report_table')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            input[type=date].form-control form-control-sm {
                line-height: 1;
            }
        }
    </style>
@endsection
