@extends('skeleton::layout')
@push('style')
    <style>
        .select-option {
            min-height: 2.375rem !important;
        }

        .custom-input {
            width: 200px;
        }

        table thead tr th {
            white-space: nowrap;
        }

        .select2-selection--single {
            border-radius: 0px !important;
            border: 1px solid #e7e7e7 !important;
        }
    </style>
@endpush
@section('content')

    <div class="padding">
        <div class="box">
            <div class="box-header">
                <div class="col-md-6">
                    <h2>Profit Loss Statement</h2>
                </div>
                <div class="col-md-6" align="right">
                    @if(Request::has('buyer_id'))
                        <ul>
                            <li style="list-style: none;display: inline-block"><a class="" href="{{url('proft-loss-statement-report-pdf?buyer_id='.request()->buyer_id.'&order_id='.request()->order_id.'&budget_id='.request()->budget_id)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>
                        </ul>
                    @endif
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="box-body b-t ">
                <div class="flash-message">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="text-center alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                <div class="">
                    <form action="{{ url('proft-loss-statement-report') }}" method="get">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <td>Buyer</td>
                                <td>Booking No</td>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{!! Form::select('buyer_id',$buyers,request()->buyer_id ?? null,['class'=>'custom-input form-control form-control-sm buyer_id','id'=>'buyer_id','placeholder'=>'Select Buyer',isset($purchase_order_data)? 'style="pointer-events: none;"' : '']) !!}</td>
                                <td>{!! Form::select('order_id',[],request()->order_id ?? null,['class'=>'custom-input form-control form-control-sm order_id','id'=>'order_id','placeholder'=>'Select Booking No',isset($purchase_order_data)? 'style="pointer-events: none;"' : '']) !!}</td>
                                <td>
                                    <button class="btn btn-xs btn-success">Search</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <hr>
                @if(isset($order_data) && $order_data->count() > 0)
                    {{--<div style="text-align: center;text-transform: uppercase;padding-top: 15px">--}}
                    {{--<h5>Budgeting Details Report</h5>--}}
                    {{--<p>Order No : {{$order->order_style_no}}</p>--}}
                    {{--<p>Booking No : {{$order->booking_no}}</p>--}}
                    {{--<p>Budget No : {{$budget_id->budget_number}}</p>--}}
                    {{--</div>--}}
                    <br>
                    <div class="table-responsive">
                        <table class="reportTable">
                            <thead>
                            <th>Booking No</th>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            </thead>
                            <tbody>
                            @php
                                $total_order_qty = 0;
                                $total_order_price = 0;
                            @endphp
                            @foreach($order_data as $order)
                                <tr>
                                    @if($loop->first)
                                        <td rowspan="{{$order_data->count()}}">{{$order->order->booking_no}}</td>
                                    @endif
                                    <td>{{$order->item->item_name}}</td>
                                    <td>$ {{$order->unit_price}}</td>
                                    <td>{{$order->quantity}} Pcs</td>
                                    <td>$ {{($order->quantity*$order->unit_price)}}</td>
                                </tr>
                                @php
                                    $total_order_qty += $order->quantity;
                                    $total_order_price += ($order->quantity*$order->unit_price);
                                @endphp
                            @endforeach
                            <tr>
                                <td colspan="3"><b>Total</b></td>
                                <td><b>{{$total_order_qty}} Pcs</b></td>
                                <td><b>$ {{$total_order_price}}</b></td>
                            </tr>
                            </tbody>
                        </table>
                        <h5>Budget Details</h5>
                        @php $grand_total_production_cost = 0 @endphp
                        @foreach($budget_details as  $detail)
                            @php
                                $knitting_cost = $detail->budget_knitting->sum('knitting_part_knitting_total');
                                $yarn_cost = $detail->budget_yarn->sum('yarn_part_total_yarn_value');
                                $dyeing_cost = $detail->budget_dyeing->sum('dyeing_part_total_cost');
                                $total_fab_cost = ($knitting_cost + $yarn_cost + $dyeing_cost);
                                $total_trims_cost = $detail->budget_trims->sum('total_cost');
                                $total_others_cost = $detail->budget_others->sum('total_cost');
                                $total_production_cost = $total_fab_cost+ $total_trims_cost+$total_others_cost;
                                $grand_total_production_cost += $total_production_cost;
                            @endphp
                            <table class="reportTable">
                                <tbody>
                                <tr>
                                    <td colspan="2"><b>BUDGET NO : {{$detail->budget_number}}</b></td>
                                </tr>
                                <tr>
                                    <td>Fabric Cost</td>
                                    <td>$ {{$total_fab_cost}}</td>
                                </tr>
                                <tr>
                                    <td>Trims Accessories Cost</td>
                                    <td>$ {{$total_trims_cost}}</td>
                                </tr>
                                <tr>
                                    <td>Others Cost</td>
                                    <td>$ {{$total_others_cost}}</td>
                                </tr>
                                <tr>
                                    <td><b>Total Production Cost</b></td>
                                    <td><b>$ {{$total_production_cost}}</b></td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                        @endforeach
                    </div>
                    <h5>Profit Or Loss Statement</h5>
                    <table class="reportTable">
                        <tr>
                            <td><b>Approx Cost</b></td>
                            <td><b>$ {{$total_order_price}}</b></td>
                        </tr>
                        <tr>
                            <td><b>Production Cost</b></td>
                            <td><b>$ {{$grand_total_production_cost}}</b></td>
                        </tr>
                        <tr>
                            <td><b>Profit Or Loss</b></td>
                            @php $res = ($total_order_price - $grand_total_production_cost)  @endphp
                            <td><b>$ {!! $res < 0 ? '<span style="background:red;padding:3px">'.$res.'</span>' : '<span style="background:green;padding:3px">'.$res.'</span>' !!}</b></td>
                        </tr>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(function () {
            /* repopulate form input while report generate mode */
            var buyer_id = '{{request()->buyer_id}}';
            var order_id = '{{request()->order_id}}';
            if (buyer_id) {
                get_order();
            }
            if (order_id) {
                get_budget();
            }
            $('select').select2();
            $('.buyer_id').on('change', function () {
                var buyer_id = $(this).val();
                $('.buyer_id_hidden').val(buyer_id);
                $.ajax({
                    url: '{{url('get-orders-by-buyer-budget')}}' + '/' + buyer_id,
                    type: 'GET',
                    context: this,
                    success: function (data) {
                        var options = '<option>Select Style / Order</option>';
                        $.each(data, function (index, value) {
                            options += '<option value="' + index + '">' + value + '</option>';
                        });
                        $('.order_id').html(options);
                    }
                });
            });

            $('.order_id').on('change', function () {
                var order_id = $(this).val();
                $('.order_id_hidden').val(order_id);
                $.ajax({
                    url: '{{url('get-budget-with-order')}}' + '/' + order_id,
                    type: 'GET',
                    context: this,
                    success: function (data) {
                        var options = '<option value="">Select PO</option>';
                        $.each(data, function (index, value) {
                            options += '<option value="' + index + '">' + value + '</option>';
                        });
                        $('.budget_id').html(options);
                        set_hidden_master_field_value();
                    }
                });
            });

            $('.purchase_order_id').on('change', function () {
                var purchase_order_id = $(this).val();
                $('.purchase_order_id_hidden').val(purchase_order_id);
                var budget_status = '{{request()->segment(2)}}';
                if (budget_status == 'create') {
                    $.ajax({
                        url: '{{url('check-if-budget-exists')}}' + '/' + purchase_order_id,
                        type: 'GET',
                        success: function (data) {
                            if (data != 00) {
                                alert('Budget Already Created Under This Purchase Order');
                                window.location.href = "{{url('budget/update?purchase_order_id=')}}" + data;
                            }
                        }
                    });
                }
            });
        });

        function get_order() {
            var buyer_id = $('.buyer_id').val();
            var order_id = '{{request()->order_id}}';
            $.ajax({
                url: '{{url('get-orders-by-buyer-budget')}}' + '/' + buyer_id,
                type: 'GET',
                context: this,
                success: function (data) {
                    var options = '<option>Select Style / Order</option>';
                    $.each(data, function (index, value) {
                        var selected = index == order_id ? 'selected="selected"' : '';
                        options += '<option value="' + index + '" ' + selected + ' >' + value + '</option>';
                    });
                    $('.order_id').html(options).select2();
                }
            });
        }

        function get_budget() {
            var order_id = '{{request()->order_id}}';
            var budget_id = '{{request()->budget_id}}';
            $('.order_id_hidden').val(order_id);
            $.ajax({
                url: '{{url('get-budget-with-order')}}' + '/' + order_id,
                type: 'GET',
                context: this,
                success: function (data) {
                    var options = '<option value="">Select PO</option>';
                    $.each(data, function (index, value) {
                        var selected = index == budget_id ? 'selected="selected"' : '';
                        options += '<option value="' + index + '" ' + selected + '>' + value + '</option>';
                    });
                    $('.budget_id').html(options).select2();
                }
            });
        }
    </script>
@endpush
