@extends('warehouse-management::layout')
@section('title', 'Warehouse Shipment Challan List')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Warehouse Shipment Challan List</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body b-t">
                <div>
                    <div class="pull-right" style="margin-bottom: 20px;">
                        <form action="{{ url('/warehouse-shipment-challans/search') }}" method="GET">
                            <div class="pull-left" style="margin-right: 10px;">
                                <input type="text" class="form-control" name="q" value="{{ $q ?? '' }}" placeholder="Search here">
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
                            <th>Challan No</th>
                            <th>Challan Qty</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(!$warehouse_shipment_challans->getCollection()->isEmpty())
                            @foreach($warehouse_shipment_challans->getCollection() as $warehouse_shipment_challan)
                                @php
                                    $challan_qty = 0;
                                    $warehouse_shipment_challan->warehouseShipmentCartons->each(function ($carton, $key) use(&$challan_qty) {
                                        $challan_qty += $carton->warehouseCarton->garments_qty;
                                    });
                                @endphp
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $warehouse_shipment_challan->challan_no }}</td>
                                    <td>{{ $challan_qty }}</td>
                                    <td>
                                        <div class="dropdown inline">
                                            <button class="btn btn-xs white dropdown-toggle" data-toggle="dropdown"
                                                    aria-expanded="false">Action
                                            </button>
                                            <div class="dropdown-menu pull-right">
                                                <a href="{{ url('/warehouse-shipment-challans/' . $warehouse_shipment_challan->challan_no) }}"
                                                   class="dropdown-item">View Challan</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @if($warehouse_shipment_challans->total() > 15)
                                <tr>
                                    <td colspan="4"
                                        class="text-center">{{ $warehouse_shipment_challans->appends(request()->except('page'))->links() }}</td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td colspan="4" class="text-center">No Data</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
