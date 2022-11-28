@extends('skeleton::layout')
@section("title", "Trims MRR")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Trims MRR</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 m-b">
                        <a href="{{ url('inventory/trims-store/mrr/create') }}"
                           class="btn btn-sm white m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <div class="col-md-5 m-b pull-right">
                        {!! Form::open(['url'=>'inventory/trims-store/mrr', 'method'=>'GET']) !!}
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
                            <button class="btn btn-sm white m-b" type="submit">Search</button>
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
                                <th>Buyer</th>
                                <th>Store</th>
                                <th>Booking No</th>
                                <th>Booking Date</th>
                                <th>Challan No</th>
                                <th>Delivery Date</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'inventory/trims-store/mrr', 'method'=>'GET']) !!}
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
                                    {!! Form::select('buyer_id',  $buyers ?? [], request('buyer_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td></td>
                                <td>
                                    {!! Form::text('booking_no', request('booking_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('booking_date', request('booking_date') ?? null, [
                                       'class'=>'text-center form-control form-control-sm'
                                   ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('challan_no', request('challan_no') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::text('delivery_date', request('delivery_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            @forelse($trimsMrr as $mrr)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $mrr->mrr_no }}</td>
                                    <td>{{ $mrr->factory->factory_name ?? '' }}</td>
                                    <td>{{ $mrr->buyer->name ?? '' }}</td>
                                    <td>{{ $mrr->store->name ?? '' }}</td>
                                    <td>{{ $mrr->booking_no }}</td>
                                    <td>{{ $mrr->booking_date }}</td>
                                    <td>{{ $mrr->challan_no }}</td>
                                    <td>{{ $mrr->delivery_date }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('inventory/trims-store/mrr/create?id='.$mrr->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('inventory/trims-store/mrr/view/'.$mrr->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete MRR"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('inventory-api/v1/trims-store/mrr/'.$mrr->id) }}">
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
                            {{ $trimsMrr->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

