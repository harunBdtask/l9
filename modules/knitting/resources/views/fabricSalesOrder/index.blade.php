@extends('skeleton::layout')
@section('title','Fabric Sales Orders')
@section('content')
    <style>
        .border-right {
            border-right: 1px solid #f0f0f0;
            border-right-width: 1px;
            border-right-style: solid;
            border-right-color: rgb(240, 240, 240);
        }
    </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <div>
                    <h2>Fabric Sales Orders</h2>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('inventory::partials.flash')
                    </div>
                    <div class="col-md-12">

                        @permission('permission_of_fabric_sales_order_add')
                        <a class="btn btn-sm btn-info m-b" href="{{ url('knitting/fabric-sales-order/create') }}">
                            <i class="fa fa-plus"></i> New Fabric Sales Order
                        </a>
                        @endpermission

                    </div>

                    @include('skeleton::partials.dashboard',$dashboardOverview)

                    @include('skeleton::partials.table-export')


                    <div class="col-md-12 m-t-1" style=" padding-top:20px">
                        <form class="table-responsive" action="/knitting/fabric-sales-order">
                            <table class="reportTable-zero-padding">
                                <thead title="Press shift & scroll mouse for horizontal scroll">
                                <tr style="background: #0ab4e6;">
                                    <th nowrap class="p-x-2">SL</th>
                                    <th nowrap class="p-x-2">S.O.No</th>
                                    <th nowrap class="p-x-2">Type</th>
                                    <th nowrap class="p-x-2">Year</th>
                                    <th nowrap class="p-x-2">Within.Grp</th>
                                    <th nowrap class="p-x-2">Buyer</th>
                                    <th nowrap class="p-x-2">Booking No</th>
                                    <th nowrap class="p-x-2">Booking date</th>
                                    <th class="p-x-2" style="">Style</th>
                                    <th nowrap class="p-x-2" style="min-width:240px">Location</th>
                                    <th nowrap class="p-x-2">B.T.Status</th>
                                    <th nowrap class="p-x-2">O.Status</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>

                                        <select
                                            name="sales_order_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($salesOrderNo as $salesOrder)
                                                <option
                                                    @if(request()->get('sales_order_no') == $salesOrder) selected @endif
                                                value="{{ $salesOrder }}"> {{ $salesOrder }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                            <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                            <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                        </select>
                                    </th>
                                    <th>
                                        {!! Form::selectRange('year', date('Y'), 2010, request('year'),
                                            ['class'=>"form-control select2-input", 'id'=>"year", 'name'=>"year"])
                                        !!}
                                    </th>
                                    <th>
                                        <select
                                            name="within_group"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option @if(request()->get('within_group') == 1) selected @endif value="1">
                                                Yes
                                            </option>
                                            <option @if(request()->get('within_group') == 2) selected @endif value="2">
                                                No
                                            </option>
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="buyer_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($buyer as $value)
                                                <option
                                                    @if(request()->get('buyer_id') == $value->id) selected @endif
                                                value="{{ $value->id }}"> {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="booking_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($fabricSalesOrderBookingNo as $key => $saleorder)
                                                <option
                                                    @if(request()->get('booking_no') == $saleorder) selected
                                                    @endif
                                                    value="{{ $saleorder }}"> {{ $saleorder }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="booking_date"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($salesOrders as $saleorder)
                                                <option
                                                    @if(request()->get('booking_date') == $saleorder->booking_date) selected
                                                    @endif
                                                    value="{{ $saleorder->booking_date}}"> {{ $saleorder->booking_date }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </th>
                                    <th>
                                        <select
                                            name="style_name"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($salesOrders as $saleorder)
                                                <option
                                                    @if(request()->get('style_name') == $saleorder->style_name) selected
                                                    @endif
                                                    value="{{ $saleorder->style_name }}"> {{ $saleorder->style_name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </th>
                                    <th>
                                        <select
                                            name="location"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($salesOrders as $saleorder)
                                                <option
                                                    @if(request()->get('location') == $saleorder->location) selected
                                                    @endif
                                                    value="{{ $saleorder->location}}"> {{ $saleorder->location }}
                                                </option>
                                            @endforeach
                                        </select>


                                    </th>
                                    <th>
                                        <select
                                            name="booking_type_status"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach(\SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::BOOKING_TYPE_STATUS as $key => $value)
                                                <option
                                                    @if(request()->get('booking_type_status') == $key) selected
                                                    @endif value="{{ $key }}"> {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <div style="display:flex; margin-top:15px ">
                                            <select
                                                name="order_status"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach(\SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::ORDER_STATUS as $key => $value)
                                                    <option
                                                        @if(request()->get('order_status') == $key) selected
                                                        @endif value="{{ $key }}"> {{ $value }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div>
                                                <button class="btn btn-xs white">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                                <a class="btn btn-xs btn-warning"
                                                   href="{{ url('knitting/fabric-sales-order') }}">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </th>


                                </tr>
                                </thead>
                                @forelse($salesOrders as $key => $value)
                                    <tr class="tooltip-data row-options-parent">
                                        <td nowrap class="p-x-2">{{ str_pad($loop->iteration,2,0,STR_PAD_LEFT)}}</td>


                                        <td nowrap class="p-x-2 wide-row">{{ $value->sales_order_no }}
                                            <br>
                                            <div class="row-options" style="display:none ">
                                                @permission('permission_of_fabric_sales_order_view')
                                                <a href="/knitting/fabric-sales-order/{{ $value->id }}/view"
                                                   class=" text-success"
                                                   target="_blank"
                                                   title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <span>|</span>

                                                <a href="/knitting/fabric-sales-order/{{ $value->id }}/view-v2"
                                                   class="  text-info"
                                                   target="_blank"
                                                   title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <span>|</span>
                                                @endpermission

                                                @permission('permission_of_fabric_sales_order_edit')
                                                <a href="/knitting/fabric-sales-order/{{ $value->id }}/edit"
                                                   class=" text-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>

                                                </a>
                                                <span>|</span>
                                                @endpermission

                                                @permission('permission_of_fabric_sales_order_delete')
                                                <a href="{{ url('/knitting/fabric-sales-order/'.$value->id.'/delete') }}"

                                                   data-toggle="modal"
                                                   ui-target="#animate"
                                                   ui-toggle-class="flip-x"
                                                   title="Delete"
                                                   data-target="#confirmationModal"
                                                   data-url="{{ url('/knitting/fabric-sales-order/'.$value->id.'/delete') }}"
                                                   class=" text-danger show-modal">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <span>|</span>
                                                @endpermission
                                            </div>

                                        </td>
                                        <td nowrap class="p-x-2 text-capitalize">{{ $value->booking_type }}</td>
                                        <td nowrap class="p-x-2">{{ date('Y', strtotime($value->receive_date)) }}</td>
                                        <td nowrap
                                            class="p-x-2">{{ \SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::WITHIN_GROUP[$value->within_group] }}</td>
                                        <td nowrap class="p-x-2">{{ optional($value->buyerData)->name }}</td>
                                        <td nowrap class="p-x-2">{{ $value->booking_no }}</td>
                                        <td nowrap class="p-x-2">{{ $value->booking_date }}</td>
                                        <td>{{ $value->style_name }}</td>
                                        <td class="p-x-2">{{ $value->location }}</td>
                                        <td class="p-x-2">{{ \SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::BOOKING_TYPE_STATUS[$value->booking_type_status] ?? null }}</td>
                                        <td class="p-x-2">{{ \SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::ORDER_STATUS[$value->order_status] ?? null }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13">No data available</td>
                                    </tr>
                                @endforelse
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $salesOrders->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

