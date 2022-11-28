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
                                    <span style="font-size: 8pt; font-weight: bold;">Order Profit Loss Analysis</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row">
                        <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                        <table class="borderless">
                    
                        <tbody>
                            <tr>
                                <td style="padding-left: 0;">
                                    <b>Party :</b>
                                </td>
                                <td style="padding-left: 30px;"> {{ $order->supplier->name }} </td>
                            </tr>
                            <tr>
                                <td style="padding-left: 0;">
                                    <b>Sales Order No :</b>
                                </td>
                                <td style="padding-left: 30px;"> {{ $order->order_no }} </td>
                            </tr>
                            
                          </tbody>
                        </table>
                    
                        </div>
                        <div class="col-md-2"></div>
                        <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
                    
                            <table class="borderless">
                                <tbody>
                                    <tr>
                                        <td>
                                            <b>Order Rec date :</b>
                                        </td>
                                        <td style="padding-left: 30px;"> {{ $order->receive_date }}  </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0;">
                                            <b>Order Value(BDT) :</b>
                                        </td>
                                      
                                        <td style="padding-left: 30px;">{{ $orderDetails->total_value }} </td>
                                    </tr>
                                    
                                  </tbody>
                           </table>
                    
                        </div>
                    </div>

                    <table class="reportTable">

                        <thead>
                        <tr>
                            <th><b>Dyes & Chemical Cost</b></th>
                            <th><b>Costing</b></th>
                        </tr>
                        </thead>
                    
                        <tbody>
                            <tr>
                                <td class="text-center">Dyeing Cost</td>
                                <td class="text-center">{{ $totalSubDyeingCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Dryer Cost</td>
                                <td class="text-center">{{ $totalSubDryerCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Slitting Cost</td>
                                <td class="text-center">{{ $totalSubSlittingCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Stenter Cost</td>
                                <td class="text-center">{{ $totalStenteringCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Compactor Cost</td>
                                <td class="text-center">{{ $totalSubCompactorCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Tumble Cost</td>
                                <td class="text-center">{{ $totalSubTumbleCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Brush/Peach Cost</td>
                                <td class="text-center">{{ $totalSubDyeingPeachCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">Total Finishing Cost</td>
                                <td class="text-center">{{ $totalSubDyeingFinishingCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">TOTAL OVERALL COST</td>
                                <td class="text-center">{{ $totalOverAllCost }}</td>
                            </tr>
                            <tr>
                                <td class="text-center">PROFIT %</td>
                                <td class="text-center">{{ ($orderDetails->total_value -  $totalOverAllCost) / 100 }}</td>
                            </tr>
                           
                        </tbody>
                    
                    </table>
                    

                </div>
        </div>
    </div>
</div>
