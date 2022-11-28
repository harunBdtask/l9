@extends('printembrdroplets::layout')
@section('title', 'Daily Print/Embr. Sent & Received Summary')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Date Wise Print/Embr. Sent & Received Summary
            <span class="pull-right">
              <a href="{{url('daily-print-embr-report-download?type=pdf&date='.$date) }}">
              <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
              </a>
              |
              <a
                href="{{url('daily-print-embr-report-download?type=xls&date='.$date)}}">
                <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
              </a>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          {!! Form::open(['url' => '/daily-print-embr-report', 'method' => 'get']) !!}
          <div class="form-group">
            <div class="row m-b">
              <div class="col-sm-3">
                {!! Form::label('date', 'Date', ['class' => 'form-control-label']) !!}
                {!! Form::date('date', $date ?? date('Y-m-d'), ['class' => 'form-control form-control-sm', 'onchange' => 'this.form.submit();']) !!}
              </div>
            </div>
          </div>
          {!! Form::close() !!}
          <div class="table-responsive" id="parentTableFixed">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <th rowspan="2">Buyer</th>
                  <th rowspan="2">Item</th>
                  <th rowspan="2">Ref. No</th>
                  <th rowspan="2">Style</th>
                  <th rowspan="2">Color</th>
                  <th rowspan="2">Order Qty</th>
                  <th colspan="7">Print Section</th>
                  <th colspan="7">Embr. Section</th>
                  <th rowspan="2">Remarks</th>
                </tr>
                <tr>
                  <th>Today Send</th>
                  <th>Prev. Send</th>
                  <th>Total Send</th>
                  <th>Today Rcvd</th>
                  <th>Prev. Rcvd</th>
                  <th>Total Rcvd</th>
                  <th>Balance</th>
                  <th>Today Send</th>
                  <th>Prev. Send</th>
                  <th>Total Send</th>
                  <th>Today Rcvd</th>
                  <th>Prev. Rcvd</th>
                  <th>Total Rcvd</th>
                  <th>Balance</th>
                </tr>
              </thead>
              <tbody>
                @includeIf('printembrdroplets::reports.tables.daily_print_embr_report_iris_fabrics_table')
              </tbody>
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
      line-height: .75;
    }
  }
</style>
@endsection