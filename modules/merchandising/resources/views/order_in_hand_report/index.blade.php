@extends('skeleton::layout')
@section('title','Order in hand report')
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
                <h2>Order in hand report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action='{{ url("order-in-hand-report/get-report") }}' method="post" id="reportForm">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" class="form-control form-control-sm" name="from_date"
                                               value="{{ Carbon\Carbon::now()->firstOfYear()->format('Y-m-d') }}"
                                               id="from_date"/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" class="form-control form-control-sm" name="to_date"
                                               value="{{ date('Y-m-d') }}"
                                               id="to_date"/>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select name="buyer_id" class="form-control select2-input" id="buyer_id">
                                            <option value="">Select Buyer</option>
                                            @foreach($buyers as $key => $buyer)
                                                <option
                                                    value="{{ $buyer->id }}" {{ $key == 0 ? 'selected' : null }}>{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Order Status</label>
                                        <select name="status" class="form-control select2-input" id="status">
                                            <option value="">All</option>
                                            <option value="1" selected>Approved</option>
                                            <option value="2">Unapproved</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            name="search" title="search" id="search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-sm-3"></div>
                                <div id="reportExport" class="col-sm-2 text-right" style="display:none; margin-top: 1%; float: right;">
                                    <button id="report_pdf" type="button" class="btn">
                                        <i class="fa fa-file-pdf-o"></i>
                                    </button>
                                    <button id="report_excel" type="button" class="btn">
                                        <i class="fa fa-file-excel-o"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
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
                <img class="loader" src="{{asset('loader.gif')}}" style="height: 30px;display: none;" alt="loader">
            </div>
            @endsection
            @push("script-head")
                <script>
                    $(document).ready(function () {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        getReport();
                    });
                    $(document).on('submit', '#reportForm', function (e) {
                        e.preventDefault();
                        if (!$("#from_date").val()) {
                            alert("Fill from date");
                            return false;
                        }
                        getReport();
                    })

                    function getReport() {
                        let formData = $("#reportForm").serializeArray();
                        let reportTable = $("#reportTable");
                        const reportExport = $('#reportExport');
                        reportTable.empty();
                        $.ajax({
                            url: "/order-in-hand-report/get-report",
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
                                reportExport.show();
                                reportTable.html(data);
                            },
                            error(errors) {
                                console.log(errors);
                            }
                        })
                    }

                    $(document).on('click', '#report_pdf', function () {
                        let fromDate = $("#from_date").val();
                        let toDate = $("#to_date").val();
                        let buyerId = $("#buyer_id").val();
                        let status = $("#status").val();
                        let queryString = `from_date=${fromDate}&to_date=${toDate}&buyer_id=${buyerId}&status=${status}`
                        window.location.assign(`/order-in-hand-report/get-report-pdf?${queryString}`);
                    });

                    $(document).on('click', '#report_excel', function () {
                        let fromDate = $("#from_date").val();
                        let toDate = $("#to_date").val();
                        let buyerId = $("#buyer_id").val();
                        let status = $("#status").val();
                        let queryString = `from_date=${fromDate}&to_date=${toDate}&buyer_id=${buyerId}&status=${status}`
                        window.location.assign(`/order-in-hand-report/get-report-excel?${queryString}`);
                    });
                </script>
    @endpush
