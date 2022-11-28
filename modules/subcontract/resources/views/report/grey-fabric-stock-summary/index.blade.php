@extends('skeleton::layout')
@section('title','Grey Fabric Stock Summary Report')
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
                <h2>Grey Fabric Stock Summary Report</h2>
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
                                            <label for="from_date" style="margin-bottom: -2.5rem;">Party</label>
                                            {!! Form::select('party_id', $parties ?? [], request('party_id') ?? null, [
                                                'class'=>'text-center select2-input', 'id' => 'party_id'
                                            ]) !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="text-left">
                                            <button style="margin-top: 19px;" id="greyFabricStockSummarySearch"
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

                            <a id="report_pdf" data-value="" class="btn"
                               href="/subcontract/report/grey-fabric-stock-summary/pdf">
                                <em class="fa fa-file-pdf-o"></em></a>

                            <a id="report_excel" data-value="" class="btn"
                               href="/subcontract/report/grey-fabric-stock-summary/excel">
                                <em class="fa fa-file-excel-o"></em>
                            </a>


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span
                                        style="font-size: 10pt; font-weight: bold;">Grey Fabric Stock Summary Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="dyesChemicalCostingReport">

                        </div>
                    </div>


                </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click', '#greyFabricStockSummarySearch', function (event) {
                    event.preventDefault();
                    const party_id = $('#party_id').val();

                    if (party_id) {
                        $.ajax({
                            method: 'GET',
                            url: `/subcontract/report/grey-fabric-stock-summary/get-report-data`,
                            data: {
                                'party_id': party_id,
                            },
                            success: function (result) {
                                $("#dyesChemicalCostingReport").html(result);

                                const QueryString = new URLSearchParams({
                                    'party_id': party_id,
                                });

                                const pdfQueryString = `/subcontract/report/grey-fabric-stock-summary/pdf?${QueryString}`;
                                const excelQueryString = `/subcontract/report/grey-fabric-stock-summary/excel?${QueryString}`;
                                $("#report_pdf").attr('href', pdfQueryString);
                                $("#report_excel").attr('href', excelQueryString);
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })
                    } else {
                        alert('Please Select Date Range!');
                    }
                });
            </script>
    @endpush
