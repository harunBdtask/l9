@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('misdroplets::layout')
@section('title', 'Efficiency Summary Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Efficiency Summary Report || {{ date("jS F, Y") }} <span class="pull-right"><a
                href="{{ url('/monthly-efficiency-summary-report-download/pdf/'.$year.'/'.$month) }}"><i
                  style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>{{-- | <a
                href="{{ url('/monthly-efficiency-summary-report-download/xls/'.$year.'/'.$month) }}"><i
                  style="color: #0F733B" class="fa fa-file-excel-o"></i></a> --}}</span></h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::open(['url' => url('/monthly-efficiency-summary-report'), 'method' => 'GET', 'sutocomplete' =>
          'off']) !!}
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-3">
                <label>Year</label>
                {!! Form::selectRange('year', 2018, 2050, $year ?? null, ['id' => 'month', 'class' => 'form-control form-control-sm']) !!}
                @if($errors->has('from_date'))
                <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
              </div>
              <div class="col-sm-3">
                <label>Month</label>
                {!! Form::selectMonth('month', $month ?? null, ['id' => 'month', 'class' => 'form-control form-control-sm']) !!}
                @if($errors->has('from_date'))
                <span class="text-danger">{{ $errors->first('from_date') }}</span>
                @endif
              </div>
              <div class="col-sm-1">
                <button class="btn btn-md btn-info btn-sm" style="margin-top: 25px;" type="submit">
                  Search
                </button>
              </div>
            </div>
          </div>
          {!! Form::close() !!}

          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable"
              style="min-height: 200px !important; display: block; overflow-x: auto;white-space: nowrap;" id="fixTable">
              @include('misdroplets::reports.tables.monthly_efficiency_summary_table')
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection