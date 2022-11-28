@extends('skeleton::layout')
@section('title','Order Confirmation List')
@section('styles')
    {{-- <style>
        .table-header {
            background: #93dcf9;
        }
    </style> --}}
@endsection
@push('style')
    <style>
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

        .data-count {
            padding: 0px;
            text-align: right;
            font-size: 14px;
        }

        .reportTableCustom tbody tr td {
            padding-left: 3px;
            padding-right: 3px;
        }

        .in-print {
            display: none !important;
        }

        @media print {
            @page {
                size: a4 portrait !important;
                margin-top: -7mm;
            }

            .reportTable th,
            .reportTable td {
                border: 1px solid #000;
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
        }
    </style>
@endpush
@section('content')
    <div class="padding">
        <div class="box current-page">
            <div class="box-header">
                <div class="row in-print" style="display: none">
                    <h2 class="text-center">{{groupName()}}</h2>
                    <h3 class="text-center m-t-1">Unit: {{factoryName()}}</h3>
                    <h4 class="text-center m-t-1">{{factoryAddress()}}</h4>
                    <h2 class="text-center m-t-1 m-b-1">Order Conformation List</h2>

                </div>
                <div class="row print-delete">
                    <div class="col-md-6">
                        <h2>Order Confirmation list</h2>
                    </div>
                    <div class="col-md-6" align="right">
                        @if(request('buyer_id'))
                            <ul>
                                <li style="list-style: none;display: inline-block"><a class="hidden-print btn btn-xs"
                                                                                      title="Print this document"
                                                                                      id="print"><i
                                                class=" fa fa-print"></i>&nbsp;Print</a>
                                </li>
                                <li style="list-style: none;display: inline-block"><a
                                            href="{{ url('order-confirmation-list-download?type=pdf&'.request()->getQueryString())}}"
                                            class="hidden-print btn btn-xs" title="Download this pdf"><i
                                                class="fa fa-file-pdf-o"></i>&nbsp;PDF</a>
                                </li>
                                <li style="list-style: none;display: inline-block"><a
                                            href="{{ url('order-confirmation-list-download?type=excel&'.request()->getQueryString()) }}"
                                            class="hidden-print btn btn-xs" title="Download this excel file"><i
                                                class="fa fa-file-excel-o"></i>&nbsp;Excel</a></li>
                            </ul>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="box-body b-t" style="margin-top: -10px;">
                {!! Form::open(['url' => 'order-confirmation-list', 'method' => 'GET', 'class' => 'print-delete', 'autocomplete' => 'off']) !!}
                <div class="form-group row">
                    <div class="col-md-3">
                        <label>Buyer</label>
                        @php
                            $buyer_id = request()->buyer_id ?? null;
                        @endphp
                        {!! Form::select('buyer_id',$buyers, $buyer_id, ['class' => 'form-control form-control-sm', 'placeholder' => 'Select Buyer']) !!}
                    </div>
                    <div class="col-md-3">
                        <label>From</label>
                        {!! Form::text('start_date', request('start_date') ?? null, ['class' => 'form-control form-control-sm custom-datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="col-md-3">
                        <label>To</label>
                        {!! Form::text('end_date', request('end_date') ?? null, ['class' => 'form-control form-control-sm custom-datepicker', 'placeholder' => 'yyyy-mm-dd']) !!}
                    </div>
                    <div class="col-md-1">
                        <label>:</label>
                        <button type="submit" class="btn btn-sm white">Search <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}

                <hr class="print-delete">
                <div class="flash-message print-delete">
                    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                        @if(Session::has('alert-' . $msg))
                            <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                        @endif
                    @endforeach
                </div>
                {{--                    <p class="data-count print-delete">{{ $orders_list->firstItem() }}--}}
                {{--                        to {{ $orders_list->lastItem() }} of total {{$orders_list->total()}} entries</p>--}}
                <div>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Style No</th>
                            <th>PO</th>
                            <th>Buying Agent</th>
                            <th>Dealing Merchant</th>
                            <th>Order Qty</th>
                            <th>Confirmation Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if($orders_list->count()>0)
                            @foreach($orders_list as $orders_data)
                                <tr>
                                    <td>{{ $orders_data['buyer_name'] ?? 'N/A'}}</td>
                                    <td>{{ $orders_data['style_name'] ?? 'N/A'}}</td>
                                    <td>{{ $orders_data['po_no'] ?? 'N/A'}}</td>
                                    <td>{{ $orders_data['buying_agent'] ?? 'N/A'}}</td>
                                    <td>{{ $orders_data['dealing_merchant'] ?? 'N/A'}}</td>
                                    <td>{{ $orders_data['total_qty'] ?? 'N/A'}}</td>
                                    <td>{{ $orders_data['po_receive_date'] ?? 'N/A'}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7"><b>No Data found</b></td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="text-center print-delete"> {{ $orders_list->appends($_GET)->links() }}</div>
            </div>
        </div>
    </div>

@endsection

@push('script-head')
    <script>
        $(document).ready(function () {
            $('.current-page select').select2();

            $('body').on('click', '#print', function () {
                $('.print-delete').hide();
                window.print();
                $('.print-delete').show();
            });

            $('.custom-datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                clearBtn: true,
                todayBtn: 'linked',
                todayHighlight: true
            })

        })
    </script>
@endpush
