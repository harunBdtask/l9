@extends('finishingdroplets::layout')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Getup Production Update</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form autocomplete="off" action="{{ url('/update-getup-production-action') }}" method="post">
              @csrf
              <input type="hidden" name="challan_no" value="{{ $challan_no ?? 1 }}">

              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'getup-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Style/Order No</label>
                    {!! Form::select('order_id', [], null, ['class' => 'getup-style-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Style/Order']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>PO</label>
                    {!! Form::select('purchase_order_id', [], null, ['class' => 'getup-order-select form-control form-control-sm select2-input', 'placeholder' => 'Select a PO']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Color</label>
                    {!! Form::select('color_id', [], null, ['class' => 'getup-color-select form-control form-control-sm select2-input']) !!}
                  </div>
                </div>
              </div>
              <div class="box-body table-responsive">
                <table class="reportTable">
                  <thead>
                  <tr class="text-center">
                    <th>Size Name</th>
                    <th>Order Qty</th>
                    <th>Previous Qty</th>
                    <th>Add Qty</th>
                  </tr>
                  </thead>
                  <tbody class="getup-update-generate-form">
                  </tbody>
                </table>

                <div class="sutton-area">
                  <button type="submit" style="display: none; margin-left: 500px"
                          class="btn white update-getup-production-btn">Submit
                  </button>
                </div>

            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection
