@extends('skeleton::layout')
@section('title','Yarn Purchase Order')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Yarn Purchase Order List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-6">
                        <a href="{{ url('/yarn-purchase/order/create') }}" class="btn btn-sm btn-info m-b"><i
                                class="fa fa-plus"></i>
                            New Yarn Order</a>
                    </div>
                    <div class="col-sm-4 col-sm-offset-2">
                        <form action="{{ url('/yarn-purchase/order/') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ $search ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @include('skeleton::partials.dashboard', ['dashboardOverview' => $dashboardOverview])

                @include('skeleton::partials.table-export')
                <div class="row m-t">
                    <div class="col-sm-12" style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Company Name</th>
                                <th>Buyer</th>
                                <th>Style</th>
                                <th>WO No</th>
                                <th>Delivery Date</th>
                                <th>WO Date</th>
                                <th>Pay Mode</th>
                                <th>Source</th>
                                <th>Currency</th>
                                <th>Supplier</th>
                                <th>Appr.</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($yarnOrders as $order)
                                @php
                                    $yarnStyle = collect($order->details)->pluck('style_name')->join(', ');
                                @endphp
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $order->factory->factory_name }}</td>
                                    <td>{{ $order->buyer->name }}</td>
                                    <td style="word-break: break-word;width: 20%;">{{ $yarnStyle }}</td>
                                    <td>{{ $order->wo_no }}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            <a
                                                href="{{ url('/yarn-purchase/order/create?yarn_order_id=') . $order->id }}"
                                                class="text-warning"
                                            >
                                                <em class="fa fa-edit" style="color:#f0ad4e"></em>
                                            </a>
                                            <span>|</span>
                                            <a href="{{ url('/yarn-purchase/order/' . $order->id.'/view') }}"
                                               class="text-info">
                                                <em class="fa fa-eye" style="color:#269abc"></em>
                                            </a>
                                            <span>|</span>
                                            <a href="{{ url('/yarn-purchase/order/' . $order->id.'/yarn-booking/view') }}"
                                               class="text-info">
                                                <em class="fa fa-eye text-danger"></em>
                                            </a>
                                            <span>|</span>
                                            <a href="{{ url('/yarn-purchase/order/'.$order->id) }}"
                                               style="margin-left: 2px;"
                                               type="button"
                                               class="text-danger show-modal"
                                               title="Delete Budget"
                                               data-toggle="modal"
                                               data-target="#confirmationModal" ui-toggle-class="flip-x"
                                               ui-target="#animate"
                                               data-url="{{ url('/yarn-purchase/order/'.$order->id) }}">
                                                <em class="fa fa-trash"></em>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $order->delivery_date }}</td>
                                    <td>{{ $order->wo_date }}</td>
                                    <td>{{ $order->pay_mode_value }}</td>
                                    <td>{{ $order->source_value }}</td>
                                    <td>{{ $order->currency }}</td>
                                    <td>{{ $order->supplier->name }}</td>
                                    <td>
                                        @if($order->is_approved == 1)
                                            <i class="fa fa-check-circle-o label-success-md"></i>
                                        @elseif($order->step > 0 || $order->ready_to_approve == 1)
                                            <button type="button"
                                                    class="btn btn-xs btn-warning"
                                                    data-toggle="modal"
                                                    onclick="getApproveList('{{ $order->step }}', {{ $order->buyer_id }})"
                                                    data-target="#exampleModalCenter"
                                            >
                                                <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                            </button>
                                        @elseif($order->ready_to_approve != 1)
                                            <i class="fa fa-times label-default-md"></i>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <th colspan="10">No Data Found</th>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $yarnOrders->links() }}
                    </div>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
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
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        const approveList = jQuery('.approve-list');

        function getApproveList(step, buyerId) {
            const page = 'Yarn Purchase Approval';

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
