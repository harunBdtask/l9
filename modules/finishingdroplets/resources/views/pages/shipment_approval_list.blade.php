@extends('finishingdroplets::layout')

@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Shipment Approval List</h2>
      </div>

      <div class="response-message text-center" style="margin-top: 20px;">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has('alert-' . $msg))
            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
          @endif
        @endforeach
      </div>

      <div class="box-body">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>SL</th>
            <th>Buyer Name</th>
            <th>Style/Order No</th>
            <th>PO</th>
            <th>Ship Qty</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @if(!$shipment_approval_list->getCollection()->isEmpty())
            @foreach($shipment_approval_list->groupBy('purchase_order_id') as $groupByOrder)
              @php
                $purchase_order_id = $groupByOrder->first()->purchase_order_id;
                $buyer_name = $groupByOrder->first()->buyer->name ?? 'N/A';
                $order_style_no = $groupByOrder->first()->order->order_style_no ?? 'N/A';
                $purchase_order_no = $groupByOrder->first()->purchaseOrder->po_no ?? 'N/A';
                $ship_qty = 0;
                foreach($groupByOrder as $shipment) {
                    $ship_qty += $shipment->ship_quantity ?? 0;
                }
              @endphp
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $buyer_name ?? 'N/A' }}</td>
                <td>{{ $order_style_no?? 'N/A' }}</td>
                <td>{{ $purchase_order_no ?? 'N/A' }}</td>
                <td>{{ $ship_qty ?? 0 }}</td>
                <td>
                  <a href="{{ url('/shipment-status-approval/'.$purchase_order_id) }}"
                     class="btn btn-sm white">
                    Approve
                  </a>
                </td>
              </tr>
            @endforeach
            @if($shipment_approval_list->total() > 15)
              <tr>
                <td colspan="10"
                    class="text-center">{{ $shipment_approval_list->appends(request()->except('page'))->links() }}</td>
              </tr>
            @endif
          @else
            <tr>
              <td colspan="10" align="center">No Data
              <td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection