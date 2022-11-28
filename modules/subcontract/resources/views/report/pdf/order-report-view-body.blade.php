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
                                    <span style="font-size: 12pt; font-weight: bold;">Order Report</span>
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
                                        <td style="padding-left: 30px;"> {{ $order->supplier->name }} </td>
                                    </tr>
                                  </tbody>
                                </table>
                                </div>
                                <div class="col-md-2"></div>
                                <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
                                    <table class="borderless">
                                        <tbody>
                                            <tr>
                                                <td style="padding-left: 0;">
                                                    <b>Order :</b>
                                                </td>
                                                <td style="padding-left: 30px;"> {{ $order->order_no }} </td>
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
                                       $recipeSum = 0;
                                       $productionSum = 0;
                                       $dryerSum = 0;
                                       $slittingSum = 0;
                                       $stenterSum = 0;
                                       $compactorSum = 0;
                                       $tumbleSum = 0;
                                       $peachSum = 0;
                                       $finishingSum = 0;
                                       $deliverySum = 0;
                                   @endphp
                                    
                                   @forelse ($recipes as $recipe)
                                   @foreach ($recipe->recipeDetails as $details)
                                   <tr>
                                       <td class="text-center">Receipe</td>
                                       <td class="text-center">{{ $details->created_at }}</td>
                                       <td class="text-center">{{ $details->total_qty }} KG</td>
                                       <td class="text-center">{{ $recipe->recipe_uid }}</td>
                                       @php
                                           $recipeSum += $details->total_qty;
                                       @endphp
                                    </tr>
                                    @endforeach
                                   @empty
                                    <tr>
                                        <td colspan="4"> <b>Dyeing Receipe</b> </td>
                                    </tr>
                                       <tr>
                                           <td class="text-center">No Data<td>
                                       </tr>
                                   @endforelse
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b>{{ $recipeSum }} KG</td>
                                    </tr>
                                    
                                    @forelse ($order->subDyeingProductionDetail as $production)
                                    <tr>
                                        <td class="text-center">Dyeing Production</td>
                                        <td class="text-center">{{ $production->created_at }}</td>
                                        <td class="text-center">{{ $production->batch_qty }} KG</td>
                                        <td class="text-center">{{ $production->subDyeingProduction->production_uid }}</td>
                                        @php
                                            $productionSum += $production->batch_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"> <b>Dyeing Production</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endforelse
                                   
                                    
                                   <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $productionSum }} KG</td>
                                    </tr>
                                   
                                    @forelse ($order->subDryerDetail as $details)
                                    <tr>
                                        <td class="text-center">Dryer</td>
                                        <td class="text-center">{{ $details->created_at }}</td>
                                        <td class="text-center"> {{ $details->finish_qty }} KG</td>
                                        <td class="text-center">{{ $details->subDryer->sub_dryer_uid }}</td>
                                       @php
                                           $dryerSum += $details->finish_qty;
                                       @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"> <b>Dryer</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endforelse
                                   
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $dryerSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($order->subSlittingDetail as $details)
                                    <tr>
                                        <td class="text-center">Slitting</td>
                                        <td class="text-center">{{ $details->created_at }}</td>
                                        <td class="text-center">{{ $details->finish_qty }} KG</td>
                                        <td class="text-center">{{ $details->subSlitting->sub_slitting_uid }}</td>
                                       @php
                                           $slittingSum += $details->finish_qty;
                                       @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"> <b>Slitting</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endforelse
                            
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $slittingSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($order->subDyeingStenteringDetail as $detail)
                                    <tr>
                                        <td class="text-center">Stenter</td>
                                        <td class="text-center">{{ $detail->created_at }}</td>
                                        <td class="text-center"> {{ $detail->finish_qty }} KG</td>
                                        <td class="text-center"> {{ $detail->subDyeingStentering->sub_stentering_uid }} </td>
                                        @php
                                            $stenterSum += $detail->finish_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"> <b>Stenter</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endforelse
                                    
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $stenterSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($order->subCompactorDetail as $detail)
                                    <tr>
                                        <td class="text-center">Compactor</td>
                                        <td class="text-center">{{ $detail->created_at }}</td>
                                        <td class="text-center"> {{ $detail->finish_qty }} KG</td>
                                        <td class="text-center">{{ $detail->subCompactor->sub_compactor_uid }}</td>
                                       @php
                                           $compactorSum += $detail->finish_qty;
                                       @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"> <b>Compactor</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $compactorSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($order->subDyeingTumbleDetail as $detail)
                                    <tr>
                                        <td class="text-center">Tumble</td>
                                        <td class="text-center">{{ $detail->created_at }}</td>
                                        <td class="text-center">{{ $detail->order_qty }} KG</td>
                                        <td class="text-center">{{ $detail->tumble->tumble_uid }}</td>
                                        @php
                                            $tumbleSum += $detail->order_qty;
                                        @endphp
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4"> <b>Tumble</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                    @endforelse
                                   
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b>{{ $tumbleSum }} KG</td>
                                    </tr>
                            
                                   @forelse ($order->subDyeingPeachDetail as $detail)
                                   <tr>
                                        <td class="text-center">Brush</td>
                                        <td class="text-center">{{ $detail->created_at }}</td>
                                        <td class="text-center">{{ $detail->order_qty }} KG</td>
                                        <td class="text-center">{{ $detail->peach->peach_uid }}</td>
                                        @php
                                            $peachSum += $detail->order_qty;
                                        @endphp
                                   </tr>
                                   @empty
                                   <tr>
                                    <td colspan="4"> <b>Brush</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                   @endforelse
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $peachSum }} KG</td>
                                    </tr>
                            
                                   @forelse ($order->subDyeingFinishingProduction as $detail)
                                   <tr>
                                        <td class="text-center">FINISHING</td>
                                        <td class="text-center">{{ $detail->created_at }}</td>
                                        <td class="text-center"> {{ $detail->order_qty }} KG</td>
                                        <td class="text-center">{{ $detail->finishingProduction->production_uid }}</td>
                                        @php
                                            $finishingSum += $detail->order_qty;
                                        @endphp
                                    </tr>
                                   @empty
                                   <tr>
                                    <td colspan="4"> <b>FINISHING</b> </td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                   @endforelse
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b>{{ $finishingSum }} KG</td>
                                    </tr>
                            
                                    @forelse ($order->subDyeingDeliveryDetails as $detail)
                                    <tr>
                                        <td class="text-center">DELIVERY</td>
                                        <td class="text-center">{{ $detail->created_at }}</td>
                                        <td class="text-center">{{ $detail->delivery_qty }} KG</td>
                                        <td class="text-center">{{ $detail->subDyeingGoodsDelivery->goods_delivery_uid }}</td>
                                        @php
                                            $deliverySum += $detail->delivery_qty;
                                        @endphp
                                    
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4">No Data</td>
                                    </tr>
                                  
                                    <tr>
                                        <td colspan="4"> <b>DELIVERY</b> </td>
                                    </tr>
                                    @endforelse
                                    <tr>
                                        <td colspan="2" class="text-center"> <b>Sub Total</b> </td>
                                        <td colspan="2" class="text-center"> <b></b> {{ $deliverySum }} KG</td>
                                    </tr>
                            
                            
                                </tbody>
                            </table>
                            
                            
                        </div>
                    </div>
                    

                </div>
        </div>
    </div>
</div>

