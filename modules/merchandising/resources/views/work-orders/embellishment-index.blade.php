@extends('skeleton::layout')
@section('title','Embellishment Work Order')
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
                    Embellishment Work Order List
                </h2>
            </div>

            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

            <div class="box-body">
                <div class="row">
                    <div class="col-md-2">
                        @permission('permission_of_embellishment_work_order_add')
                        <a href="{{ url('/work-order/embellishment/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Embellishment Work Order</a>
                        @endpermission
                    </div>
                    <div class="col-md-10 pull-right" style="margin-right: -7%;">
                        <form action="{{ url('/work-order/embellishment/search') }}" method="GET">

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
                                    <input type="hidden" name="paginateNumber" id="paginateNumber" value={{$paginateNumber}}>

                                    <input type="text" class="form-control form-control-sm" id="search"
                                           name="search"
                                           value="{{ $search ?? request('search') ?? '' }}" placeholder="Search">
                                    <span class="input-group-btn">
                                                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                                    &nbsp;
                                    <span class="input-group-btn">
                                            <a  href="{{ url('/work-order/embellishment') }}" class="btn btn-sm btn-danger m-b">Clear</a>
                                    </span>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                @include('skeleton::partials.row-number')
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>SL</th>
                                <th>Company Name</th>
                                <th>Location</th>
                                <th>Buyer</th>
                                <th>Booking No</th>
                                <th>Style</th>
                                <th>Unique ID</th>
                                <th>Booking Date</th>
                                <th>Delivery Date</th>
                                <th>Supplier</th>
                                <th>Pay Mode</th>
                                <th>Source</th>
                                <th>Appr.</th>

                            </tr>
                            </thead>
                            <tbody>
                            @forelse($workOrders as $key => $workOrder)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ $workOrders->firstItem()+$key }}</td>
                                    <td>{{ $workOrder->factory->factory_short_name ?? $workOrder->factory->factory_name }}</td>
                                    <td>{{ $workOrder->location }}</td>
                                    <td class="text-left">{{ $workOrder->buyer->name }}</td>
                                    <td>{{ $workOrder->unique_id }}
                                        <div  class="row-options" style="display:none ">
                                        @buyerPermission($workOrder->buyer->id,'permission_of_embellishment_work_order_edit')
                                        <a class="text-warning" title="Edit Embellishment Work Order"
                                           href="{{ url("/work-order/embellishment/$workOrder->id/edit" ) }}"><i
                                                class="fa fa-edit" style="color:#f0ad4e"></i></a>
                                        @endbuyerPermission
                                        <span>|</span>
                                        @buyerViewPermission($workOrder->buyer->id,'EMBELLISHMENT_WORK_ORDER_VIEW')
                                        <a class="text-info" title="View Embellishment Work Order"
                                           href="{{ url("/work-order/embellishment/$workOrder->id/view") }}"><i
                                                class="fa fa-eye" style="color:#5bc0de" ></i></a>
                                        @endbuyerViewPermission
                                        </div>
                                    </td>
                                    <td>
                                        <button type="button"
                                                style="color: #000000;height: 25px;margin:5px;"
                                                class="btn btn-sm btn-outline btn-info"
                                                data-toggle="modal"
                                                id="styleModalBtn"
                                                data-id="{{ $workOrder->bookingDetails->pluck('style')->unique()->implode(', ') ?? null }}"
                                                data-target="#styleModal">
                                            Browse
                                        </button>
                                    </td>
                                    <td>
                                        <button type="button"
                                                style="color: #000000;height: 25px;margin:5px;"
                                                class="btn btn-sm btn-outline btn-info"
                                                data-toggle="modal"
                                                id="uniqueIdModalBtn"
                                                data-id="{{ $workOrder->bookingDetails->pluck('budget_unique_id')->unique()->implode(', ') ?? null }}"
                                                data-target="#uniqueModal">
                                            Browse
                                        </button>
                                    </td>
                                    <td>{{ $workOrder->booking_date }}</td>
                                    <td>{{ $workOrder->delivery_date }}</td>
                                    <td>{{ $workOrder->supplier->name }}</td>
                                    <td>{{ $workOrder->pay_mode_value }}</td>
                                    <td>{{ $workOrder->source_value }}
                                    </td>
                                    <td>
                                        @if($workOrder->is_approved == 1)
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($workOrder->step > 0 || $workOrder->ready_to_approve == 1)
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $workOrder->step }}', {{ $workOrder->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                            </button>
                                        @elseif($workOrder->ready_to_approve != 1)
                                            <i class="fa fa-times label-default-md"></i>
                                        @endif
                                    </td>

                                </tr>

                            @empty
                                <tr>
                                    <td class="text-center p-a" colspan="14">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $workOrders->links() }}
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

                <div class="modal fade" id="uniqueModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xs" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Unique ID List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>SL</th>
                                        <th>Unique ID</th>
                                    </tr>
                                    </thead>
                                    <tbody class="unique-id-list"></tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="styleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-xs" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Style List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>SL</th>
                                        <th>Style</th>
                                    </tr>
                                    </thead>
                                    <tbody class="style-list"></tbody>
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
        @endsection

        @section('scripts')
            <script>
                $(document).ready(function () {

                    $(document).on('click', '#excel_all', function () {
                        let search = $('#search').val()
                        let link = `{{ url('/work-order/embellishment/excel-list-all') }}?search=${search}`;
                        window.open(link, '_blank');
                    });
                    $(document).on('click', '#list_excel', function () {
                        let search = $('#search').val()
                        let page = {{$workOrders->currentPage()}};

                        let link = `{{ url('/work-order/embellishment/excel-list-by-page') }}?search=${search}&page=${page}&paginateNumber={{$paginateNumber}}`;
                        window.open(link, '_blank');
                    });


                })


                $("#selectOption").change(function(){
                    var selectBox = document.getElementById("selectOption");
                    var selectedValue = (selectBox.value);
                    if (selectedValue == -1){
                        if(window.location.href.indexOf("search") != -1){
                            selectedValue = {{$searchedOrders}};
                    }
                        else{
                            selectedValue = {{$dashboardOverview["Total Embellished Orders"]}};
                        }
                    }
                    let url = new URL(window.location.href);
                    url.searchParams.set('paginateNumber',parseInt(selectedValue));
                    window.location.replace(url);
                });

                const approveList = jQuery('.approve-list');

                function getApproveList(step, buyer_id) {
                    var buyerId = buyer_id;
                    var page = 'Embellishment Approval';

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

                };

                $(document).on('click', "#styleModalBtn", function () {
                    let styleList = $(".style-list");
                    let stles = $(this).attr('data-id').split(",").map(item => item.trim());
                    styleList.empty();

                    $.each(stles, function (key, value) {
                        styleList.append(`
                                <tr>
                                    <td>${key + 1}</td>
                                    <td>${value}</td>
                                </tr>
                            `);
                    });
                });

                $(document).on('click', "#uniqueIdModalBtn", function () {
                    let uniqueIdList = $(".unique-id-list");
                    let uniqueIds = $(this).attr('data-id').split(",").map(item => item.trim());
                    uniqueIdList.empty();

                    $.each(uniqueIds, function (key, value) {
                        uniqueIdList.append(`
                                <tr>
                                    <td>${key + 1}</td>
                                    <td>${value}</td>
                                </tr>
                            `);
                    });
                });
            </script>
@endsection
