<div class="body-section" style="margin-top: 0px;">
    <div>
        <table>
            <tr>
                <th>Unique ID</th>
                <td>{{ $job_no ?? ''}}</td>
                <th>Buyer</th>
                <td>{{ $buyer['name'] ?? ''}}</td>
                <th>Garments Item</th>
                <td>{{ $items ?? ''}}</td>
                <td>Picture</td>
            </tr>

            <tr>
                <th>Style Name</th>
                <td>{{ $style_name ?? ''}}</td>
                <th>Style Qty</th>
                <td>{{ $order['pq_qty_sum'] ?? 0.00 }} {{ $order_uom_id ? $styleUom[(int)$order_uom_id]  : '' }}</td>
                <th>Plan Cut Qty</th>
                <td>{{ $plan_cut_qty ?? 0.00 }}</td>

                <td rowspan="5" style="text-align: center">
                    @if($image)
                        <img src="{{ $image }}" width="50px" height="80px"/>
                    @else
                        @if($type == 'pdf')
                            <img src="{{ public_path('/images/no_image.jpg') }}" alt="" height="50" width="90">
                        @endif
                        @if($type == 'view')
                                <img src="{{ asset('/images/no_image.jpg') }}" alt="" width="100">
                        @endif
                    @endif
                </td>
            </tr>

            <tr>
                <th>Po Numbers</th>
                <td colspan="5">{{ $po_no ?? '' }}</td>
            </tr>

            <tr>
                <th>Knit Fabric Cons</th>
                <td>{{ number_format($knit_fabric_cons,4) ?? 0.00 }}</td>
                <th>Woven Fabric Cons</th>
                <td>{{ number_format($woven_fabric_cons,4) ?? 0.00}}</td>
                <th>Price Per Unit</th>
                <td>{{ number_format($costing_per_pcs, 4) ?? 0.00 }}</td>
            </tr>

            <tr>
                <th>Avg Yarn Req</th>
                <td>{{ $avg_yarn_req ?? '' }}</td>
                <th>Costing Per</th>
                <td>{{ $costingPer[(int)$costing_per] ?? '' }}</td>
                <th>Shipment Date</th>
                <td>{{ formatDate($shipment_date) ?? '' }}</td>
            </tr>

            <tr>
                <th>Knit Fin Fabric Cons</th>
                <td>{{ $knit_fabric_fin_cons ?? ''}}</td>
                <th>Woven Fin Fabric Cons</th>
                <td>{{ $woven_fabric_fin_cons ?? ''}}</td>
                <th>GSM</th>
                <td>{{ $gsm ?? '' }}</td>
            </tr>

        </table>
    </div>
    <div style="margin-top: 15px;">
        <table>
            <tr>
                <th colspan="5" class="text-center">Order Profitability</th>
            </tr>
            <tr>
                <th class="text-center">Line Items</th>
                <th class="text-center">Particulars</th>
                <th class="text-center">Amount (USD)/ DZN</th>
                <th class="text-center">Total Value</th>
                <th class="text-center">Percentage(%)</th>
            </tr>
            <tr>
                <td class="text-center"><b>01</b></td>
                <td>Gross FOB Value</td>
                <td class="text-right">{{ number_format($gross_fob_value,4)}}</td>
                <td class="text-right">{{ number_format($value_gross_fob_value,4) }}</td>
                <td class="text-right">{{ number_format($percent_gross_fob_value, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>02</b></td>
                <td>Less: commission</td>
                <td class="text-right">{{ number_format($less_commission,4) }}</td>
                <td class="text-right">{{ number_format($value_less_commission,4) }}</td>
                <td class="text-right">{{ number_format($percent_less_commission, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>03</b></td>
                <td>Net FOB Value (1-2)</td>
                <td class="text-right">{{ number_format($net_fob_value,4) }}</td>
                <td class="text-right">{{ number_format($value_net_fob_value,4) }}</td>
                <td class="text-right">{{ number_format($percent_net_fob_value, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>04</b></td>
                <td>Less: Cost of Material & Services (5+6+7+8+9)</td>
                <td class="text-right">{{ number_format($cost_of_material_services,4) }}</td>
                <td class="text-right">{{ number_format($value_cost_of_material_services,4) }}</td>
                <td class="text-right">{{ number_format($percent_cost_of_material_services, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>05</b></td>
                <td>Yarn Cost</td>
                <td class="text-right">{{ number_format($yarn_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_yarn_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_yarn_cost, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>06</b></td>
                <td>Conversion Cost</td>
                <td class="text-right">{{ number_format($conversion_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_conversion_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_conversion_cost, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>07</b></td>
                <td>Trim Cost</td>
                <td class="text-right">{{ number_format($trim_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_trim_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_trim_cost, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>08</b></td>
                <td>Embelishment Cost</td>
                <td class="text-right">{{ number_format($embellishment_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_embellishment_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_embellishment_cost, 2) .'%' }}</td>
            </tr>
            <tr>
                <td rowspan="7" class="text-center"><b>09</b></td>
                <td><b>Other Direct Expenses</b></td>
                <td class="text-right"><b>{{ number_format($other_direct_expenses,4) }}</b></td>
                <td class="text-right"><b>{{ number_format($value_other_direct_expenses,4) }}</b></td>
                <td class="text-right">{{ number_format($percent_other_direct_expenses, 2) .'%' }}</td>
            </tr>

            <tr>
                <td>Lab Test</td>
                <td class="text-right">{{ number_format($lab_test,4) }}</td>
                <td class="text-right">{{ number_format($value_lab_test,4) }}</td>
                <td class="text-right">{{ number_format($percent_lab_test, 2) .'%' }}</td>
            </tr>

            <tr>
                <td>Inspection</td>
                <td class="text-right">{{ number_format($inspection,4) }}</td>
                <td class="text-right">{{ number_format($value_inspection,4) }}</td>
                <td class="text-right">{{ number_format($percent_inspection, 2) .'%' }}</td>
            </tr>

            <tr>
                <td>Freight Cost</td>
                <td class="text-right">{{ number_format($freight,4) }}</td>
                <td class="text-right">{{ number_format($value_freight,4) }}</td>
                <td class="text-right">{{ number_format($percent_freight, 2) .'%' }}</td>
            </tr>

            <tr>
                <td>Courier Cost</td>
                <td class="text-right">{{ number_format($courier_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_courier_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_courier_cost, 2) .'%' }}</td>
            </tr>

            <tr>
                <td>Certificate Cost</td>
                <td class="text-right">{{ number_format($certificate_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_certificate_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_certificate_cost, 2) .'%' }}</td>
            </tr>

            <tr>
                <td>Garments Wash Cost</td>
                <td class="text-right">{{ number_format($gmts_wash_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_gmts_wash_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_gmts_wash_cost, 2) .'%' }}</td>
            </tr>

            <tr>
                <td class="text-center"><b>10</b></td>
                <td>Contributions/Value Additions (3-4)</td>
                <td class="text-right">{{ number_format($contributions_value_additions,4) }}</td>
                <td class="text-right">{{ number_format($value_contributions_value_additions,4) }}</td>
                <td class="text-right">{{ number_format($percent_contributions_value_additions, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>11</b></td>
                <td>Less: CM Cost</td>
                <td class="text-right">{{ number_format($less_cm_cost,4) }}</td>
                <td class="text-right">{{ number_format($value_less_cm_cost,4) }}</td>
                <td class="text-right">{{ number_format($percent_less_cm_cost, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>12</b></td>
                <td>Gross Profit (10-11)</td>
                <td class="text-right">{{ number_format($gross_profit,4) }}</td>
                <td class="text-right">{{ number_format($value_gross_profit,4) }}</td>
                <td class="text-right">{{ number_format($percent_gross_profit, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>13</b></td>
                <td><b>Less: Commercial Cost</b></td>
                <td class="text-right">{{ number_format($less_commercial_cost,4) }}</td>
                <td class="text-right"><b>{{ number_format($value_less_commercial_cost,4) }}</b></td>
                <td class="text-right"><b>{{ number_format($percent_less_commercial_cost, 2) .'%' }}</b></td>
            </tr>
            <tr>
                <td class="text-center"><b>14</b></td>
                <td>Less: Operating Expensees</td>
                <td class="text-right">{{ number_format($less_operating_expenses,4) }}</td>
                <td class="text-right">{{ number_format($value_less_operating_expenses,4) }}</td>
                <td class="text-right">{{ number_format($percent_less_operating_expenses, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>15</b></td>
                <td>Operating Profit/ Loss (12-(13+14))</td>
                <td class="text-right">{{ number_format($operating_profit_loss,4) }}</td>
                <td class="text-right">{{ number_format($value_operating_profit_loss,4) }}</td>
                <td class="text-right">{{ number_format($percent_operating_profit_loss, 2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>16</b></td>
                <td>Less: Depreciation & Amortization</td>
                <td class="text-right">{{ number_format($depc_amort, 4) }}</td>
                <td class="text-right">{{ number_format($value_depc_amort, 4) }}</td>
                <td class="text-right">{{ number_format($percent_depc_amort,  2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>17</b></td>
                <td>Less: Interest</td>
                <td class="text-right">{{ number_format($interest,4) }}</td>
                <td class="text-right">{{ number_format($value_interest, 4) }}</td>
                <td class="text-right">{{ number_format($percent_interest,  2) .'%' }}</td>
            </tr>

            <tr>
                <td class="text-center"><b>18</b></td>
                <td>Less: Income Tax</td>
                <td class="text-right">{{ number_format($income_tax, 4) }}</td>
                <td class="text-right">{{ number_format($value_income_tax, 4) }}</td>
                <td class="text-right">{{ number_format($percent_income_tax,  2) .'%' }}</td>
            </tr>
            <tr>
                <td class="text-center"><b>19</b></td>
                <td>Grey Cons</td>
                <td class="text-right">{{ number_format($totalGreyConsFabric, 4) }}</td>
                <td class="text-right">{{ number_format($totalGreyConsAmount, 4) }}</td>
                <td class="text-right">{{ number_format($percent_grey_cons_value,  2) .'%' }}</td>
            </tr>

            <tr>
                <td class="text-center"><b>20</b></td>
                <td>Net Profit (15-(16+17+18+19))</td>
                <td class="text-right">{{ number_format($net_profit_value, 4) }}</td>
                <td class="text-right">{{ number_format($value_net_profit_value, 4) }}</td>
                <td class="text-right">{{ number_format($percent_net_profit_value,  2) .'%' }}</td>
            </tr>

        </table>
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
        <table>
            <tr>
                <th colspan="9" class="text-center">All Fabric Cost</th>
            </tr>
            <tr>
                <th rowspan="{{ count($knit_fabrics) + 1}}">Knit Fabric</th>
                <th class="text-center">Fabric Description</th>
                <th class="text-center">Source</th>
                <th class="text-center">Uom</th>
                <th class="text-center">Fab. Cons</th>
                <th class="text-center">Total Fab. Qty</th>
                <th class="text-center">Rate (USD)</th>
                <th class="text-center">Amount (USD)</th>
                <th class="text-center">Total Amount</th>
            </tr>
            @if(count($knit_fabrics) > 0)
                @foreach($knit_fabrics as $knit_key=>$knit_fabric)
                    <tr>
                        <td>
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
                            $knitUom = array_key_exists((int)$knit_fabric['uom'], $uom) ? $uom[(int)$knit_fabric['uom']] : null;
                        @endphp
                        <td class="text-right">{{ $knitUom ? $knitUom : ''}}</td>
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
            @endif

            {{--                <tr style="height: 25px;">--}}
            {{--                    <td colspan="9"></td>--}}
            {{--                </tr>--}}
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
            @endif

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
        </table>
    </div>
    @if( count($yarn_costing) >0)
        <div style="margin-top: 15px;">
            @php
                $yarn_qty_sum = collect($yarn_costing)->sum('cons_qty');
                $yarn_rate_sum = collect($yarn_costing)->sum('rate');
                $yarn_amount_sum = collect($yarn_costing)->sum('amount');
                $yarn_total_qty_sum = collect($yarn_costing)->sum('total_yarn_qty');
                $yarn_total_amount_sum = collect($yarn_costing)->sum('total_yarn_amount');
            @endphp
            <table>
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
                        <td class="text-right">{{ number_format($yarn_value['total_yarn_qty'], 4) }}</td>
                        <td class="text-right">{{ number_format($yarn_value['total_yarn_amount'], 4) }}</td>
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
            </table>
        </div>
    @endif

    @if( count($conversion_costing) > 0)
        <div style="margin-top: 15px;">
            @php
                $conversion_qty_sum = collect($conversion_costing)->sum('req_qty');
                $conversion_rate_sum = collect($conversion_costing)->sum('unit');
                $conversion_amount_sum = collect($conversion_costing)->sum('amount');
                $conversion_total_qty_sum = collect($conversion_costing)->sum('total_qty');
                $conversion_total_amount_sum = collect($conversion_costing)->sum('total_amount');
            @endphp
            <table>
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
                {{--                <tr>--}}
                {{--                    <td colspan="4" class="text-center"><b>Total Fabric Cost</b></td>--}}
                {{--                    <td class="text-right"></td>--}}
                {{--                    <td class="text-right"></td>--}}
                {{--                    <td class="text-right"></td>--}}
                {{--                    <td class="text-right"></td>--}}
                {{--                </tr>--}}
            </table>
        </div>
    @endif


    @if( count($conversion_costing_color_wise) > 0)
        <div style="margin-top: 15px;">

            <table>
                <tr>
                    <th rowspan="{{ count($conversion_costing_color_wise->flatten(1)) + 1 }}">Conversion Cost to Fabric
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
                @php
                    $conversion_color_amount_sum = 0;
                    $conversion_color_total_amount_sum = 0;
                @endphp
                @foreach($conversion_costing_color_wise as $con_key_value => $conversion_value_color_wise)
                    @php

                        $conversion_color_qty_sum = collect($conversion_value_color_wise)->sum('total_qty');
                        $conversion_color_rate_sum = collect($conversion_value_color_wise)->sum('charge_unit');
                        $conversion_color_cons_sum = collect($conversion_value_color_wise)->sum('cons');
                        $conversionCostProcessWise = collect($conversion_value_color_wise)->groupBy('process');
                        $particularsRowSpan = true;
                    @endphp
                    @foreach( $conversionCostProcessWise as $con_key => $conversion_values)
                        @foreach($conversion_values as $key => $conversion_value)

                            <tr>
                                @if($loop->first)
                                    @if($particularsRowSpan == true)

                                        <td rowspan="{{ count($conversion_value_color_wise) }}">{{ $con_key_value  }}</td>
                                    @endif
                                    <td rowspan="{{ count($conversion_values) }}">{{ $conversion_value['process'] ?? '' }}</td>
                                @endif
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
                                @endphp
                                <td class="text-right">{{ number_format($cons, 4) }}</td>
                                <td class="text-right">{{ number_format($charge_unit, 4) }}</td>
                                <td class="text-right">{{ number_format($conversion_color_amount, 4) }}</td>
                                <td class="text-right">{{  number_format($total_conversition_qty, 4) }}</td>
                                <td class="text-right">{{ number_format($conversion_color_amount_total, 4) }}</td>
                            </tr>
                            @php
                                $particularsRowSpan = false;
                            @endphp

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
                {{--                <tr>--}}
                {{--                    <td colspan="8" class="text-center"><b>Total Fabric Cost</b></td>--}}
                {{--                    <td class="text-right"></td>--}}
                {{--                    <td class="text-right"></td>--}}
                {{--                </tr>--}}
            </table>
        </div>
    @endif

    @if( count($trims_details) >0)
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
            <table>
                <tr>
                    <th colspan="9" class="text-center">Trims Cost</th>
                </tr>
                <tr>
                    <th class="text-center"><b>Item Group</b></th>
                    <th class="text-center"><b>Description</b></th>
                    <th class="text-center"><b>Nominated Supplier</b></th>
                    <th class="text-center"><b>UOM</b></th>
                    <th class="text-center"><b>Cons/Dzn</b></th>
                    <th class="text-center"><b>Ex. %</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                    <th class="text-center"><b>Total Qty</b></th>
                    <th class="text-center"><b>Total Amount (USD)</b></th>
                </tr>
                @foreach($trims_details as $trim_key=>$trim_value)
                    <tr>
                        <td>{{ $trim_value['group_name'] ?? '' }}</td>
                        <td>{{ $trim_value['description'] ?? ''}}</td>
                        <td>{{ $trim_value['nominated_supplier_value'] ?? ''}}</td>
                        <td>{{ $trim_value['cons_uom_value'] ?? ''}}</td>
                        <td class="text-right">{{ $trim_value['cons_gmts'] ?? '' }}</td>
                        <td class="text-right">
                            {{
                                isset($trim_value['breakdown']) && isset($trim_value['breakdown']['details'])
                                    ? collect($trim_value['breakdown']['details'])->first()['ex_cons_percent'] ?? ''
                                    : ''
                            }}
                        </td>
                        <td class="text-right">{{ $trim_value['rate'] ?? ''}}</td>
                        <td class="text-right">{{ $trim_value['amount'] ?? '' }}</td>
                        <td class="text-right">{{ $trim_value['total_quantity'] ?? 0 }}</td>
                        <td class="text-right">{{ $trim_value['total_amount'] ?? 0 }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-center"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format($trims_cons_gmts,4) }}</b></td>
                    <td></td>
                    <td class="text-right"><b>{{ number_format($trims_rate,4) }}</b></td>
                    <td class="text-right"><b>{{ number_format($trims_amount,4) }}</b></td>
                    <td class="text-right"><b>{{ number_format($total_trims_qty_sum, 4)  }}</b></td>
                    <td class="text-right"><b>{{ number_format($total_trims_amount_sum, 4) }}</b></td>
                </tr>

            </table>
        </div>
    @endif

    @if( count($embellishment_details) > 0)
        <div style="margin-top: 15px">
            @php
                $embellishment_cons_sum = collect($embellishment_details)->sum('consumption');
                $embellishment_rate_sum = collect($embellishment_details)->sum('consumption_rate');
                $embellishment_amount_sum = collect($embellishment_details)->sum('consumption_amount');
            @endphp
            <table>
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
            </table>
        </div>
    @endif

    @if( count($commercial_details) >  0)
        <div style="margin-top: 15px">
            @php
                $commercial_cost_sum = collect($commercial_details)->sum('commercial_cost');
                $commercial_amount_sum = 0;
            @endphp
            <table>
                <tr>
                    <th colspan="3" class="text-center"><b>Commercial Cost</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
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
            </table>
        </div>
    @endif

    @if(count($commission_details) > 0)
        <div style="margin-top: 15px">
            @php
                $commission_rate_sum = collect($commission_details)->sum('commission_rate') ?? 0;
                $commission_amount_sum = collect($commission_details)->sum('amount') ?? 0;
            @endphp
            <table>
                <tr>
                    <th colspan="4" class="text-center"><b>Commission Cost</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Commission Basis</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
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
            </table>
        </div>
    @endif

    <div style="margin-top: 15px">
        <table>
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
                <td>
                    @php
                        echo  collect([$cut_efficiency,$sew_efficiency])->implode(',')
                    @endphp
                </td>
            </tr>
        </table>
    </div>
</div>
