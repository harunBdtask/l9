@extends('iedroplets::layout')
@section('title', 'Shipment Entry Update')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Shipment Entry Update</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            @include('partials.response-message')
            <form action="{{ url('/shipment-status-inspection-date-post')}}" method="post">
              @csrf
              <div class="form-group">
                <div class="row m-b">
                    <div class="col-sm-2">
                      <label>Buyer</label>
                      {!! Form::select('buyer_id', $buyers, null, ['class' => 'shipment-buyer-select form-control form-control-sm select2-input', 'placeholder' => 'Select a Buyer']) !!}
                        @if($errors->has('buyer_id'))
                          <span class="text-danger">{{ $errors->first('buyer_id') }}</span>
                        @endif
                    </div>
                    @php
                      if(old('buyer_id')) {
                        $orders_list = \SkylarkSoft\GoRMG\Merchandising\Models\Order::getBookingNoByBuyer(old('buyer_id'));
                      }
                    @endphp
                    <div class="col-sm-2">
                      <label>Style/Order</label>
                      {!! Form::select('order_id', $orders_list ?? [], old('order_id') ?? null, ['class' => 'shipment-style-select form-control form-control-sm select2-input']) !!}
                        @if($errors->has('order_id'))
                            <span class="text-danger">{{ $errors->first('order_id') }}</span>
                        @endif
                    </div>
                </div>
              </div>

              <table class="reportTable">
                <thead class="text-center">
                  <tr>
                    <th>PO</th>
                    <th>PO Qty</th>
                    <th>Total Ship Qty</th>
                    <th>Ship Qty</th>
                    <th>Short/Reject Qty</th>
                    <th>Reasons</th>
                  </tr>
                </thead>
                <tbody class="shipment-status-update">
                </tbody>
              </table>
	          </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
