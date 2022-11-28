<table>
    <thead>
    <tr>
        <td colspan="2"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="2"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">Order No : {{ $order->order_no }}</td>
    </tr>
    <tr>
        <td colspan="2"
            style="text-align: center;height: 35px">
            <b>Order Profit Loss Report</b>
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
                    <td style="padding-left: 0;">
                        <b>Order Receive date :</b>
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
        <th><b>Dyes And Chemical Cost</b></th>
        <th><b>Costing</b></th>
    </tr>
    </thead>

    <tbody>
        <tr>
            <td>Dyeing Cost</td>
            <td>{{ $totalSubDyeingCost }}</td>
        </tr>
        <tr>
            <td>Dryer Cost</td>
            <td>{{ $totalSubDryerCost }}</td>
        </tr>
        <tr>
            <td>Slitting Cost</td>
            <td>{{ $totalSubSlittingCost }}</td>
        </tr>
        <tr>
            <td>Stenter Cost</td>
            <td>{{ $totalStenteringCost }}</td>
        </tr>
        <tr>
            <td>Compactor Cost</td>
            <td>{{ $totalSubCompactorCost }}</td>
        </tr>
        <tr>
            <td>Tumble Cost</td>
            <td>{{ $totalSubTumbleCost }}</td>
        </tr>
        <tr>
            <td>Brush/Peach Cost</td>
            <td>{{ $totalSubDyeingPeachCost }}</td>
        </tr>
        <tr>
            <td>Total Finishing Cost</td>
            <td>{{ $totalSubDyeingFinishingCost }}</td>
        </tr>
        <tr>
            <td>TOTAL OVERALL COST</td>
            <td>{{ $totalOverAllCost }}</td>
        </tr>
        <tr>
            <td>PROFIT %</td>
            <td>{{ ($orderDetails->total_value -  $totalOverAllCost) / 100 }}</td>
        </tr>
       
    </tbody>

</table>