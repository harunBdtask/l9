<table>
    <thead>

    <tr>
        <td colspan="4"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">Order No : {{ $order->order_no }}</td>
    </tr>
    <tr>
        <td colspan="4"
            style="text-align: center;height: 35px">
            <b>Order Report</b>
        </td>
    </tr>
    </thead>
</table>
<div class="row">
    <div class="col-md-5" style="float: left; position:relative; margin-top:30px">
    <table class="borderless">

    <tbody>
        <tr>
            <td style="padding-left: 0;">
                <b>Party :</b>
            </td>
            <td> {{ $order->supplier->name }} </td>
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
                    <td> {{ $order->order_no }} </td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b>{{ $recipeSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $productionSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $dryerSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $slittingSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $stenterSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $compactorSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b>{{ $tumbleSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $peachSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b>{{ $finishingSum }} KG</td>
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
            <td colspan="2"> <b>Sub Total</b> </td>
            <td colspan="2"> <b></b> {{ $deliverySum }} KG</td>
        </tr>


    </tbody>
</table>
