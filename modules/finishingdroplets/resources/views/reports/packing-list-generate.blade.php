@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('finishingdroplets::layout')
@section('title', 'Packing List Generate')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Cutting No. Wise Cutting Production Report || {{ date("D\ - F d- Y") }}</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body cutting-no">

              @include('partials.response-message')

              <form>
                <div class="form-group">
                  <div class="row m-b">
                      <div class="col-sm-2">
                        <label>Buyer</label>
                        {!! Form::select('buyer_id', $buyers, null, ['class' => 'buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                      </div>
                      <div class="col-sm-2">
                        <label>PO</label>
                        {!! Form::select('purchase_order_id', [], null, ['class' => 'order-select form-control form-control-sm select2-input']) !!}
                      </div>
                      <div class="col-sm-2">
                        <label>Color</label>
                        {!! Form::select('color_id', [], null, ['class' => 'color-select form-control form-control-sm select2-input']) !!}
                      </div>
                      <div class="col-sm-2">
                        <label>Cutting No</label>
                        {!! Form::select('cutting_no', [], null, ['class' => 'cutting-no-select form-control form-control-sm select2-input']) !!}
                      </div>
                    </div>
                  </div>
              </form>

              <div id="parentTableFixed" class="table-responsive">
                <table class="reportTable" id="fixTable">
                  <thead>
                    <tr>
                      <th>Size Name</th>
                      <th>Total Bundle</th>
                      <th>Cutting Quantity</th>
                      <th>Cutting Date</th>
                    </tr>
                  </thead>
                  <tbody class="cutting-no-wise-report">
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
