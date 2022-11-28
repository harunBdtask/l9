@extends('skeleton::layout')
@section('title','Monthly Stock Up Report')
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
            padding: 0px 10px 10px;
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

        thead {
            background-color: rgb(148, 218, 251) !important;
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
                <h2>Monthly Stock Up Report</h2>
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
                                            <label for="from_date" style="margin-bottom: -2.5rem;">From Date</label>
                                            <input name="from_date" id="from_date"
                                                   style="height: 32px;" type="date"
                                                   class="form-control form-control-sm"
                                                   autocomplete="off"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="to_date" style="margin-bottom: -2.5rem;">To Date</label>
                                            <input name="to_date" id="to_date"
                                                   style="height: 32px;" type="date"
                                                   class="form-control form-control-sm"
                                                   autocomplete="off"/>
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="text-left">
                                            <button style="margin-top: 19px;" id="monthlyStockUpReport"
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
                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                            {{--                            <a id="pdf" data-value="" class="btn"--}}
                            {{--                               href="/inventory/trims-store/reports/monthly-stock-up-report/pdf">--}}
                            {{--                                <em class="fa fa-file-pdf-o"></em></a>--}}

                            <a id="excel" data-value="" class="btn"
                               href="/inventory/trims-store/reports/monthly-stock-up-report/excel">
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
                                        style="font-size: 10pt; font-weight: bold;">Monthly Stock Up Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="body">
                            {{--@include('inventory::trims-store.reports.monthly_stock_up_report.body')--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push("script-head")
    <script>
        $(document).on('click', '#monthlyStockUpReport', function (event) {
            event.preventDefault();
            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();

            if (from_date === '' || to_date === '') {
                alert('Please Select Date Range')
            } else {
                $.ajax({
                    method: 'GET',
                    url: `/inventory/trims-store/reports/monthly-stock-up-report/fetch-report-data`,
                    data: {
                        from_date,
                        to_date,
                    },
                    success: function (result) {
                        $("#body").html(result);
                        let pdfQueryString = `/inventory/trims-store/reports/monthly-stock-up-report/pdf?from_date=${from_date}&to_date=${to_date}`;
                        let excelQueryString = `/inventory/trims-store/reports/monthly-stock-up-report/excel?from_date=${from_date}&to_date=${to_date}`;
                        $("#pdf").attr('href', pdfQueryString);
                        $("#excel").attr('href', excelQueryString);
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            }
        });
    </script>
@endpush
