@extends('skeleton::layout')
@section("title", "Trims Delivery Challan")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Delivery Challan</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 m-b">
                        <a href="{{ url('inventory/trims-store/delivery-challan/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <div class="col-md-5 m-b pull-right">
                        {!! Form::open(['url'=>'inventory/trims-store/delivery-challan', 'method'=>'GET']) !!}
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
                                <th>Factory</th>
                                <th>Buyer</th>
                                <th>Store</th>
                                <th>Item Description</th>
                                <th>Challan No</th>
                                <th>Challan Type</th>
                                <th>Challan Date</th>
                                <th>Challan Qty</th>
                                <th>Booking No</th>
                                <th>Booking Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>

                            <tbody>
                            {!! Form::open(['url'=>'inventory/trims-store/delivery-challan', 'method'=>'GET']) !!}
                            <tr>
                                <td>
                                    {!! Form::select('factory_id',  $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('buyer_id',   $buyers ?? [], request('buyer_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::text('item_description', request('item_description') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('challan_no', request('challan_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::date('challan_date', request('challan_date') ?? null, [
                                       'class'=>'text-center form-control form-control-sm'
                                   ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::text('booking_no', request('booking_no') ?? null, [
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
                            @foreach(collect($trimsDeliveryChallans->items())->groupBy('challan_no') as $challanNo => $challans)
                                @php
                                    $challansCollection = collect($challans);
                                @endphp
                                <tr>
                                    <td>{{ $challansCollection->pluck('factory')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challansCollection->pluck('buyer')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challansCollection->pluck('store')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challansCollection->pluck('item_description')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challanNo }}</td>
                                    <td>{{ $challansCollection->pluck('challan_type')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challansCollection->pluck('challan_date')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challansCollection->sum('challan_qty') }}</td>
                                    <td>{{ $challansCollection->pluck('booking_no')->unique()->values()->join(', ') }}</td>
                                    <td>{{ $challansCollection->sum('booking_qty') }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           title="Edit"
                                           href="{{ url('inventory/trims-store/delivery-challan/create?challanNo='.$challanNo) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           title="View"
                                           href="{{ url("inventory/trims-store/delivery-challan/$challanNo/view/") }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Delivery Challan"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('inventory-api/v1/trims-store/delivery-challan/'.$challanNo) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

