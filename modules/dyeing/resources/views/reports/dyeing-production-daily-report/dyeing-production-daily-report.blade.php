@extends('skeleton::layout')
@section('title','Dyeing Daily Production Report')
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
                <h2>Dyeing Production Daily Report</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                <div class="col-sm-2" style="width: 20%;">
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">Factory</label>
                                        <select name="" id="factory_id" class="form-control form-control-sm select2-input">
                                            @foreach ($factories as $key => $factorie)
                                            <option value="{{ $key }}">{{ $factorie }}</option>
                                            @endforeach
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="width: 12%;">
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">From Date</label>
                                        <input name="from_date" id="from_date" value="{{ date('m/d/Y') }}" style="height: 32px;" type="text"
                                                class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2" style="width: 12%;">
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">To Date</label>
                                        <input name="to_date" id="to_date" value="{{ date('m/d/Y') }}" style="height: 32px;" type="text"
                                               class="form-control form-control-sm datepicker"
                                               autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-sm-2" style="width: 23%;">
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">Buyer</label>
                                        <select name="" id="buyer_id" class="form-control form-control-sm select2-input">
                                            @foreach ($buyers as $key => $buyer)
                                            <option value="{{ $key }}">{{ $buyer }}</option>
                                            @endforeach
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2" style="width: 12%">
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">Order No</label>
                                        <select name="" id="order_id" class="form-control form-control-sm select2-input">
                                            @foreach ($orders as $key => $order)
                                             <option value="{{ $key }}">{{ $order }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-2" style="width: 125px;">
                                    <div class="form-group">
                                        <label style="margin-bottom: -2.5rem;">Batch No</label>
                                        <select name="" id="batch_id" class="form-control form-control-sm select2-input">
                                            @foreach ($batches as $key => $batch)
                                             <option value="{{ $key }}">{{ $batch }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-sm-1">
                                    <div class="text-center">
                                        <button style="margin-top: 19px;" id="dailyDyeingProduction" class="btn btn-sm btn-info"
                                                name="type" title="Details">
                                            <i class="fa fa-search"></i>
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
                                
                                <a id="dyeing_production_pdf" data-value="" class="btn" href="dyeing-production-daily-report-pdf"><i
                                        class="fa fa-file-pdf-o"></i></a>
                                
                                <a id="dyeing_production_excel" data-value="" class="btn" href="dyeing-production-daily-report-excel">
                                    <i class="fa fa-file-excel-o"></i>
                                </a>
                             

                            </div>
                        </div>
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 10pt; font-weight: bold;">Dyeing Production Daily Report</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="row p-x-1">
                            <div class="col-md-12" id="subDyeingDailyProductionTable">
                                @includeIf('dyeing::reports.dyeing-production-daily-report.dyeing-production-daily-report-table')
                            </div>
                        </div>
                        

                    </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click','#dailyDyeingProduction', function(event) {
                    event.preventDefault();
                    let factory_id = $('#factory_id').val();
                    let form_date = $('#from_date').val();
                    let to_date = $('#to_date').val();
                    let order_id = $('#order_id').val();
                    let batch_id = $('#batch_id').val();
                    let buyer_id = $('#buyer_id').val();

                    if(form_date == '' || to_date == '')
                    {
                        alert('Please Select Date Range')
                    }
                    else {
                        $.ajax({
                        method : 'GET',
                        url : `date-wise-dyeing-production-daily-report`,
                        data : {
                            factory_id,
                            form_date,
                            to_date,
                            order_id,
                            batch_id,
                            buyer_id
                        },
                        success: function(result){
                            let pdfQueryString = `/dyeing/dyeing-production-daily-report-pdf?form_date=${form_date}
                                                  &to_date=${to_date}&factory_id=${factory_id}&order_id=${order_id}
                                                  &batch_id=${batch_id}&buyer_id=${buyer_id}`
                            let excelQueryString = `/dyeing/dyeing-production-daily-report-excel?form_date=${form_date}&to_date=${to_date}
                                                  &to_date=${to_date}&factory_id=${factory_id}&order_id=${order_id}
                                                  &batch_id=${batch_id}&buyer_id=${buyer_id}`
                            $('#subDyeingDailyProductionTable').html(result);
                            $("#dyeing_production_pdf").attr('href',pdfQueryString)
                            $("#dyeing_production_excel").attr('href',excelQueryString)
                            console.log(result)
                        },
                        error: function(error){
                            console.log(error)
                        }
                    })
                    }

                    //console.log(batch_id);

                   
                });
            </script>
    @endpush
