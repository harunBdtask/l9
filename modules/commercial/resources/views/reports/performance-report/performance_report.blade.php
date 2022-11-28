@extends('skeleton::layout')
@section('title', 'Performance Report')
@section('content')
    <style>
        .v-align-top td, .v-align-top th {
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
                <h2>Performance Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="/performance/get-report" method="post" id="reportForm">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            Company
                                            <span class="text-danger req">*</span>
                                        </label>
                                        <select class="form-control form-control-sm select2-input" name="company_id"
                                                style="height: 20px"
                                                id="company_id">
                                            @foreach($companies as $key => $company)
                                                <option
                                                    value="{{ $company->id }}" {{$key==0 ? 'selected' : ''}}>{{ $company->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            Bank File No
                                            <span class="text-danger req">*</span>
                                        </label>
                                        <select class="form-control form-control-sm select2-input" name="bank_file_no"
                                                style="height: 20px"
                                                id="bank_file_no">
                                            <option value="">Select Bank File No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            Buyer
                                            <span class="text-danger req">*</span>
                                        </label>
                                        <select class="form-control form-control-sm select2-input" name="buyer_id"
                                                style="height: 20px"
                                                id="buyer_id">
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label>
                                            Style Name
                                            <span class="text-danger req">*</span>
                                        </label>
                                        <select class="form-control form-control-sm select2-input" name="unique_id"
                                                style="height: 20px"
                                                id="unique_id">
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
        let company = $('#company_id');
        let bankFileNo = $('#bank_file_no');
        let buyer = $('#buyer_id');
        let style = $('#unique_id');
        const exportArea = $('#export_area');
        $(document).ready(function () {
            fetchBankFileNos();

            $(document).on('submit', '#reportForm', function (e) {
                e.preventDefault();
                if (!company.val() || !bankFileNo.val() || !buyer.val() || !style.val()) {
                    alert('Please select all required fields !');
                    return false;
                }

                let formData = $(this).serializeArray();
                let reportTable = $("#reportTable");
                reportTable.empty();

                $.ajax({
                    url: "/commercial/performance-report/get",
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
                        alert('Something Went Wrong !');
                    }
                })
            })

            $(document).on('click', '#report_pdf', function () {
                let buyerId = buyer.val();
                let companyId = company.val();
                let uniqueId = style.val();
                let queryString = `company_id=${buyerId}&buyer_id=${companyId}&unique_id=${uniqueId}`

                window.location.assign(`/commercial/performance-report/pdf?${queryString}`);
            });

            $(document).on('click', '#report_excel', function () {
                let buyerId = buyer.val();
                let companyId = company.val();
                let uniqueId = style.val();
                let queryString = `company_id=${buyerId}&buyer_id=${companyId}&unique_id=${uniqueId}`

                window.location.assign(`/commercial/performance-report/excel?${queryString}`);
            });
        });

        company.change(function () {
            fetchBankFileNos();
        });

        bankFileNo.change(function () {
            fetchBankFileData();
        });

        function fetchBankFileNos() {
            let companyId = company.val();
            bankFileNo.empty().append(`<option value="">Select Bank File No</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/commercial/performance-report/fetch-bank-file-nos?company_id=${companyId}`,
                success(result) {
                    $.each(result, function (key, value) {
                        let element = `<option value="${value}">${value}</option>`;
                        bankFileNo.append(element);
                    })
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }

        function fetchBankFileData() {
            let bank_file_no = bankFileNo.val();
            buyer.empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
            style.empty().append(`<option value="">Select Style</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/commercial/performance-report/fetch-bank-file-data?bank_file_no=${bank_file_no}`,
                success(result) {
                    console.log('result', result)
                    $.each(result.buyers, function (key, value) {
                        let element = `<option value="${value.id}">${value.text}</option>`;
                        buyer.append(element);
                    });
                    $.each(result.styles, function (key, value) {
                        let element = `<option value="${value.unique_id}">${value.text}</option>`;
                        style.append(element);
                    });
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }
    </script>
@endpush

