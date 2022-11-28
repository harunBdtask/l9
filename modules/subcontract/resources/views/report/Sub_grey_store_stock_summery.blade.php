@extends('skeleton::layout')
@section('title','Order View')
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
                <h2>SubContract Grey Store Stock Summery Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-8 col-sm-offset-2">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input name="from_date" id="from_date" style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input name="to_date" id="to_date" style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </div>
                                </div>

                                <div class="col-sm-3">
                                    <div class="text-center">
                                        <button style="margin-top: 30px;" id="stockSummery" class="btn btn-sm btn-info"
                                                name="type" title="Details">
                                            <i class="fa fa-search"></i>
                                        </button>

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

                            <a id="stock_summery_pdf" data-value="" class="btn"
                               href="/subcontract/report/sub-grey-store/stock-summery/date-wise/pdf">
                                <em class="fa fa-file-pdf-o"></em></a>

                            <a id="stock_summery_excel" data-value="" class="btn"
                               href="/subcontract/report/sub-grey-store/stock-summery/date-wise/excel">
                                <em class="fa fa-file-excel-o"></em>
                            </a>


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Stock Summery Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="dateWiseStockSummeryTable">
                            @includeIf('subcontract::report.sub_grey_store_stock_summery_table')
                        </div>
                    </div>


                </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click', '#stockSummery', function (event) {
                    event.preventDefault();
                    let form_date = $('#from_date').val();
                    let to_date = $('#to_date').val();

                    $.ajax({
                        method: 'GET',
                        url: `/subcontract/report/sub-grey-store/stock-summery/date-wise`,
                        data: {
                            form_date,
                            to_date
                        },
                        success: function (result) {
                            let pdfQueryString = `/subcontract/report/sub-grey-store/stock-summery/date-wise/pdf?form_date=${form_date}&to_date=${to_date}`;
                            let excelQueryString = `/subcontract/report/sub-grey-store/stock-summery/date-wise/excel?form_date=${form_date}&to_date=${to_date}`;
                            $('#dateWiseStockSummeryTable').html(result);
                            $("#stock_summery_pdf").attr('href', pdfQueryString);
                            $("#stock_summery_excel").attr('href', excelQueryString);
                        },
                        error: function (error) {
                            console.log(error);
                        }
                    })
                });
            </script>
    @endpush
