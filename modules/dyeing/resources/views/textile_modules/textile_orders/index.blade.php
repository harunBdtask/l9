@extends('dyeing::layout')
@section("title","Dyeing Textile Orders")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Textile Orders</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="{{ url('dyeing/textile-orders/create') }}"
                           class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Unique Id</th>
                                <th>Production Date</th>
                                <th>Factory</th>
                                <th>Booking Type</th>
                                <th>Buyer</th>
                                <th>Currency</th>
                                <th>Payment Basis</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/dyeing/textile-orders', 'method'=>'GET']) !!}
                            <tr>
                                <td>*</td>
                                <td>
                                    {!! Form::text('unique_id', request('unique_id') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::date('receive_date', request('receive_date') ?? null, [
                                        'class'=>'text-center form-control form-control-sm'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('factory_id', $factories ?? [], request('factory_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    <select
                                        name="type"
                                        class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                        <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                        <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                    </select>
                                </td>
                                <td>
                                    {!! Form::select('buyer_id', $buyers ?? [], request('buyer_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('currency_id', $currencies ?? [], request('currency_id') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    {!! Form::select('payment_basis', $paymentBasis ?? [], request('payment_basis') ?? null, [
                                        'class'=>'text-center select2-input'
                                    ]) !!}
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <em class="fa fa-search"></em>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="10">&nbsp;</td>
                            </tr>
                            @forelse ($textileOrders as $textileOrder)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $textileOrder->unique_id }}</td>
                                    <td>{{ $textileOrder->receive_date }}</td>
                                    <td>{{ $textileOrder->factory->factory_name }}</td>
                                    <td style="text-transform: capitalize">{{ $textileOrder->fabricSalesOrder->booking_type }}</td>
                                    <td>{{ $textileOrder->buyer->name }}</td>
                                    <td>{{ $textileOrder->currency->currency_name }}</td>
                                    <td>{{ $textileOrder->payment_basis_value }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('dyeing/textile-orders/create?id='.$textileOrder->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('dyeing/textile-orders/view/'.$textileOrder->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('dyeing/textile-orders/'.$textileOrder->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" align="center">No Data</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $textileOrders->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->


    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')
    <script>
    </script>
@endsection
