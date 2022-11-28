@php
$tableHeadColorClass = 'tableHeadColor';
if (isset($type) || request()->has('type') || request()->route('type')) {
$tableHeadColorClass = '';
}
@endphp
@extends('inputdroplets::layout')
@section('title', 'Daily Input Report')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Daily Input Report || {{ date("jS F, Y") }}
            <span class="pull-right">
              <a href="{{url('/v2/daily-input-status-download?type=pdf&date='.($date ?? date("Y-m-d")))}}">
                <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a>
              |
              <a href="{{url('/v2/daily-input-status-download?type=xls&date='.($date ?? date("Y-m-d")))}}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">

          <form action="{{ url('/v2/daily-input-status') }}" method="get">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-3">
                  <input type="date" name="date" class="form-control form-control-sm" required="required"
                    value="{{ $date ?? null }}">
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
            <table class="reportTable" id="fixTable">
              @include('inputdroplets::reports.v2.tables.daily_input_status_table')
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection