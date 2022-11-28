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
                                    <span style="font-size: 12pt; font-weight: bold;">Batch Report</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>
                    <br>
                    <div class="row">
                        <div class="col-md-12" >
                            <div class="row">
                                <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
                                <table class="borderless">
                            
                                <tbody>
                                    <tr>
                                        <td style="padding-left: 0;">
                                            <b>Party :</b>
                                        </td>
                                        <td style="padding-left: 30px;"> {{ $batch->supplier->name }} </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0;">
                                            <b>Order :</b>
                                        </td>
                                        <td style="padding-left: 30px;"> {{ $batchDetail->subTextileOrder->order_no }} </td>
                                    </tr>
                                    <tr>
                                        <td style="padding-left: 0;">
                                            <b>Batch Qty :</b>
                                        </td>
                                        <td style="padding-left: 30px;"> {{ $batchDetail->issue_qty }} KG </td>
                                    </tr>
                                   
                                  </tbody>
                                </table>
                                </div>
                                <div class="col-md-1"></div>
                                <div class="col-md-6" style="float: right; position:relative;margin-top:30px">
                                    <table class="borderless">
                                        <tbody>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Batch No :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $batch->batch_no }}  </td>
                                            </tr>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Batch Create:</b>
                                                </td>
                                                @php
                                                    $batchCreateDate = strtotime($batch->created_at);
                                                @endphp
                                                <td style="padding-left: 30px;"> {{ $batchDetail->issue_qty }} KG {{   date("Y-M-d h:i:sa", $batchCreateDate) }} </td>
                                            </tr>
                            
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Batch Color :</b>
                                                </td>
                                                <td style="padding-left: 30px;">  {{  $batch->color->name }} </td>
                                            </tr>
                                           
                                          </tbody>
                                   </table>
                                </div>
                            </div>
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th><b>Heading</b></th>
                                    <th><b>Date</b></th>
                                    <th><b>Qty</b></th>
                                    <th><b>ID No</b>
                                </tr>
                              
                                </thead>
                                <tbody>
                                   
                                    @php
                                        $dyeingReceipeSum = 0;
                                        $dyeingProductionSum = 0;
                                        $dryerSum = 0;
                                        $slittingSum = 0;
                                        $stenterSum = 0;
                                        $compactorSum = 0;
                                        $tumbleSum = 0;
                                        $peachSum = 0;
                                        $finishingSum = 0;
                                        $deliverySum = 0;
                            
                                    @endphp
                                    @foreach ($batch->subDyeingRecipe as $receipe)
                                        @foreach ($receipe->recipeDetails as $details)
                                            @php
                                                $receipeDate = strtotime($details->created_at);
                                            @endphp
                                            <tr>
                                                <td class="text-center" >Receipe</td>
                                                <td class="text-center">{{ date("Y-M-d h:i:sa", $receipeDate) }}</td>
                                                <td class="text-center">{{ $details->total_qty }} KG</td>
                                                <td class="text-center">{{ $receipe->recipe_uid }}</td>
                                                @php
                                                    $dyeingReceipeSum+=$details->total_qty;
                                                @endphp
                                            </tr>
                                        @endforeach
                                    @endforeach
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $dyeingReceipeSum }} KG</td>
                                    </tr>
                                    @forelse ($batch->subDyeingProductionDetail as $production)
                                    @php
                                        $productionDate = strtotime($production->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Dyeing Production</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $productionDate) }}</td>
                                        <td class="text-center">{{ $production->batch_qty }} KG</td>
                                        <td class="text-center">{{ $production->subDyeingProduction->production_uid }}</td>
                                        @php
                                            $dyeingProductionSum+=$production->batch_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Dyeing Production</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                                    
                                   <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $dyeingProductionSum }} KG</td>
                                    </tr>
                                    @forelse ($batch->SubDryerDetail as $dryer)
                                    @php
                                        $dryerDate = strtotime($dryer->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Dryer</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $dryerDate) }}</td>
                                        <td class="text-center">{{ $dryer->finish_qty }} KG</td>
                                        <td class="text-center">{{ $dryer->subDryer->sub_dryer_uid }}</td>
                                        @php
                                            $dryerSum+=$dryer->finish_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Dryer</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $dryerSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->subSlittingDetail as $slitting)
                                    @php
                                        $slittingDate = strtotime($slitting->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Slitting</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $slittingDate) }}</td>
                                        <td class="text-center">{{ $slitting->finish_qty }} KG</td>
                                        <td class="text-center">{{ $slitting->subSlitting->sub_slitting_uid }}</td>
                                        @php
                                            $slittingSum+=$slitting->finish_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Slitting</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $slittingSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->subDyeingStenteringDetail as $stenter)
                                    @php
                                        $stenterDate = strtotime($stenter->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Stenter</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $stenterDate) }}</td>
                                        <td class="text-center">{{ $stenter->finish_qty }} KG</td>
                                        <td class="text-center">{{ $stenter->subDyeingStentering->sub_stentering_uid }}</td>
                                        @php
                                            $stenterSum+=$stenter->finish_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Stenter</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $stenterSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->subCompactorDetail as $compactor)
                                    @php
                                        $compactorDate = strtotime($compactor->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Compactor</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $compactorDate) }}</td>
                                        <td class="text-center">{{ $compactor->finish_qty }} KG</td>
                                        <td class="text-center">{{ $compactor->subCompactor->sub_compactor_uid }}</td>
                                        @php
                                            $compactorSum+=$compactor->finish_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Compactor</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $compactorSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->SubDyeingTumbleDetails as $tumble)
                                    @php
                                        $tumbleDate = strtotime($tumble->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Tumble</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $tumbleDate) }}</td>
                                        <td class="text-center">{{ $tumble->batch_qty }} KG</td>
                                        <td class="text-center">{{ $tumble->tumble->tumble_uid }}</td>
                                        @php
                                            $tumbleSum+=$tumble->batch_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Tumble</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $tumbleSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->SubDyeingPeachDetail as $peach)
                                    @php
                                        $peachDate = strtotime($peach->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">Brush</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $peachDate) }}</td>
                                        <td class="text-center">{{ $peach->batch_qty }} KG</td>
                                        <td class="text-center">{{ $peach->peach->peach_uid }}</td>
                                        @php
                                            $peachSum+=$peach->batch_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>Brush</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $peachSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->subDyeingFinishProductionDetail as $finishing)
                                    @php
                                        $finishingDate = strtotime($finishing->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">FINISHING</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $finishingDate) }}</td>
                                        <td class="text-center">{{ $finishing->finish_qty }} KG</td>
                                        <td class="text-center">{{ $finishing->finishingProduction->production_uid }}</td>
                                        @php
                                            $finishingSum+=$finishing->finish_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>FINISHING</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $finishingSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($batch->subDyeingGoodsDeliveryDetail as $delivery)
                                    @php
                                        $deliveryDate = strtotime($delivery->created_at);
                                    @endphp
                                    <tr>
                                        <td class="text-center">DELIVERY</td>
                                        <td class="text-center">{{ date("Y-M-d h:i:sa", $deliveryDate) }}</td>
                                        <td class="text-center">{{ $delivery->delivery_qty }} KG</td>
                                        <td class="text-center">{{ $delivery->subDyeingGoodsDelivery->challan_no }}</td>
                                        @php
                                            $deliverySum+=$delivery->delivery_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No Data</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="4" class="text-center"> <b>DELIVERY</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2"> {{ $deliverySum }} KG</td>
                                    </tr>
                            
                            
                                </tbody>
                            </table>
                            
                            
                        </div>
                    </div>
                    

                </div>
        </div>
    </div>
</div>

