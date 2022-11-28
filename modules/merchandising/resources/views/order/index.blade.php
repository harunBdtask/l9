@extends('skeleton::layout')
@section('title','Order')
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
            <div class="box-header row" style="display: flex; justify-content: space-between;">
                <div class="col-sm-11">
                    <h2>
                        Order/Style List
                    </h2>
                </div>
                <!-- <div class="col-sm-1" style="justify-content:right;">
                        <a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="For All List"
                           id="order_excel_all"><i class="fa fa-file-excel-o"></i></a>
                        <a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
                           title="For Per Page List" id="order_excel"><i class="fa fa-file-excel-o"></i></a>
                    </div> -->
            </div>

            <div class="box-body">


                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

                <div class="row">
                    <div class="col-md-2">
                        @permission('permission_of_order_entry_add')
                        <a href="{{ url('/orders/create') }}" class="btn btn-sm btn-info m-b"><i class="fa fa-plus"></i>
                            New Order/Style</a>
                        @endpermission
                    </div>
                    <div class="col-md-10 pull-right" style="margin-right: -7%;">
                        <div class="row">
                            <div>
                                <form action="{{ url('orders/search') }}" method="GET">

                                    <div class="col-sm-1">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm white m-b" type="button">From:</button>
                                    </span>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -3%;">
                                        <input class="form-control form-control-sm" name="from_date" type="date"
                                               value="{{ request('from_date') }}"/>
                                    </div>
                                    <div class="col-sm-1">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm white m-b" type="button">To:</button>
                                    </span>
                                    </div>
                                    <div class="col-md-3" style="margin-left: -5%;">
                                        <input class="form-control form-control-sm" name="to_date" type="date"
                                               value="{{ request('to_date') }}"/>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="input-group">

                                            <input type="hidden" name="page" id="page" value={{$orders->currentPage()}}>
                                            <input type="hidden" name="paginateNumber" id="paginateNumber"
                                                   value={{$paginateNumber}}>
                                            <input type="text" class="form-control form-control-sm" id="search"
                                                   name="search" value="{{ $search ?? '' }}" placeholder="Search">
                                            <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                                            &nbsp;
                                            <span class="input-group-btn">
                                            <a href="{{ url('/orders') }}" class="btn btn-sm btn-danger m-b">Clear</a>
                                        </span>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @include('partials.response-message')
                @include('skeleton::partials.row-number')


                <div class="row m-t">
                    <div class="col-sm-12 " style="overflow-x: scroll;">
                        <table class="reportTable ">
                            <thead>
                            <tr class="table-header">
                                @php
                                    $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                                    $search = request('search') ?? null;
                                    $extended = isset($search) ? '&search='. $search : null;
                                @endphp

                                <th>
                                    <a class="btn btn-sm btn-light"
                                       href="{{  url('orders/search?sort=' . $sort . $extended)}}">
                                        <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">SL</i>
                                    </a>
                                </th>
                                <th>Company</th>
                                <th>Buying Agent</th>
                                <th>Buyer</th>
                                <th>Unique Id</th>
                                <th>Product Dept.</th>
                                <th>{{ localizedFor('Style') }}</th>
                                <th>Style Qty.</th>
                                <th>UOM</th>
                                <th>Season</th>
                                <th>SMV</th>
                                <th>PO No.</th>
                                <th>Total PO</th>
                                <th>Projection Quantity</th>
                                <th>Balance Quantity</th>
                                <th>Leader</th>
                                <th>F. Merchant</th>
                                <th>Currency</th>
                                <th>Booking/Ref no</th>
                                <th>Comm. File No</th>
                                <th>Sustainable Material</th>
                                <th>Appr.</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $key => $order)
                                <tr data-html=true data-toggle="tooltip" data-placement="top"
                                    title="{{ $order->getToolTip() }}" class="tooltip-data row-options-parent">
                                    <td style="font-weight: bold;width: 20px;">
                                        {{ str_pad($loop->iteration + $orders->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}

                                    </td>
                                    <td style="width: 100px;">
                                        {{ $order->factory->factory_short_name ?? '' }}
                                    </td>
                                    <td class="text-left">{{ $order->buyingAgent->buying_agent_name ?? '' }}</td>
                                    <td class="text-left">{{ $order->buyer->name ?? '' }}</td>
                                    <td nowrap style="min-width:169px">{{ $order->job_no }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            <!-- <td  style="padding: 0.2%; width: 10% !important"> -->
                                            @if($order->cancel_status == 0)
                                                @buyerViewPermission($order->buyer->id, 'COLOR_WISE_SUMMARY_REPORT')
                                                <a class="text-info" title="Color Wise Summary Report"
                                                   href="{{ url("/orders/color-wise-summary/$order->id") }}"
                                                   target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <span>|</span>
                                                @endbuyerViewPermission

                                                @buyerViewPermission($order->buyer->id,'ORDER_VIEW')
                                                <a class="text-primary" title="View Order"
                                                   href="{{ url('/order-entry-report?factory_id=' . $order->factory_id ) . '&buyer_id=' . $order->buyer_id . '&job_no=' . $order->job_no . '&style_name=' . $order->style_name . '&type=po_details' }}"
                                                   target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <span>|</span>

                                                @endbuyerViewPermission

                                                @buyerViewPermission($order->buyer->id, 'CUTTING_WORK_ORDER_SHEET')
                                                <a class="text-warning" title="Cutting Work Order Sheet"
                                                   href="{{ url('/orders/work-order-sheet?order_id='. $order->id)}}"
                                                   target="_blank">
                                                    <i class="fa fa-eye" style="color:#f0ad4e"></i>
                                                </a>
                                                <span>|</span>

                                                @endbuyerViewPermission

                                                @if($order->attachments->count() != 0)
                                                    <a class="text-warning" title="Order Attached Files" onclick="openFilesModal({{$order->id}})">
                                                        <i class="fa fa-eye" style="color:#cd5ee9"></i>
                                                    </a>
                                                    <span>|</span>
                                                @endif

                                                @buyerPermission($order->buyer->id,'permission_of_order_entry_edit')
                                                <a class="text-success" title="Edit Order"
                                                   href="{{ url('/orders/edit?order_id=' . $order->id ) }}">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <span>|</span>

                                                @endbuyerPermission
                                                @buyerPermission($order->buyer->id,'permission_of_order_entry_delete')
                                                <a href="{{ url('orders/'.$order->id) }}" style="margin-left: 2px;"
                                                   type="button" class="text-danger show-modal" title="Delete Order"
                                                   data-toggle="modal" data-target="#confirmationModal"
                                                   ui-toggle-class="flip-x" ui-target="#animate"
                                                   data-url="{{ url('orders/'.$order->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                <span>|</span>

                                                @endbuyerPermission

                                                @if (!isset($order->budgetData->job_no))
                                                    <a class="text-primary" title="Create Budget" id="create_budget"
                                                       href="/budgeting/create?from={{ $order->id }}">
                                                        <i class="fa fa-caret-square-o-right" style="color:#0275d8"></i>
                                                    </a>
                                                @endif
                                            @else
                                                <small class="label bg-danger">Cancelled</small>
                                            @endif
                                        </div>
                                    </td>

                                    <td>{{ $order->productDepartment->product_department ?? '' }}</td>
                                    <td class="text-left">{{ $order->style_name }}</td>
                                    <td>{{ $order->total_po_quantity ?? 0 }}</td>
                                    <td>{{ $order->unit_of_measurement }}</td>
                                    <td>{{ $order->season->season_name }}</td>
                                    <td>{{ number_format($order->smv, 2) }}</td>
                                    <td>
                                        <button type="button" style="color: #000000;height: 25px;"
                                                class="btn btn-sm btn-outline btn-info" data-toggle="modal"
                                                onclick="poNo('{{ $order->id }}')" data-target="#exampleModalCenter">
                                            Browse
                                        </button>
                                    </td>
                                    <td>{{ $order->purchase_orders_count ?? '' }}</td>
                                    <td>{{ $order->projection_qty ?? 0 }}</td>
                                    <td>{{ $order->order_status_id === \SkylarkSoft\GoRMG\Merchandising\Models\Order::PROJECTION ? $order->projection_qty - $order->total_confirm_quantity : 0 }}</td>
                                    <td class="text-left">{{ $order->teamLeader->first_name ?? '' }} {{ $order->teamLeader->last_name ?? '' }}</td>
                                    <td class="text-left">{{ $order->factoryMerchant->screen_name  ?? '' }}</td>
                                    <td>{{ $order->currency->currency_name  ?? '' }}</td>
                                    <td>{{ $order->reference_no }}</td>
                                    <td>{{ $order->purchaseOrders->first()->comm_file_no  ?? '' }}</td>
                                    <td>
                                        {{ $order->sustainable_material_name }}
                                    </td>
                                    <td>
                                        @php
                                            $approvedStatus = collect($order->purchaseOrders)
                                            ->where('is_approved', 0)
                                            ->where('approve_date', null)
                                            ->count();
                                        @endphp
                                        @if($order->is_approve == 1 || $approvedStatus < 1)
                                            <i
                                                class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($approvedStatus > 0 || $order->is_approve == 0)
                                            <button type="button" class="btn btn-xs btn-warning" data-toggle="modal"
                                                    onclick="getApproveList('{{ $order->id }}')"
                                                    data-target="#approvalModal">
                                                <em class="fa  fa-circle-o-notch label-primary-md"></em>
                                            </button>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td class="text-center p-a" colspan="20">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $orders->appends(request()->query())->links() }}
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
                                <h5 class="modal-title" id="exampleModalLongTitle">PO List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>Po No</th>
                                        <th>Po Quantity</th>
                                        <th>FOB</th>
                                        <th>Ship Date</th>
                                        <th>Lead Time</th>
                                        <th>Status</th>
                                        <th>Approve Status</th>
                                        <th>Approve Date</th>
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

                <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog"
                     aria-labelledby="approvalModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="approvalModalLongTitle">Approval List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>PO No</th>
                                        <th>Approve Status</th>
                                    </tr>
                                    </thead>
                                    <tbody class="approve-list">

                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="modal fade" id="filesModal" tabindex="-1" role="dialog"
                     aria-labelledby="filesModalTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title">Files</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-y: scroll">
                                <table style="border-collapse: separate;border-spacing: 0 10px;">
                                    <tbody id="fileModalTable">
                                    </tbody>
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

        @push("script-head")
            <script>
                $(document).ready(function () {

                    $(document).on('click', '#excel_all', function () {
                        let search = $('#search').val()
                        let link = `{{ url('/orders/excel-list-all') }}?search=${search}`;
                        window.open(link, '_blank');
                    });

                    $(document).on('click', '#list_excel', function () {
                        let search = $('#search').val()
                        let page = {{$orders->currentPage()}};

                        let link = `{{ url('/orders/excel-list-by-page') }}?search=${search}&page=${page}&paginateNumber={{$paginateNumber}}`;
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
                            selectedValue = {{$searchedOrders}};

                        } else {
                            selectedValue = {{$dashboardOverview["Total Orders"]}};
                            selectedValue = {{$dashboardOverview["Approved Order"]}};
                            selectedValue = {{$dashboardOverview["Un Approved Order"]}};
                        }


                    }
                    let url = new URL(window.location.href);
                    url.searchParams.set('paginateNumber', parseInt(selectedValue));
                    window.location.replace(url);

                });

                function openFilesModal(order_id){
                    $.ajax({
                        url: `/orders/fetch-order-pdf-attachments/${order_id}`,
                        type: "get",
                        dataType: "JSON",
                        success(response) {
                            if (response) {
                                let imageRow = '';
                                response.data.forEach(function (file, index) {
                                    imageRow += `
                                    <tr id="index-${index}">
                                        <td>${file.name}</td>
                                        <td>
                                            <a target="_blank" href="{{ url('/storage/')}}/${file.path}" title="${file.name}">
                                                <i class="m-l-1 text-primary fa fa-eye"> </i>
                                            </a>
                                        </td>
                                        <td>
                                             <a style="margin-left: 15px;" class="text-danger" title="Delete Attachment" onclick="deleteOrderAttachment(${index},${file.order_id},${file.id})">
                                                 <i class="fa fa-trash"></i>
                                             </a>
                                        </td>
                                    </tr>
                                    `;
                                });

                                $("#fileModalTable").html(imageRow);
                                $("#filesModal").modal("show")
                            }
                        }
                    });
                }

               function deleteOrderAttachment(index, order_id, attachment_id) {

                   $.ajax({
                        url: `/orders/delete-order-pdf-attachment/?order_id=${order_id}&attachment_id=${attachment_id}`,
                        type: "DELETE",
                        dataType: "JSON",
                        success: function (response) {
                            if (response.status === "success") {
                                console.log("Deleted successful");
                                $(`#index-${index}`).remove();
                                toastr.success("Deleted successful");
                            }else{
                                console.log("Something went wrong!")
                            }
                        },
                       error : function (errorMessage ) {
                            console.log("Something went wrong!"+ errorMessage);
                        }
                    });
                }

                function getApproveList(orderId) {
                    $.ajax({
                        url: `/orders/get-po-approval-status/${orderId}`,
                        type: "get",
                        dataType: "JSON",
                        success(response) {
                            if (response) {
                                let row = '';
                                response.forEach(function (value, key) {
                                    row += `
                                    <tr>
                                        <td>${key + 1}</td>
                                        <td>${value.po_no}</td>
                                        <td>
                                            ${value.is_approved == 1 ? 'Approved' : 'Un-Approved'}
                                        </td>
                                    </tr>
                                    `;
                                });
                                $(".approve-list").html(row);
                            }
                        }
                    });
                }

                const poList = jQuery('.po-list');

                function poNo(id) {
                    $.ajax({
                        url: `/orders/${id}/load-po`,
                        type: `get`,
                        success: function (response) {
                            poList.empty();
                            if (response?.data?.purchase_orders.length) {
                                $.each(response?.data?.purchase_orders, function (index, value) {
                                    poList.append(`
                            <tr>
                                <td style="padding: 4px; font-weight: bold">${index + 1}</td>
                                <td style="padding: 4px">${value.po_no ? value.po_no : ''}</td>
                                <td style="padding: 4px">${value.po_quantity ? value.po_quantity : ''}</td>
                                <td style="padding: 4px">${value.avg_rate_pc_set ? value.avg_rate_pc_set : ''}</td>
                                <td style="padding: 4px">${value.ex_factory_date ? value.ex_factory_date : ''}</td>
                                <td style="padding: 4px">${value.lead_time ? value.lead_time : ''}</td>
                                <td style="padding: 4px">${value.status ? value.status : ''}</td>
                                <td style="padding: 4px">${value.is_approved == 0
                                        ? 'Unapproved'
                                        : 'Approved'}</td>
                                <td>${value.approve_date != null ? value.approve_date : ''}</td>
                            </tr>
                        `);
                                })
                            } else {
                                poList.append(`
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
