@extends('cuttingdroplets::layout')
@section('title', 'Month Wise Cutting Production Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Month Wise Cutting Production Report
              @if(isset($from_date) && isset($to_date))
                <span class="pull-right">
                  <a href="{{ url('/month-wise-cutting-report-download/pdf/'.$from_date.'/'.$to_date) }}" >
                    <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                  </a>
                  |
                  <a href="{{ url('/month-wise-cutting-report-download/xls/'.$from_date.'/'.$to_date) }}">
                    <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                  </a>
                </span>
              @endif
          </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
              <form action="{{ url('/month-wise-cutting-report') }}" method="get">
                <div class="form-group">
                  <div class="row m-b">
                    <div class="col-sm-3">
                        <label>From Date</label>
                        <input type="date" name="from_date" class="form-control form-control-sm" required="required" value="{{ $from_date ?? '' }}">
                        @if($errors->has('from_date'))
                          <span class="text-danger">{{ $errors->first('from_date') }}</span>
                        @endif
                    </div>
                    <div class="col-sm-3">
                        <label>End Date</label>
                        <input type="date" name="to_date" class="form-control form-control-sm" required="required" value="{{ $to_date ?? '' }}">
                        @if($errors->has('to_date'))
                          <span class="text-danger">{{ $errors->first('to_date') }}</span>
                        @endif
                        @if(Session::has('error'))
                            <span class="text-danger">{{ Session::get('error') }}</span>
                        @endif
                    </div>
                    <div class="col-sm-2">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-sm btn-info form-control form-control-sm">Search</button>
                    </div>
                  </div>
                </div>
              </form>
              <div id="parentTableFixed" class="table-responsive">
                @include('cuttingdroplets::reports.tables.month_wise_cutting_summary_table')
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
      @media screen and (-webkit-min-device-pixel-ratio: 0){
      input[type=date].form-control form-control-sm{
        line-height: 1;
      }
      }
  </style>
@endsection
