@extends('skeleton::layout')
@section("title","Quotations")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Procurement Quotations</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @includeIf('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <a href="{{ url('procurement/quotations/create') }}"
                           class="btn btn-sm white m-b">
                            <em class="fa fa-plus"></em>&nbsp;Create
                        </a>
                    </div>
                    <form action="{{ url('/procurement/quotations') }}" method="GET">
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
                                <th>Supplier</th>
                                <th>Item</th>
                                <th>Description</th>
                                <th>UOM</th>
                                <th>Price per UOM</th>
                                <th>Last Modified</th>
                                <th>Created By</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($quotations as $key => $quotation)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $quotation->supplier->name }}</td>
                                    <td>{{ $quotation->item->item_group }}</td>
                                    <td>{{ $quotation->item_description }}</td>
                                    <td>{{ $quotation->uom->unit_of_measurement }}</td>
                                    <td>{{ $quotation->unit_price }}</td>
                                    <td>{{ date('d M Y', strtotime($quotation->last_modified_at)) }}</td>
                                    <td>{{ $quotation->createdBy->screen_name }}</td>
                                    <td>
                                        <a class="btn btn-xs btn-info" type="button"
                                           href="{{ url('/procurement/quotations/create?id='. $quotation->id) }}">
                                            <em class="fa fa-pencil"></em>
                                        </a>
                                        <a class="btn btn-success btn-xs" type="button"
                                           href="{{ url('/procurement/quotations/'. $quotation->id) }}">
                                            <em class="fa fa-eye"></em>
                                        </a>
                                        <button style="margin-left: 2px;" type="button"
                                                class="btn btn-xs btn-danger show-modal"
                                                title="Delete Order"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('/procurement/quotations/'. $quotation->id) }}">
                                            <em class="fa fa-trash"></em>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr class="tr-height">
                                    <td colspan="9" class="text-center text-danger">No Account Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $quotations->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /*.custom-field {*/
        /*    */
        /*    */
        /*}*/
    </style>
@endsection
@section('scripts')

@endsection
