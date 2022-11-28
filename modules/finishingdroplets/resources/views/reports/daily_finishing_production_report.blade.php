@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('finishingdroplets::layout')
@section('title', 'Production Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        <h2>Production Report || {{ date("jS F, Y", strtotime($date)) }} <span class="pull-right">
                                <a href="{{url('daily-finishing-production-report-download/pdf/'.$date)}}"><i
                                            style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                <a href="{{url('daily-finishing-production-report-download/xls/'.$date)}}"><i
                                            style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <form action="{{ url('/daily-finishing-production-report') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row m-b">
                                    <div class="col-sm-3">
                                        <label>Date</label>
                                        <input type="date" name="date" class="form-control form-control-sm" required="required" value="{{ $date }}">
                                    </div>
                                    <div class="col-sm-2">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable"
                                   style="max-width: 150% !important; font-size: 11px !important; display: block; overflow-x: auto;white-space: nowrap;"
                                   id="fixTable">
                                @include('finishingdroplets::reports.includes.daily_finishing_production_report_table')
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style type="text/css">
        @media  screen and (-webkit-min-device-pixel-ratio: 0){
            input[type=date].form-control form-control-sm{
                line-height: 1;
            }
        }
    </style>
@endsection
