@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('cuttingdroplets::layout')
@section('title', 'Color Wise Cutting Production Summary Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Color Wise Cutting Production Summary Report
              <span class="pull-right">
                <a download-type="pdf" class="color-wise-cutting-repot-dwnld-btn">
                  <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                </a>
                |
                <a download-type="xls" class="color-wise-cutting-repot-dwnld-btn">
                  <i style="color: #0F733B" class="fa fa-file-excel-o"></i>
                </a>
              </span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body cutting-clr-summary">
            <div class="form-group">
              <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select-rep form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Order/Style</label>
                    {!! Form::select('style_id', [], null, ['class' => 'style-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Order/Style']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>PO</label>
                    {!! Form::select('order_id', [], null, ['class' => 'order-select form-control form-control-sm select2-input', 'placeholder' => 'Select a PO']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Color</label>
                    {!! Form::select('color_id', [], null, ['class' => 'color-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Color']) !!}
                  </div>
                </div>
              </div>

            {{--
            <table class="reportTable">
              <thead>
                <tr>
                    <th>Table No.</th>
                    <th>Buyer</th>
                    <th>Our Reference</th>
                    <th>PO</th>
                    <th>Color</th>
                    <th>Cutting No.</th>
                    <th>Bundle Quantity</th>
                    <th>Cutting Production</th>
                    <th>Date of Cutting</th>
                </tr>
              </thead>
              <tbody class="color-wise-summary-table">
              </tbody>
            </table>
            --}}
              <div id="parentTableFixed" class="table-responsive">
                <table class="reportTable" id="fixTable">
                  <thead>
                    <tr>
                      <th>Size Name</th>
                      <th>Order Quantity</th>
                      <th>Cutting Quantity</th>
                      <th>Left Quantity</th>
                      <th>Extra Cutting</th>
                    </tr>
                  </thead>
                  <tbody class="size-wise-summary">
                  </tbody>
                </table>
                <span class="loader"></span>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
