@extends('skeleton::layout')

@push('style')
    <style>
        select {
            height: 38px !important;
        }

        .select2-container--default .select2-selection--single {
            height: 38px !important;
            border-radius: 0px !important;
            border-color: rgba(120, 130, 140, 0.2) !important;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .custom-select {
            width: 100%;
            height: 30px !important;
            margin: 5px 0px;
            border: 1px solid rgba(120, 130, 140, 0.2);
        }

        .modal-dialog {
            width: 842px !important;
            margin: 30px auto;
        }

        .modal-body .select2-container {
            box-sizing: border-box;
            display: inline-block;
            margin: 0;
            position: relative;
            vertical-align: middle;
            width: 169px !important;
        }

        .table > thead > tr > th,
        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th {
            border: 1px solid #ddd;
        }

        .table > tbody > tr {
            height: 35px !important;
        }

        .table thead tr:nth-child(even) {
            background: #f9f9f9 !important;
        }

        table thead tr th {
            white-space: nowrap;
        }

        table body tr td {
            width: 15%;
        }

        .data-count {
            padding: 0px;
            text-align: right;
            font-size: 14px;
        }

        #date_from, #date_to {
            font-size: 12px;
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box order-confirmation-report">
                    <div class="box-header text-center">
                        @php
                            $buyer_id = $old_data['buyer_id'] ?? 'all';
                            $style_no = $old_data['style_no'] ?? 'all';
                            $booking_no = $old_data['booking_no'] ?? 'all';
                            $date_from = isset($old_data['date_from']) ? date('Y-m-d H:i:s', strtotime($old_data['date_from']))  :'all';
                            $date_to = isset($old_data['date_to']) ? date('Y-m-d H:i:s', strtotime($old_data['date_to']))  :'all';
                        @endphp
                        <h2>Order Confirmation Report || {{ date("jS F, Y") }}
                            <span class="pull-right hidden-print"><a href="{{url('get-order-confirmation-report-data-download/pdf/'.$buyer_id.'/'.$style_no.'/'.$booking_no.'/'.$date_from.'/'.$date_to)}}" class="hidden-print" id="order-confirmation-report-pdf"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> |
                                {{--<a class="hidden-print" id="order-confirmation-report-xls" href="{{url('get-order-confirmation-report-data-download/excel/'.$buyer_id.'/'.$style_no.'/'.$booking_no.'/'.$date_from.'/'.$date_to)}}"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a> |--}}
                                <button class="hidden-print btn btn-sm white m-b btn-xs" onclick="window.print()">Print</button></span>
                        </h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        @include('partials.response-message')
                        <div class="form-group hidden-print">
                            <div class="m-b">
                                <div class="reportTable">
                                    {!! Form::open( ['url' => 'get-order-confirmation-report-data', 'method' => 'get']) !!}
                                    <table class="table">
                                        <thead>
                                        <th>Select Buyer</th>
                                        <th>Select Style</th>
                                        <th>Select Booking</th>
                                        <th>From Date</th>
                                        <th>To Date</th>
                                        <th cla></th>
                                        <th>Action</th>
                                        </thead>
                                        <tr>
                                            <td>
                                                {!! Form::select('buyer_id', $buyers, null, ['id' => 'buyer_id', 'class' => 'custom-select buyer_id form-control form-control-sm select2-input', 'placeholder' => 'Select Buyer','onchange'=>'get_style(this,this.options[this.selectedIndex].value)']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select('style_no', [], null, ['id' => 'style_no', 'class' => 'custom-select style_no form-control form-control-sm select2-input', 'placeholder' => 'Select Style']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select('booking_no', [], null, ['id' => 'booking_no', 'class' => 'custom-select booking_no form-control form-control-sm select2-input', 'placeholder' => 'Select Booking No']) !!}
                                            </td>
                                            <td>
                                                {!! Form::date('date_from',  null, ['id' => 'date_from', 'class' => 'custom-select date_from form-control form-control-sm', 'placeholder' => 'mm/dd/yyyy','autocomplete'=>'off']) !!}
                                            </td>
                                            <td>
                                                {!! Form::date('date_to',  null, ['id' => 'date_to', 'class' => 'custom-select date_to form-control form-control-sm ', 'placeholder' => 'mm/dd/yyyy','autocomplete'=>'off']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select('search_type', ['1'=>'Order Confirmation','2'=>'Shipment Wise'], null, ['id' => 'search_type', 'class' => 'custom-select search_type form-control form-control-sm select2-input']) !!}
                                            </td>
                                            <td>
                                                <button type="submit" class="btn btn-sm btn-primary">Search</button>
                                            </td>
                                        </tr>
                                    </table>
                                    {!! form::close() !!}
                                </div>
                            </div>
                        </div>
                        <p style="text-align: center;margin-bottom: 20px;margin-top:30px;font-size: 13px;">{!! $title !!}</p>
                        <h6 style="text-align: center;margin-bottom: 20px;margin-top:20px;font-size: 13px;">Report Summary</h6>
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Total Order</th>
                                <th>Total Buyer</th>
                                <th>Total PO</th>
                                <th>Total Order Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $po = 0;$total = 0;$sum = 0; @endphp
                            @foreach($total_po as $val)
                                @foreach($val->orders as $orders)
                                    @php
                                        $total ++;
                                        $sum += $orders->total_quantity;
                                    @endphp
                                @endforeach
                            @endforeach
                            <tr>
                                <td> {{$order_info_data->total()}}</td>
                                <td> {{$total_po->groupBy('buyer_id')->count()}}</td>
                                <td>{{$total}}</td>
                                <td>{{$sum}}</td>
                            </tr>
                            </tbody>
                        </table>
                        <p class="data-count">{{ $order_info_data->firstItem() }} to {{ $order_info_data->lastItem() }} of total {{$order_info_data->total()}} entries</p>
                        <table class="reportTable">
                            <thead>
                            <tr>
                                <th>Sl.</th>
                                <th>Buyer</th>
                                <th>Order</th>
                                <th>Style</th>
                                <th>Order Confirmation Date</th>
                                <th>PO</th>
                                <th>Order Quantity</th>
                                <th>Ship Date</th>
                                <th>Remarks</th>
                            </tr>
                            </thead>
                            <tbody class="table-body">
                            @if($order_info_data != null)
                                @php
                                    $i=1;
                                    $sum = 0;
                                @endphp
                                @foreach($order_info_data->getCollection() as $key => $order_info)
                                    @if (count($order_info->orders) > 0)
                                        <tr>
                                            <td rowspan="{{count($order_info->orders)}}">{{ $i++ }}</td>
                                            <td rowspan="{{count($order_info->orders)}}">{{$order_info->buyer->name ?? 'N/A'}}</td>
                                            <td rowspan="{{count($order_info->orders)}}">{{$order_info->master_order_no}}</td>
                                            <td rowspan="{{count($order_info->orders)}}">{{$order_info->style->name ?? 'NA'}}</td>
                                            <td rowspan="{{count($order_info->orders)}}">{{$order_info->order_confirmation_date ? date('d/M/Y', strtotime($order_info->order_confirmation_date)) : date('d/M/Y', strtotime($order_info->created_at))}}</td>
                                        @foreach ($order_info->orders as $key2 => $orders)
                                            @if ($key2 > 0)
                                                <tr>
                                                    @endif
                                                    @php $sum += $orders->total_quantity; @endphp
                                                    <td>{{$orders->order_no }}</td>
                                                    <td>{{$orders->total_quantity  }}</td>
                                                    <td>{{date('d/M/Y', strtotime($orders->shipment_date)) }}</td>
                                                    <td>{{$orders->ods  }}</td>
                                                </tr>
                                                @endforeach
                                            @endif
                                        @endforeach
                                        <tr>
                                            <td colspan="6"><strong>Total Order Quantity :</strong></td>
                                            <td colspan=""><strong>{{$sum}} </strong></td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="8" align="center">No data</td>
                                        </tr>
                                    @endif
                            </tbody>
                        </table>
                        <div style="text-align: center" class="hidden-print">
                            {{$order_info_data->appends(Illuminate\Support\Facades\Input::except('page'))->links()}}
                        </div>
                    </div>
                </div>
                {{--<div class="main-load-pagination">--}}
                {{--{{ $order_info_data->render() }}--}}
                {{--</div>--}}
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        function get_style(obj, val) {
            var buyer_id = val;
            $.ajax({
                type: 'GET',
                url: 'get-style-by-buyer?buyer_id=' + buyer_id,
                success: function (response) {
                    $('#style_no').html(response);
                }
            });
        }

        $(function () {
            var aa = '{{$old_data['buyer_id'] ?? null }}';
            if (aa) {
                get_style(this, aa);
            }
            // get style by buyer id
//            $('.buyer_id').on('change', function () {
//                var buyer_id = $(this).val();
//                $.ajax({
//                    type: 'GET',
//                    url: 'get-style-by-buyer?buyer_id=' + buyer_id,
//                    success: function (response) {
//                        $('#style_no').html(response);
//                    }
//                });
//            });
            // get booking no by style id
            $('.style_no').on('change', function () {
                var style_no = $(this).val();
                $.ajax({
                    type: 'GET',
                    url: 'get-booking-no-by-buyer?style_no=' + style_no,
                    success: function (response) {
                        $('#booking_no').html(response);
                    }
                });
            });
        });
    </script>
@endpush
