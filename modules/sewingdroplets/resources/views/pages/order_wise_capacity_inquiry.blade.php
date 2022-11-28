@extends('sewingdroplets::layout')
@section('styles')
<style>
  .select2-container .select2-selection--single {
    height: 40px;
    border-radius: 0px;
    line-height: 50px;
    border: 1px solid #e7e7e7;
  }

  .reportTable .select2-container .select2-selection--single {
    border: 1px solid #e7e7e7;
  }

  .select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 40px;
    width: 150px;
  }

  .select2-container--default .select2-selection--single .select2-selection__arrow {
    top: 8px;
  }

  .error+.select2-container .select2-selection--single {
    border: 1px solid red;
  }

  .select2-container--default .select2-selection--multiple {
    min-height: 40px !important;
    border-radius: 0px;
    width: 100%;
  }

  .custom-input {
    min-height: 40px !important;
    width: 150px;
    border: 1px solid #e7e7e7;
  }

  .custom-action-column {
    min-height: 40px !important;
    width: 100px;
    padding: 0.5rem;
  }

  #loader {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(226, 226, 226, 0.75) no-repeat center center;
    width: 100%;
    z-index: 1000;
  }

  .spin-loader {
    position: relative;
    top: 46%;
    left: 5%;
  }
</style>
@endsection
@section('title', 'Order Wise Capacity Inquiry')
@section('content')
<div class="padding order-wise-capacity-inquiry-page">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Order Wise Capacity Inquiry</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          @include('partials.response-message')
          <div class="box-body">
            {!! Form::open(['url' => '/order-wise-capacity-inquiry-action', 'autocomplete' => 'off', 'method' =>
            'post']) !!}
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-2">
                  <label for="buyer_id">Buyer</label>
                  {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'buyer_id', 'placeholder' => 'Select Buyer']) !!}
                </div>
                <div class="col-sm-2">
                  <label for="order_id">Style/Order</label>
                  {!! Form::select('order_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'order_id', 'placeholder' => 'Select Booking']) !!}
                </div>
                <div class="col-sm-2">
                  <label for="item_id">Garments Item</label>
                  {!! Form::select('item_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'item_id', 'placeholder' => 'Select Garments Item']) !!}
                </div>
                <div class="col-sm-2">
                  <label for="purchase_order_id">Purchase Order</label>
                  {!! Form::select('purchase_order_id', [], null, ['class' => 'form-control form-control-sm', 'id' => 'purchase_order_id', 'placeholder' => 'Select PO']) !!}
                </div>
                <div class="col-sm-2">
                  <label for="smv">SMV</label>
                  {!! Form::text('smv', null, ['class' => 'form-control form-control-sm', 'id' => 'smv', 'placeholder' => 'SMV', 'readonly' => true]) !!}
                </div>
                <div class="col-sm-2">
                  <label for="floor_id">Floor</label>
                  {!! Form::select('floor_id', $floors, null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'floor_id', 'placeholder' => 'Select Floor']) !!}
                </div>
              </div>
            </div>
            {!! Form::close() !!}

            <div class="row">
              <div class="table-responsive poCapacityTableSection" style="width: 40%!important;float: left">

              </div>
              <div class="col-md-12 table-responsive lineCapacityTableSection"
                style="width: 60%!important;float: right">
                &nbsp;
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="loader">
  <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
</div>
@endsection
@section('scripts')
  <script src="{{ asset('protracker/custom.js') }}"></script>
  <script src="{{ asset('protracker/order_wise_capacity_inquiry.js') }}"></script>
@endsection
