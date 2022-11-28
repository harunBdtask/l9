@extends('skeleton::layout')
@section('title','Buyer-PO List')
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
                <h2>Buyer-PO List</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="/buyer-season-color-order/get-report" method="post" id="reportForm">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select class="form-control form-control-sm select2-input" name="buyer_id"
                                                style="height: 20px"
                                                id="buyer_id">
                                            <option>Select Buyer</option>
                                            <option value="all">All Buyer</option>
                                            @foreach($buyers as $key => $buyer)
                                                <option
                                                    value="{{ $buyer->id }}" {{$key==0 ? 'selected' : ''}}>
                                                    {{ $buyer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Season</label>
                                        <select class="form-control form-control-sm select2-input" name="season_id"
                                                id="season_id">
                                            <option>Select Season</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Type</label>
                                        <select class="form-control form-control-sm select2-input" name="shipment_type"
                                                id="shipment_type">
                                            <option>Select Shipment Type</option>
                                            <option value="1">Act Ship Date</option>
                                            <option value="2">Fac Ship Date</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2" style="">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input class="form-control form-control-sm"
                                               name="from_date"
                                               type="date"
                                               value="{{ request('from_date') }}"/>
                                    </div>
                                </div>

                                <div class="col-sm-2" style="">
                                    <label>To Date</label>
                                    <input class="form-control form-control-sm"
                                           name="to_date"
                                           type="date"
                                           value="{{ request('to_date') }}"/>
                                </div>

                                <div class="col-sm-1">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            name="search" title="search" id="search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>

                                <div id="reportExprot" class="col-sm-1 text-right" style="display:none; margin-top: 1.5%">
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

                        $('.print').click(function (e) {
                            e.preventDefault();
                            let buyerId = $("#buyer_id").val();
                            let seasonId = $("#season_id").val();
                            let queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                            let link = `/buyer-season-color-order/get-report-print?${queryString}`;

                            printPage(link);
                        });

                        function closePrint() {
                            document.body.removeChild(this.__container__);
                        }

                        function setPrint() {
                            this.contentWindow.__container__ = this;
                            this.contentWindow.onbeforeunload = closePrint;
                            this.contentWindow.onafterprint = closePrint;
                            this.contentWindow.focus(); // Required for IE
                            this.contentWindow.print();
                        }

                        function printPage(sURL) {
                            var oHiddFrame = document.createElement("iframe");
                            oHiddFrame.onload = setPrint;
                            oHiddFrame.style.visibility = "hidden";
                            oHiddFrame.style.position = "fixed";
                            oHiddFrame.style.right = "0";
                            oHiddFrame.style.bottom = "0";
                            oHiddFrame.src = sURL;
                            document.body.appendChild(oHiddFrame);
                        }

                        fetchSeason();

                        $(document).on('submit', '#reportForm', function (e) {
                            e.preventDefault();
                            let formData = $(this).serializeArray();
                            let reportTable = $("#reportTable");
                            const reportExprot = $('#reportExprot');
                            reportTable.empty();
                            $.ajax({
                                url: "/buyer-season-color-order/get-report",
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
                                    reportExprot.show();
                                    reportTable.html(data);
                                },
                                error(errors) {
                                    console.log(errors);
                                }
                            })
                        })

                        $(document).on('click', '#report_pdf', function () {
                            let buyerId = $("#buyer_id").val();
                            let seasonId = $("#season_id").val();
                            let queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                            window.location.assign(`/buyer-season-color-order/get-report-pdf?${queryString}`);
                        });

                        $(document).on('click', '#report_excel', function () {
                            let buyerId = $("#buyer_id").val();
                            let seasonId = $("#season_id").val();
                            let queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                            window.location.assign(`/buyer-season-color-order/get-report-excel?${queryString}`);
                        });
                    });

                    $('#buyer_id').change(function () {
                        fetchSeason();
                    });

                    function fetchSeason() {
                        let buyerId = $("#buyer_id").val();
                        $('#season_id').empty().append(`<option value="">Select Season</option>`).val('').trigger('change');
                        $.ajax({
                            method: 'GET',
                            url: `/get-buyers-seasons/${buyerId}`,
                            success(result) {
                                $.each(result, function (key, value) {
                                    let element = `<option value="${value.id}">${value.season_name}</option>`;
                                    $('#season_id').append(element);
                                })
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })
                    }
                </script>
    @endpush
