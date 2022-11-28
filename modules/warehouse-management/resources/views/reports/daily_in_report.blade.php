@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('warehouse-management::layout')
@section('title', 'Date Wise Warehouse In Report')

@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Date Wise Warehouse In Report
                            <span class="pull-right">
                                <a href="{{ url('/warehouse-daily-in-report-download/pdf/'.($from_date).'/'.($to_date)) }}">
                                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | 
                                <a href="{{ url('/warehouse-daily-in-report-download/excel/'.($from_date).'/'.($to_date)) }}"><i
                                            style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/warehouse-daily-in-report') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <label>From Date</label>
                                        <input type="date" name="from_date" class="form-control"
                                               value="{{ $from_date }}" required="required">
                                        @if($errors->has('from_date'))
                                            <span class="text-danger">{{ $errors->first('from_date') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-sm-3">
                                        <label>To Date</label>
                                        <input type="date" name="to_date" class="form-control"
                                               value="{{ $to_date }}" required="required"
                                               onchange="this.form.submit();">
                                        @if($errors->has('to_date'))
                                            <span class="text-danger">{{ $errors->first('to_date') }}</span>
                                        @endif
                                        @if(Session::has('error'))
                                            <span class="text-danger">{{ Session::get('error') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="reportTable {{ $tableHeadColorClass }}">
                                @include('warehouse-management::reports.includes.daily_in_report_table')
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style type="text/css">
        @media screen and (-webkit-min-device-pixel-ratio: 0) {
            input[type=date].form-control {
                line-height: .75;
            }
        }
    </style>
@endsection
