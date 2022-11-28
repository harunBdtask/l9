<style>
    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>

<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
               id="fixTable">
            <tr style="text-align: left;">
                <th style="background-color: #cae4f1; text-align: left;">Buyer</th>
                <td>{{ $buyer['name'] ?? ''}}</td>

                <th style="background-color: #cae4f1; text-align: left;">Garments Item</th>
                <td>{{ $items ?? ''}}</td>
                <th style="background-color: #cae4f1; text-align: left;">Total Style Value</th>
                <td>{{ ($order['pq_qty_sum'] ?? 0.00)*(number_format($costing_per_pcs ?? 0, 4) ?? 0.00) }}</td>
                <td rowspan="3" style="text-align: center">
                    @if($image)
                        <img src="{{ asset("storage/$image")  }}" alt="" height="50" width="50">
                    @else
                        <img src="{{ asset('/images/no_image.jpg') }}" alt="" height="50" width="50">
                    @endif
                </td>
            </tr>

            <tr style="text-align: left;">
                <th style="background-color: #cae4f1; text-align: left;"><b>Style Name</b></th>
                <td>{{ $style_name }}</td>

                <th style="background-color: #cae4f1; text-align: left;"><b>Costing Per</b></th>
                <td>{{ $costingPer[(int)$costing_per] ?? '' }}</td>


                <th style="background-color: #cae4f1; text-align: left;"><b>Plan Cut Qty</b></th>
                <td>{{ $plan_cut_qty ?? 0.00 }}</td>
            </tr>

            <tr style="text-align: left;">
                <th style="background-color: #cae4f1; text-align: left;"><b>Style Qty</b></th>
                <td>{{ $order['pq_qty_sum'] ?? 0.00 }} {{ $order_uom_id ? $styleUom[(int)$order_uom_id]  : '' }}</td>

                <th style="background-color: #cae4f1; text-align: left;"><b>Price Per Unit</b></th>
                <td>{{ number_format($costing_per_pcs, 4) ?? 0.00 }}</td>

                <th style="background-color: #cae4f1; text-align: left;"><b>Shipment Date</b></th>
                <td>{{ formatDate($shipment_date) ?? '' }}</td>
            </tr>
        </table>
        <div style="margin-top: 15px;">
        </div>
        <div style="margin-top: 15px;">
            @php
                $knit_cons_calculate = collect($knit_fabrics)->sum('grey_cons');
                //knit_fab_qty_calculate = collect($knit_fabrics)->sum('fabricConsumptionCalculation.cons_avg');
                $knit_fab_qty_calculate = collect($knit_fabrics)->sum('grey_cons_total_quantity');
                $knit_rate_calculate = collect($knit_fabrics)->sum('grey_cons_rate');
                $knit_amount_calculate = collect($knit_fabrics)->sum('grey_cons_amount');
                $knit_total_amount_calculate = collect($knit_fabrics)->sum('grey_cons_total_amount');

                $woven_cons_calculate = collect($woven_fabrics)->sum('grey_cons');
                $woven_avg_calculate = collect($woven_fabrics)->sum('grey_cons_total_quantity');
                $woven_rate_calculate = collect($woven_fabrics)->sum('grey_cons_rate');
                $woven_amount_calculate = collect($woven_fabrics)->sum('grey_cons_amount');
                $woven_total_amount_calculate = collect($woven_fabrics)->sum('grey_cons_total_amount');

                $total_cons = $knit_cons_calculate + $woven_cons_calculate;
                $total_cons_avg = $knit_fab_qty_calculate + $woven_avg_calculate;
                $total_rate = $knit_rate_calculate + $woven_rate_calculate;
                $total_amount = $knit_amount_calculate + $woven_amount_calculate;
                $grand_total_amount = $knit_total_amount_calculate + $woven_total_amount_calculate;
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th colspan="9" class="text-center">All Fabric Cost</th>
                </tr>
                <tr>
                    <th rowspan="{{ count($knit_fabrics) + 1}}">Knit Fabric</th>
                    <th style="text-align: left">Fabric Description</th>
                    <th class="text-center">Source</th>
                    <th class="text-center">Uom</th>
                    <th style="text-align: right;">Fab. Cons</th>
                    <th style="text-align: right;">Total Fab. Qty</th>
                    <th style="text-align: right;">Rate (USD)</th>
                    <th style="text-align: right;">Amount (USD)</th>
                    <th style="text-align: right;">Total Amount</th>
                </tr>
                @if(count($knit_fabrics) > 0)
                    @foreach($knit_fabrics as $knit_key=>$knit_fabric)
                        <tr>
                            <td class="text-left">
                                {{ $knit_fabric['body_part_value'] ?? '' }},
                                {{ $knit_fabric['color_type_value'] }},
                                {{ $knit_fabric['gsm'] }},
                                {{ $knit_fabric['fabric_composition_value'] }}
                            </td>
                            @if($knit_fabric['fabric_source'] == 1)
                                <td>Production</td>
                            @elseif($knit_fabric['fabric_source'] == 2)
                                <td>Purchase</td>
                            @elseif($knit_fabric['fabric_source'] == 3)
                                <td>Buyer Supplier</td>
                            @elseif($knit_fabric['fabric_source'] == 4)
                                <td>Stock</td>
                            @endif
                            @php
                                $knitUom = $uom[(int)$knit_fabric['uom']];
                            @endphp
                            <td class="text-center">{{ $knitUom ? $knitUom : ''}}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons'] ??  0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_total_quantity'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_rate'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_total_amount'] ?? 0.00 }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center"><b>Sub Total</b></td>
                        <td class="text-right">
                            <b>{{ number_format($knit_cons_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($knit_fab_qty_calculate,4)  }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($knit_rate_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($knit_amount_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($knit_total_amount_calculate, 4) }}</b>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="9">No Data Found</td>
                    </tr>
                @endif
                @if(count($woven_fabrics) > 0)
                    <tr>
                        <th rowspan="{{ count($woven_fabrics) + 1}}"> Woven Fabric</th>
                        <th colspan="8"></th>
                    </tr>
                    @foreach($woven_fabrics as $woven_key=>$woven_fabric)
                        <tr>
                            <td>
                                {{ $woven_fabric['body_part_value'] }},
                                {{ $woven_fabric['color_type_value'] }},
                                {{ $woven_fabric['gsm'] }},
                                {{ $woven_fabric['fabric_composition_value'] }}
                            </td>
                            @if($woven_fabric['fabric_source'] == 1)
                                <td>Production</td>
                            @elseif($woven_fabric['fabric_source'] == 2)
                                <td>Purchase</td>
                            @elseif($woven_fabric['fabric_source'] == 3)
                                <td>Buyer Supplier</td>
                            @elseif($woven_fabric['fabric_source'] == 4)
                                <td>Stock</td>
                            @endif
                            @php
                                $wovenUom = $uom[(int)$woven_fabric['uom']] ?? '';
                            @endphp
                            <td class="text-right">{{ $wovenUom ? $wovenUom : '' }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_total_quantity'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_rate'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_total_amount'] ?? 0.00 }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center"><b>Sub Total</b></td>
                        <td class="text-right">
                            <b>{{ number_format($woven_cons_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($woven_avg_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($woven_rate_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($woven_amount_calculate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($woven_total_amount_calculate,4) }}</b>
                        </td>

                    </tr>
                    <tr>
                        <td colspan="4" class="text-center"><b>Grand Total</b></td>
                        <td class="text-right">
                            <b>{{ number_format($total_cons,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($total_cons_avg,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($total_rate,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($total_amount,4) }}</b>
                        </td>
                        <td class="text-right">
                            <b>{{ number_format($grand_total_amount,4) }}</b>
                        </td>
                    </tr>
                @else
                    <tr>
                        <td colspan="9">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>
        <div style="margin-top: 15px;">
            @php
                $yarn_qty_sum = collect($yarn_costing)->sum('cons_qty');
                $yarn_rate_sum = collect($yarn_costing)->sum('rate');
                $yarn_amount_sum = collect($yarn_costing)->sum('amount');
                $yarn_total_qty_sum = 0;
                $yarn_total_amount_sum = 0;
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th rowspan="{{ count($yarn_costing) + 1 }}">Yarn Cost</th>
                    <th class="text-center"><b>Fabric Desc</b></th>
                    <th class="text-center"><b>Yarn Desc</b></th>
                    <th class="text-center"><b>Yarn Qty</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                    <th class="text-center"><b>Total Yarn Qty</b></th>
                    <th class="text-center"><b>Total Yarn Amount (USD)</b></th>
                </tr>
                @if( count($yarn_costing) >0)

                    @foreach($yarn_costing as $yarn_key=>$yarn_value)
                        <tr>
                            <td>
                                {{ $yarn_value['fabric_description'] ?? ''}}
                            </td>
                            <td>
                                {{ $yarn_value['count_value'] ?? ''}},
                                {{ $yarn_value['yarn_composition_value'] ?? '' }},
                                {{ $yarn_value['type'] ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ $yarn_value['cons_qty'] ?? '' }}
                            </td>
                            <td class="text-right">
                                {{ $yarn_value['rate'] ?? ''}}
                            </td>
                            <td class="text-right">
                                {{ $yarn_value['amount'] ?? ''}}
                            </td>
                            @php
                                $totalfabricqty = ((collect($knit_fabrics))->firstWhere('fabric_composition_id', $yarn_value['fabric_composition_id'])['grey_cons_total_quantity']) ?? 0;
                                $totalfabricamount = ((collect($knit_fabrics))->firstWhere('fabric_composition_id', $yarn_value['fabric_composition_id'])['grey_cons_total_amount']) ?? 0;

                                $yarnAmount = $yarn_value['rate'] ?? 0;
                                $percent = $yarn_value['percentage'] ?? 0;
                                $totalyarnqty = $totalfabricqty * 0.01 * $percent;
                                $totalyarnamount = $totalyarnqty * $yarnAmount;
                                $yarn_total_qty_sum += $totalyarnqty;
                                $yarn_total_amount_sum += $totalyarnamount;
                            @endphp
                            <td class="text-right">{{  number_format($totalyarnqty, 4)   }}</td>
                            <td class="text-right">{{   number_format($totalyarnamount, 4) }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center"><b>Total</b></td>
                        <td class="text-right"><b>{{ number_format($yarn_qty_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($yarn_rate_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($yarn_amount_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($yarn_total_qty_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($yarn_total_amount_sum,4) }}</b></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="8">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin-top: 15px;">
            @php
                $conversion_qty_sum = collect($conversion_costing)->sum('req_qty');
                $conversion_rate_sum = collect($conversion_costing)->sum('unit');
                $conversion_amount_sum = collect($conversion_costing)->sum('amount');
                $conversion_total_qty_sum = collect($conversion_costing)->sum('total_qty');
                $conversion_total_amount_sum = collect($conversion_costing)->sum('total_amount');
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th rowspan="{{ count($conversion_costing) + 1 }}">Conversion Cost to Fabric</th>
                    <th class="text-center"><b>Particulars</b></th>
                    <th class="text-center"><b>Process</b></th>
                    <th class="text-center"><b>Cons</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                    <th class="text-center"><b>Total Qty</b></th>
                    <th class="text-center"><b>Total Amount (USD)</b></th>
                </tr>
                @if( count($conversion_costing) > 0)

                    @foreach($conversion_costing as $con_key=>$conversion_value)
                        <tr>
                            <td>{{ $conversion_value['fabric_description'] ?? '' }}</td>
                            <td class="text-right">{{ $conversion_value['process'] ?? '' }}</td>
                            <td class="text-right">{{ $conversion_value['req_qty'] ?? '' }}</td>
                            <td class="text-right">{{ $conversion_value['unit'] }}</td>
                            <td class="text-right">{{ $conversion_value['amount'] ?? '' }}</td>
                            <td class="text-right">{{ $conversion_value['total_qty'] ?? 0}}</td>
                            <td class="text-right">{{ $conversion_value['total_amount'] ?? 0}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" class="text-center"><b>Total</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_qty_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_rate_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_amount_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_total_qty_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_total_amount_sum,4) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-center"><b>Total Fabric Cost</b></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="8">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>


        <div style="margin-top: 15px;">

            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th rowspan="{{ count(collect($conversion_costing_color_wise)->flatten(1)) + 1 }}">Conversion
                        Cost
                        to
                        Fabric
                        color wise charge
                    </th>
                    <th class="text-center"><b>Particulars</b></th>
                    <th class="text-center"><b>Process</b></th>
                    <th class="text-center"><b>Gmts Color</b></th>
                    <th class="text-center"><b>Fabric Color</b></th>
                    <th class="text-center"><b>Cons</b></th>
                    <th class="text-center"><b>Charge/Unit</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                    <th class="text-center"><b>Total Qty.</b></th>
                    <th class="text-center"><b>Total Amount</b></th>
                </tr>
                @if( count($conversion_costing_color_wise) > 0)
                    @php
                        $conversion_color_amount_sum = 0;
                        $conversion_color_total_amount_sum = 0;
                        $particularsRowSpan = true;
                    @endphp
                    @foreach($conversion_costing_color_wise as $con_key_value => $conversion_value_color_wise)
                        @php
                            $conversion_color_qty_sum = collect($conversion_value_color_wise)->sum('total_qty');
                            $conversion_color_rate_sum = collect($conversion_value_color_wise)->sum('charge_unit');
                            $conversion_color_cons_sum = collect($conversion_value_color_wise)->sum('cons');
                            $conversionCostProcessWise = collect($conversion_value_color_wise)->groupBy('process');
                        @endphp
                        @foreach( $conversionCostProcessWise as $con_key => $conversion_values)
                            @foreach($conversion_values as $key => $conversion_value)
                                <tr>
                                    <td>{{ $conversion_value['particulars'] ?? '' }}</td>
                                    <td>{{ $conversion_value['process'] ?? '' }}</td>
                                    <td class="text-right">{{ $conversion_value['gmts_color'] ?? '' }}</td>
                                    <td class="text-right">{{ $conversion_value['fabric_color'] ?? '' }}</td>
                                    @php
                                        $charge_unit = (float)$conversion_value['charge_unit'] ?? 0;
                                        $cons = (float)$conversion_value['cons'] ?? 0 ;
                                        $conversion_color_amount = $charge_unit * $cons ;
                                        $total_conversition_qty = (float)$conversion_value['total_qty'] ?? 0;
                                        $conversion_color_amount_total = $total_conversition_qty * $charge_unit;

                                        $conversion_color_amount_sum += $conversion_color_amount;
                                        $conversion_color_total_amount_sum +=  $conversion_color_amount_total;
                                        $particularsRowSpan = false
                                    @endphp
                                    <td class="text-right">{{ number_format($cons, 4) }}</td>
                                    <td class="text-right">{{ number_format($charge_unit, 4) }}</td>
                                    <td class="text-right">{{ number_format($conversion_color_amount, 4) }}</td>
                                    <td class="text-right">{{  number_format($total_conversition_qty, 4) }}</td>
                                    <td class="text-right">{{ number_format($conversion_color_amount_total, 4) }}</td>

                                </tr>

                            @endforeach
                        @endforeach
                    @endforeach
                    <tr>
                        <td colspan="5" class="text-center"><b>Total</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_color_cons_sum, 4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_color_rate_sum, 4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_color_amount_sum, 4)}}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_color_qty_sum, 4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($conversion_color_total_amount_sum, 4) }}</b></td>
                    </tr>
                    <tr>
                        <td colspan="8" class="text-center"><b>Total Fabric Cost</b></td>
                        <td class="text-right"></td>
                        <td class="text-right"></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="10">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin-top: 15px;">
            @php
                $trims_cons_gmts = collect($trims_details)->sum('cons_gmts');
                $trims_rate = collect($trims_details)->sum('rate');
                $trims_amount = collect($trims_details)->sum('amount');
                $total_trims_qty_sum = collect($trims_details)->pluck('total_quantity')->map(function ($val){
                    return (float)$val;
                })->sum();

                $total_trims_amount_sum = collect($trims_details)->pluck('total_amount')->map(function ($val){
                    return (float)$val;
                })->sum();
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th colspan="9" class="text-center">Trims Cost</th>
                </tr>
                <tr>
                    <th class="text-center"><b>Item Group</b></th>
                    <th class="text-center"><b>Description</b></th>
                    <th class="text-center"><b>Brand/Supp Ref</b></th>
                    <th class="text-center"><b>UOM</b></th>
                    <th class="text-center"><b>Cons/Dzn</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                    <th class="text-center"><b>Total Qty</b></th>
                    <th class="text-center"><b>Total Amount (USD)</b></th>
                </tr>
                @if( count($trims_details) >0)

                    @foreach($trims_details as $trim_key=>$trim_value)
                        <tr>
                            <td class="text-left">{{ $trim_value['group_name'] ?? '' }}</td>
                            <td>{{ $trim_value['description'] ?? ''}}</td>
                            <td>{{ $trim_value['brand_value'] ?? ''}}</td>
                            <td>{{ $trim_value['cons_uom_value'] ?? ''}}</td>
                            <td class="text-right">{{ $trim_value['cons_gmts'] ?? '' }}</td>
                            <td class="text-right">{{ $trim_value['rate'] ?? ''}}</td>
                            <td class="text-right">{{ $trim_value['amount'] ?? '' }}</td>
                            <td class="text-right">{{ $trim_value['total_quantity'] ?? 0 }}</td>
                            <td class="text-right">{{ $trim_value['total_amount'] ?? 0 }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-center"><b>Total</b></td>
                        <td class="text-right"><b>{{ number_format($trims_cons_gmts,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($trims_rate,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($trims_amount,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($total_trims_qty_sum, 4)  }}</b></td>
                        <td class="text-right"><b>{{ number_format($total_trims_amount_sum, 4) }}</b></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="9">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin-top: 15px">
            @php
                $embellishment_cons_sum = collect($embellishment_details)->sum('consumption');
                $embellishment_rate_sum = collect($embellishment_details)->sum('consumption_rate');
                $embellishment_amount_sum = collect($embellishment_details)->sum('consumption_amount');
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th colspan="5" class="text-center"><b>Embellishment Details</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Cons/ DZN</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
                @if( count($embellishment_details) > 0)
                    @foreach($embellishment_details as $key=>$embellishment_value)
                        <tr>
                            <td>{{ $embellishment_value['name'] ?? '' }}</td>
                            <td>{{ $embellishment_value['type'] ?? '' }}</td>
                            <td class="text-right">{{ $embellishment_value['consumption'] ?? ''}}</td>
                            <td class="text-right">{{ $embellishment_value['consumption_rate'] ?? '' }}</td>
                            <td class="text-right">{{ $embellishment_value['consumption_amount'] ?? '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-center"><b>Total</b></td>
                        <td class="text-right"><b>{{ number_format($embellishment_cons_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($embellishment_rate_sum,4) }}</b></td>
                        <td class="text-right"><b>{{ number_format($embellishment_amount_sum,4) }}</b></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="5">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin-top: 15px">
            @php
                $commercial_cost_sum = collect($commercial_details)->sum('commercial_cost');
                $commercial_amount_sum = 0;
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th colspan="3" class="text-center"><b>Commercial Cost</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
                @if( count($commercial_details) >  0)
                    @foreach($commercial_details  as $commercial_key=>$commercial_value)
                        <tr>
                            <td>{{ $commercial_value['name'] ?? '' }}</td>
                            <td class="text-right">{{ $commercial_value['commercial_cost'] ?? '' }}</td>
                            @php
                                $commercial_value_amount = $commercial_value['amount'] ?? 0;
                                $commercial_amount_sum += $commercial_value_amount;
                            @endphp
                            <td class="text-right">{{ $commercial_value['amount'] ?? '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-center"><b>Total</b></td>
                        <td class="text-right"></td>
                        <td class="text-right">{{ number_format($commercial_amount_sum,4) }}</td>
                    </tr>
                @else
                    <tr>
                        <td colspan="3">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>


        <div style="margin-top: 15px">
            @php
                $commission_rate_sum = collect($commission_details)->sum('commission_rate');
                $commission_amount_sum = collect($commission_details)->sum('amount');
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th colspan="4" class="text-center"><b>Commission Cost</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Commission Basis</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
                @if(count($commission_details) > 0)
                    @foreach($commission_details as $commission_key=>$commission_value)
                        <tr>
                            <td>{{ $commission_value['particular_name'] ?? ''}}</td>
                            <td>{{ $commission_value['commission_base_name'] ?? ''}}</td>
                            <td class="text-right">{{ $commission_value['commission_rate'] ?? '' }}</td>
                            <td class="text-right">{{ $commission_value['amount'] ?? '' }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="text-center"><b>Total</b></td>
                        <td class="text-right"></td>
                        <td class="text-right"><b>{{ number_format($commission_amount_sum,4) }}</b></td>
                    </tr>
                @else
                    <tr>
                        <td colspan="4">No Data Found</td>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin-top: 15px">
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
                <tr>
                    <th colspan="3" class="text-center"><b>CM Details</b></th>
                </tr>
                <tr>
                    <th class="text-center">CPM</th>
                    <th class="text-center">SMV</th>
                    <th class="text-center">Eff%</th>
                </tr>
                <tr>
                    <td class="text-right">{{ $financial_parameter }}</td>
                    <td>{{ $smv }}</td>
                    <td>{{collect([$cut_efficiency,$sew_efficiency])->implode(',')}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
