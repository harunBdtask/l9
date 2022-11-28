@extends('washingdroplets::layout')
@section('title', 'Received From Washing')

@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Received From Wash</h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body color-finishing">
          <div class="box-body">
            <div class="js-response-message text-center"></div>
            <div class="validation-message alert alert-danger text-center" style="display:none"></div>
            <form id="washingReceivedForm">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                  <div class="col-sm-2">
                    <label>Buyer</label>
                    {!! Form::select('buyer_id', $buyers, null, ['class' => 'washing-received-buyer form-control form-control-sm
                    select2-input', 'placeholder' => 'Select a Buyer']) !!}
                  </div>
                  <div class="col-sm-2">
                    <label>Order/Style</label>
                    {!! Form::select('order_id', [], null, ['class' => 'washing-received-order form-control form-control-sm
                    select2-input']) !!}
                  </div>
                </div>
              </div>

              <div class="box-divider m-a-0"></div>

              <br />
              <b>Challan No : </b><input type="text" name="challan_no" class="form-control form-control-sm" placeholder="Enter Challan No">
              <div class="col-sm-12 uniqueColorList" style="padding-top: 20px; padding-bottom: 30px"></div>

              <table class="reportTable" style="">
                <thead>
                  <tr>
                    <th>PO</th>
                    <th>Color</th>
                    <th>Order Qty</th>
                    <th>Total Sent</th>
                    <th>Total Received</th>
                    <th>Balance</th>
                    <th>Received</th>
                    <th>Short/Rejection Qty</th>
                    <th width="20%">Reasons</th>
                  </tr>
                </thead>
                <tbody class="poColorList">
                </tbody>
              </table>

              <div class="form-group m-t-md washing-recv-submit-btn" style="display: none">
                <div class="col-sm-4 col-sm-offset-4 text-center">
                  <button type="button" class="btn white washing-rcv-save-btn">Submit</button>
                  <a href="{{ url('/') }}" class="btn white">Cancel</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script src="{{ asset('protracker/custom.js') }}"></script>
<script src="{{ asset('protracker/washing.js') }}"></script>
@endsection
