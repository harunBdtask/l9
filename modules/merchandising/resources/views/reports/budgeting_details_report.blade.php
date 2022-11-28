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
    @php
        $total_yarn_qty = 0;
        $total_yarn_price = 0;
        $total_knitting_qty = 0;
        $total_knitting_price = 0;
        $total_dyeing_qty = 0;
        $total_dyeing_cost = 0;
        $total_aop_cost = 0;
        $total_peached_cost = 0;
        $total_brushed_cost = 0;
        $total_dyeing_price = 0;
    @endphp
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <div class="col-md-6">
                    <h2>Budgeting Details Report </h2>
                </div>
                <br>
                <div class="col-md-6" align="right">
                    @if(Request::has('buyer_id'))
                        <ul>
{{--                            <li style="list-style: none;display: inline-block"><a href="{{url('budget-details-report-print?buyer_id='.request()->buyer_id.'&order_id='.request()->order_id.'&budget_id='.request()->budget_id)}}" class="hidden-print btn btn-xs" title="Print this document" id="print"><i class=" fa fa-print"></i>&nbsp;Print</a></li>--}}
                        <div style="margin-top:-23px; margin-left:960px">
                            <li style="list-style: none;display: inline-block"><a href="{{url('budget-details-report-pdf?buyer_id='.request()->buyer_id.'&order_id='.request()->order_id.'&budget_id='.request()->budget_id)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp; PDF</a></li>
                        </div>
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
                    <form action="{{ url('budget-details-report') }}" method="get">
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <td>Buyer</td>
                                <td>Booking No</td>
                                <td>Budget By PO(s)</td>
                                <td>Action</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>{!! Form::select('buyer_id',$buyers,request()->buyer_id ?? null,['class'=>'custom-input form-control form-control-sm buyer_id','id'=>'buyer_id','placeholder'=>'Select Buyer',isset($purchase_order_data)? 'style="pointer-events: none;"' : '']) !!}</td>
                                <td>{!! Form::select('order_id',[],request()->order_id ?? null,['class'=>'custom-input form-control form-control-sm order_id','id'=>'order_id','placeholder'=>'Select Booking No',isset($purchase_order_data)? 'style="pointer-events: none;"' : '']) !!}</td>
                                <td>{!! Form::select('budget_id',[],null,['class'=>'custom-input form-control form-control-sm budget_id','id'=>'budget_id']) !!}</td>
                                <td>
                                    <button class="btn btn-xs btn-success">Search</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
                <hr>
                @if(isset($budget_fabric_booking) && $budget_fabric_booking->count() > 0)
                    <div style="text-align: center;text-transform: uppercase;padding-top: 15px">
                        <h5>Budgeting Details Report</h5>
                        <p>Order No : {{$order->order_style_no}}</p>
                        <p>Booking No : {{$order->booking_no}}</p>
                        <p>Budget No : {{$budget_id->budget_number}}</p>
                    </div>
                    <br>
                    <div class="table-responsive">
                        <table class="reportTable gray-fabric">
                            <thead>
                            <tr style="background: #b5ebbd6e">
                                <th>Source</th>
                                <th>Garments Part</th>
                                <th>Garments Color</th>
                                <th>Fabric Composition</th>
                                <th>Fabric Type</th>
                                <th>Fabric GSM</th>
                                <th>Size</th>
                                <th>Cutable Dia</th>
                                <th>Finish Dia</th>
                                <th>Finished Type</th>
                                <th>Part Wise Qty.</th>
                                <th>Consumption</th>
                                <th>Unit Consumption</th>
                                <th>Actual Fabric Req. Qty</th>
                                <th>Process Loss</th>
                                <th>Short Req Qty</th>
                                <th>Total Fabric Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $actual_fab_qty = 0 ;
                                $req_fab_qty = 0 ;
                                $short_req_qty = 0;
                            @endphp

                            @foreach($budget_fabric_booking as $key=>$value)
                                <tr>
                                    <td>{{BUDGET_SOURCE[$value->source_id ?? 2]}}</td>
                                    <td>{{$value->part->name ?? 'N/A'}}</td>
                                    <td>{{$value->color->name ?? 'N/A'}}</td>
                                    <td>{{$value->fabric_composition ?? 'N/A'}}</td>
                                    <td>{{$value->fabric_type->fabric_type_name ?? 'N/A'}}</td>
                                    <td>{{$value->fabric_gsm ?? 'N/A'}}</td>
                                    <td>
                                        @php
                                            $sizes = explode(',',$value->size_id);
                                            $sizes = SkylarkSoft\GoRMG\SystemSettings\Models\Size::whereIn('id',$sizes)->get();
                                        @endphp
                                        <span style="margin: 3px">{{$sizes->implode('name',' ')}}</span>
                                    </td>
                                    <td>{{$value->cutable_dia ?? 'N/A'}}</td>
                                    <td>{{$value->finish_dia ?? 'N/A'}}</td>
                                    <td>{{$value->color_type->color_types }}</td>
                                    <td>{{$value->part_wise_qty ?? 'N/A'}}</td>
                                    <td>{{$value->consumption ?? 'N/A'}}</td>
                                    <td>{{BOOKING_UNIT_CONSUMPTION[$value->unit_consumption != 0 ? $value->unit_consumption : 1]}}</td>
                                    <td>{{$value->actual_req_qty ? ceil($value->actual_req_qty).' Kg' : 'N/A'}}</td>
                                    <td>{{$value->process_loss ? $value->process_loss .'%' : 'N/A'}}</td>
                                    <td>{{ceil($value->short_req_qty) ?? 0}}</td>
                                    <td>{{ceil($value->total_fabric_qty) ?? 'N/A'}}</td>
                                    @php
                                        $actual_fab_qty += ceil($value->actual_req_qty);
                                        $req_fab_qty += ceil($value->total_fabric_qty);
                                        $short_req_qty += ceil($value->short_req_qty);
                                    @endphp
                                </tr>
                            @endforeach
                            <tr class="budget-tr">
                                <td colspan="13"><b>Total</b></td>
                                <td><b>{{$actual_fab_qty.' Kg'}}</b></td>
                                <td></td>
                                <td><b>{{$short_req_qty.' Kg'}}</b></td>
                                <td><b>{{$req_fab_qty.' Kg'}}</b></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                @endif
                <br>
                @if(isset($yarn_part) && $yarn_part->count() > 0)
                    <h6>YARN DETAILS</h6>
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #b5ebbd6e">
                            <th>Source</th>
                            <th>Fabric Type</th>
                            <th>Fab. Composition</th>
                            <th>Fab. GSM</th>
                            <th>Total Yarn Qty</th>
                            <th>Yarn Count</th>
                            <th>Yarn Unit Price</th>
                            <th>Total Yarn Cost</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($yarn_part as $key=>$value)
                            <tr>
                                <td>{{BUDGET_SOURCE[$value->yarn_part_source]}}</td>
                                <td>{{$value->fabric_description->fabric_type_name ?? ''}}</td>
                                <td>{{$value->yarn_part_fabric_composition}}</td>
                                <td>{{$value->yarn_part_fabric_gsm}}</td>
                                <td>{{$value->yarn_part_total_yarn_quantity . ' Kg'}}</td>
                                <td>{{$value->yarn_count->yarn_count}}</td>
                                <td>{{'$ '.$value->yarn_part_yarn_unit_price}}</td>
                                <td>{{'$ '.$value->yarn_part_total_yarn_value}}</td>
                            </tr>
                            @php
                                $total_yarn_qty += $value->yarn_part_total_yarn_quantity;
                                $total_yarn_price += $value->yarn_part_total_yarn_value;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="4"><b>Total</b></td>
                            <td><b>{{$total_yarn_qty}} Kg</b></td>
                            <td colspan="2"></td>
                            <td><b>$ {{$total_yarn_price}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                <br>
                @if(isset($knitting_part) && $knitting_part->count() > 0)
                    <h6>KNITTING DETAILS</h6>
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #b5ebbd6e">
                            <th>Source</th>
                            <th>Fabric Type</th>
                            <th>Fab. Composition</th>
                            <th>Fab. GSM</th>
                            <th>Yarn Count</th>
                            <th>Knitting Qty</th>
                            <th>Knitting Price</th>
                            <th>Total Knitting Cost</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($knitting_part as $key=>$value)
                            <tr>
                                <td>{{BUDGET_SOURCE[$value->knitting_part_supplier_id]}}</td>
                                <td>{{$value->fabric_description->fabric_type_name ?? 'N/A'}}</td>
                                <td>{{$value->knitting_part_fabric_composition}}</td>
                                <td>{{$value->knitting_part_fabric_gsm}}</td>
                                <td>{{$value->yarn_count->yarn_count}}</td>
                                <td>{{$value->knitting_part_knitting_qty}} Kg</td>
                                <td>$ {{$value->knitting_part_knitting_unit_price}}</td>
                                <td>$ {{$value->knitting_part_knitting_total}}</td>
                            </tr>
                            @php
                                $total_knitting_qty += $value->knitting_part_knitting_qty;
                                $total_knitting_price += $value->knitting_part_knitting_total;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="5"><b>Total</b></td>
                            <td><b>{{$total_knitting_qty}} Kg</b></td>
                            <td></td>
                            <td><b>$ {{$total_knitting_price}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                <br>
                @if(isset($dyeing_part) && $dyeing_part->count() > 0)
                    <h6>DYEING DETAILS</h6>
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #b5ebbd6e">
                            <th>Source</th>
                            <th>Fabric Type</th>
                            <th>Fab. Composition</th>
                            <th>Fab. GSM</th>
                            <th>Yarn Count</th>
                            <th>Dyeing Qty</th>
                            <th>Dyeing Cost</th>
                            <th>AOP Cost</th>
                            <th>Peached Cost</th>
                            <th>Brushed Cost</th>
                            <th>Dyeing/Finsh. Unit Cost</th>
                            <th>Total Dyeing Cost</th>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($dyeing_part as $key=>$value)
                            <tr>
                                <td>{{BUDGET_SOURCE[$value->dyeing_part_supplier_id]}}</td>
                                <td>{{$value->fabric_description->fabric_type_name ?? 'N/A'}}</td>
                                <td>{{$value->dyeing_part_fabric_composition}}</td>
                                <td>{{$value->dyeing_part_fabric_gsm}}</td>
                                <td>{{$value->yarn_count->yarn_count}}</td>
                                <td>{{$value->dyeing_part_dyeing_qty}} Kg</td>
                                <td>$ {{$value->dyeing_part_dyeing_cost}}</td>
                                <td>$ {{$value->dyeing_part_aop_cost}}</td>
                                <td>$ {{$value->dyeing_part_peached_cost}}</td>
                                <td>$ {{$value->dyeing_part_brushed_cost}}</td>
                                <td>$ {{$value->dyeing_part_finishing_cost}}</td>
                                <td>$ {{$value->dyeing_part_total_cost}}</td>
                            </tr>
                            @php
                                $total_dyeing_qty += $value->dyeing_part_dyeing_qty;
                                $total_dyeing_cost += $value->dyeing_part_dyeing_cost;
                                $total_aop_cost += $value->dyeing_part_aop_cost;
                                $total_peached_cost += $value->dyeing_part_peached_cost;
                                $total_brushed_cost += $value->dyeing_part_brushed_cost;
                                $total_dyeing_price += $value->dyeing_part_total_cost;
                            @endphp
                        @endforeach
                        <tr>
                            <td colspan="5"><b>Total</b></td>
                            <td><b>{{$total_dyeing_qty}} Kg</b></td>
                            <td><b>$ {{$total_dyeing_cost}}</b></td>
                            <td><b>$ {{$total_aop_cost}}</b></td>
                            <td><b>$ {{$total_peached_cost}}</b></td>
                            <td><b>$ {{$total_brushed_cost}}</b></td>
                            <td></td>
                            <td><b>$ {{$total_dyeing_price}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                <br>
                @if(isset($accessories) && $accessories->count() > 0)
                    <h6>ACCESSORIES</h6>
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #b5ebbd6e">
                            <th>Item</th>
                            <th>Consumption Qty</th>
                            <th>Total Qty</th>
                            <th>Short Book Qty</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Total Cost</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $total_accessories_qty = 0;
                            $total_accessories_amount = 0;
                            $total_short_book_qty = 0;
                            $total_consumption_qty =0;

                        @endphp
                        @foreach($accessories as $accessory)
                            <tr>
                                <td>{{$accessory->item->item_name ?? '--'}}</td>
                                <td>{{$accessory->consumption_qty}}</td>
                                <td>{{$accessory->total_qty}}</td>
                                <td>{{$accessory->short_book_qty ?? 0}}</td>
                                <td>{{$accessory->consumption_uom->unit_of_measurements ?? '--'}}</td>
                                <td>{{$accessory->cost_per_unit}}</td>
                                {{--<td>{{($accessory->cost_per_unit * $total_qty)}}</td>--}}
                                <td>{{($accessory->cost_per_unit * $accessory->total_qty)}}</td>
                            </tr>
                            @php
                                $total_short_book_qty += $accessory->short_book_qty;
                                $total_accessories_qty += $accessory->total_qty;
                                $total_accessories_amount += ($accessory->cost_per_unit * $accessory->total_qty);
                                $total_consumption_qty += $accessory->consumption_qty;
                            @endphp
                        @endforeach
                        <tr>
                            <td><b>Total</b></td>
                            <td><b>{{$total_consumption_qty}}</b></td>
                            <td><b>{{$total_accessories_qty}}</b></td>
                            <td><b>{{$total_short_book_qty}}</b></td>
                            <td colspan="2"></td>
                            <td><b>${{$total_accessories_amount}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                @endif
                @if(isset($other_costing) && $other_costing->count() > 0)
                    <h6>OTHERS COSTING</h6>
                    <table class="reportTable">
                        <thead>
                        <tr style="background: #b5ebbd6e">
                            <th>Item</th>
                            <th>Garments Qty</th>
                            <th>Unit Cost</th>
                            <th>Total Cost</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $total_garments = 0;$total_item_cost = 0 @endphp
                        @foreach($other_costing as $key=>$value)
                            <tr>
                                <td>{{$value->others_item->component ?? 'N/A'}}</td>
                                <td>{{$value->total_garments}}</td>
                                <td>{{$value->other_unit_cost}}</td>
                                <td>{{$value->total_cost}}</td>
                            </tr>
                            @php
                                $total_garments += $value->total_garments;
                                $total_item_cost += $value->total_cost;
                            @endphp
                        @endforeach
                        <tr>
                            <td><b>Total</b></td>
                            <td><b>{{$total_garments}}</b></td>
                            <td></td>
                            <td><b>$ {{$total_item_cost}}</b></td>
                        </tr>
                        </tbody>
                    </table>
                    <div>
                        <h6>COSTING BREAKDOWN SUMMARY</h6>
                        <table class="reportTable">
                            @php
                                $total_prod_cost = $total_yarn_price + $total_knitting_price + $total_dyeing_price + $total_accessories_amount + $total_item_cost;
                                $fabric_cost =$total_yarn_price + $total_knitting_price + $total_dyeing_price;
                            @endphp
                            <tr>
                                <td><b>Total Production Cost</b></td>
                                <td><b>$ {{$total_prod_cost}}</b></td>
                            </tr>
                            <tr>
                                <td><b>Fabric Cost</b></td>
                                <td><b>$ {{($fabric_cost)}} ( {{round(((100/$total_prod_cost)*$fabric_cost))}} % of Total Cost )</b></td>
                            </tr>
                            <tr>
                                <td><b>Trims Accessories Cost</b></td>
                                <td><b>$ {{($total_accessories_amount)}} ( {{round(((100/$total_prod_cost)*$total_accessories_amount))}} % of Total Cost )</b></td>
                            </tr>
                            <tr>
                                <td><b>Others Cost</b></td>
                                <td><b>$ {{($total_item_cost)}} ( {{round(((100/$total_prod_cost)*$total_item_cost))}} % of Total Cost )</b></td>
                            </tr>
                        </table>
                    </div>
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
