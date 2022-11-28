@extends('subcontract::layout')
@section("title","Trims Inventory")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Inventory</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 m-b">
                        <a href="{{ url('inventory/trims-store/inventory/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <div class="col-md-5 m-b pull-right">
                        {!! Form::open(['url'=>'inventory/trims-store/inventory', 'method'=>'GET']) !!}
                        <div class="col-sm-1">
                            <span class="input-group-btn">
                                <button class="btn btn-sm white m-b" type="button">From:</button>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" name="from_date" type="date"
                                   value="{{ request('from_date') }}"/>
                        </div>
                        <div class="col-sm-1">
                            <span class="input-group-btn">
                                <button class="btn btn-sm white m-b" type="button">To:</button>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" name="to_date" type="date"
                                   value="{{ request('to_date') }}"/>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Booking No</th>
                                <th>Supplier Name</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>Po</th>
                                <th>Booking Qty</th>
                                <th>Received Qty</th>
                                <th>Short / Excess</th>
                                <th>Challan No</th>
                                <th>Challan Date</th>
                                <th>Inventory No</th>
                                <th>Inventory Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'inventory/trims-store/inventory', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::text('booking_no', request('booking_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('supplier_id',  $suppliers ?? [], request('supplier_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('buyer_id',   $buyers ?? [], request('buyer_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('style_name', request('style_name') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('pi_no', request('pi_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>
                                    {!! Form::text('challan_no', request('challan_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('challan_date', request('challan_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('bin_no', request('bin_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            @forelse($trimsInventory as $inventory)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $inventory->booking_no }}</td>
                                    <td>{{ $inventory->booking->supplier->name }}</td>
                                    <td>{{ $inventory->buyer->name }}</td>
                                    <td>{{ $inventory->booking->style }}</td>
                                    <td>{{ $inventory->booking->po_no }}</td>
                                    <td>{{ $inventory->booking_qty }}</td>
                                    <td>{{ collect($inventory->details)->sum('receive_qty') }}</td>
                                    <td>{{ format($inventory->booking_qty - $inventory->delivery_qty, 4) }}</td>
                                    <td>{{ $inventory->challan_no }}</td>
                                    <td>{{ $inventory->challan_date }}</td>
                                    <td>{{ $inventory->bin_no }}</td>
                                    <td>{{ $inventory->challan_date }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('inventory/trims-store/inventory/create?id='.$inventory->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('inventory/trims-store/inventory/view/'.$inventory->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Inventory"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('inventory-api/v1/trims-store/inventory/'.$inventory->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="13" align="center">No Data Found!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $trimsInventory->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

