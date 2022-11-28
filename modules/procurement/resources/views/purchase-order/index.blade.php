@extends('skeleton::layout')
@section("title","Purchase Order")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Procurement Purchase Order</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @includeIf('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <a href="{{ url('procurement/purchase-order/create') }}"
                           class="btn btn-sm white m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <form action="{{ url('/procurement/purchase-order') }}" method="GET">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search" value="{{ request()->get('search') ?? '' }}"
                                       placeholder="Search">
                                <span class="input-group-btn">
                                  <button class="btn btn-sm white" type="submit"> Search</button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Created By</th>
                                <th>Requisition ID</th>
                                <th>PO No.</th>
                                <th>Item</th>
                                <th>Item Details</th>
                                <th>Unit Price</th>
                                <th>Quantity</th>
                                <th>Amount</th>
                                <th>Report</th>
                                <th>Integration</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($purchaseOrders as $key => $po)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ date('d M, Y', strtotime($po->po_date)) }}</td>
                                    <td>{{ $po->createdBy->screen_name }}</td>
                                    <td>{{ $po->requisition->requisition_uid }}</td>
                                    <td>{{ $po->po_number }}</td>
                                    {{-- <td>{{ $po->item->item_group }}</td>
                                    <td>{{ $po->item_description }}</td>
                                    <td>{{ $po->uom->unit_of_measurement }}</td>
                                    <td>{{ $po->unit_price }}</td> --}}
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('/procurement/purchase-order/create?id='. $po->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('/procurement/purchase-order/'. $po->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/procurement/purchase-order/'. $po->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="tr-height">
                                    <td colspan="13" class="text-center text-danger">No Account Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $purchaseOrders->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


@endsection
@section('scripts')

@endsection
