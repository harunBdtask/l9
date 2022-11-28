@extends('skeleton::layout')
@section('title', 'Daily Details Report')
@section('content')
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
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Daily Details Report</h2>
                <div class="clearfix"></div>
            </div>

            <div class="box-body table-responsive b-t">
                <div class="row">
                    {!! Form::open(['url'=>'/fabric-stock-summery-report/get-report-data', 'id'=>'reportForm']) !!}
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>From Date</label>
                                    {!! Form::date('from_date', \Carbon\Carbon::now()->firstOfMonth(),
                                            ['class'=>'form-control form-control-sm', 'id'=>'from_date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>To Date</label>
                                    {!! Form::date('to_date', \Carbon\Carbon::today(),
                                            ['class'=>'form-control form-control-sm', 'id'=>'to_date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                        name="search" title="search" id="search">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <div class="col-sm-5"></div>
                            <div class="col-sm-2 text-right" style="margin-top: 25px;">
                                <button id="report_pdf" type="button" class="btn">
                                    <i class="fa fa-file-pdf-o"></i>
                                </button>
                                <button id="report_excel" type="button" class="btn">
                                    <i class="fa fa-file-excel-o"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div>
                    <br>
                    <div>
                        <div class="body-section" style="margin-top: 0;" id="reportTable">
                        </div>
                    </div>
                </div>
            </div>

            <div style="text-align: center;">
                <img class="loader" src="{{ asset('loader.gif')}} " style="height: 30px;display: none;" alt="loader">
            </div>

        </div>
    </div>
@endsection

@push("script-head")
    <script>
        let fromDate = $("#from_date").val();
        let toDate = $("#to_date").val();
        let queryString = `from_date=${fromDate}&to_date=${toDate}`

        $(document).on('submit', '#reportForm', function (e) {
            e.preventDefault();
            let formData = $(this).serializeArray();
            let reportTable = $("#reportTable");
            reportTable.empty();
            $.ajax({
                url: "/inventory/trims-store/reports/daily-details-report/get",
                method: "get",
                data: formData,
                beforeSend() {
                    $('html,body').css('cursor', 'wait');
                    $("html").css({'background-color': 'black', 'opacity': '0.5'});
                    $(".loader").show();
                },
                complete() {
                    $('html,body').css('cursor', 'default');
                    $("html").css({'background-color': '', 'opacity': ''});
                    $(".loader").hide();
                },
                success(data) {
                    reportTable.html(data);
                },
                error(errors) {
                    console.log(errors);
                }
            })
        });

        $(document).on('click', '#report_pdf', function () {
            window.open(`/inventory/trims-store/reports/daily-details-report/pdf?${queryString}`, '_blank');
        });

        $(document).on('click', '#report_excel', function () {
            window.location.replace(`/inventory/trims-store/reports/daily-details-report/excel?${queryString}`);
        });
    </script>
@endpush
