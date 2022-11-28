@extends('skeleton::layout')
@section('title','Order Status Report')
@section('styles')
    <style>
        .v-align-top td, .v-algin-top th {
            vertical-align: top;
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .header-section {
            padding: 10px;
        }

        .body-section {
            padding: 10px;
            padding-top: 0px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding-left: 5px;
            padding-right: 5px;
            padding-top: 3px;
            padding-bottom: 3px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
            /*margin: 5mm;*/
            /*margin-left: 15mm;*/
            /*margin-right: 15mm;*/
        }

        @media print {
            html, body {
                width: 210mm;
                /*height: 293mm;*/
            }

            .page {
                margin: 0;
                border: initial;
                border-radius: initial;
                width: initial;
                min-height: initial;
                box-shadow: initial;
                background: initial;
                page-break-after: always;
            }
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Order Status Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="{{ url('knitting/order-status-report') }}">
                        <div class="col-sm-12">
                            <form class="row" method="GET" action="{{ url('knitting/order-status-report') }}">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="buyer">Buyer/Party</label>
                                        <select name="buyer_id" class="form-control form-control-sm select2-input" id="buyer">
                                            @foreach($buyers as $buyer)
                                                <option value="{{ $buyer->id }}" {{ request('buyer_id') == $buyer->id ? 'selected' : '' }}>
                                                    {{ $buyer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label for="salesOrderNo">Sales Order No</label>
                                        <select name="sales_order_no" class="form-control form-control-sm select2-input" id="salesOrderNo">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="display:flex;">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            title="Search" type="submit">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <a href="/knitting/order-status-report" style="margin-top: 30px; margin-left: 5px;" class="btn btn-sm btn-primary"
                                            title="Refresh">
                                        <i class="fa fa-refresh"></i>
                                    </a>
                                </div>
                                <div class="col-sm-3"></div>
                                <div class="col-sm-2 text-right" style="margin-top: 2%;">
                                    <a class="btn" href="{{ url('/knitting/order-status-report/pdf?'.Request::getQueryString()) }}">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </a>
                                    <a class="btn" href="{{ url('/knitting/order-status-report/excel?'.Request::getQueryString()) }}">
                                        <i class="fa fa-file-excel-o"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div>
                    <br>
                    @if($data)
                        @includeIf('knitting::reports.order-status-report.view-body')
                    @endif
                </div>
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection


@section('scripts')

<script type="text/javascript">
    $(function() {
        getSalesOrder();
    })

    $(document).on('change', '#buyer', function () {
        getSalesOrder();
    });

    function getSalesOrder() {
        const buyerId = $('#buyer').val();
        const element = $('#salesOrderNo');
        element.empty().append(`<option value="">Select</option>`).val('').trigger('change');
        $.ajax({
            method: 'GET',
            url: '/knitting/api/v1/common/get-sales-order-nos-by-buyer/'+buyerId,
            success(response) {
                $.each(response, function (i) {
                    element.append("<option value="+response[i].sales_order_no+">"+response[i].sales_order_no+"</option>")
                })
                element.val('{{ request("sales_order_no") }}').trigger('change');
            }
        })
    }
</script>

@endsection