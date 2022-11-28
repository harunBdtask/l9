@extends('cuttingdroplets::layout')
@section('title', 'Daily Cutting Production Report')
@section('styles')
    <style type="text/css">
        th, td {
            font-size: 11px !important;
            padding: 0px !important;
        }

        #parentTableFixed {
            height: 400px !important;
        }

        .table > thead > tr > th {
            padding-top: 2px !important;
            padding-right: 2px !important;
            /*padding-bottom: 2px !important;*/
            padding-left: 2px !important;
        }

        .table > tbody > tr > td {
            padding-right: 2px !important;
            padding-bottom: 0px !important;
            padding-left: 2px !important;
            padding-top: 0px !important;
        }

        .box-header {
            padding-top: .60rem !important;
            padding-bottom: .60rem !important;
        }
    </style>
@endsection
@section('refresh')
    <meta http-equiv="refresh" content="60"/>
@endsection
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Daily Cutting Production Report || {{ date("jS F, Y", strtotime($date)) }}
                            <span class="pull-right">
                <a href="{{ url('/daily-cutting-report-download/pdf/'.($date ?? date('Y-m-d'))) }}">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a>
                |
                <a href="{{ url('/daily-cutting-report-download/xls/'.($date ?? date('Y-m-d'))) }}">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/daily-cutting-report') }}" method="get">
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <input type="date" name="date" class="form-control form-control-sm"
                                               required="required" value="{{ $date ?? date('Y-m-d') }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">
                                            Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="parentTableFixed" class="table-responsive">
                            @include('cuttingdroplets::reports.tables.daily-cutting-report-table')
                        </div>
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
