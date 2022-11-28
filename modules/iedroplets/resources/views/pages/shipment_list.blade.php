@extends('iedroplets::layout')
@section('title', 'Operation Bulletin')
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Shipment Status List</h2>
      </div>
      <div class="box-body b-t">
        @include('partials.response-message')

        @if(Session::has('permission_of_shipments_add') || getRole() == 'super-admin' || getRole() == 'admin')
          <a class="btn btn-sm btn-info m-b" href="{{ url('shipments/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Entry
          </a>
          <div class="pull-right m-b-1">
            <form action="{{ url('/search-shipments') }}" method="GET">
              <div class="pull-left" style="margin-right: 10px;">
                <input type="text" class="form-control form-control-sm" name="q" value="{{ request('q') ?? '' }}"
                       placeholder="Enter search key">
              </div>
              <div class="pull-right">
                <input type="submit" class="btn btn-sm btn-info" value="Search">
              </div>
            </form>
          </div>
        @endif

        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Buyer Name</th>
            <th>Style/Order.</th>
            <th>PO</th>
            <th>Ship Qty</th>
            <th>Short/Reject. Qty</th>
            <th>Reason</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @if(!$shipment_list->getCollection()->isEmpty())
            @foreach($shipment_list as $shipment)
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $shipment->buyer->name }}</td>
                <td>{{ $shipment->order->style_name }}</td>
                <td>{{ $shipment->purchaseOrder->po_no }}</td>
                <td>{{ $shipment->ship_quantity }}</td>
                <td>{{ $shipment->short_reject_qty }}</td>
                <td>{{ $shipment->remarks }}</td>
                <td>{{ $shipment->created_at->format('Y-m-d') }}</td>
                <td>
                  @if(Session::has('permission_of_shipments_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal"
                            data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate"
                            data-url="{{ url('shipments/'.$shipment->id) }}">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
            @if($shipment_list->total() > 15)
              <tr>
                <td colspan="10" class="text-center">
                  {{ $shipment_list->appends(request()->except('page'))->links() }}
                </td>
              </tr>
            @endif
          @else
            <tr>
              <td colspan="9" align="center">No Data
              <td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
