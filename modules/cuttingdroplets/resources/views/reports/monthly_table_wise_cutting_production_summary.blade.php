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
@section('title', 'Monthly Table Wise Production Summary')
@section('content')
  <div class="padding buyer-wise-sewing-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Monthly Table Wise Production Summary
              <span class="pull-right">
                                <a href="{{ ($cutting_floor_id) ? url('/monthly-table-wise-cutting-production-summary-report-download?type=pdf&cutting_floor_id='.$cutting_floor_id.'&cutting_table_id='.$cutting_table_id. '&year='.$year.'&month='.$month) : '#' }}"><i
                                      style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                <a href="{{ ($cutting_floor_id) ? url('/monthly-table-wise-cutting-production-summary-report-download?type=xls&cutting_floor_id='.$cutting_floor_id.'&cutting_table_id='.$cutting_table_id. '&year='.$year.'&month='.$month) : '#' }}"><i
                                      style="color: #0F733B" class="fa fa-file-excel-o"></i></a>
                            </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body color-sewing-output">
            <div class="form-group">
              {!! Form::open(['url' => '/monthly-table-wise-cutting-production-summary-report','method' => 'GET']) !!}
              <div class="row m-b">
                <div class="col-sm-3">
                  <label>Floor</label>
                  {!! Form::select('cutting_floor_id', $cutting_floors, $cutting_floor_id, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Floor', 'onchange' => 'this.form.submit();']) !!}
                </div>
                <div class="col-sm-3">
                  <label>Table</label>
                  {!! Form::select('cutting_table_id', $cutting_tables, $cutting_table_id, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Table', 'onchange' => 'this.form.submit();']) !!}
                </div>
                <div class="col-sm-3">
                  <label>Year</label>
                  {!! Form::selectYear('year', date('Y'), 2019, $year, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Year', 'onchange' => 'this.form.submit();']) !!}
                </div>
                <div class="col-sm-3">
                  <label>Month</label>
                  {!! Form::selectMonth('month', $month, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Month', 'onchange' => 'this.form.submit();']) !!}
                </div>
              </div>
              {!! Form::close() !!}
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                @includeIf('cuttingdroplets::reports.tables.monthly_table_wise_production_summary_table')
              </table>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
