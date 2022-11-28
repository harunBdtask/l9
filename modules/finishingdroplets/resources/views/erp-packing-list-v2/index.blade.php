@extends('skeleton::layout')
@section('title','ERP Packing List V2')

@section('styles')
    {{-- <style>
        .table-header {
            background: #93dcf9;
        }
    </style> --}}
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    ERP Packing List V2
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        {{--                        @permission('permission_of_main_fabric_bookings_add')--}}
                        <a href="{{ url('/erp-packing-list-v2/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Packing</a>
                        {{--                        @endpermission--}}
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        {{--                        <form action="{{ url('/erp-packing-list-v2') }}" method="GET">--}}
                        {{--                            <div class="input-group">--}}
                        {{--                                <input type="text" class="form-control form-control-sm" name="search"--}}
                        {{--                                       value="{{ request()->search ?? '' }}" placeholder="Search with challan no">--}}
                        {{--                                <span class="input-group-btn">--}}
                        {{--                                            <button class="btn btn-sm white m-b" type="submit">Search</button>--}}
                        {{--                                        </span>--}}
                        {{--                            </div>--}}
                        {{--                        </form>--}}
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>UID</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>PO</th>
                                <th>Total Carton</th>
                                <th>Total Qty</th>
                                <th>Total CBU/M3</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($packingList as $list)
                                <tr>
                                    <th>{{ $list->uid }}</th>
                                    <td>{{ $list->buyer->name }}</td>
                                    <td>{{ $list->order->style_name }}</td>
                                    <td>{{ $list->purchaseOrder->po_no }}</td>
                                    <td>{{ $list->total_carton }}</td>
                                    <td>{{ $list->total_qty }}</td>
                                    <td>{{ $list->total_cbu }}</td>
                                    <td>{{ $list->created_at }}</td>
                                    <td>{{ $list->CreatedByUser->screen_name }}</td>
                                    <td style="padding: 2px">

                                        <a href="{{ url('/erp-packing-list-v2/'.$list->uid)}}"
                                           class="btn btn-xs btn-info"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a href="{{ url('/erp-packing-list-v2/create?uid='.$list->uid)}}"
                                           class="btn btn-xs btn-warning"
                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title=""
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/erp-packing-list-v2/destroy/'. $list->uid) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10" style="text-align: center">No Data Found</th>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $erpPackingList->links() }}
                    </div>
                </div> --}}

            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection


