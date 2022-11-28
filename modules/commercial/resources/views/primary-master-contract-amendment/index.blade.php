@extends('skeleton::layout')
@section('title','Primary\Master Contract Amendment List')
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
                    Primary/Master Contract Amendment List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-6">
                        {{--                        @permission('permission_of_embellishment_work_order_add')--}}
                        <a href="{{ url('/commercial/primary-master-contract-amendments/create') }}"
                           class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Entry</a>
                        {{--                        @endpermission--}}
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-sm-6 col-sm-offset-6">
                                <form action="{{ url('/commercial/primary-master-contract-amendments/search') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" id="search"
                                               name="search"
                                               value="{{ $search ?? request('search') ?? '' }}" placeholder="Search">
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
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>SL</th>
                                <th>Unique Id</th>
                                <th>Amend No</th>
                                <th>Amend Date</th>
                                <th>Beneficiary</th>
                                <th>Buying Agent</th>
                                <th>Ex. Contract Number</th>
                                <th>Contract Value</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($contracts as $key => $contract)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td style="text-align: left">{{ $contract->unique_id ?? '' }}</td>
                                    <td style="text-align: left">{{ $contract->amend_no ?? '' }}</td>
                                    <td style="text-align: left">{{ $contract->amend_date ?? '' }}</td>
                                    <td style="text-align: left">{{ $contract->beneficiary->factory_short_name ?? $contract->beneficiary->factory_name ?? '' }}</td>
                                    <td style="text-align: left">{{ $contract->buyingAgent->buying_agent_name ?? '' }}</td>
                                    <td style="text-align: center">{{ $contract->ex_contract_number ?? '' }}</td>
                                    <td style="text-align: center">{{ $contract->contract_value ?? '' }}</td>
                                    <td style="padding: 0.2%; width: 10% !important">
                                        {{--                                        @buyerPermission($workOrder->buyer->id,'permission_of_embellishment_work_order_edit')--}}
{{--                                        <a class="btn btn-xs btn-warning" title="Edit Embellishment Work Order"--}}
{{--                                           href="{{ url("/commercial/primary-master-contract/$contract->id/edit" ) }}"><i--}}
{{--                                                class="fa fa-edit"></i></a>--}}
                                        {{--                                        @endbuyerPermission--}}
                                        {{--                                        @buyerViewPermission($workOrder->buyer->id,'EMBELLISHMENT_WORK_ORDER_VIEW')--}}
{{--                                        <a class="btn btn-xs btn-info" title="View Embellishment Work Order"--}}
{{--                                           href="{{ url("/work-order/embellishment/$contract->id/view") }}"><i--}}
{{--                                                class="fa fa-eye"></i></a>--}}
                                        {{--                                        @endbuyerViewPermission--}}

{{--                                        <button style="margin-left: 2px;" type="button"--}}
{{--                                                class="btn btn-xs btn-danger show-modal"--}}
{{--                                                title="Delete Budget"--}}
{{--                                                data-toggle="modal"--}}
{{--                                                data-target="#confirmationModal" ui-toggle-class="flip-x"--}}
{{--                                                ui-target="#animate"--}}
{{--                                                data-url="{{ url('/commercial/primary-master-contract/'.$contract->id) }}">--}}
{{--                                            <i class="fa fa-trash"></i>--}}
{{--                                        </button>--}}
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
                        {{ $contracts->appends(request()->query())->links()  }}
                    </div>
                </div>
                <!-- Modal -->
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
