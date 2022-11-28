@extends('skeleton::layout')
@section('title','Sample Summary Report')
@section('content')
    <style>
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
                <h2>Sample Summary Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="reportTable">
                                    <tr>
                                        <th style="width: 15%;">Merchandiser</th>
                                        <th style="width: 10%;">Buyer</th>
                                        <th style="width: 15%;">Style</th>
                                        <th style="width: 10%;">Sample name</th>
                                        <th style="width: 10%;">Sample Stage</th>
                                        <th style="width: 10%;">Delivery Status</th>
                                        <th style="width: 15%;">From Date</th>
                                        <th style="width: 15%;">To Date</th>
                                        <th>Action</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select class="form-control form-control-sm custom-select select2-input" name="dealing_merchant_id" id="dealing_merchant_id">
                                                <option value="">Select</option>
                                                @foreach($dealingMerchant as $value)
                                                    <option value="{{ $value->id }}">{{ $value->screen_name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm custom-select select2-input" name="buyer_id" id="buyer_id">
                                                <option value="">Select</option>
                                                @foreach($buyer as $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm custom-select select2-input" name="style_id" id="style_id">
                                                <option value="">Select</option>
                                                @foreach($style as $value)
                                                    <option value="{{ $value }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm custom-select select2-input" name="sample_id" id="sample_id">
                                                <option value="">Select</option>
                                                @foreach($sample as $value)
                                                    <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm custom-select select2-input" name="sample_stage" id="sample_stage">
                                                <option value="">Select</option>
                                                @foreach(\SkylarkSoft\GoRMG\Merchandising\Models\Samples\SampleRequisition::SAMPLE_STAGES as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            <select class="form-control form-control-sm custom-select select2-input" name="delivery_status" id="delivery_status">
                                                <option value="">Select</option>
                                                <option value="1">Yes</option>
                                                <option value="2">No</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input name="from_date" value="{{ $startOfMonth }}" id="from_date"
                                                   style="height: 32px;" type="text"
                                                   class="form-control form-control-sm datepicker"
                                                   autocomplete="off">
                                        </td>
                                        <td>
                                            <input name="to_date" id="to_date" value="{{ date('m/d/Y') }}"
                                                   style="height: 32px;" type="text"
                                                   class="form-control form-control-sm datepicker"
                                                   autocomplete="off">
                                        </td>
                                        <td>
                                            <button id="sampleSummaryReportSearch"
                                                    class="btn btn-sm btn-info"
                                                    name="type" title="Details">
                                                <em class="fa fa-search"> Search </em>
                                            </button>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">
                                <a id="report_pdf" data-value="" class="btn"
                                   href="/sample-summary-report/get-report-pdf">
                                    <em class="fa fa-file-pdf-o"></em></a>

                                <a id="report_excel" data-value="" class="btn"
                                   href="/sample-summary-report/excel">
                                    <em class="fa fa-file-excel-o"></em>
                                </a>
                            </div>
                        </div>
                        <center>
                            <table style="border: 1px solid black; width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                    <span
                                        style="font-size: 10pt; font-weight: bold;">Sample Summary Report</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="row p-x-1">
                            <div class="col-md-12" id="sampleSummaryReport">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                function getReportData() {
                    const $from_date = $('#from_date').val();
                    const $to_date = $('#to_date').val();
                    const $dealing_merchant_id = $('#dealing_merchant_id').val();
                    const $buyer_id = $('#buyer_id').val();
                    const $style_id = $('#style_id').val();
                    const $sample_stage = $('#sample_stage').val();
                    const $sample_id = $('#sample_id').val();
                    const $delivery_status = $('#delivery_status').val();
                    const $search = $('#search').val();
                    let reportTable = $('#sampleSummaryReport');

                    reportTable.empty();

                    if ($from_date == '' || $to_date == '') {
                        alert('Please Select Date Range')
                    } else {
                        const QueryString = new URLSearchParams({
                            'from_date': $from_date ?? '',
                            'to_date': $to_date ?? '',
                            'dealing_merchant_id': $dealing_merchant_id ?? '',
                            'buyer_id': $buyer_id ?? '',
                            'style_id': $style_id ?? '',
                            'sample_stage': $sample_stage ?? '',
                            'sample_id': $sample_id ?? '',
                            'search': $search ?? '',
                            'delivery_status': $delivery_status
                        });

                        $.ajax({
                            method: 'GET',
                            url: `/sample-summary-report/get-report?${QueryString}`,
                            success: function (result) {

                                const pdfQueryString = `/sample-summary-report/get-report-pdf?${QueryString}`;
                                const excelQueryString = `/sample-summary-report/excel?${QueryString}`;

                                reportTable.html(result);

                                $("#report_pdf").attr('href', pdfQueryString);
                                $("#report_excel").attr('href', excelQueryString);
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })
                    }
                }

                $(document).ready(function () {
                    getReportData();
                    $(document).on('click', '#sampleSummaryReportSearch', function (event) {
                        getReportData();
                    });
                })
            </script>
    @endpush
