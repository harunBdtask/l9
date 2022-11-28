@extends('skeleton::layout')
@section('title','Short Fabric Booking')
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
                    Short Fabric Booking
                </h2>
            </div>

            <div class="box-body">
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
                <div class="row">
                    <div class="col-sm-6">
                        @permission('permission_of_short_fabric_bookings_add')
                        <a href="{{ url('short-fabric-bookings/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i> New
                            Short Fabric Booking</a>
                        @endpermission
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/short-fabric-bookings/search') }}" method="GET">
                            <div class="input-group">
                                <input type="hidden" name="paginateNumber" id="paginateNumber" value={{$paginateNumber}}>

                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                

                @include('skeleton::partials.row-number',['allExcel'=>'true', 'noExport'=>'true'])
                <div class="row m-t">
                    <div class="col-sm-12 parentTableFixed"  style="overflow-x: scroll;">
                        <table class="reportTable fixTable">
                            <thead>
                            <tr class="table-header" style="background-color: rgb(148, 218, 251);">
                                @php
                                    $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                                    $search = request('search') ?? null;
                                    $extended = isset($search) ? '&search='. $search  : null;
                                @endphp

                                <th>
                                    <a class="btn btn-sm btn-light" href="{{  url('short-fabric-bookings/search?sort=' . $sort . $extended)}}">
                                        <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">Sl</i>
                                    </a>
                                </th>
                                <th>Company Name</th>
                                <th>Buyer Name</th>
                                <th>Booking No</th>
                                <th>Fabric Source</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Supplier</th>
                                <th>Level</th>
                                <th>Appr.</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($fabricBookings as $fabricBooking)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration + $fabricBookings->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $fabricBooking->factory->factory_short_name ?? $fabricBooking->factory->factory_name }}</td>
                                    <td class="text-left">{{ $fabricBooking->buyer->name }}</td>
                                    <td nowrap>{{ $fabricBooking->unique_id }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @buyerPermission($fabricBooking->buyer->id,'permission_of_short_fabric_bookings_edit')
                                            <a href="{{ url('/short-fabric-bookings/create?fabric_bookings_id=') . $fabricBooking->id }}"
                                            class="text-warning">
                                                <i class="fa fa-edit" style="color:#f0ad4e;"></i>
                                            </a>
                                            @endbuyerPermission
                                            @buyerViewPermission($fabricBooking->buyer->id,'SHORT_FABRIC_BOOKINGS_SUMMARY')
                                            <span>|</span>
                                            <a href="{{ url('/short-fabric-bookings/' . $fabricBooking->id.'/summary-view?type=short') }}"
                                            class="text-success"
                                            target="_blank"
                                            >
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            @endbuyerViewPermission
                                            @buyerPermission($fabricBooking->buyer->id,'permission_of_short_fabric_bookings_delete')
                                            <span>|</span>

                                            <a href="{{ url('/short-fabric-bookings/'.$fabricBooking->id) }}" style="margin-left: 2px;" type="button"
                                                    class="text-danger show-modal"
                                                    title="Delete Budget"
                                                    data-toggle="modal"
                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                    ui-target="#animate"
                                                    data-url="{{ url('/short-fabric-bookings/'.$fabricBooking->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            @endbuyerPermission
                                        </div>
                                    </td>
                                    <td>{{ $fabricBooking->fabric_source_name }}</td>
                                    <td>{{ $fabricBooking->booking_date }}</td>
                                    <td>{{ $fabricBooking->delivery_date }}</td>
                                    <td align="left">{{ $fabricBooking->supplier->name }}</td>
                                    <td>{{ $fabricBooking->level_name }}</td>
                                    <td>
                                        @if($fabricBooking->is_approved == 1)
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($fabricBooking->step > 0 || $fabricBooking->ready_to_approve == 1)
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $fabricBooking->step }}', {{ $fabricBooking->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                            </button>
                                        @elseif($fabricBooking->ready_to_approve != 1)
                                            <i class="fa fa-times label-default-md"></i>
                                        @endif
                                    </td>
                                   
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="11">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $fabricBookings->appends(request()->query())->links() }}
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Approval List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>User</th>
                                        <th>Approve Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="approve-list"></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

                $("#selectOption").change(function(){
                    var selectBox = document.getElementById("selectOption");
                    var selectedValue = (selectBox.value);
                    if (selectedValue == -1){
                        if(window.location.href.indexOf("search") != -1){
                            selectedValue = {{$searchedOrders}};
                    }
                        else{
                            selectedValue = {{$dashboardOverview["Total Short Fabric Booking"]}};
                        }
                    }
                    let url = new URL(window.location.href);
                    url.searchParams.set('paginateNumber',parseInt(selectedValue));
                    window.location.replace(url);
                });
        const approveList = jQuery('.approve-list');

        function getApproveList(step, buyer_id) {
            var buyerId = buyer_id;
            var page = 'Short Fabric Approval';

            $.ajax({
                url: `/get-approval-list/${buyerId}/${page}`,
                type: `get`,
                success: function (data) {
                    approveList.empty();

                    if (data.length) {
                        $.each(data, function (index, value) {
                            $priority = value.priority;
                            approveList.append(`
                            <tr>
                                <td style="padding: 4px; font-weight: bold">${index + 1}</td>
                                <td style="padding: 4px; text-align: left">${value.user}</td>
                                <td style="padding: 4px;">${value.priority <= step ? 'Approved' : 'Un-Approved'}</td>
                           </tr>
                        `);
                        })
                    } else {
                        approveList.append(`
                            <tr>
                                <td colspan="3">No Data Found</td>
                            </tr>
                        `)
                    }
                }
            })

        }
    </script>
@endsection
