@extends('skeleton::layout')
@section('title','Yarn Requisitions')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header row" style="display: flex; justify-content: space-between;">
                <div class="col-sm-11">
                    <h2>Yarn Requisitions</h2>
                </div>
                <div class="col-sm-1" style="justify-content:right;">
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top" title="For All List"
                       id="yarn_requisition_excel_all"><i class="fa fa-file-excel-o"></i></a>
                    <a class="btn btn-xs btn-success" data-toggle="tooltip" data-placement="top"
                       title="For Per Page List" id="yarn_requisition_excel"><i class="fa fa-file-excel-o"></i></a>
                </div>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('inventory::partials.flash')
                    </div>

                    @include('skeleton::partials.dashboard',['dashboardOverview'=>$dashboardOverview])
                    @include('skeleton::partials.table-export')

                    <div class="col-md-12 m-t-1" style="padding-top:20px">
                        <form class="table-responsive" action="{{ url('knitting/yarn-requisition-list') }}">
                            <table class="reportTable-zero-padding">
                                <thead>
                                <tr style="background: #0ab4e6;">
                                    <th nowrap>SL</th>
                                    <th nowrap>Requisition No</th>
                                    <th nowrap>Buyer</th>
                                    <th nowrap>Style</th>
                                    <th nowrap>Booking Type</th>
                                    <th nowrap>Booking No</th>
                                    <th nowrap>Sales Order No</th>
                                    <th nowrap>Within Group</th>
                                    <th nowrap>Knitting Source</th>
                                    <th nowrap>Program No</th>
                                    <th nowrap>Date</th>
                                    <th nowrap>Knitting Floor</th>
                                    <th nowrap style="width: 50px">Yarn Count</th>
                                    <th nowrap style="width: 100px">Yarn Composition</th>
                                    <th nowrap>Yarn Type</th>
                                    <th nowrap>Brand</th>
                                    <th nowrap>Color</th>
                                    <th nowrap>Lot</th>
                                    <th nowrap>Issue Qty</th>
                                    <th nowrap>Attention</th>
                                    <th nowrap>Remarks</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <select
                                            name="requisition_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($yarnRequisition as  $key => $value)
                                                <option
                                                    @if(request()->get('requisition_no') == $value) selected @endif
                                                    value="{{ $value }}"> {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </th>
                                    <th>
                                        <select
                                            name="buyer"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data as  $key => $value)
                                                <option
                                                    @if(request()->get('buyer') == $value->program->planInfo->buyer_name) selected
                                                    @endif
                                                    value="{{ $value->program->planInfo->buyer_name }}"> {{ $value->program->planInfo->buyer_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="style"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data as  $key => $value)
                                                <option
                                                    @if(request()->get('style') == $value->program->planInfo->style_name) selected
                                                    @endif
                                                    value="{{ $value->program->planInfo->style_name }}"> {{ $value->program->planInfo->style_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                            <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                            <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="booking_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data as  $key => $value)
                                                <option
                                                    @if(request()->get('booking_no') == $value->program->planInfo->booking_no) selected
                                                    @endif
                                                    value="{{ $value->program->planInfo->booking_no }}"> {{ $value->program->planInfo->booking_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="sales_order_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data as  $key => $value)
                                                <option
                                                    @if(request()->get('sales_order_no') == $value->program->planInfo->programmable->sales_order_no) selected
                                                    @endif
                                                    value="{{ $value->program->planInfo->programmable->sales_order_no }}"> {{ $value->program->planInfo->programmable->sales_order_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="within_group"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach(\SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::WITHIN_GROUP as $key => $value)
                                                <option
                                                    @if(request()->get('within_group') == $key) selected @endif
                                                value="{{ $key }}"> {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="knitting_source"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach(\SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram::KnittingSources as $key => $value)
                                                <option
                                                    @if(request()->get('knitting_source') == $key) selected @endif
                                                value="{{ $key }}"> {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="program_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($programNo as  $key => $value)
                                                <option
                                                    {{ request()->get('program_no') == $value ? 'selected' : '' }}
                                                    value="{{ $value }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input
                                            name="req_date"
                                            value="{{ request()->get('req_date') }}"
                                            type="date"
                                            class="form-control form-control-sm search-field text-center"
                                            placeholder="Search">
                                    </th>
                                    <th>
                                        <select
                                            name="knitting_floor_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($knittingFloors as $value)
                                                <option
                                                    @if(request()->get('knitting_floor_id') == $value->id) selected
                                                    @endif
                                                    value="{{ $value->id }}"> {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th style="width: 50px">
                                        <select
                                            name="yarn_count"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($counts as $key => $value)
                                                <option
                                                    {{ request()->get('yarn_count') == $value->id ? 'selected' : '' }}
                                                    value="{{ $value->id }}"> {{ $value->yarn_count }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <select
                                            name="yarn_brand"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data as  $key => $value)
                                                <option
                                                    @if(request()->get('yarn_brand') == $value->details->pluck('yarn_brand')[0]) selected
                                                    @endif
                                                    value="{{ $value->details->pluck('yarn_brand')[0] }}"> {{ $value->details->pluck('yarn_brand')[0] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th>
                                        <select
                                            name="yarn_lot"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data as  $key => $value)
                                                <option
                                                    @if(request()->get('yarn_lot') == $value->details->pluck('yarn_lot')[0]) selected
                                                    @endif
                                                    value="{{ $value->details->pluck('yarn_lot')[0] }}"> {{ $value->details->pluck('yarn_lot')[0] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th></th>
                                    <th></th>
                                    <th>
                                        <div style="display: flex;">
                                            <button style="margin-right: 2px;" class="btn btn-xs btn-info">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <a class="btn btn-xs btn-warning"
                                            href="{{ url('knitting/yarn-requisition-list') }}">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                @forelse($data as $key => $value)
                                    @php
                                        $detailsCollection = collect($value->details);
                                    @endphp
                                    <tr class="tooltip-data row-options-parent">
                                        <td nowrap
                                            class="p-x-1">{{ str_pad($loop->iteration + $data->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td nowrap class="p-x-1">{{ $value->requisition_no }}
                                            <br>
                                            <div class="row-options" style="display:none ">
                                                @permission('permission_of_yarn_requisition_list_view')
                                                <a href="/knitting/yarn-requisition/{{ $value->id }}/view"
                                                   class="text-success"
                                                   target="_blank"
                                                   title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                @endpermission
                                                <span>|</span>
                                                @permission('permission_of_yarn_requisition_list_edit')
                                                <a href="/knitting/yarn-requisition-info?program_id={{$value->program->id ?? ''}}&requisition={{$value->requisition_no}}"
                                                   class="text-primary" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endpermission
                                                <span>|</span>
                                                @permission('permission_of_yarn_requisition_list_delete')
                                                <a href="{{ url('/knitting/yarn-requisition/'.$value->id.'/delete') }}"
                                                   data-toggle="modal"
                                                   ui-target="#animate"
                                                   ui-toggle-class="flip-x"
                                                   title="Delete"
                                                   data-target="#confirmationModal"
                                                   data-url="{{ url('/knitting/yarn-requisition/'.$value->id.'/delete') }}"
                                                   class="text-danger show-modal">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endpermission
                                                <span>|</span>
                                                @permission('permission_of_yarn_requisition_list_view')
                                                <a href="/inventory/yarn-issue/create"
                                                   class="text-info"
                                                   title="Goto Yarn Issue">
                                                    <i class="fa fa-angle-right"></i>
                                                </a>
                                                @endpermission
                                            </div>
                                        </td>
                                        <td class="p-x-1">{{ $value->program->planInfo->buyer_name ?? '' }}</td>
                                        <td nowrap class="p-x-1">{{ $value->program->planInfo->style_name ?? '' }}</td>
                                        <td nowrap class="p-x-1 text-capitalize">{{ $value->program->planInfo->booking_type ?? '' }}</td>
                                        <td nowrap class="p-x-1">{{ $value->program->planInfo->booking_no ?? '' }}</td>
                                        <td nowrap
                                            class="p-x-1">{{ $value->program->planInfo->programmable->sales_order_no ?? '' }}</td>
                                        <td nowrap
                                            class="p-x-1">{{ $value->program->planInfo->programmable->within_group_text ?? '' }}</td>
                                        <td nowrap class="p-x-1">{{ $value->program->knitting_source_value ?? '' }}</td>
                                        <td nowrap class="p-x-1">{{ $value->program->program_no ?? '' }}</td>
                                        <td nowrap class="p-x-1">{{ $value->req_date }}</td>
                                        <td nowrap class="p-x-1">{{ $value->knittingFloor->name ?? '' }}</td>
                                        <td style="width: 50px" class="p-x-1">
                                            {{ $detailsCollection->pluck('yarn_count.yarn_count')->unique()->values()->join(', ') }}
                                        </td>
                                        <td class="p-x-1" style="width: 100px">
                                            {{ $detailsCollection->pluck('composition.yarn_composition')->unique()->values()->join(', ') }}
                                        </td>
                                        <td class="p-x-1">
                                            {{ $detailsCollection->pluck('type.name')->unique()->values()->join(', ') }}
                                        </td>
                                        <td class="p-x-1">
                                            {{ $detailsCollection->pluck('yarn_brand')->unique()->values()->join(', ') }}
                                        </td>
                                        <td class="p-x-1">
                                            {{ $detailsCollection->pluck('yarn_color')->unique()->values()->join(', ') }}
                                        </td>
                                        <td class="p-x-1">
                                            {{ $detailsCollection->pluck('yarn_lot')->unique()->values()->join(', ') }}
                                        </td>
                                        <td class="p-x-1">{{ $value->yarn_issue_sum_issue_qty }}</td>
                                        <td class="p-x-1">{{ $value->attention }}</td>
                                        <td class="p-x-1">{{ $value->remarks }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="22">No data available</td>
                                    </tr>
                                @endforelse
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push("script-head")
    <script>
        $(document).ready(function () {
            $(document).on('click', '#yarn_requisition_excel_all', function () {
                let link = `/knitting/yarn-requisition/excel-list-all${queryString()}`;
                window.open(link, '_blank');
            });

            $(document).on('click', '#yarn_requisition_excel', function () {
                let link = `/knitting/yarn-requisition/excel-list-by-page${queryString()}`;
                window.open(link, '_blank');
            });
        })

        function queryString() {
            let page = {{ $data->currentPage() }};
            if (location.search) {
                return location.search + `&page=${page}`;
            } else {
                return `?page=${page}`;
            }
        }
    </script>
@endpush
