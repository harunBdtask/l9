<style>
    .reportTable thead, .reportTable tbody, .reportTable th {
        text-align: left !important;
    }

    .w-100 {
        width: 100%;
        display: flex;
        flex-wrap: wrap;
    }
</style>

<center>
    <table style="border: 1px solid black; width: 20%;">
        <thead>
        <tr>
            <td class="text-center">
                <span style="font-size: 12pt; font-weight: bold;">CONTRACT NO : {{ $contractNumber }}</span>
                <br>
            </td>
        </tr>
        </thead>
    </table>
</center>
<br>
<div class="w-100">
    <table class="reportTable" style="width : 40%;">
        <tr>
            <th>Date</th>
            <td>{{ date('d M, Y',strtotime($order->created_at)) }}</td>
            <th>Revised</th>
            <td>{{ $order->created_at != $order->updated_at ? date('d M, Y',strtotime($order->updated_at)) : null }}</td>
        </tr>
        <tr>
            <th colspan="2">Merchandiser</th>
            <td colspan="2">{{ $order->dealingMerchant->screen_name }}</td>
        </tr>
        <tr>
            <th colspan="2">Buyer</th>
            <td colspan="2">{{ $order->buyer->name }}</td>
        </tr>
    </table>
</div>
<br>
<div class="w-100" style="display:inline-block;">
    <div style="width : 40%; float: left">
        <table class="reportTable">
            <tr>
                <th style="width: 50%">Total L/C value :-</th>
                <td>{{ number_format($totalLCValue, 4) }}</td>
            </tr>
            <tr>
                <th>Commercial Charge :-</th>
                <td>{{ number_format($commercialCharge, 4) }}</td>
            </tr>
            <tr>
                <th>Net Value available :-</th>
                <td>{{ number_format($netValue, 4) }}</td>
            </tr>
            <tr>
                <th>75% of Net value :-</th>
                <td>{{ number_format($netValue * 0.75, 4) }}</td>
            </tr>
            <tr>
                <th>25% of Net value :-</th>
                <td>{{ number_format($netValue * 0.25, 4) }}</td>
            </tr>
        </table>
    </div>
    <div style="width : 40%; float: right;">
        <table class="reportTable">
            <tr>
                <th>Remarks :</th>
            </tr>
            <tr style="height: 76px;">
                <td>{{ $order->remarks }}</td>
            </tr>
        </table>
    </div>
</div>
<br>
<div class="w-100" style="display:inline-block;">
    <div style="width: 70%; float: left;">
        <table class="reportTable" style="margin-top : 10px;">
            <tr>
                <th>Style Desc</th>
                <th>Style No</th>
                <th>PO</th>
                <th>Order Date</th>
                <th>Delivery Date</th>
                <th>Order Qty</th>
                <th>FOB</th>
                <th>Total Value</th>
            </tr>
            @foreach($order->purchaseOrders as $key => $value)
                <tr>
                    @if($loop->first)
                        <td style="width: 25%" rowspan="{{ count($order->purchaseOrders) }}">{{ $order->style_description }}</td>
                        <td rowspan="{{ count($order->purchaseOrders) }}">{{ $order->style_name }}</td>
                    @endif
                    <td>{{ $value->po_no }}</td>
                    <td>{{ date('d M, Y',strtotime($value->po_receive_date)) }}</td>
                    <td>{{ date('d M, Y',strtotime($value->ex_factory_date)) }}</td>
                    <td>{{ number_format($value->po_quantity, 4) }}</td>
                    <td style="text-align: right">${{ number_format($value->avg_rate_pc_set, 4) }}</td>
                    <td style="text-align: right">${{ number_format($value->po_quantity * $value->avg_rate_pc_set, 4) }}</td>
                </tr>
            @endforeach
            <tr>
                <th>Total (actual)</th>
                <th colspan="4"></th>
                <th>{{ number_format($totalPOQty, 4) }} {{ $order->uom->unit_of_measurement ?? 'PCS' }}</th>
                <th></th>
                <th style="text-align: right">${{ number_format($totalStyleValue, 4) }}</th>
            </tr>
            <tr>
                <th>Total with excess</th>
                <th colspan="4"></th>
                <th>{{ number_format($totalPOQtyWithExcess, 4) }}  {{ $order->uom->unit_of_measurement ?? 'PCS' }}</th>
                <th></th>
                <th> ${{ number_format($totalStyleValueWithExcess, 4) }}</th>
            </tr>
        </table>
    </div>
    <div style="width: 20%; float: right;">
        <table class="reportTable">
            @foreach($colorQty as $key => $value)
                <tr>
                    <th>{{ $key }}</th>
                    <td>{{ number_format(collect($value)->sum('value'), 4) }}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<br>
<div class="w-100">
    <div style="width: 100%">
        <table class="reportTable" style="margin-top : 10px;">
            <thead>
            <tr>
                <th>SL. NO.</th>
                <th>ITEM DESCRIPTION</th>
                <th>SUPPLIER</th>
                <th>Nominated (Y/N)</th>
                <th>Consumption / Pcs</th>
                <th>Unit</th>
                <th>Consumption / Dzn</th>
                <th>Margin</th>
                <th>Consumption with Margin / Pcs</th>
                <th>Total Req Qty</th>
                <th>Cost Per Yds/Meter</th>
                <th>Cost Per Pcs</th>
                <th>Cost Per Dzn</th>
                <th>Cost Per Gross/ Cones /Roll</th>
                <th>TOTAL COST</th>
            </tr>
            </thead>
            <tbody>
            @foreach($costing as $key => $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value['item'] }}</td>
                    <td>{{ $value['supplier'] }}</td>
                    <td>{{ $value['nominated_status'] }}</td>
                    <td>{{ number_format($value['consumption_in_pcs'], 4) ?? '' }}</td>
                    <td>{{ $value['unit'] }}</td>
                    <td>{{ number_format($value['consumption_in_dzn'], 4) ?? '' }}</td>
                    <td>{{ $value['margin'] }}%</td>
                    <td>{{ number_format($value['consumption_with_margin'], 4) ?? '' }}</td>
                    <td>{{ number_format($value['total_req_qty'], 4) }} {{ $value['unit'] }}</td>
                    <td>{{ $value['cost_per_yds'] ? number_format($value['cost_per_yds'], 4) : '' }}</td>
                    <td>{{ $value['cost_per_pcs'] ? number_format($value['cost_per_pcs'], 4) : '' }}</td>
                    <td>{{ $value['cost_per_dzn'] ? number_format($value['cost_per_dzn'], 4) : '' }}</td>
                    <td>{{ $value['cost_per_gross'] ? number_format($value['cost_per_gross'], 4) : '' }}</td>
                    <td>${{ number_format($value['total_cost'], 4) ?? '' }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="14">Sub Total Cost</th>
                <th>${{ number_format($totalCost, 4) }}</th>
            </tr>
            <tr>
                <th colspan="14" style="text-align: right">Discount / Commercial Clouse 2%</th>
                <th>-</th>
{{--                <th>${{ $discountOrCommercialClause }}</th>--}}
            </tr>
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="w-100" style="display:inline-block;">
    <div style="width: 50%; float: left;">
        @if($order->images && File::exists('storage/'.$order->images))
            <img
                src="{{asset('storage/'. $order->images)}}"
                alt="style image" width="200">
        @else
            <img style="border: 1px solid #dddddd" src="{{ asset('images/no_image.jpg') }}" width="150"
                 alt="no image">
        @endif
    </div>
    <div style="width: 25%; float: right;">
        <table class="reportTable">
            <tr>
                <th> TTL Fab. Cost</th>
                <th>${{ number_format($totalFabricCost, 4) }}</th>
            </tr>
            <tr>
                <th> TTL Acc. Cost</th>
                <th>${{ number_format($totalAccCost, 4) }}</th>
            </tr>
            <tr>
                <th> Discount Clouse</th>
                <th>-</th>
{{--                <th>$</th>--}}
            </tr>
            <tr>
                <th> TTL Cost/Dzn</th>
                <th>${{ number_format($totalCostDozen, 4) }}</th>
            </tr>
            <tr>
                <th> Total FOB</th>
                <th>${{ number_format($totalFOB, 4) }}</th>
            </tr>
            <tr>
                <th> CM /Dzn</th>
                <th>${{ number_format($cmDozen, 4) }}</th>
            </tr>
        </table>
        <br>
        <table class="reportTable">
            <tr>
                <th> Fabric cost / Pcs</th>
                <th>${{ number_format($totalFabricCostInPCS, 4) }}</th>
            </tr>
            <tr>
                <th> Trims cost / Pcs</th>
                <th>$ {{ number_format($totalTrimsCost, 4) }}</th>
            </tr>
            <tr>
                <th> Wash Cost / Pcs</th>
                <th>${{ number_format($washCostInPCS, 4) }}</th>
            </tr>
        </table>
    </div>
</div>

@include('skeleton::reports.downloads.signature')
