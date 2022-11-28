@extends('skeleton::layout')
@section('title','Finish Fabric Receive Report')
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
                <h2>Day Wise Finish Fabric Receive Statement Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">

                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Buyer</label>
                                            {!! Form::select('buyer', $buyers, null,
                                                    ['class'=>'form-control form-control-sm select2-input', 'id'=>'buyer']) !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Style</label>
                                            {!! Form::select('style', [], null,
                                                    ['class'=>'form-control form-control-sm select2-input', 'id'=>'style']) !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Color</label>
                                            {!! Form::select('color', $colors ?? [], null,
                                                    ['class'=>'form-control form-control-sm select2-input', 'id'=>'color']) !!}
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">From Date</label>
                                            <input name="from_date" id="from_date"
                                                   value="{{ request('from_date') ?? date('m/d/Y') }}"
                                                   style="height: 32px;" type="text"
                                                   class="form-control form-control-sm datepicker"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">To Date</label>
                                            <input name="to_date" id="to_date"
                                                   value="{{ request('to_date') ?? date('m/d/Y') }}"
                                                   style="height: 32px;" type="text"
                                                   class="form-control form-control-sm datepicker"
                                                   autocomplete="off">
                                        </div>
                                    </div>

                                    <div class="col-sm-1">
                                        <div class="text-center">
                                            <button style="margin-top: 19px;"  id="fabricReceiveReport"
                                                    class="btn btn-sm btn-info"
                                                    name="type" title="Details">
                                                <i class="fa fa-search"></i>
                                            </button>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                <br>

                <div class="">
                    <div class="header-section" style="padding-bottom: 0px;">
                        <div class="pull-right" style="margin-bottom: -5%;">

                            <a id="finish_fabric_receive_pdf" data-value="" class="btn"
                               href="finish-fabric-receive-report-pdf"><i
                                    class="fa fa-file-pdf-o"></i></a>

                            <a id="finish_fabric_receive_excel" data-value="" class="btn"
                               href="finish-fabric-receive-report-excel">
                                <i class="fa fa-file-excel-o"></i>
                            </a>


                        </div>
                    </div>
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 10pt; font-weight: bold;">Finish Fabric Receive Day Wise Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row p-x-1">
                        <div class="col-md-12" id="finishFabricReceiveTable">

                        </div>
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
                function getReportData(){
                    let from_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    let buyer = $('#buyer').val();
                    let style = $('#style').val();
                    let color = $('#color').val();
                    let reportTable = $('#finishFabricReceiveTable');
                    reportTable.empty();

                    if (from_date === '' || to_date === '') {
                        alert('Please Select Date Range')
                    } else {
                        $.ajax({
                            method: 'GET',
                            url: `date-wise-finish-fabric-receive-report`,
                            data: {
                                from_date,
                                to_date,
                                buyer,
                                style,
                                color,
                            },
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
                            success: function (result) {
                                $('html,body').css('cursor', 'default');
                                $("html").css({'background-color': '', 'opacity': ''});
                                $(".loader").hide();
                                let pdfQueryString = `/inventory/finish-fabric-receive-report-pdf?from_date=${from_date}
                                                   &to_date=${to_date}&buyer=${buyer}&style=${style}&color=${color}`
                                let excelQueryString = `/inventory/finish-fabric-receive-report-excel?from_date=${from_date}&to_date=${to_date}
                                                        &to_date=${to_date}&buyer=${buyer}&style=${style}&color=${color}`
                                reportTable.html(result);
                                $("#finish_fabric_receive_pdf").attr('href', pdfQueryString)
                                $("#finish_fabric_receive_excel").attr('href', excelQueryString)
                            },
                            error: function (error) {
                                console.log(error)
                            }
                        })
                    }
                }

                $(document).ready(function (){
                    getReportData();

                    $(document).on('click', '#fabricReceiveReport', function (event) {
                        getReportData();
                    });

                    $("#buyer").change(function () {

                        $.ajax({
                            url: `/common-api/buyers-style-name/${$(this).val()}`,
                            type: 'get',
                            beforeSend() {
                                $("#style").empty().html(`<option value='0'>Select Style</option>`);
                            },
                            success(data) {
                                $.each(data, function (key, value) {
                                    let element = `<option value="${value.id}">${value.text}</option>`;
                                    $("#style").append(element);
                                })
                            }
                        });
                    });
                })
            </script>
    @endpush
