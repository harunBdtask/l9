@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@extends('warehouse-management::layout')
@section('title', 'Scan Barcode For Carton Details')
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>Scan Barcode For Carton Details</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message" style="margin-bottom: 20px;">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                        {!! Form::open(['url' => '/warehouse-scan-barcode-check', 'method' => 'POST', 'id' => 'warehouse-barcode-scan-check-form']) !!}
                        <div class="form-group row">
                            <div class="col-sm-8 col-sm-offset-2">
                                {!! Form::text('barcode_no', $barcode_no, ['class' => 'form-control', 'id' => 'barcode_no', 'placeholder' => 'Scan barcode here', 'required' => true]) !!}
                                <span class="text-danger barcode_no"></span>
                            </div>
                        </div>
                        {!! Form::close() !!}
                        @if($report)
                            <div class="table-responsive shipment-scan-table" style="margin-top: 20px;">
                                <div class="col-md-9">
                                    <table class="reportTable {{ $tableHeadColorClass }}">
                                        <thead>
                                        <tr>
                                            <th>Buyer</th>
                                            <th>Order/Style</th>
                                            <th>Purchase Order</th>
                                            <th>Garments Qty</th>
                                            <th>Rack Allocation Status</th>
                                            <th>Allocated Floor</th>
                                            <th>Allocated Rack</th>
                                            <th>Shipment Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($report->count())
                                            <tr>
                                                <td>{{ $report->buyer->name }}</td>
                                                <td>{{ $report->order->style_name }}</td>
                                                <td>{{ $report->purchaseOrder->po_no }}</td>
                                                <td>{{ $report->garments_qty }}</td>
                                                <td class="{{ $report->rack_allocation_status ? 'text-success' : 'text-warning' }}">{{ $report->rack_allocation_status ? 'Allocated' : 'Not Allocated' }}</td>
                                                <td>{{ $report->warehouseFloor->name }}</td>
                                                <td>{{ $report->warehouseRack->name }}</td>
                                                <td class="{{ $report->shipment_status ? 'text-success' : 'text-warning' }}">{{ $report->shipment_status ? 'Shipped' : 'Not Shipped' }}</td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td colspan="8">No Data Found</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <table class="reportTable {{ $tableHeadColorClass }}">
                                        <thead>
                                        <tr>
                                            <th>Color</th>
                                            <th>Size</th>
                                            <th>Quantity</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if($report->warehouseCartonDetails && $report->warehouseCartonDetails->count())
                                            @foreach($report->warehouseCartonDetails as $warehouse_carton_detail)
                                                <tr>
                                                    <td>{{ $warehouse_carton_detail->color->name }}</td>
                                                    <td>{{ $warehouse_carton_detail->size->name }}</td>
                                                    <td>{{ $warehouse_carton_detail->quantity }}</td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <th colspan="2">Total</th>
                                                <th>{{ $report->warehouseCartonDetails->sum('quantity') }}</th>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection