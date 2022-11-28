@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
    }
@endphp
@extends('misdroplets::layout')
@section('title', 'Color Wise Production Summary Report')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header text-center">
                        @php
                            $currentPage = $reportData ? $reportData->currentPage() : 1;
                        @endphp
                        <h2>Color Wise Production Summary Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                                    href="{{ url('/color-wise-production-summary-report-download/pdf/'.($from_date ?? date('Y-m-d')).'/'.($to_date ?? date('Y-m-d')).'/'.$currentPage ) }}"><i
                                        style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a
                                    href="{{ url('/color-wise-production-summary-report-download/xls/'.($from_date ?? date('Y-m-d')).'/'.($to_date ?? date('Y-m-d')).'/'.$currentPage ) }}"><i
                                        style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                      {!! Form::open(['url' => url('/color-wise-production-summary-report'), 'method' => 'GET', 'autocomplete' => 'off']) !!}
                        <div class="form-group">
                          <div class="row m-b">
                            <div class="col-sm-3">
                              <label>From Date</label>
                              {!! Form::date('from_date', $from_date ?? null, ['id' => 'factory_id', 'class' => 'form-control form-control-sm', 'placeholder' => 'Enter from date']) !!}
                              @if($errors->has('from_date'))
                                <span class="text-danger">{{ $errors->first('from_date') }}</span>
                              @endif
                            </div>
                            <div class="col-sm-3">
                              <label>To Date</label>
                              {!! Form::date('to_date', $to_date ?? null, ['id' => 'factory_id', 'class' => 'form-control form-control-sm', 'placeholder' => 'Enter to date']) !!}
                              @if($errors->has('to_date'))
                                <span class="text-danger">{{ $errors->first('to_date') }}</span>
                              @endif
                              @if(session()->has('error'))
                                <span class="text-danger">{{ session()->get('error') }}</span>
                              @endif
                            </div>
                            <div class="col-sm-2">
                              <label>&nbsp;</label>
                              <button class="btn btn-sm btn-info btn-block" type="submit">Search</button>
                            </div>
                          </div>
                        </div>
                      {!! Form::close() !!}

                        <div id="parentTableFixed" class="table-responsive">
                            <table class="reportTable"
                                   style="min-height: 200px !important;display: block; overflow-x: auto;white-space: nowrap;"
                                   id="fixTable">
                                @include('misdroplets::reports.tables.color_wise_production_summary_report')
                            </table>
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
