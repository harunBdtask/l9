@extends('skeleton::layout')
@section('title','Date Wise Delivery Report')
@section('content')
    <style type="text/css">
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

        .select2-container .select2-selection--single {
            height: 32px !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 35px !important;
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
            <div class="box-header">
                <h2>Date Wise Delivery Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Party</label>
                                            <select name="" id="supplier_id"
                                                    class="form-control form-control-sm select2-input">
                                                @foreach($suppliers as $key => $supplier)
                                                    <option value="{{ $key }}">{{ $supplier }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Order No</label>
                                            <select name="" id="order_no"
                                                    class="form-control form-control-sm select2-input">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Colors</label>
                                            <select name="" id="color_id"
                                                    class="form-control form-control-sm select2-input">
                                                @foreach($colors as $key => $color)
                                                    <option value="{{ $key }}">{{ $color }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Batch No</label>
                                            <select name="" id="batch_no"
                                                    class="form-control form-control-sm select2-input">
                                                <option value="">Select</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">From Date</label>
                                            <input name="from_date" id="from_date"
                                                   style="height: 32px;" type="text"
                                                   class="form-control form-control-sm datepicker"
                                                   autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">To Date</label>
                                            <input name="to_date" id="to_date"
                                                   style="height: 32px;" type="text"
                                                   class="form-control form-control-sm datepicker"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="text-center">
                                            <button style="margin-top: 19px;" id="dyeingLedgerReport"
                                                    class="btn btn-sm btn-info"
                                                    name="type" title="Details">
                                                <i class="fa fa-search"></i>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <br>

                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                            <a id="date_wise_delivery_report_pdf" data-value="" class="btn"
                               href="">
                                <em class="fa fa-file-pdf-o"></em></a>

                            <a id="date_wise_delivery_report_excel" data-value="" class="btn"
                               href="">
                                <em class="fa fa-file-excel-o"></em>
                            </a>


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span
                                        style="font-size: 10pt; font-weight: bold;">Date Wise Delivery Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="Table">

                        </div>
                    </div>


                </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click', '#dyeingLedgerReport', function (event) {
                    event.preventDefault();

                    let supplier_id = $('#supplier_id').val();
                    let color_id = $('#color_id').val();
                    let form_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    let order_id = $('#order_no').val();
                    let batch_id = $('#batch_no').val();


                    $.ajax({
                        method: 'GET',
                        url: `/subcontract/report/date-wise-delivery-report/get-report`,
                        data: {
                            form_date,
                            to_date,
                            order_id,
                            supplier_id,
                            color_id,
                            batch_id
                        },
                        success: function (result) {
                            let pdfQueryString = `/subcontract/report/date-wise-delivery-report/pdf?form_date=${form_date}
                                              &to_date=${to_date}&order_id=${order_id}&supplier_id=${supplier_id}
                                              &color_id=${color_id}&batch_id=${batch_id}`;
                            let excelQueryString = `/subcontract/report/date-wise-delivery-report/excel?form_date=${form_date}
                                              &to_date=${to_date}&order_id=${order_id}&supplier_id=${supplier_id}
                                              &color_id=${color_id}&batch_id=${batch_id}`;
                            $('#Table').html(result);
                            $("#date_wise_delivery_report_pdf").attr('href', pdfQueryString);
                            $("#date_wise_delivery_report_excel").attr('href', excelQueryString);
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    })
                });

                $(document).on('change', '#supplier_id', function () {
                    let supplier_id = $('#supplier_id').val();

                    $.ajax({
                        method: 'GET',
                        url: `{{ url('subcontract/report/suppliers-order') }}`,
                        data: {
                            supplier_id
                        },
                        success: function (result) {
                            $('#order_no').empty().val("").trigger('change');
                            $('#order_no').append(`
                                <option value="">Select</option>
                            `)
                            $('#order_no').select2();
                            $.each(result, function (index, data) {
                                $('#order_no').append(`
                                    <option value="${data.id}">${data.order_no}</option>
                                `)
                            })
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });
                });

                $(document).on('change', '#order_no', function () {
                    let order_id = $('#order_no').val();
                    let url = `/subcontract/api/v1/order-wise-dyeing-batch/${order_id}`;

                    if (order_id) {
                        $.ajax({
                            method: 'GET',
                            url: url,
                            data: {
                                order_id
                            },
                            success: function (result) {
                                $('#batch_no').empty().val("").trigger('change');
                                $('#batch_no').append(`
                                <option value="">Select</option>
                            `)
                                $('#batch_no').select2();
                                $.each(Object.values(result.data), function (index, data) {
                                    console.log(data)
                                    $('#batch_no').append(`
                                    <option value="${data.id}">${data.text}</option>
                                `)
                                })
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        });
                    }

                });

            </script>
    @endpush
