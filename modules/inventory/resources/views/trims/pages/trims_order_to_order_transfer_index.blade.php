@extends('skeleton::layout')
@section('title','Trims Order To Order Transfer')
@section('content')
<div class="padding">
    <div class="box" >
        <div class="box-header">
            <h2>
                Trims Order To Order Transfer List
            </h2>
        </div>

        <div class="box-body">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ url('/inventory/trims-order-transfer/create') }}" class="btn btn-sm white m-b"><i
                            class="fa fa-plus"></i> New
                        Trims Order To Order Transfer</a>
                </div>
                <!-- <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/commercial/btb-margin-lc/') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div> -->
            </div>
            @include('partials.response-message')
            <div class="row m-t">
                <div class="col-sm-12">
                    <table class="reportTable">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <!-- <th>Issue ID</th>
                                <th>Year</th> -->
                                <th>Style Name </th>
                                <th>Order No</th>
                                <!-- <th>Transfer ID</th> -->
                                <th>Transfer Date</th>
                                <th>Challan No</th>
                                <th>Transfer Qty</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @if(count($trimsOrderToOrderTransfer))
                            @foreach($trimsOrderToOrderTransfer as $key => $transfer)
                            <tr>
                                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>

                                <td>{{$transfer->from_order['style_name']}}-> {{$transfer->to_order['style_name']}}</
                                        <td>
                                <td>{{$transfer->from_order['order_uniq_id']}}->
                                    {{$transfer->to_order['order_uniq_id']}}
                                </td>
                                <!-- <td>
                                    </ <td> -->
                                <td>{{$transfer->transfer_date}}
                                    </ <td>
                                <td>{{$transfer->challan_no}}</ <td>

                                <td>{{$transfer->from_order['transfer_qty']}}

                                </td>


                                <td>
                                    <a href="{{ url('/inventory/trims-order-transfer/' . $transfer->id) . '/edit'}}"
                                        class="btn btn-xs btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                        class="btn btn-xs btn-danger show-modal" title="Delete Realization"
                                        data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x"
                                        ui-target="#animate"
                                        data-url="{{ url('/inventory-api/v1/trims-order-transfer/'.$transfer->id) }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="9" align="text-center">No Data Found</td>
                            </tr>
                            @endif
                        </tbody>


                        <!-- <tbody>

                            <tr>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>
                                <td>--</td>

                                <td style="padding: 2px">
                                    <a href="{{ url('/#') }}" class="btn btn-xs btn-warning">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <button style="margin-left: 2px;" type="button"
                                        class="btn btn-xs btn-danger show-modal" title="Delete Budget"
                                        data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x"
                                        ui-target="#animate" data-url="{{ url('/#') }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>

                            </tr>
                            <tr>
                                <th colspan="10">No Data Found</th>
                            </tr>

                        </tbody> -->
                    </table>
                </div>
            </div>
            <div class="row m-t">
                <div class="col-sm-12">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
