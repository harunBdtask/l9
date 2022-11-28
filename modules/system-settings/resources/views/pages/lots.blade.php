@extends('skeleton::layout')
@section('title', 'Lots')
@section('content')
<div class="padding">
  <div class="box" >
    <div class="box-header">
      <h2>Lot List</h2>
    </div>
    <div class="box-body b-t">
      @include('partials.response-message')
      @if(Session::has('permission_of_lots_add') || getRole() == 'super-admin' || getRole() == 'admin')
        <a class="btn btn-sm white m-b" href="{{ url('lots/create') }}">
          <i class="glyphicon glyphicon-plus"></i> New Lot
        </a>
      @endif
      <div class="pull-right  m-b-1">
        <form action="{{ url('/search-lots') }}" method="GET">
          <div class="pull-left" style="margin-right: 10px;">
            <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
          </div>
          <div class="pull-right">
            <input type="submit" class="btn btn-sm white" value="Search">
          </div>
        </form>
      </div>
      <table class="reportTable">
        <thead>
          <tr>
            <th width="5%">SL</th>
            <th>Lot No.</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>PO</th>
            <th>Color</th>
            <th>Fabric Rcv.</th>
            <th>Fabric Rcv. At</th>
            <th width="12%">Actions</th>
          </tr>
        </thead>
          <tbody>
          @if(!$lots->getCollection()->isEmpty())
            @foreach($lots->getCollection() as $lot)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $lot->lot_no }}</td>
                <td>{{ $lot->buyer->name ?? '' }}</td>
                <td>{{ $lot->order->style_name ?? '' }}</td>
                <td>{{ $lot->purchaseOrders->implode('po_no', ', ') }}</td>
                <td>{{ $lot->color->name ?? 'Deleted' }}</td>
                <td>{{ $lot->fabric_received.' kg' }}</td>
                <td>{{ $lot->fabric_received_at }}</td>
                <td>
                    @if(Session::has('permission_of_lots_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                        <a class="btn btn-sm white" href="{{ url('lots/'.$lot->id.'/edit') }}"><i class="fa fa-edit"></i></a>
                    @endif
                    @if(Session::has('permission_of_lots_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                        <button type="button" class="btn btn-sm white show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('lots/'.$lot->first()->id) }}">
                            <i class="fa fa-times"></i>
                        </button>
                    @endif
                  </td>
                </tr>
            @endforeach
          @else
            <tr class="tr-height">
                <td colspan="9" class="text-center text-danger">No Lots</td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($lots->total() > 15)
              <tr>
                  <td colspan="9" align="center">{{ $lots->appends(request()->except('page'))->links() }}</td>
              </tr>
          @endif
          </tfoot>
      </table>
    </div>
  </div>
</div>
@endsection
