@extends('skeleton::layout')
@section('title','Fabric Booking')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header" style="display: flex; justify-content: space-between">
                <div class="col-sm-11">
                    <h2>
                        Fabric Booking List
                    </h2>
                </div>

            </div>
            <div class="box-body">
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
                <div class="row">
                    <div class="col-md-2">
                        @permission('permission_of_main_fabric_bookings_add')
                        <a href="{{ url('/fabric-bookings/create') }}" class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em>
                            New Fabric Booking</a>
                        @endpermission
                    </div>
                    <div class="col-md-10 pull-right" style="margin-right: -7%;">
                        <form action="{{ url('/fabric-bookings/search') }}" method="GET">

                            <div class="col-sm-1">
                                        <span class="input-group-btn">
                                                <button class="btn btn-sm white m-b" type="button">From:</button>
                                        </span>
                            </div>
                            <div class="col-md-3" style="margin-left: -3%;">
                                <input class="form-control form-control-sm"
                                       name="from_date"
                                       type="date"
                                       value="{{ request('from_date') }}"/>
                            </div>
                            <div class="col-sm-1">
                                        <span class="input-group-btn">
                                                <button class="btn btn-sm white m-b" type="button">To:</button>
                                        </span>
                            </div>
                            <div class="col-md-3" style="margin-left: -5%;">
                                <input class="form-control form-control-sm"
                                       name="to_date"
                                       type="date"
                                       value="{{ request('to_date') }}"/>
                            </div>

                            <div class="col-md-4">
                                <div class="input-group">
                                    <input type="hidden" name="paginateNumber" id="paginateNumber"
                                           value={{$paginateNumber}}>

                                    <input type="text" class="form-control form-control-sm" name="search" id="search"
                                           value="{{ $search ?? '' }}" placeholder="Search">
                                    <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                    </span>
                                    &nbsp;
                                    <span class="input-group-btn">
                                            <a href="{{ url('/fabric-bookings') }}"
                                               class="btn btn-sm btn-danger m-b">Clear</a>
                                    </span>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
                @include('partials.response-message')

                @include('skeleton::partials.row-number',['allExcel' => 'true'])
                <div class="row m-t ">
                    <div class="col-sm-12 " style="overflow-x: scroll">
                        <table class="reportTable ">
                            <thead>
                            <tr class="table-header" style="background-color: rgb(148, 218, 251);">
                                @php
                                    $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                                    $search = request('search') ?? null;
                                    $extended = isset($search) ? '&search='. $search  : null;
                                @endphp

                                <th>
                                    <a class="btn btn-sm btn-light"
                                       href="{{  url('fabric-bookings/search?sort=' . $sort . $extended)}}">
                                        <em class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">Sl</em>
                                    </a>
                                </th>
                                <th>Company</th>
                                <th>Buyer</th>
                                <th>Booking ID</th>
                                <th>{{ localizedFor('Style') }}</th>
                                <th>Budget UQ Id</th>
                                <th>{{ localizedFor('PO') }}</th>
                                {{--                                <th>Booking/Ref no</th>--}}
                                <th>Source</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Supplier</th>
                                <th>Appr.</th>
                                <th>Level</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($fabricBookings as $fabricBooking)
                                <tr class="tooltip-data row-options-parent"
                                    style="{{ $fabricBooking->detailsBreakdown()->count() === 0 ? 'background: #c7c7c7' : '' }}">
                                    <td>{{ str_pad($loop->iteration + $fabricBookings->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td align="left"
                                        style="padding: 3px">{{ $fabricBooking->factory->factory_short_name ?? $fabricBooking->factory->factory_name }}</td>
                                    <td align="left" style="padding: 3px">{{ $fabricBooking->buyer->name }}</td>
                                    <td style="min-width:238px">{{ $fabricBooking->unique_id }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @if($fabricBooking->cancel_status == 0)
                                                @buyerPermission($fabricBooking->buyer->id,'permission_of_main_fabric_bookings_edit')
                                                <a href="{{ url('/fabric-bookings/create?fabric_bookings_id=') . $fabricBooking->id }}"
                                                   class="text-warning"

                                                >
                                                    <em class="fa fa-edit" style="color:#f0ad4e"></em>
                                                </a>
                                                @endbuyerPermission
                                                @buyerViewPermission($fabricBooking->buyer->id,'FABRIC_BOOKINGS_VIEW')
                                                <span>|</span>
                                                <a href="{{ url('/fabric-bookings/' . $fabricBooking->id.'/view') }}"
                                                   class="text-info"
                                                   target="_blank"
                                                >
                                                    <em class="fa fa-eye" style="color:#5bc0de"></em>
                                                </a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($fabricBooking->buyer->id,'FABRIC_BOOKINGS_SUMMARY')
                                                <span>|</span>

                                                <a href="{{ url('/fabric-bookings/' . $fabricBooking->id.'/summary-view') }}"
                                                   class="text-success"
                                                   target="_blank"
                                                >
                                                    <em class="fa fa-eye" style="color:#5cb85c"></em>
                                                </a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($fabricBooking->buyer->id,'FABRIC_BOOKINGS_SHEET')
                                                <span>|</span>

                                                <a href="{{ url('/fabric-bookings/gears/' . $fabricBooking->id.'/view') }}"
                                                   class="text-default"
                                                   target="_blank"
                                                >
                                                    <em class="fa fa-eye" style="color:#0275d8"></em>
                                                </a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($fabricBooking->buyer->id,'FABRIC_BOOKINGS_PURCHASE_ORDER')
                                                <span>|</span>

                                                <a href="{{ url('/fabric-bookings/' . $fabricBooking->id.'/view-2') }}"
                                                   class="text-primary"
                                                   target="_blank"
                                                >
                                                    <em class="fa fa-eye" style="color:#0275d8"></em>
                                                </a>
                                                @endbuyerViewPermission
                                                {{--                                        @buyerViewPermission($fabricBooking->buyer->id,'FABRIC_BOOKINGS_PURCHASE_ORDER')--}}
                                                <span>|</span>

                                                <a href="{{ url('/fabric-bookings/' . $fabricBooking->id.'/view-3?view-for=mondol') }}"
                                                   class="text-danger"
                                                   target="_blank"
                                                >
                                                    <em class="fa fa-eye"></em>
                                                </a>
                                                {{--                                        @endbuyerViewPermission--}}
                                                <span>|</span>

                                                @buyerViewPermission($fabricBooking->buyer->id,'FABRIC_BOOKINGS_PURCHASE_ORDER')
                                                <a href="{{ url('/fabric-bookings/' . $fabricBooking->id.'/view-4') }}"
                                                   class="text-xs"
                                                   style="color: #bb23f9; "
                                                   target="_blank"
                                                >
                                                    <em class="fa fa-eye"></em>
                                                </a>
                                                @endbuyerViewPermission
                                                @buyerPermission($fabricBooking->buyer->id,'permission_of_main_fabric_bookings_delete')
                                                <span>|</span>

                                                <a href="{{ url('/fabric-bookings/'.$fabricBooking->id) }}"
                                                   style="margin-left: 2px;" type="button"
                                                   class="text-danger show-modal"
                                                   title="Delete Budget"
                                                   data-toggle="modal"
                                                   data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                   ui-target="#animate"
                                                   data-url="{{ url('/fabric-bookings/'.$fabricBooking->id) }}">
                                                    <em class="fa fa-trash"></em>
                                                </a>
                                                @endbuyerPermission
                                            @else
                                                <small class="label bg-danger">Cancelled</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-left">
                                        {{ collect($fabricBooking->detailsBreakdown)->pluck('style_name')->unique()->implode(',') }}
                                    </td>
                                    <td>
                                        <button type="button"
                                                style="color: #000000;height: 25px;margin:5px;"
                                                class="btn btn-sm btn-outline btn-info"
                                                data-toggle="modal"
                                                id="jobModalButton"
                                                data-id="{{ collect($fabricBooking->detailsBreakdown)->pluck('job_no')->unique()->implode(',') }}"
                                                data-target="#jobNoModal">
                                            Browse
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button"
                                                style="color: #000000;height: 25px;margin:5px;"
                                                class="btn btn-sm btn-outline btn-info"
                                                data-toggle="modal"
                                                id="poModalButton"
                                                data-id="{{ collect($fabricBooking->detailsBreakdown)->pluck('po_no')->unique()->implode(',') }}"
                                                data-target="#poModal">
                                            Browse
                                        </button>
                                    </td>
                                    {{--                                    <td>{{ collect($fabricBooking->detailsBreakdown)->pluck('budget.order.reference_no')->whereNotNull()->unique()->values()->join(', ') ?? '' }}</td>--}}
                                    <td>{{ $fabricBooking->fabric_source_name }}</td>
                                    <td>{{ $fabricBooking->booking_date }}</td>
                                    <td>{{ $fabricBooking->delivery_date }}</td>
                                    <td align="left">{{ $fabricBooking->supplier->name }}</td>
                                    <td>
                                        @if($fabricBooking->is_approve == 1)
                                            <em class="fa fa-check-circle-o label-success-md"></em>
                                        @elseif($fabricBooking->step > 0 || $fabricBooking->ready_to_approved == 1)
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $fabricBooking->step }}', {{ $fabricBooking->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <em class="fa  fa-circle-o-notch label-primary-md"></em>
                                            </button>
                                        @elseif($fabricBooking->ready_to_approved != 1)
                                            <em class="fa fa-times label-default-md"></em>
                                        @endif
                                    </td>
                                    <td>{{ $fabricBooking->level_name }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <th colspan="13">No Data Found</th>
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
    <div class="modal fade" id="poModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLongTitle">PO List</h5>
                </div>
                <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #0ab4e6;">
                            <th>SL</th>
                            <th>{{ localizedFor('PO') }} </th>
                        </tr>
                        </thead>
                        <tbody class="po-list"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="jobNoModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xs" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h5 class="modal-title" id="exampleModalLongTitle">Job No List</h5>
                </div>
                <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #0ab4e6;">
                            <th>SL</th>
                            <th>Job No</th>
                        </tr>
                        </thead>
                        <tbody class="job-no-list"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
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
                let link = `{{ url('/fabric-bookings/excel-list-all') }}?search=${search}`;
                window.open(link, '_blank');
            });
        })

        $("#selectOption").change(function () {
            var selectBox = document.getElementById("selectOption");
            var selectedValue = (selectBox.value);
            if (selectedValue == -1) {
                if (window.location.href.indexOf("search") != -1) {
                    selectedValue = {{$searchedBookings}};
                } else {
                    selectedValue = {{$dashboardOverview["Total Fabric Booking"]}};
                }
            }
            let url = new URL(window.location.href);
            url.searchParams.set('paginateNumber', parseInt(selectedValue));
            window.location.replace(url);
        });
    </script>
@endpush
@section('scripts')
    <script>
        const approveList = jQuery('.approve-list');

        function getApproveList(step, buyer_id) {
            var buyerId = buyer_id;
            var page = 'Fabric Approval';

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

        $(document).ready(function () {
            $(document).on('click', "#poModalButton", function () {
                let poList = $(".po-list");
                let poNo = $(this).attr('data-id').split(",").map(item => item.trim());
                poList.empty();

                $.each(poNo, function (key, value) {
                    poList.append(`
                                <tr>
                                    <td>${key + 1}</td>
                                    <td>${value}</td>
                                </tr>
                            `);
                });
            })
            $(document).on('click', "#jobModalButton", function () {
                let jobNoList = $(".job-no-list");
                let jobNo = $(this).attr('data-id').split(",").map(item => item.trim());
                jobNoList.empty();

                $.each(jobNo, function (key, value) {
                    jobNoList.append(`
                                <tr>
                                    <td>${key + 1}</td>
                                    <td>${value}</td>
                                </tr>
                            `);
                });
            })
        })
    </script>
@endsection


