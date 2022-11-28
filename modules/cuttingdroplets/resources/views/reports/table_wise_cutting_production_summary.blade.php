@extends('cuttingdroplets::layout')

@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp

@section('styles')
  <style>
    .select2-container .select2-selection--single {
      height: 40px;
      border-radius: 0px;
      line-height: 50px;
      border: 1px solid #e7e7e7;
    }

    .select2-container .select2-selection--single {
      background-color: #ffffff !important;
    }

    .reportTable .select2-container .select2-selection--single {
      border: 1px solid #e7e7e7;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 40px;
      width: 100%;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 8px;
    }

    .error + .select2-container .select2-selection--single {
      border: 1px solid red;
    }

    .select2-container--default .select2-selection--multiple {
      min-height: 40px !important;
      border-radius: 0px;
      width: 100%;
    }
  </style>
@endsection
@section('title', 'Daily Table Wise Cutting & Input Summary')
@section('content')
  <div class="padding buyer-wise-sewing-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Daily Table Wise Cutting & Input Summary
              <span class="pull-right">
                                <a href="{{url('/cutting-production-summary-report-download?type=pdf&buyer_id='.$buyer_id.'&order_id='.$order_id) }}"><i
                                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                <a href="{{url('/cutting-production-summary-report-download?type=xls&buyer_id='.$buyer_id.'&order_id='.$order_id)}}"><i
                                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body color-sewing-output">
            <div class="form-group">
              {!! Form::open(['url' => '/cutting-production-summary-report','method' => 'GET']) !!}
              <div class="row m-b">
                <div class="col-sm-3">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, $buyer_id, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Buyer', 'onchange' => 'this.form.submit();']) !!}
                </div>

                <div class="col-sm-3">
                  <label>Style</label>
                  {!! Form::select('order_id', $orders, $order_id, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Style', 'onchange' => 'this.form.submit();']) !!}
                </div>
              </div>
              {!! Form::close() !!}
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @includeIf('cuttingdroplets::reports.tables.table_wise_production_summary_table')
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
