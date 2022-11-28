@extends('skeleton::layout')
@push('style')
    <style>
        @media only screen {
            #parentTableFixed {
                height: 700px !important;
            }
        }

        table > tr > td {
            width: 100% !important;
        }

        .reportTable tr td {
            padding: 0px 0px;
        }

        .inner-table tr td {
            border-left: none;
            border-right: none;
        }

        /*.inner-table tr:last-child td{*/
        /*border-bottom: none;*/
        /*}*/

        .inner-table tr td {
            border-top: none;
        }

        .inner-table tr:last-child td {
            border-bottom: none;
        }

        /*.table thead tr:nth-child(even) {*/
        /*background: #f9f9f9 !important;*/
        /*}*/
        .data-count {
            padding: 0px;
            text-align: right;
            font-size: 14px;
        }

        table thead tr th {
            white-space: nowrap;
        }

        table tbody tr td {
            white-space: nowrap;
        }

        .right-align {
            padding-right: 10px;
        }

        .reportTable tbody th:last-child {
            width: 150px !important;
        }

        .select2-container--default .select2-selection--single {
            border-color: rgba(120, 130, 140, 0.2) !important;
            border-radius: 0px;
            height: 30px;
        }

        .in-print {
            display: none !important;
        }

        .stay-top {
            position: relative;
            top: 0px;
        }

        @media print {
            @page {
                size: a4 landscape !important;
                margin-top: -7mm;
            }

            .reportTable th,
            .reportTable td {
                border: 1px solid #000;
                padding: 0px;
                font-size: 10px !important;
                white-space: normal;
            }

            .reportTable {
                margin-bottom: 1rem;
                width: 100%;
                max-width: 100%;
                font-size: 16px !important;
                border-collapse: collapse;
            }

            .in-print {
                display: block !important;
                margin-top: -15px !important;
            }

            .print-delete {
                display: none !important;
            }

            .stay-top {
                position: relative;
                top: inherit;
            }
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        @if(Session::has('permission_of_recap_report_view') || Session::get('user_role') == 'super-admin' || Session::get('user_role') == 'admin')
            <div class="box knit-card">
                <div class="row in-print">
                    <h4 class="text-center" style="font-size: 20px;padding: 0px">{{groupName()}}</h4>
                    <h6 class="text-center" style="font-size: 16px;margin-top: 0px">Unit: {{factoryName()}}</h6>
                    <p class="text-center">{{factoryAddress()}}</p>
                    <h4 class="text-center" style="margin-bottom: -5px;font-size: 16px">Recap Report List</h4>
                    <br>
                </div>
                <div class="box-header print-delete">
                    <h2>Recap Report</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="box-body b-t print-delete">
                    <div class="row print-delete">
                        <div class="col-md-8">
                            <form class="form-inline print-delete" method="GET" action="{{ url('po-wise-recap-report-search') }}" role="search">
                                <div class="form-group">
                                    {!! Form::select('buyer_id',$buyers, request()->buyer_id ?? null, ['class' => 'form-control form-control-sm select2-input', 'id' => '']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectMonth('month',request()->month ?? date('n'),['class'=>'form-control form-control-sm']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::selectRange('year', date('Y',strtotime('5 years ago')), date('Y',strtotime('+5 years')),request()->year ?? date('Y'),['class'=>'form-control form-control-sm']) !!}
                                </div>
                                <button type="submit" class="btn btn-sm white m-b">Search</button>
                            </form>
                        </div>
                        <div class="col-md-4 print-delete" align="right">
                            <ul>
                                {{--<li style="list-style: none;display: inline-block"><a  id="print"><i class=" fa fa-print" ></i>&nbsp;Print</a></li>--}}
                                <li style="list-style: none;display: inline-block"><a href="{{ url('po-wise-recap-report-pdf?buyer_id='.request()->buyer_id.'&month='.request()->month.'&year='.request()->year)}}" class="hidden-print btn btn-xs" title="Download this pdf"><i class="fa fa-file-pdf-o"></i>&nbsp;PDF</a></li>
{{--                                <li style="list-style: none;display: inline-block"><a href="{{ url('recap-report-excel-download?buyer_id='.request()->buyer_id.'&month='.request()->month.'&year='.request()->year)}}" class="hidden-print btn btn-xs" title="Download this excel file"><i class="fa fa-file-excel-o"></i>&nbsp;Excel</a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div class="clearfix print-delete"></div>
                </div>
                {{--@if($pos->count())--}}
                <div class="box-body" style="margin-top: -15px">
                    <div id="parentTableFixed" class="table-responsive" style="overflow: auto;">
                        <table class="reportTable table-bordered" id="fixTable">
                            <thead>
                            <tr>
                                <th class="stay-top">Buyer</th>
                                <th class="stay-top">Booking No</th>
                                <th class="stay-top">Style</th>
                                <th class="stay-top">PO / Order No</th>
                                <th class="stay-top">Fabrication</th>
                                <th class="stay-top">Fab (special)</th>
                                <th class="stay-top">GSM</th>
                                <th class="stay-top">Item</th>
                                <th class="stay-top">T-shirt</th>
                                <th class="stay-top">Polo</th>
                                <th class="stay-top">Pant</th>
                                <th class="stay-top">Intimates</th>
                                <th class="stay-top">Others</th>
                                <th class="stay-top">O/QTY</th>
                                <th class="stay-top">Unit Price</th>
                                <th class="stay-top">Total Value</th>
                                <th class="stay-top">CM (DZN)</th>
                                <th class="stay-top">Shipment Date</th>
                                <th class="stay-top">Print</th>
                                <th class="stay-top">EMB</th>
                                <th class="stay-top">FAC</th>
                                <th class="stay-top">P.P</th>
                                <th class="stay-top">Fac</th>
                                <th class="stay-top">Remarks</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($recap_report) )
                                @php
                                    $last_buyer_id = '';
                                    $last_booking_no = '';
                                    $last_style = '';
                                    $po_no = '';
                                    $i = 0;
                                    $sub_total_tshirt = 0;
                                    $sub_total_polo = 0;
                                    $sub_total_pant = 0;
                                    $sub_total_intimate = 0;
                                    $sub_total_others = 0;
                                    $sub_total_po_qty = 0;
                                    $sub_total_value = 0;

                                    $grand_total_tshirt = 0;
                                    $grand_total_polo  = 0;
                                    $grand_total_pant  = 0;
                                    $grand_total_intimate  = 0;
                                    $grand_total_others  = 0;
                                    $grand_total_po_qty  = 0;
                                    $grand_total_value  = 0;

                                @endphp
                                @foreach($recap_report as $key => $value)
                                    @php
                                        $i++;
                                        $sub_total_tshirt +=$value->t_shirt;
                                        $sub_total_polo +=$value->polo;
                                        $sub_total_pant +=$value->pant;
                                        $sub_total_intimate +=$value->intimate;
                                        $sub_total_others +=$value->others;
                                        $sub_total_value +=$value->total_value;

                                        $grand_total_tshirt +=$value->t_shirt;
                                        $grand_total_polo +=$value->polo;
                                        $grand_total_pant +=$value->pant;
                                        $grand_total_intimate +=$value->intimate;
                                        $grand_total_others +=$value->others;
                                        $grand_total_value +=$value->total_value;

                                        $buyer_count  = $recap_report->where('buyer',$value->buyer)->count();
                                    @endphp
                                    <tr>
                                        @if($last_buyer_id != $value->buyer)
                                            <td rowspan="{{$buyer_count}}">{{$value->buyers->name}}</td>
                                        @endif
                                        @if($last_booking_no != $value->booking_no)
                                            <td rowspan="{{$recap_report->where('booking_no',$value->booking_no)->count()}}">{{$value->booking_no}}</td>
                                        @endif
                                        @if($last_style != $value->order_style_no)
                                            <td rowspan="{{$recap_report->where('order_style_no',$value->order_style_no)->count()}}">{{$value->order_style_no}}</td>
                                        @endif
                                        @if($po_no != $value->po_no)
                                            <td rowspan="{{$recap_report->where('po_no',$value->po_no)->count()}}">{{$value->po_no}}</td>
                                        @endif
                                        <td>{{$value->fabrication}}</td>
                                        <td></td>
                                        <td>{{$value->gsm}}</td>
                                        <td>{{$value->item_data->item_name}}</td>
                                        <td>{{$value->t_shirt}}</td>
                                        <td>{{$value->polo}}</td>
                                        <td>{{$value->pant}}</td>
                                        <td>{{$value->intimate}}</td>
                                        <td>{{$value->others}}</td>
                                        @if( $po_no != $value->po_no)
                                            @php $sub_total_po_qty += $value->order_qty;$grand_total_po_qty += $value->order_qty @endphp
                                            <td rowspan="{{$recap_report->where('purchase_id',$value->purchase_id)->count()}}">{{$value->order_qty}}</td>
                                        @endif
                                        <td>{{$value->unit_price}}</td>
                                        <td>{{$value->total_value}}</td>
                                        <td>{{$value->cm}}</td>
                                        <td>{{date('d M Y',strtotime($value->shipment_date))}}</td>
                                        <td>{{$value->print ?? "N/A"}}</td>
                                        <td>{{$value->emb ?? "N/A" }}</td>
                                        <!-- <td>{{$value->purchase->print ?? "N/A"}}</td>
                                        <td>{{$value->purchase->embroidery  ?? "N/A"}}</td> -->
                                        <td>{{$value->fac ? 'Yes' : 'No'}}</td>
                                        <td>{{$value->pp ? 'Yes' : 'No'}}</td>
                                        <td>{{$value->fac}}</td>
                                        <td>{{$value->remarks}}</td>
                                    </tr>
                                    @if($buyer_count == $i)
                                        <tr style="background: #60a7f7;">
                                            <td colspan="8" style="font-weight: bold">Sub Total</td>
                                            <td><b>{{$sub_total_tshirt}}</b></td>
                                            <td><b>{{$sub_total_polo}}</b></td>
                                            <td><b>{{$sub_total_pant}}</b></td>
                                            <td><b>{{$sub_total_intimate}}</b></td>
                                            <td><b>{{$sub_total_others}}</b></td>
                                            <td><b>{{$sub_total_po_qty}}</b></td>
                                            <td></td>
                                            <td><b>{{$sub_total_value}}</b></td>
                                            <td colspan="8"></td>
                                        </tr>
                                        @php
                                            $i = 0;
                                            $sub_total_tshirt = 0;
                                            $sub_total_polo = 0;
                                            $sub_total_pant = 0;
                                            $sub_total_intimate = 0;
                                            $sub_total_others = 0;
                                            $sub_total_po_qty = 0;
                                            $sub_total_value = 0;
                                        @endphp
                                    @endif
                                    @php
                                        $last_buyer_id = $value->buyer;
                                        $last_booking_no = $value->booking_no;
                                        $last_style = $value->order_style_no;
                                        $po_no = $value->po_no;
                                    @endphp
                                @endforeach
                                <tr style="background: yellow;">
                                    <td colspan="8" style="font-weight: bold">Total</td>
                                    <td><b>{{$grand_total_tshirt}}</b></td>
                                    <td><b>{{$grand_total_polo}}</b></td>
                                    <td><b>{{$grand_total_pant}}</b></td>
                                    <td><b>{{$grand_total_intimate}}</b></td>
                                    <td><b>{{$grand_total_others}}</b></td>
                                    <td><b>{{$grand_total_po_qty}}</b></td>
                                    <td></td>
                                    <td><b>{{$grand_total_value}}</b></td>
                                    <td colspan="8"></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('script-head')
    <script src="{{ asset('js/tableHeadFixer.js') }}"></script>

    <script>
        $(document).ready(function () {
            $("#fixTable").tableHeadFixer();
        });
    </script>
    <script>
        $('body').on('click', '#print', function () {
            window.location.replace('{{ url('recap-report-print') }}');
            $('.box-body').find('div').removeClass('table-responsive');
            $('.print-delete').hide();
            window.print();
            $('.print-delete').show();
        });
    </script>
@endpush
