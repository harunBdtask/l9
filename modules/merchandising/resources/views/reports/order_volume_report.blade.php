@extends('skeleton::layout')
@section('title','Order Volume Report')
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
                <h2>Order Volume Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    {!! Form::open(['url'=>'/order-volume-report/get-report', 'method'=>'post', 'id'=> 'reportForm']) !!}
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>From</label>
                                    {!! Form::date('from_date', \Carbon\Carbon::now()->firstOfMonth()->format('Y-m-d'), ['class'=>'form-control form-control-sm', 'id'=>'from_date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-2">
                                <div class="form-group">
                                    <label>To</label>
                                    {!! Form::date('to_date', \Carbon\Carbon::now()->lastOfMonth()->format('Y-m-d'), ['class'=>'form-control form-control-sm', 'id'=>'to_date']) !!}
                                </div>
                            </div>
                            <div class="col-sm-1">
                                <button style="margin-top: 30px;" class="btn btn-sm btn-info" name="search"
                                        title="search" id="search" type="submit">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                            <div class="col-sm-5"></div>
                            <div class="col-sm-2 text-right" style="margin-top: 1%">
                                {{-- <button type="button" class="btn print"><i class="fa fa-print"></i></button> --}}
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
                        <div class="body-section" style="margin-top: 0;" id="reportTable"></div>
                    </div>
                </div>
            </div>
            <div style="text-align: center;">
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="{{ asset('modules/skeleton/flatkit/assets/chartjs/chartjs.min.js') }}"></script>
    <script>
        let fromDate = $("#from_date");
        let toDate = $("#to_date");
        let reportTable = $("#reportTable");
        let queryString = '';
        let reportFormElement = '#reportForm';
        $(document).ready(function () {

            $(document).on('submit', reportFormElement, function (e) {
                e.preventDefault();
                generateReport();
            });
            $(reportFormElement).submit();

            $(document).on('click', '#report_pdf', function () {
                queryString = `from_date=${fromDate.val()}&to_date=${toDate.val()}`;
                window.location.replace(`/order-volume-report/get-report-pdf?${queryString}`);
            });

            $(document).on('click', '#report_excel', function () {
                queryString = `from_date=${fromDate.val()}&to_date=${toDate.val()}`;
                window.location.replace(`/order-volume-report/get-report-excel?${queryString}`);
            });
        });

        function generateReport() {
            let formData = $(reportFormElement).serializeArray();
            reportTable.empty();
            $.ajax({
                url: "/order-volume-report/get-report",
                type: "post",
                dataType: "html",
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
        }
    </script>
@endsection
