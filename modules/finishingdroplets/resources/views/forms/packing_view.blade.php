@extends('finishingdroplets::layout')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Packing Details</h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <table class="reportTable">
              <thead>
              <tr>
                <th colspan="4">Packing Challan No.: {{ $packing_details->first()->challan_no }} </th>
              </tr>
              <tr>
                <th>Buyer: {{ $packing_details->first()->buyer->name ?? '' }}</th>
                <th> Style/Order No: {{ $packing_details->first()->order->order_style_no ?? '' }}</th>
                <th> PO: {{ $packing_details->first()->purchaseOrder->po_no ?? '' }}</th>
                <th>Color: {{ $packing_details->first()->color->name ?? '' }}</th>
              </tr>
              <tr>
                <th>Size Name</th>
                <th>Order Qty</th>
                <th>Pack Qty</th>
                <th>Short/Excess</th>
              </tr>
              </thead>
              <tbody>
              @if(!empty($packing_details))
                @foreach($packing_details as $packing)
                  <tr>
                    <td>{{ $packing->size->name }}</td>
                    <td>{{ $packing->size_order_qty }}</td>
                    <td>{{ $packing->quantity }}</td>
                    <td>{{ $packing->quantity - $packing->size_order_qty}}</td>
                  </tr>
                @endforeach
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
