@extends('skeleton::layout')
@section('title','Trims Booking')
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
            <div class="box-header" style="display: flex; justify-content: space-between">
                <div class="col-sm-11">
                    <h2>
                        Trims Booking List
                    </h2>
                </div>

            </div>

            <div class="box-body">

                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

                <div class="row">
                    <div class="col-md-3">
                        @permission('permission_of_main_trims_bookings_add')
                        <a href="{{ url('/trims-bookings/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Trims Booking</a>
                        @endpermission
                    </div>
                    <div class="col-md-9">
                        <form action="{{ url('/trims-bookings/search') }}" method="GET">
                            <div class="row">
                                <input type="hidden" name="paginateNumber" id="paginateNumber"
                                       value={{$paginateNumber}}>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control form-control-sm" id="booking_no"
                                           name="booking_no"
                                           value="{{ request()->query('booking_no') }}"
                                           placeholder="Search By Booking No">
                                </div>
                                <div class="col-sm-3">
                                    <select name="style" class="form-control form-control-sm select2-input">
                                        <option value="">--Search By Style---</option>
                                        @foreach($styles as $style)
                                            <option
                                                {{ request()->query('style') ==  $style ? 'selected' : '' }}
                                                value="{{ $style }}">
                                                {{ $style }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                                <div class="col-sm-3">

                                    <select name="buyer" class="form-control form-control-sm select2-input">
                                        <option value="">--Search By Buyer---</option>
                                        @foreach($buyers as $buyer)
                                            <option {{ request()->query('buyer') ==  $buyer->name ? 'selected' : '' }}
                                                    value="{{ $buyer->name }}">
                                                {{ $buyer->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                    </span>
                                    <span class="input-group-btn">
                                        <a href="/trims-bookings" class="btn btn-sm btn-danger  m-b"
                                           type="submit">Clear</a>
                                    </span>
                                </div>

                            </div>
                        </form>
                    </div>
                    <hr>
                </div>
                @include('partials.response-message')


                @include('skeleton::partials.row-number',['allExcel' => 'true'])
                <div class="row m-t">
                    <div class="col-sm-12 " style="overflow-x: scroll;">
                        <table class="reportTable ">
                            <thead>
                            <tr class="table-header">
                                @php
                                    $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                                    $search = request('search') ?? null;
                                    $extended = isset($search) ? '&search='. $search  : null;
                                @endphp

                                <th>
                                    <a class="btn btn-sm btn-light"
                                       href="{{  url('trims-bookings/search?sort=' . $sort . $extended)}}">
                                        <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">Sl</i>
                                    </a>
                                </th>
                                <th>Company Name</th>
                                <th>Buyer</th>
                                <th>Booking No</th>
                                <th>{{ localizedFor('Style') }}</th>
                                <th>Item</th>
                                <th>Unique ID</th>
                                <th>Internal Ref</th>
                                <th>Order No</th>
                                <th>Booking/Ref no</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Supplier</th>
                                <th>Pay Mode</th>
                                <th>Source</th>
                                <th>Appr.</th>
                            </tr>
                            </thead>

                            <tbody>
                            @forelse($trimsBookings as $key => $booking)
                                <tr class="tooltip-data row-options-parent">
                                    <td style="font-weight: bold">
                                        {{ $trimsBookings->firstItem()+$key }}
                                    </td>
                                    <td>{{ $booking->factory->factory_short_name ?? $booking->factory->factory_name }}</td>
                                    <td class="text-left">{{ $booking->buyer->name }}</td>
                                    <td nowrap style="min-width:240px">{{ $booking->unique_id }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @if($booking->cancel_status == 0)
                                                @buyerPermission($booking->buyer->id,'permission_of_main_trims_bookings_edit')

                                                <a class="text-warning" title="Edit Trims Booking"
                                                   href="{{ url("/trims-bookings/$booking->id/edit" ) }}"><i
                                                        class="fa fa-edit" style="color:#f0ad4e;"></i></a>
                                                @endbuyerPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET')
                                                <span>|</span>

                                                <a class="text-info" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view") }}"><i
                                                        class="fa fa-eye" style="color:#5bc0de"></i></a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET_V2')
                                                <span>|</span>

                                                <a class="text-primary" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view-2?type=v2") }}"><i
                                                        class="fa fa-eye" style="color:#0275d8;"></i></a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET_V3')
                                                <span>|</span>

                                                <a class="text-success" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view-3?type=v3") }}"><i
                                                        class="fa fa-eye"></i></a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET_V4')
                                                <span>|</span>

                                                <a class="btn btn-xs btn-white" title="View Trims Booking"
                                                   target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view-4?type=v4") }}"><i
                                                        class="fa fa-eye"></i></a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET_V5')
                                                <span>|</span>

                                                <a class="text-warning" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view-5?type=v5") }}"><i
                                                        class="fa fa-eye" style="color:#f0ad4e;"></i></a>
                                                <a class="text-info" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view-6?type=v6") }}"><i
                                                        class="fa fa-eye" style="color:#5bc0de"></i></a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET_V7')
                                                <span>|</span>

                                                <a class="text-warning" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/mondol-view?type=v7") }}"><i
                                                        class="fa fa-eye" style="color:#f0ad4e;"></i></a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($booking->buyer->id,'TRIMS_BOOKINGS_SHEET_V8')
                                                <span>|</span>
                                                <a class="text-primary" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/gears-view?type=v8") }}"><i
                                                        class="fa fa-eye" style="color:#0275d8;"></i></a>
                                                @endbuyerViewPermission
                                                <span>|</span>
                                                <a class="text-primary" title="View Trims Booking" target="_blank"
                                                   href="{{ url("/trims-bookings-views/$booking->id/view-9?type=v9") }}"><i
                                                        class="fa fa-eye" style="color:#0275d8;"></i></a>

                                            @else
                                                <small class="label bg-danger">Cancelled</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-left">{{ $booking->style }}</td>
                                    <td style="text-align: left; width: 75px">
                                        {{--                                        <button type="button"--}}
                                        {{--                                                style="color: #000000;height: 25px;"--}}
                                        {{--                                                class="btn btn-sm btn-outline btn-info"--}}
                                        {{--                                                data-toggle="modal"--}}
                                        {{--                                                onclick="poNo('{{ json_encode($booking->bookingDetails->unique('id')->pluck('id')) }}')"--}}
                                        {{--                                                data-target="#exampleModalCenter">--}}
                                        {{--                                            Browse--}}
                                        {{--                                        </button>--}}
                                        {{ $booking->bookingDetails->unique('item_name')->implode('item_name',', ') }}
                                    </td>
                                    <td>
                                        <button type="button"
                                                style="color: #000000;height: 25px;"
                                                class="btn btn-sm btn-outline btn-info"
                                                data-toggle="modal"
                                                onclick="getUniqueId('{{ json_encode($booking->bookingDetails->unique('id')->pluck('id')) }}')"
                                                data-target="#exampleModalCenterUniqueId">
                                            Browse
                                        </button>
                                        {{--                                        {{ $booking->bookingDetails->unique('budget_unique_id')->implode('budget_unique_id', ', ') }}--}}
                                    </td>
                                    <td>{{ $booking->bookingDetails->first()['unique_budget_internal_ref'] ?? null }}</td>
                                    <td style="word-break: break-all;">
                                        <button type="button"
                                                style="color: #000000;height: 25px;"
                                                class="btn btn-sm btn-outline btn-info"
                                                data-toggle="modal"
                                                onclick="poNo('{{ json_encode($booking->bookingDetails->unique('id')->pluck('id')) }}')"
                                                data-target="#exampleModalCenter">
                                            Browse
                                        </button>
                                    </td>
                                    <td>{{ collect($booking->bookingDetails)->pluck('budget.order.reference_no')->whereNotNull()->unique()->values()->join(', ') ?? '' }}</td>
                                    <td>{{ $booking->booking_date }}</td>
                                    <td>{{ $booking->delivery_date }}</td>
                                    <td class="text-left">{{ $booking->supplier->name }}</td>
                                    <td>{{ $booking->pay_mode_value }}</td>
                                    <td>{{ $booking->source_value }}</td>
                                    <td>
                                        @if($booking->is_approve == 1)
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($booking->step > 0 || $booking->ready_to_approved == 1)
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $booking->step }}', {{ $booking->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                            </button>
                                        @elseif($booking->ready_to_approved != 1)
                                            <i class="fa fa-times label-default-md"></i>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center p-a" colspan="16">No Data Found</td>
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
                <!--Approval list Modal -->
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
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenterUniqueId" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Booking Order List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>Unique Id</th>
                                        <th>Style</th>
                                        <th>Item</th>
                                        <th>Description</th>
                                        <th>Total Quantity</th>
                                    </tr>
                                    </thead>
                                    <tbody class="unique-id"></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Booking Order List</h5>
                            </div>
                            <div class="modal-body " style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>Po No</th>
                                        <th>Booking Quantity</th>
                                        <th>Item</th>
                                        <th>Color</th>
                                        <th>Country</th>
                                        <th>Supplier Name</th>
                                    </tr>
                                    </thead>
                                    <tbody class="o-list"></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Booking Order List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>Po No</th>
                                        <th>Booking Quantity</th>
                                        <th>Item</th>
                                        <th>Color</th>
                                        <th>Country</th>
                                        <th>Supplier Name</th>
                                    </tr>
                                    </thead>
                                    <tbody class="o-list"></tbody>
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
@push("script-head")
    <script>
        $(document).ready(function () {
            $(document).on('click', '#excel_all', function () {
                let search = $('#search').val()
                let link = `{{ url('/trims-bookings/excel-list-all') }}?search=${search}`;
                window.open(link, '_blank');
            });
        })
    </script>
@endpush
@section('scripts')
    <script>
        $("#selectOption").change(function () {
            var selectBox = document.getElementById("selectOption");
            var selectedValue = (selectBox.value);
            if (selectedValue == -1) {
                if (window.location.href.indexOf("search") != -1) {
                    selectedValue = {{$searchedTrimsBooking}};
                } else {
                    selectedValue = {{$dashboardOverview["Total Trims Booking"]}};
                }
            }
            let url = new URL(window.location.href);
            url.searchParams.set('paginateNumber', parseInt(selectedValue));
            window.location.replace(url);
        });

        const approveList = jQuery('.approve-list');
        const uniqueId = jQuery('.unique-id');

        function getApproveList(step, buyer_id) {
            var buyerId = buyer_id;
            var page = 'Trims Approval';

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

        function getUniqueId(ids) {
            ids = JSON.parse(ids);
            $.ajax({
                url: `/trims-bookings/load-unique-id`,
                data: {ids: ids},
                type: `post`,
                success: function (response) {
                    uniqueId.empty();
                    if (response.length) {
                        $.each(response, function (index, value) {
                            uniqueId.append(`
                                        <tr>
                                            <td style="padding: 4px; font-weight: bold">${index + 1}</td>
                                            <td style="padding: 4px">${value.budget_unique_id ? value.budget_unique_id : ''}</td>
                                            <td style="padding: 4px">${value.style_name ? value.style_name : ''}</td>
                                            <td style="padding: 4px">${value.item_name ? value.item_name : ''}</td>
                                            <td style="padding: 4px">${value.item_description ? value.item_description : ''}</td>
                                            <td style="padding: 4px">${value.total_qty ? value.total_qty : ''}</td>
                                        </tr>
                                    `);
                        })
                    } else {
                        uniqueId.append(`
                            <tr>
                                <td colspan="8">No Data Found</td>
                            </tr>
                        `)
                    }
                }
            })
        }
    </script>

    <script>
        const oList = jQuery('.o-list');

        function poNo(ids) {
            ids = JSON.parse(ids);
            $.ajax({
                url: `/trims-bookings/load-po`,
                data: {ids: ids},
                type: `post`,
                success: function (response) {
                    console.log(response);
                    oList.empty();
                    if (response.length) {
                        $.each(response, function (index, value) {
                            oList.append(`
                            <tr>
                                <td style="padding: 4px; font-weight: bold">${index + 1}</td>
                                <td style="padding: 4px">${value.po_no}</td>
                                <td style="padding: 4px">${value.booking_qty}</td>
                                <td style="padding: 4px">${value.item}</td>
                                <td style="padding: 4px">${value.color}</td>
                                <td style="padding: 4px">${value.country}</td>
                                <td style="padding: 4px">${value.supplier_name}</td>
                            </tr>
                        `);
                        })
                    } else {
                        oList.append(`
                            <tr>
                                <td colspan="8">No Data Found</td>
                            </tr>
                        `)
                    }
                }
            })
        }
    </script>

@endsection
