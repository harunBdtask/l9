@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('title', 'Style & PO Wise Sewing Output Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Style &amp; PO Wise Sewing Output Report || {{ date("jS F, Y") }} <span class="pull-right"><a download-type="pdf" class="order-wise-sewing-output-report-dwnld-btn"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a download-type="xls" class="order-wise-sewing-output-report-dwnld-btn"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span></h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-order-wise-sewing  form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Style/Order</label>
                  {!! Form::select('style_id', [], null, ['class' => 'booking-order-wise-sewing form-control form-control-sm select2-input', 'placeholder' => 'Select a Order/Style']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Order/Style</label>
                  {!! Form::select('style_id', [], null, ['class' => 'style-order-wise-sewing form-control form-control-sm select2-input', 'disabled']) !!}
                </div>
                <div class="col-sm-2">
                  <label>PO</label>
                  {!! Form::select('order_id', [], null, ['class' => 'order-order-wise-sewing form-control form-control-sm select2-input', 'placeholder' => 'Select a PO']) !!}
                </div>
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr class="style-wise-sewing-header">
                  <th>PO</th>
                  <th>Order Qty</th>
                  <th>Cutting Qty</th>
                  <th>WIP In Cutting/Print/Embr.</th>
                  <th>Today's Input to Line</th>
                  <th>Total Input to Line</th>
                  <th>Today's Output</th>
                  <th>Total Sewing Output</th>
                  <th>Sewing Rejection</th>
                  <th>Total Rejection</th>
                  <th>In_line WIP</th>
                  <th>Cut 2 Sewing Ratio(%)</th>
                </tr>
                  <tr class="order-wise-sewing-header" style="display: none">
                    <th>Colour Name</th>
                    <th>Size</th>
                    <th>Order Qty</th>
                    <th>Cutting Qty</th>
                    <th>WIP In Cutting/Print/Embr.</th>
                    <th>Today's Input to Line</th>
                    <th>Total Input to Line</th>
                    <th>Today's Output</th>
                    <th>Total Sewing Output</th>
                    <th>Total Rejection</th>
                    <th>In_line WIP</th>
                    <th>Cut 2 Sewing Ratio(%)</th>
                  </tr>
                </thead>
                <tbody class="order-wise-sewing-output-report">

                </tbody>
              </table>
            </div>
            <div class="loader"></div>

          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
