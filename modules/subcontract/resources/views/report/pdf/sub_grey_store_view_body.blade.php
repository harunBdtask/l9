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
            <div class="box-body table-responsive b-t">
                    <div class="">
                        <center>
                            <table style="border: 1px solid black;width: 20%;">
                                <thead>
                                <tr>
                                    <td class="text-center">
                                        <span style="font-size: 12pt; font-weight: bold;">Stock Summery Report</span>
                                        <br>
                                    </td>
                                </tr>
                                </thead>
                            </table>
                        </center>
                        <br>
                        <div class="row p-x-1">
                            <div class="col-md-12" id="dateWiseStockSummeryTable">
                                <table class="reportTable">
                                    <thead>
                                    <tr>
                                        <th><b>SL</b></th>
                                        <th><b>Party</b></th>
                                        <th><b>Fabric Composition</b></th>
                                        <th><b>Fab Type</b></th>
                                        <th><b>Color</b></th>
                                        <th><b>L/D No</b></th>
                                        <th><b>Color Type</b></th>
                                        <th><b>Fin Dia</b></th>
                                        <th><b>Dia Type</b></th>
                                        <th><b>GSM</b></th>
                                        <th><b>Receive Qty</b></th>
                                        <th><b>UOM</b></th>
                                        <th><b>Issue Qty</b></th>
                                        <th><b>Balance</b></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stockSummery as $summery)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td class="text-center">{{ $summery->supplier->name }}</td>
                                            <td class="text-center">{{ $summery->fabricComposition->construction }}</td>
                                            <td class="text-center">{{ $summery->fabricType->construction_name }}</td>
                                            <td class="text-center">{{ $summery->color->name }}</td>
                                            <td class="text-center">{{ $summery->ld_no }}</td>
                                            <td class="text-center">{{ $summery->colorType->color_types }}</td>
                                            <td class="text-center">{{ $summery->finish_dia }}</td>
                                            <td class="text-center">{{ $summery->dia_type_value }}</td>
                                            <td class="text-center">{{ $summery->gsm }}</td>
                                            <td class="text-center">{{ $summery->receive_qty }}</td>
                                            <td class="text-center">{{ $summery->unitOfMeasurement->unit_of_measurement }}</td>
                                            <td class="text-center">{{ $summery->issue_qty }}</td>
                                            <td class="text-center">{{ ($summery->receive_qty - $summery->receive_return_qty) - ($summery->issue_qty - $summery->issue_return_qty) }}</td>
                                        </tr>
                                        @endforeach
                                        
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                        

                    </div>
            </div>
        </div>
    </div>

