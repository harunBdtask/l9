@extends('skeleton::layout')
@section('title','Order Tracking Report')
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
                <h2>Sub Dyeing Order Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Factory</label>
                                            <select name="" id="factory_id"
                                                    class="form-control form-control-sm select2-input">
                                                @foreach ($factories as $key => $factory)
                                                    <option value="{{ $key }}">{{ $factory }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Order</label>
                                            <select name="" id="order_id"
                                                    class="form-control form-control-sm select2-input">

                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-sm-1">
                                        <div class="text-center">
                                            <button style="margin-top: 19px;" id="orderReport"
                                                    class="btn btn-sm btn-info"
                                                    name="type" title="Details">
                                                <em class="fa fa-search"></em>
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

                            <a id="order_pdf" data-value="" class="btn" href="/subcontract/report/order/pdf">
                                <em class="fa fa-file-pdf-o"></em></a>

                            <a id="batch_excel" data-value="" class="btn" href="/subcontract/report/order/excel">
                                <em class="fa fa-file-excel-o"></em>
                            </a>


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Order Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="orderTable">

                        </div>
                    </div>


                </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click', '#orderReport', function (event) {
                    event.preventDefault();
                    let order_id = $('#order_id').val();
                    if (!order_id) {
                        alert('Please Select An Order')
                    } else {
                        $.ajax({
                            method: 'GET',
                            url: `/subcontract/report/order/get-report`,
                            data: {
                                order_id,
                            },
                            success: function (result) {
                                let pdfQueryString = `/subcontract/report/order/pdf?order_id=${order_id}`;
                                let excelQueryString = `/subcontract/report/order/excel?order_id=${order_id}`;
                                $('#orderTable').html(result);
                                $("#order_pdf").attr('href', pdfQueryString);
                                $("#batch_excel").attr('href', excelQueryString);
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })
                    }
                });

                $(document).on('change', '#factory_id', function () {
                    let factory = $('#factory_id').val();
                    $.ajax({
                        method: 'GET',
                        url: `{{ url('subcontract/report/factories-order') }}`,
                        data: {
                            factory
                        },
                        success: function (result) {
                            $('#order_id').empty().select2();
                            $('#order_id').append(`
                            <option value="">Select</option>
                            `)
                            $.each(result, function (index, data) {
                                $('#order_id').append(`
                            <option value="${data.id}">${data.order_no}</option>
                            `)
                            })
                            console.log(result)
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    });
                });
            </script>
    @endpush