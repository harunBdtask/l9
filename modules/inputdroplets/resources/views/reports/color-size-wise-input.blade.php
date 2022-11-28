@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('inputdroplets::layout')
@section('title', 'Color Wise Input Report')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Color Wise Input Report || {{ date("jS F, Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body input-color-size-wise">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label>Buyer</label>
                  {!! Form::select('buyer_id', $buyers, null, ['class' => 'color-size-wise-buyer form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Order</label>
                  {!! Form::select('order_id', [], null, ['class' => 'color-size-wise-order form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label>Color</label>
                  {!! Form::select('color_id', [], null, ['class' => 'color-size-wise-color form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                </div>
              </div>
            </div>

            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                  <tr>
                    <th>Size</th>
                    <th>Order Qty</th>
                    <th>Cutt. Prod.</th>
                    <th>Print Send</th>
                    <th>Print Recv.</th>
                    <th>Today's Input Qty</th>
                    <th>Total Input Qty</th>
                  </tr>
                </thead>
                <tbody class="color-size-wise-input">

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
