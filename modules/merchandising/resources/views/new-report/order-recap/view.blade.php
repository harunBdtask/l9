@extends('skeleton::layout')
@section('title','Order Recap Report')
@section('content')
    <style type="text/css">
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
    <div class="padding">
        <div class="box">
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="/order-recap-report-v2?type={{ $type }}" method="post" id="search_form">
                        @csrf
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select name="factory_id" class="form-control select2-input" id="factory_id">
                                            <option value="">Select Company</option>
                                            @foreach($factories as $key=>$factory)
                                                <option
                                                    value="{{ $factory->id }}" {{ $factory->id == $factoryId ? 'selected' : null }}>{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select name="buyer_id" class="form-control select2-input" id="buyer_id">
                                            <option value="">Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option
                                                    value="{{ $buyer->id }}" {{ $buyer->id == $buyerId ? 'selected' : null }}>{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Seasons</label>
                                        <select name="season_id" class="form-control select2-input" id="season_id">
                                            <option value="">Select Season</option>
                                            @foreach($seasons as $season)
                                                <option
                                                    value="{{ $season->id }}" {{ $season->id == $seasonId ? 'selected' : null }}>{{ $season->season_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Style Name</label>
                                        <select name="style_id" class="form-control select2-input" id="style_id">
                                            <option value="">Select Style</option>
                                            @foreach($styles as $style)
                                                <option
                                                    value="{{ $style->id }}" {{ $style->id == $styleId ? 'selected' : null }}>{{ $style->style_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="{{!$type == 'qc' ? 'col-sm-2' : 'col-sm-3'}}">
                                <div class="form-group">
                                    <label>Po Name</label>
                                    <select name="po_id" class="form-control select2-input" id="po_id">
                                        <option value="">Select PO</option>
                                        @foreach($pos as $po)
                                            <option
                                                value="{{ $po->id }}" {{ $po->id == $poId ? 'selected' : null }}>{{ $po->po_no }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @if (!$type == 'qc')
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>Range Type</label>
                                        <select name="date_range_type" id="date_range_type"
                                                class="form-control select2-input">
                                            <option selected disabled hidden>DATE RANGE TYPE</option>
                                            <option value="1" {{$dateRangeType=='1' ? 'selected' : ''}}>PO RECEIVE DATE
                                            </option>
                                            <option value="2" {{$dateRangeType=='2' ? 'selected' : ''}}>SHIPMENT DATE
                                            </option>
                                            <option value="3" {{$dateRangeType=='3' ? 'selected' : ''}}>COUNTRY SHIP
                                                DATE
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>From Date</label>
                                    <input name="from_date" id="from_date" style="height: 40px;" type="date"
                                           class="form-control" autocomplete="off" value="{{$fromDate}}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>To Date</label>
                                    <input name="to_date" id="to_date" style="height: 40px;" type="date"
                                           class="form-control" autocomplete="off" value="{{$toDate}}">
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <div class="form-group">
                                    <label></label>
                                    <button style="margin-top: 8px;" type="submit" class="btn btn-info">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(($orderData))
                    <div class="">
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="excel_button" class="btn excel" href="javascript:void(0)"><i
                                        class="fa fa-file-excel-o"></i>
                                </a>
                                <a id="order_pdf" class="btn" href="javascript:void(0)"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                            @includeIf('merchandising::pdf.header')
                            <hr>
                        </div>
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span
                                            style="font-size: 12pt; font-weight: bold;">{{$type == 'qc' ? 'Order Recap Q/C Report' : 'Order Status'}}</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="body-section" style="margin-top: 0;">
                            @includeIf('merchandising::new-report.order-recap.table')
                        </div>

                        <div style="margin-top: 16mm">
                            <table class="borderless">
                                <tbody>
                                <tr>
                                    <td class="text-center"><u>Prepared By</u></td>
                                    <td class='text-center'><u>Checked By</u></td>
                                    <td class="text-center"><u>Approved By</u></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection
@push("script-head")
    <script>
        $(document).ready(function () {

            $(document).on('change', '#factory_id', function () {
                let factoryId = $(this).val();
                $('#buyer_id').empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
                $.ajax({
                    method: 'GET',
                    url: `/orders/get-buyers?factoryId=${factoryId}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.id}">${value.name}</option>`;
                            $('#buyer_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            });

            $(document).on('change', '#buyer_id', function () {
                let factoryId = $("#factory_id").val();
                let buyerId = $(this).val();
                $('#season_id').empty().append(`<option value="">Select Season</option>`).val('').trigger('change');

                $.ajax({
                    method: 'GET',
                    url: `/orders/get-seasons?factoryId=${factoryId}&buyerId=${buyerId}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.id}">${value.season_name}</option>`;
                            $('#season_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })

            });

            $(document).on('change', '#season_id', function () {
                let factoryId = $("#factory_id").val();
                let buyerId = $("#buyer_id").val();
                let seasonId = $(this).val();
                $('#style_id').empty().append(`<option value="">Select Style</option>`).val('').trigger('change');
                $.ajax({
                    method: 'GET',
                    url: `/get-seasons-style?factoryId=${factoryId}&buyerId=${buyerId}&seasonId=${seasonId}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.id}">${value.style_name}</option>`;
                            $('#style_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            })

            $(document).on('change', '#style_id', function () {
                let factoryId = $("#factory_id").val();
                let buyerId = $("#buyer_id").val();
                let seasonId = $("#season_id").val();
                let styleId = $(this).val();
                $('#po_id').empty().append(`<option value="">Select Po</option>`).val('').trigger('change');
                $.ajax({
                    method: 'GET',
                    url: `/get-pos?styleId=${styleId}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.id}">${value.po_no}</option>`;
                            $('#po_id').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            })
        });

        $(document).on('click', '#order_pdf', function () {
            $(`#search_form`).attr('action', 'order-recap-report-v2-pdf?type={{ $type }}');
            $(`#search_form`).submit();
        });
        $(document).on('click', '#excel_button', function () {
            $("#search_form").attr('action', 'order-recap-report-v2-excel?type={{ $type }}');
            $("#search_form").submit();
        });
    </script>
@endpush
