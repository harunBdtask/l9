@extends('skeleton::layout')
@section('title','Yarn Receive')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Receive List
                </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a class="btn btn-sm btn-info m-b"
                           href="{{ url('/inventory/yarn-receive/create') }}">
                            <i class="fa fa-plus"></i> New Yarn Receive
                        </a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">

                    </div>
                </div>
                @include('partials.response-message')
                <div class="row m-t">
                    <form
                        id="yarn_receive_filter"
                        class="col-sm-12 table-responsive"
                        action="{{url('/inventory/yarn-receive')}}">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: skyblue;">
                                <th width="50">Sl</th>
                                <th>Receive ID</th>
                                <th>Challan No</th>
                                <th>LC No</th>
                                <th>LC Receive Date</th>
                                <th>Yarn Lots</th>
                                <th>Yarn Counts</th>
                                <th>Yarn Compositions</th>
                                <th>Reference No</th>
                                <th>Details</th>
                                <th>Receive Qty</th>
                                <th>Return Qty</th>
                                <th>Receive Basis</th>
                                <th>PI / WO No</th>
                                <th>Receive Date</th>
                                <th>Year</th>
                                @if($approvalMaintain == 1)
                                    <th>Approve Status</th>
                                @endif
                                <th>Action</th>
                            </tr>
                            <tr>
                                <th width="50"></th>
                                <th>
                                    <input
                                        type="text"
                                        name="receive_no"
                                        placeholder="Search"
                                        value="{{$form['receive_no']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <input
                                        type="text"
                                        name="challan_no"
                                        placeholder="Search"
                                        value="{{$form['challan_no']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <input
                                        type="text"
                                        name="lc_no"
                                        placeholder="Search"
                                        value="{{$form['lc_no']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <input
                                        type="date"
                                        name="lc_receive_date"
                                        placeholder="dd-mm-yyyy"
                                        value="{{$form['lc_receive_date']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <select name="yarn_lot"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($yarnLots as $lot)
                                            <option value="{{ $lot }}"
                                                {{request('yarn_lot') == $lot ?'selected':''}}>
                                                {{ $lot }}
                                            </option>
                                        @endforeach
                                    </select>
                                </th>
                                <th>
                                    <input
                                        name="yarn_count"
                                        placeholder="Search"
                                        value="{{$form['yarn_count']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <input
                                        placeholder="Search"
                                        name="yarn_composition"
                                        value="{{$form['yarn_composition']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <input
                                        placeholder="Search"
                                        name="product_code"
                                        value="{{ $form['product_code'] ?? '' }}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th></th>
                                <th>
                                    <input
                                        type="text"
                                        name="receive_qty"
                                        placeholder="Search"
                                        value="{{$form['receive_qty']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th></th>
                                <th>
                                    <input
                                        type="text"
                                        placeholder="Search"
                                        name="receive_basis"
                                        value="{{$form['receive_basis']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th>
                                    <input
                                        name="basis_no"
                                        placeholder="Search"
                                        value="{{$form['basis_no']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th width="100">
                                    <input
                                        type="date"
                                        name="receive_date"
                                        placeholder="dd-mm-yyyy"
                                        value="{{$form['receive_date']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                <th width="70">
                                    <input
                                        type="text"
                                        name="receive_year"
                                        placeholder="Search"
                                        value="{{$form['receive_year']??''}}"
                                        class="form-control form-control-sm search-field text-center">
                                </th>
                                @if($approvalMaintain == 1)
                                    <td></td>
                                @endif
                                <th style="width:72px">
                                    <button type="submit" class="btn btn-xs white">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="{{url('/inventory/yarn-receive')}}"
                                       class="btn btn-xs btn-warning">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($receive_list as $data)
                                <tr>
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{$data->receive_no}}</td>
                                    <td>{{$data->challan_no}}</td>
                                    <td>{{$data->lc_no}}</td>
                                    <td>{{ $data->lc_receive_date ? date('d-m-Y', strtotime($data->lc_receive_date)) : '' }}</td>
                                    <td>{{$data->details->map(function($query){return $query->yarn_lot;})->unique()->join(', ')}}</td>
                                    <td>{{$data->details->map(function($query){return $query->yarn_count->yarn_count; })->join(', ')}}</td>
                                    <td>{{$data->details->map(function($query){return $query->composition->yarn_composition;})->join(' ; ')}}</td>
                                    <td>{{$data->details->pluck('product_code')->implode(', ')}}</td>
                                    <td>
                                        <button
                                            style="color: #000000;height: 25px;"
                                            type="button"
                                            onclick="getDetails(`{{$data->id}}`)"
                                            class="btn btn-block btn-sm btn-outline btn-info">
                                            Browse
                                        </button>
                                    </td>
                                    <td>{{$data->details->sum('receive_qty')}}</td>
                                    <td>{{ $data->yarnReceiveReturn ? collect($data->yarnReceiveReturn)->pluck('details')->flatten(1)->pluck('return_qty')->sum() : 0 }}</td>
                                    <td>{{$data->receive_basis}}</td>
                                    <td>{{$data->receive_basis_no}}</td>
                                    <td>{{date("d-m-Y", strtotime($data->receive_date))}}</td>
                                    <td>{{date('Y',strtotime($data->receive_date))}}</td>
                                    @if($approvalMaintain == 1)
                                        <td style="text-align:center;">
                                            @if ($data->is_approve == 1)
                                                <i class="fa fa-check-circle-o" style="color: green; font-size: 18px;"
                                                   title="Approved"></i>
                                            @else
                                                <i class="fa fa-close" title="UnApproved"
                                                   style="color: #d51a1a; font-size: 18px;"></i>
                                            @endif
                                        </td>
                                    @endif
                                    <td style="padding: 2px">
                                        <a href="{{ url('/inventory/yarn-receive/'.$data->id.'/edit') }}"
                                           class="btn btn-xs btn-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        @if($data->details->count())
                                            <button type="button" disabled class="btn btn-xs btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @else
                                            <button
                                                style="margin-left: 2px;"
                                                type="button"
                                                data-toggle="modal"
                                                ui-target="#animate"
                                                ui-toggle-class="flip-x"
                                                title="Delete Yarn Receive"
                                                data-target="#confirmationModal"
                                                class="btn btn-xs btn-danger show-modal"
                                                data-url="{{ url('/inventory/yarn-receive/'.$data->id. '/delete') }}">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <th colspan="17">No Yarn Receive Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row">
                    <div class="col-md-12 text-center">
                        @if(count($receive_list))
                            {{ $receive_list->appends(request()->query())->links() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="detailsTable">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLongTitle">Yarn Receive Details List</h5>
                    </div>
                    <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: skyblue">
                                <th width="50">SL</th>
                                <th>Product Details</th>
                                <th>Yarn Brand</th>
                                <th>Yarn Lot</th>
                                <th>Receive Qty</th>
                                <th>Rate</th>
                                <th>UOM</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function getDetails(id) {
            $.ajax(`/inventory-api/v1/yarn-receive/${id}/details`, {
                type: 'GET',
                success(res) {
                    const detailsTable = $('#detailsTable');
                    let rows = `<tr>
                       <td colspan="8">No Details Found Here</td>
                    </tr>`;
                    if (res.length) {
                        rows = res.map((item, i) => {
                            return `<tr>
                            <td>${i + 1}</td>
                            <td>
                                ${item.yarn_count.yarn_count},
                                ${item.composition.yarn_composition},
                                ${item.type.name}
                            </td>
                            <td>${item.yarn_brand}</td>
                            <td>${item.yarn_lot}</td>
                            <td>${item.receive_qty}</td>
                            <td>${Number(item.rate).toFixed(2)}</td>
                            <td>${item.uom.unit_of_measurement}</td>
                            <td>${Number(item.amount).toFixed(2)}</td>
                        </tr>`;
                        })
                    }
                    detailsTable.find('tbody').html(`${rows}`);
                    detailsTable.modal('show');
                }
            })
        }
    </script>
@endsection

