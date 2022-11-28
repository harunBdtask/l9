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
                                    <span style="font-size: 7pt; font-weight: bold;">Dyeing Daily Production Report</span>
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
                                    <th><b>Production Date</b></th>
                                    <th><b>Batch No</b></th>
                                    <th><b>Machine Name</b></th>
                                    <th><b>Party Name</b></th>
                                    <th><b>Sales Order No</b></th>
                                    <th><b>Fabric Description</b></th>
                                    <th><b>Dia</b></th>
                                    <th><b>Gsm</b></th>
                                    <th><b>Fabric Color</b></th>
                                    <th><b>Order Qty</b></th>
                                    <th><b>Production Qty</b></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if (isset($dyeingProduction))
                                        @foreach ($dyeingProduction as $production)
                                        @php
                                            $machines = collect($production->subDyeingBatch->machineAllocations)
                                                        ->pluck('machine.name')
                                                        ->implode(',');
                                        @endphp
                                        @foreach ($production->subDyeingProductionDetails as $details)
                                        <tr>
                                            @if ($loop->first)
                                            @php
                                                $rowSpan = $production->subDyeingProductionDetails->count();
                                            @endphp
                                            <td class="text-center" rowspan="{{ $rowSpan }}">{{ $loop->iteration }}</td>
                                            <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->production_date }}</td>
                                            <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->batch_no }}</td>
                                            <td class="text-center" rowspan="{{ $rowSpan }}">{{ $machines }}</td>
                                            <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->supplier->name }}</td>
                                            <td class="text-center" rowspan="{{ $rowSpan }}">{{ $production->order_no }}</td>
                                            @endif
                                           
                                            <td class="text-center">{{ $details->fabric_composition_value }}</td>
                                            <td class="text-center">{{ $details->dia_type_value['name'] }}</td>
                                            <td class="text-center">{{ $details->gsm }}</td>
                                            <td class="text-center">{{ $details->color->name }}</td>
                                            <td class="text-center">{{ $details->batch_qty }}</td>
                                            <td class="text-center">{{ $details->dyeing_production_qty }}</td>
                                        </tr>
                            
                                        @endforeach
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    

                </div>
        </div>
    </div>
</div>

