@extends('skeleton::layout')
@section('title','Good Received With LC Open')
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
                <h2>Good Received With Lc Open</h2>
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
                                            <label style="margin-bottom: -2.5rem;">Party</label>
                                            <select name="party_type" id="party_type"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach($loanParty as $value)
                                                    <option @if($value->id == request()->get('party_type')) selected
                                                            @endif
                                                            value="{{ $value->id }}"> {{ $value->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Count</label>
                                            <select name="count" id="count"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach($yarnCount as $value)
                                                    <option @if($value->id == request()->get('count')) selected @endif
                                                    value="{{ $value->id }}"> {{ $value->yarn_count }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Type</label>
                                            <select name="type" id="type"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach($type as $value)
                                                    <option @if($value->id == request()->get('type')) selected @endif
                                                    value="{{ $value->id }}"> {{ $value->name }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Composition</label>
                                            <select name="composition" id="composition"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach($composition as $value)
                                                    <option @if($value->id == request()->get('composition')) selected
                                                            @endif
                                                            value="{{ $value->id }}"> {{ $value->yarn_composition }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Color</label>
                                            <input name="color" id="color" type="text"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Certification</label>
                                            <input name="certification" id="certification" type="text"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>

                                    <div class="col-sm-2" style="width: 15%;">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">LC Date/PI Date</label>
                                            <select name="lc_date_or_pi_date" id="lc_date_or_pi_date"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                <option value="lc_date">LC Date</option>
                                                <option value="pi_date">PI Date</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Form Date</label>
                                            <input name="form_date" id="form_date" type="date"
                                                   class="form-control form-control-sm search-field text-center">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">To Date</label>
                                            <input name="to_date" id="to_date" type="date"
                                                   class="form-control form-control-sm search-field text-center">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">LC No</label>
                                            <select name="lc_no" id="lc_no"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach($lcNos as $value)
                                                    <option @if($value == request()->get('lc_no')) selected @endif
                                                    value="{{ $value }}"> {{ $value }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">PI No</label>
                                            <select name="pi_no" id="pi_no"
                                                    class="form-control form-control-sm search-field text-center c-select select2-input">
                                                <option value="">Select</option>
                                                @foreach($piNos as $value)
                                                    <option @if($value == request()->get('pi_no')) selected @endif
                                                    value="{{ $value }}"> {{ $value }} </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-2" style="width: 10%;">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Lot No</label>
                                            <input name="lot_no" id="lot_no" type="text"
                                                   class="form-control form-control-sm">
                                        </div>
                                    </div>


                                    <div class="col-sm-1">
                                        <div class="text-center">
                                            <button style="margin-top: 19px;" id="goodReceivedLcOpenReport"
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
                <br>

                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                            <a id="good_received_with_lc_open_pdf" data-value="" class="btn"
                               href="/subcontract/report/batch/pdf">
                                <em class="fa fa-file-pdf-o"></em></a>

                            {{--                            <a id="batch_excel" data-value="" class="btn" href="/subcontract/report/batch/excel">--}}
                            {{--                                <em class="fa fa-file-excel-o"></em>--}}
                            {{--                            </a>--}}


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span
                                        style="font-size: 12pt; font-weight: bold;">Good Received With LC Open Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="goodReceivedWithLcOpenTable">
                            {{-- @includeIf('subcontract::report.batch-report.batch-report-table') --}}
                        </div>
                    </div>


                </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click', '#goodReceivedLcOpenReport', function (event) {
                    event.preventDefault();

                    let party_type = $('#party_type').val();
                    let count = $('#count').val();
                    let type = $('#type').val();
                    let composition = $('#composition').val();
                    let color = $('#color').val();
                    let certification = $('#certification').val();
                    let lc_date_or_pi_date = $('#lc_date_or_pi_date').val();
                    let form_date = $('#form_date').val();
                    let to_date = $('#to_date').val();
                    let lc_no = $('#lc_no').val();
                    let pi_no = $('#pi_no').val();
                    let lot_no = $('#lot_no').val();

                    console.log(lc_date_or_pi_date, form_date, to_date)

                    $.ajax({
                        method: 'GET',
                        url: `/inventory/good-received-with-lc-open/get-report`,
                        data: {
                            party_type,
                            count,
                            type,
                            composition,
                            color,
                            certification,
                            lc_date_or_pi_date,
                            form_date,
                            to_date,
                            lc_no,
                            pi_no,
                            lot_no
                        },
                        success: function (result) {
                            let pdfQueryString = `/inventory/good-received-with-lc-open/pdf?party_type=${party_type}
                        &lc_date_or_pi_date=${lc_date_or_pi_date}
                        &form_date=${form_date}
                        &to_date=${to_date}
                        &lc_no=${lc_no}
                        &pi_no=${pi_no}`;
                            // let excelQueryString = `/subcontract/report/batch/excel?batch_id=${batch_id}`;
                            $('#goodReceivedWithLcOpenTable').html(result);
                            $("#good_received_with_lc_open_pdf").attr('href', pdfQueryString);
                            // $("#batch_excel").attr('href', excelQueryString);
                        },
                        error: function (error) {
                            console.log(error)
                        }
                    })
                });

            </script>
    @endpush

