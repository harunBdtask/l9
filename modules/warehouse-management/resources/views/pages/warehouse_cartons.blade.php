@extends('warehouse-management::layout')
@section('title', 'Warehouse Cartons')
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_warehouse_cartons_view') || getRole() == 'super-admin' || getRole() == 'admin')
            <div class="box">
                <div class="box-header">
                    <h2>Warehouse Cartons</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t">
                    <div>
                        @if(Session::has('permission_of_warehouse_cartons_add') || getRole() == 'super-admin' || getRole() == 'admin')
                            <a href="{{url('warehouse-cartons/create')}}" class="btn btn-info add-new-btn btn-sm">
                                <i class="glyphicon glyphicon-plus"></i> Add New
                            </a>
                        @endif
                        <div class="pull-right">
                            <form action="{{ url('/warehouse-cartons/search') }}" method="GET">
                                <div class="pull-left" style="margin-right: 10px;">
                                    <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}">
                                </div>
                                <div class="pull-right">
                                    <input type="submit" class="btn btn-md btn-info" value="Search">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive" style="margin-top: 20px; min-height: 300px;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Buyer</th>
                                <th>Order/ Style</th>
                                <th>Purchase Order</th>
                                <th>Garments Qty</th>
                                <th>Allocated Floor</th>
                                <th>Allocated Rack</th>
                                <th>Shipment Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!$warehouse_cartons->getCollection()->isEmpty())
                                @foreach($warehouse_cartons->getCollection() as $warehouse_carton)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $warehouse_carton->buyer->name }}</td>
                                        <td>{{ $warehouse_carton->order->style_name }}</td>
                                        <td>{{ $warehouse_carton->purchaseOrder->po_no }}</td>
                                        <td>{{ $warehouse_carton->garments_qty }}</td>
                                        <td>{{ $warehouse_carton->warehouseFloor ? $warehouse_carton->warehouseFloor->name : '' }}</td>
                                        <td>{{ $warehouse_carton->warehouseRack ? $warehouse_carton->warehouseRack->name : '' }}</td>
                                        <td>
                                            <span class="{{ $warehouse_carton->shipment_status ? 'text-success' : 'text-warning' }}">{{ $warehouse_carton->shipment_status ? 'Shipped' : 'Not Shipped' }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown inline">
                                                <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown"
                                                        aria-expanded="false">Action
                                                </button>
                                                <div class="dropdown-menu pull-right">
                                                    @if (Session::has('permission_of_warehouse_cartons_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                                        <a href="{{ url('/warehouse-cartons/' . $warehouse_carton->id.'/edit') }}"
                                                           class="dropdown-item">Edit</a>
                                                    @endif
                                                    <a href="{{ url('/warehouse-cartons/' . $warehouse_carton->id.'/show') }}"
                                                       class="dropdown-item">View Barcode</a>
                                                    @if (Session::has('permission_of_warehouse_cartons_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                                        <a href="#" class="dropdown-item white show-modal"
                                                           data-toggle="modal" data-target="#confirmationModal"
                                                           ui-toggle-class="flip-x" ui-target="#animate"
                                                           data-url="{{ url('/warehouse-cartons/' . $warehouse_carton->id) }}">Delete</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                @if($warehouse_cartons->total() > 15)
                                    <tr>
                                        <td colspan="9"
                                            class="text-center">{{ $warehouse_cartons->appends(request()->except('page'))->links() }}</td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td colspan="9" class="text-center">No Data</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
