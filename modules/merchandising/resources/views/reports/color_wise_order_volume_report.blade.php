@extends('skeleton::layout')
@section('title','ORDER CONFIRMATION SHEET- COLOR WISE')
@section('content')
    <style>
        #pdfGenerateInfo {
            display: none;
        }

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
            padding: 0 10px 10px;
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
            padding: 3px 5px;
        }

        table.borderless {
            border: none;
        }

        .borderless td, .borderless th {
            border: none;
        }

        @page {
            size: landscape;
        }

        .select2-container .select2-selection--single {
            height: 32px !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px !important;
        }

        @media print {
            html, body {
                width: 210mm;
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
            <div class="box-header">
                <h2>ORDER CONFIRMATION SHEET- COLOR WISE</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-12 ">
                            <table class="reportTable">
                                <tr>
                                    <th style="width: 100px;">Buyer</th>
                                    <th style="width: 100px;">Season</th>
                                    <th>Date Type</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>O. Lead Time</th>
                                    <th>P. Lead Time</th>
                                    <th>Search</th>
                                </tr>
                                <tr>
                                    <td>
                                        <select class="form-control select2-input" name="buyer_id" id="buyer_id">
                                            <option value="">Select</option>
                                            @foreach ($buyers as $buyer)
                                                <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2-input" name="season_id" id="season_id">
                                            <option value="">Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="date_type" id="date_type"
                                                class="form-control form-control-sm select2-input">
                                            <option selected value="po_receive_date">PO Receive Date</option>
                                            <option value="pi_bunch_a_budget_date">PI Bunch & Budget Date</option>
                                            <option value="expected_bom_handover_date">Ex. BOM handover date</option>
                                            <option value="fri_date">Fri Date</option>
                                            <option value="rfs_date">RFS Date</option>
                                            <option value="shipment_date">Shipment Date</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input name="from_date" id="from_date"
                                               value="{{ \Carbon\Carbon::now()->subMonths(2)->format('m/d/Y') }}"
                                               style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </td>
                                    <td>
                                        <input name="to_date" id="to_date" value="{{ date('m/d/Y') }}"
                                               style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" placeholder="Write"
                                               name="order_lead_time" id="order_lead_time" type="text">
                                    </td>
                                    <td>
                                        <input class="form-control form-control-sm" placeholder="Write"
                                               name="prod_lead_time" id="prod_lead_time" type="text">
                                    </td>
                                    <td>
                                        <button id="ColorWiseOrderVolumeReport"
                                                class="btn btn-sm btn-info"
                                                name="type" title="Details">
                                            <em class="fa fa-search"></em>
                                        </button>
                                    </td>
                                </tr>
                            </table>
                        </div>

                    </form>
                </div>
                <br>

                <div class="">
                    <div class="header-section" style="padding-bottom: 0;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                            <a id="order_volume_pdf" data-value="" class="btn"
                               href="{{ url('date-wise-stock-summery-report-pdf') }}">
                                <em class="fa fa-file-pdf-o"></em></a>

                            <a id="order_volume_excel" data-value="" class="btn"
                               href="{{ url('date-wise-stock-summery-report-excel') }}">
                                <em class="fa fa-file-excel-o"></em>
                            </a>


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 25%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span
                                        style="font-size: 12pt; font-weight: bold;">ORDER CONFIRMATION SHEET- COLOR WISE</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12" id="ColorWiseOrderVolumeReportTable">
                    </div>
                </div>


            </div>
        </div>
    </div>
@endsection
@push("script-head")
    <script>
        $(document).ready(function () {
            fetchReport();
        });
        $(document).on('click', '#ColorWiseOrderVolumeReport', function (event) {
            fetchReport();
        });

        function fetchReport() {
            event.preventDefault();
            let buyer_id = $('#buyer_id').val();
            let season_id = $('#season_id').val();
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
            let order_lead_time = $('#order_lead_time').val();
            let prod_lead_time = $('#prod_lead_time').val();
            let date_type = $('#date_type').val();

            let queryString = new URLSearchParams({
                buyer_id,
                season_id,
                from_date,
                to_date,
                order_lead_time,
                prod_lead_time,
                date_type
            });
            $.ajax({
                method: 'GET',
                url: `/color-wise-order-volume-report/get-report?${queryString}`,
                success: function (result) {
                    let pdfQueryString = `/color-wise-order-volume-report/get-pdf?${queryString}`;
                    let excelQueryString = `/color-wise-order-volume-report/get-excel?${queryString}`;
                    $('#ColorWiseOrderVolumeReportTable').html(result);
                    $("#order_volume_pdf").attr('href', pdfQueryString);
                    $("#order_volume_excel").attr('href', excelQueryString);
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }

        $(document).on('change', '#buyer_id', function () {
            let buyer = $('#buyer_id').val();
            console.log(buyer)
            $.ajax({
                method: 'GET',
                url: `{{ url('color-wise-order-volume-report/buyer-wise-season') }}`,
                data: {
                    buyer
                },
                success: function (result) {
                    $('#season_id')
                        .empty()
                        .select2()
                        .append(`
                                        <option value="">Select</option>
                                `)
                    $.each(result, function (index, data) {
                        $('#season_id').append(`
                                    <option value="${data.id}">${data.season_name}</option>
                                `)
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            });
        });
    </script>
@endpush
