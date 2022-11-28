@extends('skeleton::layout')
@section('title','Grey Receive')

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
                    Grey Receive List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        @permission('permission_of_grey_receive_add')
                        <a href="{{ url('/inventory/grey-receive/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Grey Receive</a>
                        @endpermission
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/inventory/grey-receive') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->search ?? '' }}" placeholder="Search with challan no">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>Sl</th>
                                <th>Company</th>
                                <th>Challan No</th>
                                <th>Source</th>
                                <th>Receive Type</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($greyReceives as $index => $item)
                                <tr>
                                    <td><b>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</b></td>
                                    <td>{{ $item->factory_name ?? '' }}</td>
                                    <td>{{ $item->challan_no ?? '' }}</td>
                                    <td>{{ $item->source_name ?? '' }}</td>
                                    <td>{{ $item->received_type_value ?? '' }}</td>
                                    <td style="padding: 2px">
                                        {{--                                        @buyerPermission($fabricBooking->buyer->id,'permission_of_grey_receive_edit')--}}
                                        <a href="{{ url('/inventory/grey-receive/' . $item->id . '/edit')}}"
                                           class="btn btn-xs btn-warning"

                                        >
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        {{--                                        @endbuyerPermission--}}

                                        {{--                                        @buyerPermission($fabricBooking->buyer->id,'permission_of_main_fabric_bookings_delete')--}}
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Budget"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/inventory/grey-receive/'. $item->id .'?challan_no=' . $item->challan_no) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        {{--                                        @endbuyerPermission--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $greyReceives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection


