@extends('skeleton::layout')
@section('title','Yarn Issue')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Issue List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a
                            href="{{ url('/inventory/yarn-issue/create') }}"
                            class="btn btn-sm btn-info m-b">
                            <i class="fa fa-plus"></i> New Yarn Issue
                        </a>
                    </div>
                </div>
                @include('inventory::partials.flash')
                <div class="row m-t">
                    <div class="col-sm-12">
                        <form action="{{url('inventory/yarn-issue')}}">
                            <table class="reportTable">
                                <thead>
                                <tr style="background: #0ab4e6;">
                                    <th>SL</th>
                                    <th>Issue ID</th>
                                    <th>Issue Basis</th>
                                    <th>Challan No / Program No</th>
                                    <th>Requisition No</th>
                                    <th>Booking Type</th>
                                    <th>Ref. No</th>
                                    <th>Gate Pass No</th>
                                    <th>Supplier</th>
                                    <th>Lot</th>
                                    <th>Yarn Count</th>
                                    <th>Return Qty</th>
                                    <th style="width: 90px;">Issue Purpose</th>
                                    <th>Issue Date</th>
                                    <th>Action</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th>
                                        <input
                                            name="issue_no"
                                            value="{{ request('issue_no') }}"
                                            class="form-control form-control-sm search-field text-center"
                                            placeholder="Search">
                                    </th>
                                    <th>
                                        <select
                                            name="issue_basis"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($issueBasis as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ request('issue_basis') == $key?'selected':''}}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input name="challan_no"
                                               placeholder="Search"
                                               value="{{ request('challan_no') }}"
                                               class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th style="width: 100px;">
                                        <select name="requisition_no"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($requisitionNos as $value)
                                                <option value="{{ $value }}"
                                                    {{ request('requisition_no') == $value ? 'selected' : '' }}>
                                                    {{ $value }}
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
                                        <input name="ref_no"
                                               placeholder="Search"
                                               value="{{ request('ref_no') }}"
                                               class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th>
                                        <input name="gate_pass_no"
                                               placeholder="Search"
                                               value="{{ request('gate_pass_no') }}"
                                               class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th style="width: 10%">
                                        <select name="supplier_id"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($supplier as $value)
                                                <option value="{{ $value->id }}"
                                                    {{request('supplier_id') == $value->id ?'selected':''}}>
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input name="lot"
                                               placeholder="Search"
                                               value="{{ request('lot') }}"
                                               class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th>
                                        <input name="yarn_count"
                                               placeholder="Search"
                                               value="{{ request('yarn_count') }}"
                                               class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th></th>
                                    <th>
                                        <select
                                            name="issue_purpose"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($issuePurposes as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{ request('issue_purpose') == $key ? 'selected' : ''}}
                                                >
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input
                                            type="date"
                                            name="issue_date"
                                            placeholder="Search"
                                            value="{{ request('issue_date') }}"
                                            class="form-control form-control-sm search-field text-center">
                                    </th>
                                    <th>
                                        <button type="submit" class="btn btn-xs btn-success">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <a href="{{url('inventory/yarn-issue')}}" class="btn btn-xs btn-warning">
                                            <i class="fa fa-refresh"></i>
                                        </a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse($data as $key => $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $value->issue_no }}</td>
                                        <td style="width: 6%">{{ $value->issue_basis_name }}</td>
                                        <td>{{ $value->challan_no }}</td>
                                        <td>{{ collect($value->details)->pluck('requisition.requisition_no')->join(', ') }}</td>
                                        <td style="text-transform: capitalize">{{ $value->details->pluck('booking_type')->implode(', ') }}</td>
                                        <td>{{ $value->details->pluck('product_code')->implode(', ') }}</td>
                                        <td>{{ $value->gate_pass_no }}</td>
                                        <td style="width: 10%">{{ $value->supplier->name ?? '' }}</td>
                                        <td>
                                            <button type="button"
                                                    style="color: #000000;height: 25px;"
                                                    class="btn btn-sm btn-outline btn-info"
                                                    data-toggle="modal"
                                                    onclick="lotNo('{{ $value->id }}')"
                                                    data-target="#exampleModalCenter">
                                                Browse
                                            </button>
                                        </td>
                                        <td>{{ isset($value->details) ? collect($value->details)->pluck('yarn_count.yarn_count')->unique()->values()->join(', ') : '' }}</td>
                                        <td>{{ $value->issueReturn ? collect($value->issueReturn)->pluck('details')->flatten(1)->pluck('return_qty')->sum() : 0 }}</td>
                                        <td>{{ $value->issue_purpose_name }}</td>
                                        <td>{{date("d-m-Y", strtotime($value->issue_date))}}</td>
                                        <td style="width : 10%">
                                            @if(getRole() == 'admin' || getRole() == 'super-admin')
                                                <a
                                                    title="{{  $value->is_approved ? 'Approve' : 'Not Approve' }}"
                                                    class="btn btn-xs btn-{{ $value->is_approved ? 'success' : 'warning'}}"
                                                    href="/inventory/yarn-issue/{{ $value->id }}/approval">
                                                    <i class="fa fa-check-circle"></i>
                                                </a>
                                            @endif
                                            @unless($value->is_approved)
                                                <a
                                                    class="btn btn-xs btn-primary"
                                                    href="/inventory/yarn-issue/{{ $value->id }}/edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @if (count($value->details))
                                                    <button
                                                        disabled
                                                        class="btn btn-xs btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        data-toggle="modal"
                                                        ui-target="#animate"
                                                        ui-toggle-class="flip-x"
                                                        style="margin-left: 2px;"
                                                        title="Delete Yarn Receive"
                                                        data-target="#confirmationModal"
                                                        class="btn btn-xs btn-danger show-modal"
                                                        data-url="{{ url('/inventory/yarn-issue/'.$value->id .'/delete') }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            @endunless
                                            <a
                                                title="View"
                                                class="btn btn-xs btn-success"
                                                href="/inventory/yarn-issue/{{ $value->id }}/view">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a
                                                title="Gate Pass View"
                                                class="btn btn-xs btn-primary"
                                                href="/inventory/yarn-issue/challan/{{ $value->id }}/view">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                            <a
                                                title="Yarn Challan"
                                                class="btn btn-xs btn-info"
                                                href="/inventory/yarn-issue/challan/{{ $value->id }}/yarn-challan-view">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <th class="text-center" colspan="14">No Data Found</th>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h5 class="modal-title" id="exampleModalLongTitle">Issue Details List</h5>
                            </div>
                            <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                                <table class="reportTable">
                                    <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>SL</th>
                                        <th>Challan No</th>
                                        <th>Lot No</th>
                                        <th>Yarn Count</th>
                                        <th>Composition</th>
                                        <th>Yarn Type</th>
                                        <th>Color</th>
                                        <th>Brand</th>
                                        <th>Store</th>
                                        <th>Issue Qty</th>
                                        <th>UOM</th>
                                        <th>Req. No</th>
                                        <th>Rack</th>
                                        <th>Shelf</th>
                                    </tr>
                                    </thead>
                                    <tbody class="lot-list"></tbody>
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
        const lotList = jQuery('.lot-list');

        function lotNo(id) {
            $.ajax({
                type: `get`,
                url: `/inventory/yarn-issue/${id}/show`,
                success(response) {
                    lotList.empty();
                    if (response?.details.length) {
                        $.each(response?.details, function (index, value) {
                            lotList.append(`<tr>
                                <td style="padding: 4px">${index + 1}</td>
                                <td style="padding: 4px">${value.issue.challan_no ? value.issue?.challan_no : ''}</td>
                                <td style="padding: 4px">${value.yarn_lot}</td>
                                <td style="padding: 4px">${value.yarn_count.yarn_count ? value.yarn_count?.yarn_count : ''}</td>
                                <td style="padding: 4px">${value.composition.yarn_composition ? value.composition?.yarn_composition : ''}</td>
                                <td style="padding: 4px">${value.type.name ? value.type.name : ''}</td>
                                <td style="padding: 4px">${value.yarn_color ? value.yarn_color : ''}</td>
                                <td style="padding: 4px">${value.yarn_brand ? value.yarn_brand : ''}</td>
                                <td style="padding: 4px">${value.store.name ? value.store?.name : ''}</td>
                                <td style="padding: 4px">${value.issue_qty}</td>
                                <td style="padding: 4px">${value.uom.unit_of_measurement ? value.uom?.unit_of_measurement : ''}</td>
                                <td style="padding: 4px"></td>
                                <td style="padding: 4px">${value.rack.name ? value.rack?.name : ''}</td>
                                <td style="padding: 4px">${value.shelf?.name ? value.shelf?.name : ''}</td>
                            </tr>`);
                        })
                    } else {
                        lotList.append(`<tr><td colspan="13">No Data Found</td></tr>`)
                    }
                }
            })
        }
    </script>
@endsection
