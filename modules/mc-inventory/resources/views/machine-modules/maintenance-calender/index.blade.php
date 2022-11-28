@extends('skeleton::layout')
@section('title','Maintenance Calender')
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
                <h2>Maintenance Calender</h2>
                <div class="clearfix"></div>
            </div>
            <div class="box-body table-responsive b-t">
                <div class="row">
                    <form action="" method="GET">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="col-sm-3" >
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Year</label>
                                            <input type="number" value="{{date('Y')}}" min="1900" max="3000" id="datepicker" class="form-control form-control-sm year">
                                        </div>
                                    </div>

                                    <div class="col-sm-3" >
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Month</label>
                                            @php
                                                $months = collect($month)->sortKeys();
                                            @endphp
                                            <select id="month" name="" class="form-control form-control-sm select2-input">
                                                @foreach ($months as $key => $month)
                                                <option value="{{$key}}" {{ ( $key == date('m')) ? 'selected' : '' }}>{{$month}}</option>
                                                @endforeach
                                            </select>
                                            {{-- <input type="number" value="{{date('Y-m')}}" id="month" style="line-height: 1.25rem;" class="form-control form-control-sm"> --}}
                                        </div>
                                    </div>

                                    <div class="col-sm-3" >
                                        <div class="form-group">
                                            <label style="margin-bottom: -2.5rem;">Unit</label>
                                            <select id="unit" class="form-control form-control-sm select2-input">
                                                @foreach($machineUnits as $key => $unit)
                                                    <option value="{{$key}}">{{$unit}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>




                                <div class="col-sm-1">
                                    <div class="text-center">
                                        <button style="margin-top: 19px;" id="maintenanceCalender" class="btn btn-sm btn-info"
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

                    <div id="reportShow" style="display: none;">
                        <div class="header-section" style="padding-bottom: 0px;">
                            <div class="pull-right" style="margin-bottom: -5%;">

                                <a id="maintenance_calender_pdf" data-value="" target="_blank" class="btn" href="maintenance-calender-pdf">
                                    <i class="fa fa-file-pdf-o"></i></a>

                                <a id="maintenance_calender_excel" data-value="" target="_blank" class="btn"
                                   href="maintenance_calender_excel">
                                    <em class="fa fa-file-excel-o"></em>
                                </a>


                            </div>
                        </div>
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Maintenance Calender</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="row p-x-1">
                            <div class="col-md-12" id="maintenanceCalenderTable">

                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @endsection
        @push("script-head")
            <script>
                $(document).on('click','#maintenanceCalender', function(event) {
                    let year = $('.year').val();
                    let month = $('#month').val();
                    let unit = $('#unit').val();
                    event.preventDefault()

                    if(!year){
                        alert('Please Select A Year')
                    }
                   else {
                        $.ajax({
                            method : 'GET',
                            url : `maintenance-calender/get-maintenance`,
                            data : {
                                year,
                                month,
                                unit
                            },
                            success: function(result){
                                let pdfQueryString = `/mc-inventory/maintenance-calender/get-pdf?year=${year}
                                                  &month=${month}&unit=${unit}`
                                let excelQueryString = `/mc-inventory/maintenance-calender/get-excel?year=${year}
                                                  &month=${month}&unit=${unit}`;
                                $('#maintenanceCalenderTable').html(result);

                                $("#maintenance_calender_pdf").attr('href',pdfQueryString)
                                $("#maintenance_calender_excel").attr('href',excelQueryString)

                                $("#reportShow").show();
                            },
                            error: function(error){
                                console.log(error)
                            }
                        })
                    }
                });

                $('#datepicker').datepicker({
                    minViewMode: 2,
                    format: 'yyyy'
                });

            </script>
    @endpush
