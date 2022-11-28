@extends('skeleton::layout')
@section('title', 'PI Tracking Report')
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
                <h2>PI Tracking Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="/pi-tracking-report/get" method="post" id="reportForm">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select class="form-control form-control-sm select2-input" name="buyer_id"
                                                style="height: 20px"
                                                id="buyer_id">
                                            @foreach($buyers as $key => $buyer)
                                                <option value="{{ $buyer->id }}" {{$key==0 ? 'selected' : ''}}>
                                                    {{ $buyer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            From Date
                                            <span class="text-danger req">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm" name="from_date"
                                               id="from_date">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            To Date
                                            <span class="text-danger req">*</span>
                                        </label>
                                        <input type="date" class="form-control form-control-sm" name="to_date"
                                               id="to_date">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            PI No
                                        </label>
                                        <select class="form-control form-control-sm select2-input" name="pi_no"
                                                style="height: 20px"
                                                id="pi_no">
                                            <option value="">Select</option>
                                            @foreach($piNos as $key => $value)
                                                <option value="{{ $value }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            name="search" title="search" id="search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-sm-1"></div>
                                <div id="export_area" class="col-sm-2 text-right" style="display:none; margin-top: 1%">
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
        </div>
    </div>
@endsection

@push("script-head")
    <script>
        let buyerId = $('#buyer_id');
        let fromDate = $('#from_date');
        let toDate = $('#to_date');
        let piNo = $('#pi_no');
        const exportArea = $('#export_area');

        $(document).on('submit', '#reportForm', function (e) {
            e.preventDefault();
            if (!fromDate.val() || !toDate.val()) {
                alert("Please select date range !");
                return false;
            }

            let formData = $(this).serializeArray();
            let reportTable = $("#reportTable");
            reportTable.empty();
            $.ajax({
                url: "/commercial/pi-tracking-report/get",
                type: "get",
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
                    exportArea.show();
                    reportTable.html(data);
                },
                error(errors) {
                    alert("Something Went Wrong");
                }
            })
        })

        $(document).on('click', '#report_pdf', function () {
            let queryString = `buyer_id=${buyerId.val()}&from_date=${fromDate.val()}&to_date=${toDate.val()}&pi_no=${piNo.val()}`;
            window.location.assign(`/commercial/pi-tracking-report/pdf?${queryString}`);
        });

        $(document).on('click', '#report_excel', function () {
            let queryString = `buyer_id=${buyerId.val()}&from_date=${fromDate.val()}&to_date=${toDate.val()}&pi_no=${piNo.val()}`;
            window.location.assign(`/commercial/pi-tracking-report/excel?${queryString}`);
        });

    </script>
@endpush
