@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('cuttingdroplets::layout')
@section('title', 'Style, PO & Color Wise Cutting Production Report')
@section('content')
  <div class="padding po-wise-cutting-report-page">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Style, PO & Color Wise Cutting Production Report
              <span class="pull-right">
                <a download-type="pdf" class="order-wise-cutting-repot-dwnld-btn" id="pdf-download">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a>
                |
                <a download-type="xls" class="order-wise-cutting-repot-dwnld-btn" id="excel-download">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body cutting-size-report">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, null, ['class' => 'sizze-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style</label>
                  {!! Form::select('style_id', [], null, ['class' => 'sizze-style-select form-control form-control-sm select2-input']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Order/Style</label>
                  {!! Form::select('style_id', [], null, ['class' => 'order-style-dropdon form-control form-control-sm select2-input', 'disabled']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('order_id', [], null, ['class' => 'size-cutting-order-select form-control form-control-sm select2-input']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Color</label>
                  {!! Form::select('color_id', [], null, ['class' => 'size-cutting-color-select form-control form-control-sm select2-input']) !!}
                </div>
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                  <tr class="style-wise-report-header">
                    <th>PO</th>
                    <th>PO Quantity</th>
                    <th>Today's Cutting</th>
                    <th>Total Cutting</th>
                    <th>Left Quantity</th>
                  </tr>
                  <tr class="order-wise-report-header" style="display: none;">
                    <th>Color Name</th>
                    <th>Size Name</th>
                    <th>PO Quantity</th>
                    <th>Today's Cutting</th>
                    <th>Total Cutting</th>
                    <th>Left Quantity</th>
                    <th>Extra Cuttting (%)</th>
                  </tr>
                </thead>
                <tbody class="cutting-report-order-wise">
                  <span class="loader"></span>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
