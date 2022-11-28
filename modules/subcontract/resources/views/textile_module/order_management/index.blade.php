@extends('subcontract::layout')
@section("title","Sub Textile Order Management")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Sub Textile Order Management</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 m-b">
                        <a href="/subcontract/textile-orders/create" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>&nbsp;Create</a>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company</th>
                                <th>Party</th>
                                <th>Order No</th>
                                <th>Receive Date</th>
                                <th>Currency</th>
                                <th>Payment Basis</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            {!! Form::open(['url'=>'/subcontract/textile-orders', 'method'=>'GET']) !!}
                            <tr>
                                <td></td>
                                <td>
                                    {!! Form::select('company', $factories,request('company'), ['class'=>'text-center select2-input']) !!}

                                </td>
                                <td>
                                    {!! Form::select('party', $parties ?? [], request('party'), ['class'=>'text-center select2-input', 'id'=>'party'] ) !!}
                                </td>
                                <td>
                                    <input type="text" name="order_no" placeholder="Search"
                                           style="width: 90%;border: 1px solid #cecece;"
                                           class="text-center" value="{{ request('order_no') }}">
                                </td>
                                <td>
                                    <input type="date" name="receive_date" placeholder="Search"
                                           style="width: 90%;border: 1px solid #cecece;"
                                           class="text-center" value="{{ request('receive_date') }}">
                                </td>
                                <td>
                                    {!! Form::select('currency', $currencies, request('currency'),['class'=>'text-center select2-input'] ) !!}

                                </td>
                                <td>
                                    <select name="payment_basis"
                                            class="text-center select2-input"
                                            value="{{ request('payment_basis') }}">
                                        <option selected disabled hidden>-- Select Payment Basis --</option>
                                        @foreach($payment_basis as $key => $payment)
                                            <option value="{{ $key }}">{{ $payment }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" name="description" placeholder="Search"
                                           style="width: 90%;border: 1px solid #cecece;"
                                           class="text-center" value="{{ request('description') }}">
                                </td>
                                <td>
                                    <button class="btn btn-xs white" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </td>
                            </tr>
                            {!! Form::close() !!}
                            <tr>
                                <td colspan="9">&nbsp;</td>
                            </tr>
                            @foreach($sub_textile_orders as $order)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $order->factory->factory_name }}</td>
                                    <td>{{ $order->supplier->name }}</td>
                                    <td>{{ $order->order_no }}</td>
                                    <td>{{ \Carbon\Carbon::make($order->receive_date)->toFormattedDateString() }}</td>
                                    <td>{{ $order->currency->currency_name }}</td>
                                    <td>{{ $order->payment_basis_value }}</td>
                                    <td>{{ $order->description }}</td>
                                    <td>
                                        @permission('permission_of_order_list_edit')
                                        <a class="btn btn-info btn-sm" type="button"
                                           href="/subcontract/textile-orders/create?id={{$order->id}}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        @endpermission

                                        @permission('permission_of_order_list_view')
                                        <a class="btn btn-success btn-sm" type="button"
                                           href="{{ url('subcontract/view/'.$order->id) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @endpermission

                                        @permission('permission_of_order_list_delete')
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('subcontract/textile-orders/'.$order->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        @endpermission
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $sub_textile_orders->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')
    <script>
        // $(document).ready(function () {
        //     $('#party').select2({
        //         ajax: {
        //             url: "/subcontract/api/v1/textile-parties/select-search",
        //             dataType: 'json',
        //             data: function (params) {
        //                 return {
        //                     search: params.term
        //                 };
        //             },
        //             processResults: function (response, params) {
        //                 return {
        //                     results: response.data,
        //                     pagination: {
        //                         more: false
        //                     }
        //                 };
        //             },
        //             cache: true,
        //             delay: 150,
        //         },
        //         placeholder: 'Search',
        //         allowClear: true,
        //     });
        //
        // });

    </script>
@endsection
