@extends('skeleton::layout')
@section('title','Budget')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header" style="display: flex; justify-content: space-between">
                <div class="col-sm-11">
                    <h2>
                        Budget/Costing List
                    </h2>
                </div>

            </div>

            <div class="box-body">

                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])


                <div class="row">
                    <div class="col-sm-3">
                        @permission('permission_of_budget_add')
                        <a href="{{ url('/budgeting/create') }}" class="btn btn-sm btn-info m-b"><i class="fa fa-plus"></i>
                            New
                            Budget/Costing</a>
                        @endpermission
                    </div>



                    <div class="col-md-9">
                        <div class="row">

                            <form action="{{ url('budgets/search') }}" method="GET">
                                <div class="col-md-7">
                                    <div class="col-md-2">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-sm white ">From Date :</button>
                                        </span>
                                    </div>

                                    <div class="col-md-4" style="margin-left: -2%;">
                                        <input class="form-control form-control-sm"
                                               name="from_date"
                                               type="date"
                                               value="{{ request('from_date') }}"/>
                                    </div>

                                    <div class="col-md-2">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-sm white ">To Date :</button>
                                        </span>
                                    </div>

                                    <div class="col-md-4" style="margin-left: -4%;">
                                        <input class="form-control form-control-sm"
                                               name="to_date"
                                               type="date"
                                               value="{{ request('to_date') }}"/>
                                    </div>
                                </div>

                                <div class="col-sm-5">
                                        <div class="input-group">
                                            <input type="text" class="form-control form-control-sm" name="search"
                                                   value="{{ $search ?? '' }}" id="search" placeholder="Search">
                                            <input type="hidden" name="paginateNumber" id="paginateNumber"
                                                   value={{$paginateNumber}}>

                                            <span class="input-group-btn">
                                                <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                            </span>

                                            <span class="input-group-btn">
                                                <a  href="{{ url('/budgets') }}" class="btn btn-sm btn-danger m-b" style="margin-left: 2px;" type="submit">Clear</a>
                                            </span>
                                        </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
                @include('partials.response-message')
                @include('skeleton::partials.row-number')
                <div class="row m-t">
                    <div class="col-sm-12 " style="overflow-x: scroll;">
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
                                       href="{{  url('budgets/search?sort=' . $sort . $extended)}}">
                                        <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">SL</i>
                                    </a>
                                </th>
                                <th>Company</th>
                                <th>Buyer</th>
                                <th>UQ ID</th>
                                <th> {{ localizedFor('Style') }}</th>
                                <th>Job QTY.</th>
                                <th>Product Dept.</th>
                                <th>UOM</th>
{{--                                <th>Booking/Ref no</th>--}}
                                <th>Costing Date</th>
                                <th>Appr. Date</th>
                                <th>Appr.</th>
                                <th>Creator</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($budgets as $budget)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration + $budgets->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $budget->factory->factory_short_name }}</td>
                                    <td class="text-left">{{ $budget->buyer->name }}</td>
                                    <td nowrap style="min-width: 169px;">{{ $budget->job_no }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @if($budget->cancel_status == 0)
                                                @buyerViewPermission($budget->buyer_id,'BUDGET_VIEW')
                                                <a target="_blank" class="text-info"
                                                   title="View Budget"
                                                   href="{{ url('/budgets/'. $budget->id . '/view') }}">
                                                    <i class="fa fa-eye" style="color:#5bc0de"></i>
                                                </a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($budget->buyer_id,'BUDGET_COSTING_SHEET')
                                                <span>|</span>
                                                <a target="_blank" class="text-success"
                                                   title="Budget Costing Sheet"
                                                   href="{{ url('/budgets/'. $budget->id . '/cost-breakdown-sheet/' .'view-1') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endbuyerViewPermission
                                                @buyerViewPermission($budget->buyer_id,'BUDGET_COSTING_SHEET_V2')
                                                <span>|</span>

                                                <a target="_blank" class="text-primary"
                                                   title="Budget Costing Sheet V2"
                                                   href="{{ url('/budgets/'. $budget->id . '/cost-breakdown-sheet/' .'view-2') }}">
                                                    <i class="fa fa-eye" style="color:#0275d8"></i>
                                                </a>
                                                @endbuyerViewPermission

                                                @buyerViewPermission($budget->buyer_id,'BUDGET_COSTING_SHEET_AKCL')
                                                <span>|</span>

                                                <a target="_blank" class="text-secondary"
                                                   title="Budget Costing Sheet AKCL"
                                                   href="{{ url('/budgets/'. $budget->id . '/cost-breakdown-sheet/view-akcl') }}">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endbuyerViewPermission

                                                @buyerPermission($budget->buyer_id,'permission_of_budget_edit')
                                                <span>|</span>

                                                <a href="{{ url('/budgeting/create?budget_id=') . $budget->id }}"
                                                   title="Edit Budget"
                                                   class="text-success">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endbuyerPermission

                                                @buyerPermission($budget->buyer_id,'permission_of_budget_delete')
                                                <span>|</span>

                                                <a href="{{ url('budgets/'.$budget->id) }}" style="margin-left: 2px;"
                                                   type="button"
                                                   class="text-danger show-modal"
                                                   title="Delete Budget"
                                                   data-toggle="modal"
                                                   data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                   ui-target="#animate"
                                                   data-url="{{ url('budgets/'.$budget->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endbuyerPermission
                                                <span>|</span>

                                                <a class="text-primary"
                                                   title="Create Fabric Booking"
                                                   href="{{ url('/fabric-bookings/create?from=' . $budget->id) }}">
                                                    <i class="fa fa-toggle-right" style="color:#0275d8"></i>
                                                </a>
                                                <span>|</span>

                                                <a class="text-info"
                                                   title="Create Trims Booking"
                                                   href="{{ url('/trims-bookings/create?from=' . $budget->id) }}">
                                                    <i class="fa fa-toggle-right" style="color:#5bc0de"></i>
                                                </a>
                                            @else
                                                <small class="label bg-danger">Cancelled</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-left">{{ $budget->style_name }}</td>
                                    <td>{{ $budget->total_po_quantity }}</td>
                                    <td>{{ $budget->productDepartment->product_department }}</td>
                                    <td>{{ $budget->unit_of_measurement }}</td>
{{--                                    <td>{{ $budget->order->reference_no ?? ''  }}</td>--}}
                                    <td>{{ formatDate($budget->costing_date)  }}</td>
                                    <td>{{ formatDate($budget->approve_date) }}</td>
                                    <td>
                                        @if($budget->is_approve == 1)
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($budget->step > 0 || $budget->ready_to_approved == 'Yes')
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $budget->step }}', {{ $budget->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                            </button>
                                        @elseif($budget->ready_to_approved != 'Yes')
                                            <i class="fa fa-times label-default-md"></i>
                                        @endif
                                    </td>
                                    <td>{{ $budget->createdBy->screen_name ?? null }}</td>


                                </tr>
                            @empty
                                <tr>
                                    <th colspan="12">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $budgets->appends(request()->query())->links() }}
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

        $(document).ready(function () {
            let search = $('#search');

            $(document).on('click', '#excel_all', function () {
                let searchVal = search.val();
                let link = `{{ url('/budgets/excel-list-all') }}?search=${searchVal}`;
                window.open(link, '_blank');
            });

            $(document).on('click', '#list_excel', function () {
                let searchVal = search.val();
                let page = {{$budgets->currentPage()}};
                let link = `{{ url('/budgets/excel-list-by-page') }}?search=${searchVal}&page=${page}&paginateNumber={{$paginateNumber}}`;
                window.open(link, '_blank');
            });
        });


        $("#selectOption").change(function () {
            var selectBox = document.getElementById("selectOption");
            var selectedValue = (selectBox.value);
            if (selectedValue == -1) {
                if (window.location.href.indexOf("search") != -1) {
                    selectedValue = {{$searchedBudgets}};


                } else {
                    selectedValue = {{$dashboardOverview["Total Budgets"]}};
                }


            }

            let url = new URL(window.location.href);
            url.searchParams.set('paginateNumber', parseInt(selectedValue));
            window.location.replace(url);

        });


        const approveList = jQuery('.approve-list');

        function getApproveList(step, buyer_id) {
            let buyerId = buyer_id;
            let page = 'Budget Approval';

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
