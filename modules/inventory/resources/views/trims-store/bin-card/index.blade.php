@extends('subcontract::layout')
@section("title","Trims Bin Card")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims Bin Card</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 m-b">
                        <a href="{{ url('inventory/trims-store/bin-card/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <div class="col-md-5 m-b pull-right">
                        {!! Form::open(['url'=>'inventory/trims-store/bin-card', 'method'=>'GET']) !!}
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
                                <th>MRR No</th>
                                <th>Factory</th>
                                <th>Booking Date</th>
                                <th>Booking No</th>
                                <th>Booking Qty</th>
                                <th>Challan No</th>
                                <th>PI No</th>
                                <th>Delivery Date</th>
                                <th>Delivery Qty</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'inventory/trims-store/bin-card', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::text('mrr_no', request('mrr_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('factory_id',  $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('booking_date', request('booking_date') ?? null, [
                                       'class'=>'text-center form-control form-control-sm'
                                   ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('booking_no', request('booking_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::text('challan_no', request('challan_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::date('delivery_date', request('delivery_date') ?? null, [
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
                            @forelse($binCards as $binCard)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $binCard->trimsStoreMRR->mrr_no ?? '' }}</td>
                                    <td>{{ $binCard->factory->factory_name }}</td>
                                    <td>{{ $binCard->booking_date }}</td>
                                    <td>{{ $binCard->booking_no }}</td>
                                    <td>{{ $binCard->booking_qty }}</td>
                                    <td>{{ $binCard->challan_no }}</td>
                                    <td>{{ $binCard->pi_no }}</td>
                                    <td>{{ $binCard->delivery_date }}</td>
                                    <td>{{ $binCard->delivery_qty }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('inventory/trims-store/bin-card/create?id='.$binCard->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('inventory/trims-store/bin-card/view/'.$binCard->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Inventory"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('inventory-api/v1/trims-store/bin-card/'.$binCard->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" align="center">No Data Found!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

