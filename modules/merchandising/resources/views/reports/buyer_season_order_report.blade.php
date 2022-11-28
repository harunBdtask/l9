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
                    <form action="/buyer-season-order/get-report" method="post" id="reportForm">
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
                                                    value="{{ $buyer->id }}" {{$key == 0 ? 'selected' : ''}}>{{ $buyer->name }}</option>
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
                                        <select class="form-control form-control-sm select2-input" name="search_type"
                                                id="search_type">
                                            <option value="">Select Type</option>
                                            <option value="po_receive_date">PO Receive Date</option>
                                            <option value="shipment_date">Shipment Date</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" class="form-control form-control-sm" name="from_date"
                                               id="from_date">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" class="form-control form-control-sm" name="to_date"
                                               id="to_date">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            name="search" title="search" id="search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <div id="reportExport" class="text-right" style="display:none; margin-top: 1%">
                                        <button id="report_pdf" type="button" class="btn">
                                            <i class="fa fa-file-pdf-o"></i>
                                        </button>
                                        <button id="report_excel" type="button" class="btn">
                                            <i class="fa fa-file-excel-o"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="row">
                    <div id="chartFactoryWrapper" class="col-md-6" style="display:none">
                        <canvas id="chartFactory" width="auto" height="auto"></canvas>
                    </div>
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
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
                <script>
                    $(document).ready(function () {

                        let params = (new URL(document.location)).searchParams;
                        let type = params.get("type");
                        let defaultValue = params.get("default");

                        if (defaultValue === 'all') {
                            $("#buyer_id").val('all');
                        }

                        $('.print').click(function (e) {
                            e.preventDefault();
                            let buyerId = $("#buyer_id").val();
                            let seasonId = $("#season_id").val();
                            let queryString = null;
                            if (type) {
                                queryString = `type=${type}&buyer_id=${buyerId}&season_id=${seasonId}`
                            } else {
                                queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                            }
                            let link = `/buyer-season-order/get-report-print?${queryString}`;

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
                            let render_chart = null;
                            let chart_status = false;
                            const reportExport = $("#reportExport");
                            const chartFactoryWrapper = $('#chartFactoryWrapper');
                            reportTable.empty();
                            $.ajax({
                                url: "/buyer-season-order/get-report?type=" + type,
                                type: "post",
                                dataType: "json",
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
                                    if (data.status) {
                                        chartFactoryWrapper.show();
                                        if (chart_status) {
                                            render_chart.destroy();
                                        }
                                        chart_status = data.status;

                                        if (data.chart.values.length > 0) {
                                            render_chart = new Chart("chartFactory", {
                                                type: "bar",
                                                responsive: true,
                                                data: {
                                                    labels: data.chart.keys,
                                                    datasets: [{
                                                        data: data.chart.values,
                                                        backgroundColor: data.chart.colors,
                                                    }]
                                                },
                                                options: {
                                                    legend: {display: false},
                                                    title: {
                                                        display: true,
                                                        text: "Buyer Season Order Vs Value"
                                                    }
                                                }
                                            });
                                        } else {
                                            chartFactoryWrapper.hide();
                                        }
                                    } else {
                                        chartFactoryWrapper.hide();
                                    }
                                    reportTable.html(data.view);
                                },
                                error(errors) {
                                    console.log(errors);
                                }
                            });
                        })

                        $(document).on('click', '#report_pdf', function () {
                            let buyerId = $("#buyer_id").val();
                            let seasonId = $("#season_id").val();
                            let queryString = null;
                            if (type) {
                                queryString = `type=${type}&buyer_id=${buyerId}&season_id=${seasonId}`
                            } else {
                                queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                            }
                            window.location.assign(`/buyer-season-order/get-report-pdf?${queryString}`);
                        });

                        $(document).on('click', '#report_excel', function () {
                            let buyerId = $("#buyer_id").val();
                            let seasonId = $("#season_id").val();
                            // if (type) {
                            //     queryString = `type=${type}&buyer_id=${buyerId}&season_id=${seasonId}`
                            // } else {
                            let queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                            // }
                            window.location.assign(`/buyer-season-order/get-report-excel?${queryString}`);
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
