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
    <div class="col-md-2"></div>
    <div class="col-md-5" style="float: right; position:relative;margin-top:30px">
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
                        <b>Batch Create :</b>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $dyeingReceipeSum }}</b> KG</td>
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
            <td colspan="4"> <b>Dyeing Production</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse
        
       <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $dyeingProductionSum }}</b> KG</td>
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
            <td colspan="4"> <b>Dryer</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $dryerSum }}</b> KG</td>
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
            <td colspan="4"> <b>Slitting</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $slittingSum }}</b> KG</td>
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
            <td colspan="4"> <b>Stenter</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse
        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $stenterSum }}</b> KG</td>
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
            <td colspan="4"> <b>Compactor</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $compactorSum }}</b> KG</td>
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
            <td colspan="4"> <b>Tumble</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $tumbleSum }}</b> KG</td>
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
            <td colspan="4"> <b>Brush</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $peachSum }}</b> KG</td>
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
            <td colspan="4"> <b>FINISHING</b> </td>
        </tr>
        <tr>
            <td colspan="4">No Data</td>
        </tr>
        @endforelse

        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $finishingSum }}</b> KG</td>
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
            <td colspan="4">No Data</td>
        </tr>
        @endforelse
        <tr>
            <td colspan="4"> <b>DELIVERY</b> </td>
        </tr>
        <tr>
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b>{{ $deliverySum }}</b> KG</td>
        </tr>


    </tbody>
</table>
