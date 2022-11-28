@extends('skeleton::layout')
@section('title','Gate Pass Challan')

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Gate Pass Challan List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        @if(Session::has('permission_of_gate_pass_challan_add') || Session::get('user_role') == 'super-admin')
                            <a href="{{ url('/gate-pass-challan/create') }}" class="btn btn-sm btn-info m-b">
                                <i class="fa fa-plus"></i>
                                New Gate Pass Challan
                            </a>
                        @endif

                    </div>
                </div>
                @include('partials.response-message')
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

                @include('skeleton::partials.table-export')

                <div class="row m-t">
                    <form class="col-sm-12" style="overflow-x: scroll" action="{{ url('gate-pass-challan') }}">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>Sl</th>
                                <th>Company</th>
                                <th>Challan No</th>
                                <th>Challan Date</th>
                                <th>Department</th>
                                <th>Merchant</th>
                                <th>Party</th>
                                <th>Goods</th>
                                <th>Status</th>
                                <th>Appr.</th>
                                <th>Scanned</th>
                                <th>Returnable</th>
                                <th>Attn</th>
                            </tr>
                            <tr>
                                <td></td>
                                <td>
                                    <select name="factory_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($factories as $value)
                                            <option
                                                @if(request()->get('factory_id') == $value->id) selected @endif
                                            value="{{ $value->id }}">
                                                {{ $value->factory_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input name="challan_no"
                                           value="{{ request()->get('challan_no') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <input name="challan_date" type="date"
                                           value="{{ request()->get('challan_date') }}"
                                           class="form-control form-control-sm search-field text-center"
                                           placeholder="Search">
                                </td>
                                <td>
                                    <select name="department"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($departments as $value)
                                            <option
                                                @if(request()->get('department') == $value->id) selected @endif
                                            value="{{ $value->id }}">
                                                {{ $value->product_department }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td></td>
                                <td>
                                    <select name="supplier_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($parties as $value)
                                            <option
                                                @if(request()->get('supplier_id') == $value->id) selected @endif
                                            value="{{ $value->id }}">
                                                {{ $value->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="good_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($goods as $key => $value)
                                            <option
                                                @if(request()->get('good_id') == $key) selected @endif
                                            value="{{ $key }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="status"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        @foreach($status as $key => $value)
                                            <option
                                                @if(request()->get('status') == $key) selected @endif
                                            value="{{ $key }}">
                                                {{ $value }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select name="is_approve"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                        <option value="">Select</option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                                <td>
                                    <button class="btn btn-xs btn-info" type="submit" title="Search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="{{ url('/gate-pass-challan') }}" title="Refresh"
                                       class="btn btn-xs white">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </td>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($gatePassChallan as $index =>$item)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td style="padding: 3px">{{ $item->factory->factory_short_name ?? $item->factory->factory_name }}</td>
                                    <td style="padding: 3px">{{ $item->challan_no }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            @if(Session::has('permission_of_gate_pass_challan_edit') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                <a href="{{ url('/gate-pass-challan/' . $item->id . '/edit')}}"
                                                class="text-warning"

                                                >
                                                    <i class="fa fa-edit" style = "color:#f0ad4e"></i>
                                                </a>
                                            @endif
                                            <span>|</span>
                                            <a href="{{ url('/gate-pass-challan/' . $item->id . '/view')}}"
                                            class="text-primary"

                                            >
                                                <i class="fa fa-eye" style = "color:#014682"></i>
                                            </a>
                                            <span>|</span>

                                            @if(Session::has('permission_of_gate_pass_challan_delete') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
                                                <a href="{{ url('/gate-pass-challan/'. $item->id) }}" style="margin-left: 2px;" type="button"
                                                        class="text-danger show-modal"
                                                        title="Delete Budget"
                                                        data-toggle="modal"
                                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                        ui-target="#animate"
                                                        data-url="{{ url('/gate-pass-challan/'. $item->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            @endif



                                    </td>
                                    <td>{{ $item->challan_date }}</td>
                                    <td>{{ $item->department->product_department ?? '' }}</td>
                                    <td>{{ $item->merchant->screen_name }}</td>
                                    <td>{{ $item->party->name }}</td>
                                    <td>{{ $goods[$item->good_id] ?? null}}</td>
                                    <td>{{ $status[$item->status] ?? null }}</td>
                                    <td>
                                        @if($item->is_approve == 1)
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($item->step > 0 || $item->ready_to_approve == 1)
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $item->step }}', {{ $item->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                            </button>
                                        @elseif($item->ready_to_approve != 1)
                                            <i class="fa fa-times label-default-md"></i>
                                        @endif
                                    </td>
                                    <td>
                                        @if(!empty($item->gp_exit_point_scanned_by))
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @else
                                            <i class="fa fa-times label-danger-md"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <select class="form-control form-control-sm search-field text-center c-select select2-input"
                                                id="returnable" onchange="updateReturnable({{ $item->id }})">
                                            <option value="">Select</option>
                                            <option @if($item->returnable == 1) selected @endif value="1">Yes</option>
                                            <option @if($item->returnable == 2) selected @endif value="2">No</option>
                                        </select>
                                    </td>
                                    <td>{{ $item->party_attn }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <th colspan="11">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </form>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $gatePassChallan->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
             aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
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

@endsection

@section('scripts')
    <script>
        const approveList = jQuery('.approve-list');

        function getApproveList(step, buyer_id) {
            const buyerId = null;
            const page = 'Gate Pass Challan Approval';

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

        function updateReturnable(id){

            let returnable = $('#returnable').val();
            let gatePassId = id;

            $.ajax({
                url: `/gate-pass-challan/update-returnable/${gatePassId}`,
                type: `put`,
                data: {
                    returnable: returnable,
                    gatePassId: gatePassId
                },
                success: function (result){
                    if(result.status == 200) {
                        window.location.reload('gate-pass-challan')
                    }
                }
            });
        }

    </script>
@endsection

