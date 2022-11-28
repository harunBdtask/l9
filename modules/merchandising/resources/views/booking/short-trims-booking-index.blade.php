@extends('skeleton::layout')
@section('title','Short Trims Booking')
@section('styles')
    <style>
        .tooltip-inner {
            background-color: #eee;
            color: black;
        }

        .tooltip.bs-tooltip-right .arrow:before {
            border-right-color: #eee !important;
        }

        .tooltip.bs-tooltip-left .arrow:before {
            border-right-color: #eee !important;
        }

        .tooltip.bs-tooltip-bottom .arrow:before {
            border-right-color: #eee !important;
        }

        .tooltip.bs-tooltip-top .arrow:before {
            border-right-color: #eee !important;
        }

        .tooltip-info {
            text-align: center;
            font-size: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header" style="display: flex; justify-content: space-between;">
                <h2>
                    Short Trims Booking List
                </h2>
            </div>

            <div class="box-body">
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
                <div class="row">
                    <div class="col-md-6">
                        @permission('permission_of_short_trims_bookings_add')
                        <a href="{{ url('/short-trims-bookings/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Short Trims Booking</a>
                        @endpermission
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-6">
                                <form action="{{ url('/short-trims-bookings/search') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="search"
                                               name="search"
                                               value="{{ $search ?? '' }}" placeholder="Search">
                                        <span class="input-group-btn">
                                                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials.response-message')
                <br>
                

                @include('skeleton::partials.row-number',['allExcel'=>'true','noExport'=>'true'])
                <div class="row m-t">
                    <div class="col-sm-12 parentTableFixed" style="overflow-x: scroll;">
                        <table class="reportTable fixTable">
                            <thead>
                            <tr class="table-header">
                                @php
                                    $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                                    $search = request('search') ?? null;
                                    $extended = isset($search) ? '&search='. $search  : null;
                                @endphp

                                <th>
                                    <a class="btn btn-sm btn-light"
                                       href="{{  url('short-trims-bookings/search?sort=' . $sort . $extended)}}">
                                        <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">Sl</i>
                                    </a>
                                </th>
                                <th>Company Name</th>
                                <th>Location</th>
                                <th>Buyer</th>
                                <th>Booking Id</th>
                                <th>Booking Date</th>
                                <th>Supplier</th>
                                <th>Booking Basis</th>
                                <th>Material Source</th>
                                <th>Pay Mode</th>
                                <th>Source</th>
                                <th>Appr.</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($trimsBookings as $key => $booking)
                            <tr class="tooltip-data row-options-parent">
                                <td>{{ $trimsBookings->firstItem()+$key }}</td>
                                <td>{{ $booking->factory->factory_short_name ?? $booking->factory->factory_name }}</td>
                                <td>{{ $booking->location }}</td>
                                <td>{{ $booking->buyer->name }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @buyerPermission($booking->buyer->id,'permission_of_short_trims_bookings_edit')
                                            <a class="text-warning" title="Edit Short Trims Booking"
                                            href="{{ url("/short-trims-bookings/$booking->id/edit" ) }}">
                                                <i class="fa fa-edit" style="color:#d58512"></i></a>
                                            @endbuyerPermission
                                            @buyerViewPermission($booking->buyer->id,'SHORT_TRIMS_BOOKINGS_SHEET')
                                            <span>|</span>
                                            <a class="text-info" title="View Short Trims Booking"
                                            href="{{ url("/short-trims-bookings/$booking->id/view") }}">
                                                <i class="fa fa-eye" style="color:#269abc"></i></a>
                                            @endbuyerViewPermission
                                        </div>
                                </td>
                                <td>{{ $booking->unique_id }}</td>
                                <td>{{ $booking->booking_date }}</td>
                                <td>{{ $booking->supplier->name }}</td>
                                <td>{{ $booking->booking_basis_value }}</td>
                                <td>{{ $booking->material_source_value }}</td>
                                <td>{{ $booking->pay_mode_value }}</td>
                                <td>{{ $booking->source_value }}</td>
                                <td>
                                    @if($booking->is_approved == 1)
                                        <i class="fa fa-check-circle-o label-success-md"></i>
                                    @elseif($booking->step > 0 || $booking->ready_to_approve == 1)
                                        <button type="button"
                                                class="btn btn-xs btn-warning"
                                                data-toggle="modal"
                                                onclick="getApproveList('{{ $booking->step }}', {{ $booking->buyer_id }})"
                                                data-target="#exampleModalCenter"
                                        >
                                            <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                        </button>
                                    @elseif($booking->ready_to_approve != 1)
                                        <i class="fa fa-times label-default-md"></i>
                                    @endif
                                </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center p-a" colspan="15">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $trimsBookings->appends(request()->query())->links() }}
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
                    selectedValue = {{$searchedValue}};
            }
                else{
                    selectedValue = {{$dashboardOverview["Total ShortTrims Booking"]}};
                }
            }
            let url = new URL(window.location.href);
            url.searchParams.set('paginateNumber',parseInt(selectedValue));
            window.location.replace(url);
        });

        const approveList = jQuery('.approve-list');

        function getApproveList(step, buyer_id) {
            var buyerId = buyer_id;
            var page = 'Short Trims Approval';

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
