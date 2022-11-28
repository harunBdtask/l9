@extends('skeleton::layout')
@section('title','ERP Packing List V3')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    ERP Packing List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        @permission('permission_of_erp_packing_list_v3_add')
                        <a href="{{ url('/erp-packing-list-v3/create') }}" class="btn btn-sm btn-info m-b">
                            <em class="fa fa-plus"></em> New Packing</a>
                        @endpermission
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-2">
                        <form action="{{ url('/erp-packing-list-v3') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request('search') }}" placeholder="Search"/>
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>PO</th>
                                <th>Assortment</th>
                                <th>Total Carton</th>
                                <th>Total Net WT(KG)</th>
                                <th>Total Gross WT(KG)</th>
                                <th>Total CBM</th>
                                <th>Created At</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($packing as $list)
                                <tr>
                                    <td>{{ $list->buyer->name }}</td>
                                    <td>{{ $list->order->style_name }}</td>
                                    <td>{{ $list->purchaseOrder->po_no }}</td>
                                    <td>{{ Str::headline($list->packing_ratio) }}</td>
                                    <td>{{ $list->grand_total_cartons }}</td>
                                    <td>{{ $list->grand_total_n_wt }}</td>
                                    <td>{{ $list->grand_total_g_wt }}</td>
                                    <td>{{ $list->grand_total_cbm }}</td>
                                    <td>{{ $list->created_at }}</td>
                                    <td>{{ $list->createdBy->screen_name }}</td>
                                    <td style="padding: 2px">
                                        @permission('permission_of_erp_packing_list_v3_view')
                                        <a href="{{ url('/erp-packing-list-v3/'.$list->id)}}"
                                           class="btn btn-xs btn-info">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        @endpermission
                                        @permission('permission_of_erp_packing_list_v3_edit')
                                        <a href="{{ url('/erp-packing-list-v3/'.$list->id.'/edit')}}"
                                           class="btn btn-xs btn-warning">
                                            <em class="fa fa-edit"></em>
                                        </a>
                                        @endpermission
                                        @permission('permission_of_erp_packing_list_v3_delete')
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title=""
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/erp-packing-list-v3/'. $list->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                        @endpermission
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10" style="text-align: center">No Data Found</th>
                                </tr>
                            @endforelse


                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $packing->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
@endsection


