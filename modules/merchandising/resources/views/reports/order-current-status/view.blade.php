@extends('skeleton::layout')
@section('title','Current Order Status Report')
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
                <h2>Current Order Status Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="/budget-wise-wo-report/get-report" method="post" id="reportForm">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Company</label>
                                        <select class="form-control form-control-sm select2-input" name="company_id"
                                                style="height: 20px"
                                                id="company_id">
                                            <option>Select Company</option>
                                            @foreach($companies as $key => $company)
                                                <option
                                                    value="{{ $company->id }}" {{$key==0 ? 'selected' : ''}}>{{ $company->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label>Buyer</label>
                                        <select class="form-control form-control-sm select2-input" name="buyer_id"
                                                style="height: 20px"
                                                id="buyer_id">
                                            <option>Select Buyer</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Style</label>
                                        <select class="form-control form-control-sm select2-input" name="style_name"
                                                id="style_name">
                                            <option>Select Style</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>Unique Id</label>
                                        <input class="form-control form-control-sm " name="unique_id"
                                               id="unique_id">

                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>From Date</label>
                                        <input type="date" class="form-control form-control-sm" name="from_date" id="from_date">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>To Date</label>
                                        <input type="date" class="form-control form-control-sm" name="to_date" id="to_date">
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"
                                            name="search" title="search" id="search">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="row">
{{--                                <div class="col-sm-2">--}}
{{--                                    <button style="margin-top: 30px;" class="btn btn-sm btn-info"--}}
{{--                                            name="search" title="search" id="search">--}}
{{--                                        <i class="fa fa-search"></i>--}}
{{--                                    </button>--}}
{{--                                </div>--}}
                                <div class="col-sm-10"></div>
                                <div id="exprot_area" class="col-sm-2 text-right" style="display:none; margin-top: 1%">
                                    {{--                                    <button type="button" class="btn print">--}}
                                    {{--                                        <i class="fa fa-print"></i>--}}
                                    {{--                                    </button>--}}
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
        let buyer = $('#buyer_id');
        let company = $('#company_id');
        let styleName = $('#style_name');
        let uniqueId = $('#unique_id');
        let fromDate = $('#from_date');
        let toDate = $('#to_date');
        const exportArea = $('#exprot_area');
        $(document).ready(function () {

            $('.print').click(function (e) {
                e.preventDefault();
                let buyerId = $("#buyer_id").val();
                let seasonId = $("#season_id").val();
                let queryString = `buyer_id=${buyerId}&season_id=${seasonId}`
                let link = `/budget-wise-wo-report/get-report-print?${queryString}`;

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

            fetchBuyers();

            $(document).on('submit', '#reportForm', function (e) {
                e.preventDefault();
                // if (!uniqueId.val() || !buyer.val()) {
                //     alert("Buyer and Unique id must be selected!");
                //     return false;
                // }

                let formData = $(this).serializeArray();
                let reportTable = $("#reportTable");
                reportTable.empty();
                $.ajax({
                    url: "/current-order-status-report/get-report",
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
                        console.log('data is', data)
                        exportArea.show();
                        reportTable.html(data);
                    },
                    error(errors) {
                        console.log(errors);
                    }
                })
            })

            $(document).on('click', '#report_pdf', function () {
                let buyerId = buyer.val();
                let companyId = company.val();
                let unique_id = uniqueId.val();
                let styleName = $('#style_name').val();
                let fromDate = $('#from_date').val();
                let toDate = $('#to_date').val();

                let queryString = `company_id=${companyId}&buyer_id=${buyerId}&unique_id=${unique_id}&style_name=${styleName}&to_date=${toDate}&from_date=${fromDate}`

                window.location.replace(`/current-order-status-report/get-report-pdf?${queryString}`);
            });

            $(document).on('click', '#report_excel', function () {
                let buyerId = buyer.val();
                let companyId = company.val();
                let unique_id = uniqueId.val();
                let styleName = $('#style_name').val();
                let fromDate = $('#from_date').val();
                let toDate = $('#to_date').val();

                let queryString = `company_id=${companyId}&buyer_id=${buyerId}&unique_id=${unique_id}&style_name=${styleName}&to_date=${toDate}&from_date=${fromDate}`

                window.location.replace(`/current-order-status-report/get-report-excel?${queryString}`);
            });
        });

        buyer.change(function () {
            fetchUniqueId();
        });
        company.change(function () {
            fetchBuyers();
        });
        styleName.change(function () {
            setUniqueId();
        });

        function fetchBuyers() {
            let companyId = company.val();
            buyer.empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/common-api/${companyId}/buyers`,
                success(result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.id}">${value.name}</option>`;
                        buyer.append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }

        function fetchUniqueId() {
            let buyerId = buyer.val();
            let companyId = company.val();
            if (!buyerId) {
                return false;
            }
            styleName.empty().append(`<option value="">Select Style Name</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/common-api/factory-buyers-style-name/${companyId}/${buyerId}`,
                success(result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value.text}">${value.text}</option>`;
                        styleName.append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }

        function setUniqueId() {
            let buyerId = buyer.val();
            let companyId = company.val();
            let style = styleName.val();

            if (style) {
                $.ajax({
                    method: 'GET',
                    url: `/common-api/fetch-unique-id?buyerId=${buyerId}&companyId=${companyId}&style=${style}`,
                    success(result) {
                        uniqueId.val(result);
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            }

        }
    </script>
@endpush

