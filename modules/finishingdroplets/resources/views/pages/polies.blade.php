@extends('finishingdroplets::layout')
@section('styles')
<style type="text/css">
  .add-btn-style {
    padding-bottom: 14px !important;
  }
</style>
@endsection
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <h2>Poly, Iron & Packing List</h2>
    </div>
    <div class="box-body b-t">
      <div class="add-btn-style">
        @if(Session::has('permission_of_poly_iron_packings_add') || getRole() == 'super-admin' || getRole() == 'admin')
        <a class="btn btn-sm white m-b" href="{{ url('poly-iron-packings/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Entry
        </a>
        @endif
      </div>

      @include('partials.response-message')
      <div class="js-response-message text-center"></div>
      <table class="reportTable">
        <thead>
          <tr>
            <th>SL</th>
            <th>Buyer</th>
            <th>Order</th>
            <th>Purchase Order</th>
            <th>Color</th>
            <th>Poly Qty</th>
            <th>Poly Rej. Qty</th>
            <th>Iron Qty</th>
            <th>Iron Rej. Qty</th>
            <th>Packing Qty</th>
            <th>Packing Rej. Qty</th>
            <th>Reason</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @if(!$polies->getCollection()->isEmpty())
            @foreach($polies->getCollection() as $poly)
              <tr class="tr-height">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $poly->buyer->name ?? 'N/A' }}</td>
                <td>{{ $poly->order->order_style_no ?? 'N/A' }}</td>
                <td>{{ $poly->purchaseOrder->po_no ?? 'N/A' }}</td>
                <td>{{ $poly->color->name ?? 'N/A' }}</td>
                <td>{{ $poly->poly_qty }}</td>
                <td>{{ $poly->poly_rejection_qty }}</td>
                <td>{{ $poly->iron_qty }}</td>
                <td>{{ $poly->iron_rejection_qty }}</td>
                <td>{{ $poly->packing_qty }}</td>
                <td>{{ $poly->packing_rejection_qty }}</td>
                <td>{{ $poly->reason }}</td>
                <td>{{ $poly->created_at->format('Y-m-d') }}</td>
                <td>
                  @if(Session::has('permission_of_poly_iron_packings_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-xs btn-success" href="{{ url('/poly/'.$poly->id.'/edit/') }}"><i class="fa fa-edit"></i></a>
                  @endif
                  @if(Session::has('permission_of_poly_iron_packings_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                    <button type="button" value="{{ $poly->id }}" class="btn btn-xs btn-danger delete-poly-cartoon-btn">
                      <i class="fa fa-times"></i>
                    </button>
                  @endif
                </td>
              </tr>
            @endforeach
          @else
            <tr class="tr-height">
              <td colspan="14" class="text-center text-danger">Not found data</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
            @if($polies->total() > 15)
              <tr>
                <td colspan="14" class="text-center">{{ $polies->appends(request()->except('page'))->links() }}</td>
              </tr>
            @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
