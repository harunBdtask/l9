@extends('sewingdroplets::layout')
@section('styles')
  <style type="text/css">
    th, td {
      font-size: 11px !important;
      padding: 0px !important;
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
@section('title', 'Line Wise Hourly Sewing Production')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line Wise Hourly Sewing Production || {{ date("jS F, Y", strtotime($date)) }}
              <span class="pull-right">
                @php
                  $date;
                @endphp
                <a href="{{ url("date-wise-hourly-sewing-output-report-download?type=pdf&date=$date") }}">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a>
                |
                <a href="{{ url("date-wise-hourly-sewing-output-report-download?type=xls&date=$date")}}">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <form action="{{ url('/date-wise-hourly-sewing-output') }}" method="GET">
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-3">
                    <input style="line-height: 1" type="date" name="date" class="form-control form-control-sm" required="required"
                           value="{{ $date ?? date('Y-m-d') }}">
                    @if($errors->has('date'))
                      <span class="text-danger">{{ $errors->first('date') }}</span>
                    @endif
                  </div>
                  <div class="col-sm-2">
                    <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                  </div>
                </div>
              </div>
            </form>
            <div id="parentTableFixed" class="table-responsive">
              @include('sewingdroplets::reports.tables.date_wise_hourly_report_table')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
  <script>
    $(document).ready(function () {
      $("#fixTable").tableHeadFixer();
    });
  </script>
@endsection
