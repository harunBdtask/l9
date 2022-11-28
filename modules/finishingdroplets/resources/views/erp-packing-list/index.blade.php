@extends('skeleton::layout')
@section('title','ERP Packing List')

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
                  ERP Packing List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        {{--                        @permission('permission_of_main_fabric_bookings_add')--}}
                        <a href="{{ url('/erp-packing-list/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Packing</a>
                        {{--                        @endpermission--}}
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/erp-packing-list') }}" method="GET">
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
                                <th>Buyer</th>
                                <th>Style Name</th>
                                <th>Po No</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($erpPackingList as $index => $item)
                                <tr>
                                    <th>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</th>
                                    <td>{{ $item->factory->factory_name ?? '' }}</td>
                                    <td>{{ $item->buyer->name ?? '' }}</td>
                                    <td>{{ $item->style_name ?? '' }}</td>
                                    <td>{{ $item->po_no ?? '' }}</td>
                                    <td style="padding: 2px">
{{--                                        @buyerPermission($fabricBooking->buyer->id,'permission_of_main_fabric_bookings_edit')--}}
                                        <a href="{{ url('/erp-packing-list/' . $item->id . '/edit')}}"
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
                                                data-url="{{ url('/erp-packing-list/'. $item->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
{{--                                        @endbuyerPermission--}}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="6" style="text-align: center">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $erpPackingList->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection


