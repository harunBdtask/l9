@extends('sample::layout')
@section('title','Sample Order Requisition List')
@section('content')
<div class="padding">
    <div class="box">
        <div class="box-header">
            <h2 style="font-weight: 400;">Sample Order Requisition List</h2>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    @permission('permission_of_sample_list_add')
                    <a href="{{ url('/sample-management/order-requisition/form') }}" class="btn btn-sm btn-info m-b">
                        <i class="fa fa-plus"></i>
                        Sample Entry
                    </a>
                    @endpermission
                </div>
                <div class="col-sm-4 col-sm-offset-2">
                    <form action="" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm"
                                name="search" value="{{ $searchedValue ?? '' }}"
                                placeholder="Search">
                            <span class="input-group-btn">
                                <button class="btn btn-sm btn-info" type="submit"> Search</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
            @include('partials.response-message')
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
            <div class="row m-t">
                <div class="col-sm-12">
                    <table class="reportTable">
                        <thead>
                            <tr class="table-header" style="background-color: rgb(148, 218, 251);">
                                <th>Requisition Id</th>
                                <th>Buyer Name</th>
                                <th>Style Name</th>
                                <th>Booking NO</th>
                                <th>Control / Ref. NO</th>
                                <th>Sample Stage</th>
                                <th>Sample Type</th>
                                <th>Garment Item</th>
                                <th>Product Department</th>
                                <th>Total Qty</th>
                                <th>Req. Date</th>
                                <th>Est. Ship Date</th>
                                <th>Delivery Date</th>
                                <th>Delivery Status</th>
                                <th>Company Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if($samples && count($samples))
                            @foreach($samples as $sample)
                            <tr class="tooltip-data row-options-parent">
                                <td>{{ $sample->requisition_id }}</td>
                                <td>{{ $sample->buyer->name }}</td>
                                <td>{{ $sample->style_name }}</td>
                                <td>{{ $sample->booking_no ?? null }}</td>
                                <td>{{ $sample->control_ref_no ?? null }}</td>
                                <td>{{ $sample->stage ?? null }}</td>
                                <td>{{ $sample->sample_types ?? null }}</td>
                                <td>{{ $sample->gmts_items ?? null }}</td>
                                <td>{{ $sample->department->product_department ?? null }}</td>
                                <td>{{ $sample->requis_details_cal['in_total'] ?? null }}</td>
                                <td>
                                    {{ $sample->req_date ? \Carbon\Carbon::make($sample->req_date)->toFormattedDateString() : null  }}
                                </td>
                                <td>
                                    {{ $sample->est_ship_date ? \Carbon\Carbon::make($sample->est_ship_date)->toFormattedDateString() : null  }}
                                </td>
                                <td>
                                    {{ $sample->delivery_date ? \Carbon\Carbon::make($sample->delivery_date)->toFormattedDateString() : null  }}
                                </td>
                                <td>
                                    @if($sample->delivery_date && strtotime($sample->delivery_date) < strtotime(date('Y-m-d')))
                                        <i class="fa fa-check text-success"></i>
                                    @else
                                        <i class="fa fa-spinner"></i>
                                    @endif
                                </td>
                                <td>{{ $sample->factory->factory_name ?? null }}</td>
                                <td>
                                    @if(Session::has('permission_of_sample_order_requisition_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                        <a class="btn btn-xs btn-info" href="{{ url('/sample-management/order-requisition/view/' . $sample->id) }}"><i class="fa fa-eye"></i> </a>
                                    @endif
                                    @if(Session::has('permission_of_sample_order_requisition_edit') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                        <a class="btn btn-xs btn-success" href="{{ url('/sample-management/order-requisition/form/' . $sample->id) }}"><i class="fa fa-edit"></i> </a>
                                    @endif
                                    @if(Session::has('permission_of_sample_order_requisition_delete') || Session::get('user_role') == 'super-admin' ||Session::get('user_role') == 'admin')
                                        <button type="button" class="btn btn-xs danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" data-url="{{ url('sample-management/order-requisition/delete/' . $sample->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    @endif
                                </td>

                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="20" class="text-center">No Data Found</td>
                        </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row m-t">
                <div class="col-sm-12">
                {{ $samples->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
