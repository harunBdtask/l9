@extends('skeleton::layout')
@section('title','Gate Pass Challan Exit List')

@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Gate Pass Challan Exit List
                </h2>
            </div>
            <br>
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

            @include('skeleton::partials.table-export')
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-7 m-b">
                    </div>
                    <div class="col-md-5 m-b pull-right">
                        {!! Form::open(['url'=>'gate-pass-challan/exit-list', 'method'=>'GET']) !!}
                        <div class="col-sm-1">
                            <span class="input-group-btn">
                                <button class="btn btn-sm white m-b" type="button">From:</button>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" name="from_date" type="date"
                                   value="{{ request('from_date') }}"/>
                        </div>
                        <div class="col-sm-1">
                            <span class="input-group-btn">
                                <button class="btn btn-sm white m-b" type="button">To:</button>
                            </span>
                        </div>
                        <div class="col-md-4">
                            <input class="form-control form-control-sm" name="to_date" type="date"
                                   value="{{ request('to_date') }}"/>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <div class="row m-t">
                    <form class="col-sm-12" style="overflow-x: scroll" action="{{ url('gate-pass-challan/exit-list') }}">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>Sl</th>
                                <th>Company</th>
                                <th>Challan No</th>
                                <th>Challan Exit Date & Time</th>
                                <th>Department</th>
                                <th>Merchant</th>
                                <th>Party</th>
                                <th>Goods</th>
                                <th>Status</th>
                                <th>Appr.</th>
                                <th>Scanned</th>
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
                                            <a href="{{ url('/gate-pass-challan/' . $item->id . '/view')}}"
                                            class="text-primary">
                                                <i class="fa fa-eye" styel="color:#014682"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{\Carbon\Carbon::parse($item->gp_exit_point_scanned_at)->format('F d, Y - h:i:s A')}}</td>
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
                                    <td>{{ $item->party->contact_person }}</td>

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
    </script>
@endsection

