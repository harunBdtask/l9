<div class="body-section" style="margin-top: 0px;">
    <table class="border">
        <thead>
        <tr>
            <td class="text-center">
                <span style="font-size: 12pt; font-weight: bold;">Price Quotation</span><br>
            </td>
        </tr>
        </thead>
    </table>
    <br>
    <table>
        <tr>
            <th><b>Quotation ID</b></th>
            <td>{{ $price_quotation->quotation_id ?? '' }}</td>
            <th>Buyer</th>
            <td>{{ $price_quotation->buyer->name ?? '' }}</td>
            <th>Garments Item</th>
            <td>{{ $items }}</td>
        </tr>

        <tr>
            <th><b>Style Name</b></th>
            <td>{{ $price_quotation->style_name ?? '' }}</td>
            <th><b>Order UOM</b></th>
            <td>{{ $price_quotation->style_uom_name ?? '' }}</td>
            <th><b>Offer Qnty</b></th>
            <td>{{ $price_quotation->offer_qty ?? '' }}</td>
        </tr>

        <tr>
            <th><b>Knit Fabric Cons</b></th>
            <td>{{ $totalKnit }}</td>
            <th><b>Woven Fabric Cons</b></th>
            <td>{{ $totalWoven }}</td>
            <th><b>Price Per Unit</b></th>
            <td>{{ number_format($price_quotation->confirm_price_pc_set, 2) . ' ' . $price_quotation->currency->currency_name ?? '' }}</td>
        </tr>

        <tr>
            <th><b>Avg Yarn Req</b></th>
            <td>{{ number_format($avg_yarn_val, 4) }}</td>
            <th><b>Costing Per</b></th>
            <td>{{ $price_quotation->costing_per_name ?? '' }}</td>
            <th><b>Target Price</b></th>
            <td>{{ $price_quotation->target_price ?? '' }}</td>
        </tr>

        <tr>
            <th><b>GSM</b></th>
            <td>{{ $gsm }}</td>
            <th><b>Style Desc</b></th>
            <td>{{ $price_quotation->style_desc ?? '' }}</td>
            <th><b>Season</b></th>
            <td>{{ $price_quotation->season->season_name ?? '' }}</td>
        </tr>

        <tr>
            <th><b>OP Date</b></th>
            <td>{{ $price_quotation->op_date ?? '' }}</td>
            <th><b>Est.Ship Date</b></th>
            <td>{{ $price_quotation->est_shipment_date ?? '' }}</td>
            <th><b>Lead Time</b></th>
            <td> {{ $price_quotation->lead_time_diff ?? '' }}</td>
        </tr>

    </table>
    <div style="margin-top: 15px;">
        <table>
            <tr>
                <th class="text-center">SL</th>
                <th class="text-center">Particulars</th>
                <th class="text-center">Cost</th>
                <th class="text-center">Amount (USD)</th>
                <th class="text-center">% to Ord. Value</th>
            </tr>
            <tr>
                <td class="text-right">1</td>
                <td>Order Price/ DZN</td>
                <td></td>
                <td class="text-right">{{ $price_quotation->price_with_commn_dzn ?? '0.00' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">2</td>
                <td>Less Commission / DZN</td>
                <td></td>
                <td class="text-right">{{ $price_quotation->commi_dzn ?? '0.00' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">3</td>
                <td>Net Quoted Price</td>
                <td></td>
                <td class="text-right">{{ $price_quotation->price_bef_commn_dzn ?? '0.00' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">4</td>
                <td>All Fabric Cost</td>
                <td class="text-right">{{ $price_quotation->fab_cost ?? '0.00' }}</td>
                <td rowspan="15"></td>
                <td class="text-right">{{ $price_quotation->fab_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">5</td>
                <td>Trims Cost</td>
                <td class="text-right">{{ $price_quotation->trims_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->trims_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">6</td>
                <td>Embellishment Cost</td>
                <td class="text-right">{{ $price_quotation->embl_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->embl_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">7</td>
                <td>Commercial Cost</td>
                <td class="text-right">{{ $price_quotation->comml_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->comml_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">8</td>
                <td>Washing Cost (Gmt.)</td>
                <td class="text-right">{{ $price_quotation->gmt_wash ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->gmt_wash_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">9</td>
                <td>Lab Test</td>
                <td class="text-right">{{ $price_quotation->lab_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->lab_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">10</td>
                <td>Inspection Cost</td>
                <td class="text-right">{{ $price_quotation->inspect_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->inspect_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">11</td>
                <td>CM Cost</td>
                <td class="text-right">{{ $price_quotation->cm_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->cm_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">12</td>
                <td>Freight Cost</td>
                <td class="text-right">{{ $price_quotation->freight_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->freight_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">13</td>
                <td>Currier Cost</td>
                <td class="text-right">{{ $price_quotation->currier_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->currier_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">14</td>
                <td>Certificate Cost</td>
                <td class="text-right">{{ $price_quotation->certif_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->certif_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">15</td>
                <td>Design Cost</td>
                <td class="text-right">{{ $price_quotation->design_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->design_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">16</td>
                <td>Studio Cost</td>
                <td class="text-right">{{ $price_quotation->studio_cost ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->studio_cost_prcnt ?? '0.00' }}</td>
            </tr>
            <tr>
                <td class="text-right">17</td>
                <td>Operating Expenses</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">18</td>
                <td>Deprec. & Amort.</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">19</td>
                <td>Interest</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">20</td>
                <td>Income Tax</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">21</td>
                <td><b>Total Cost (4-18)</b></td>
                <td></td>
                <td class="text-right"><b>{{ $particulars_sum ?? '0.00' }}</b></td>
                <td class="text-right"><b>{{ $particulars_percentage_sum ?? '0.00' }}</b></td>
            </tr>
            <tr>
                <td class="text-right">22</td>
                <td>Margin/ DZN</td>
                <td></td>
                <td class="text-right">{{ $price_quotation->margin_dzn  ?? '0.00'}}</td>
                <td class="text-right">{{ $price_quotation->margin_dzn_prcnt  ?? '0.00'}}</td>
            </tr>
            <tr>
                <td class="text-right">23</td>
                <td>Net Quoted Price/ Pcs</td>
                <td></td>
                <td class="text-right">{{ $price_quotation->price_with_commn_pcs ?? '0.00' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">24</td>
                <td>Cost /Pcs</td>
                <td></td>
                <td class="text-right">{{ $price_quotation->final_cost_pc_set ?? '0.00' }}</td>
                <td></td>
            </tr>
            <tr>
                <td class="text-right">25</td>
                <td>Margin/Pcs</td>
                <td></td>
                <td class="text-right">{{ ($price_quotation->final_cost_pc_set - $price_quotation->price_with_commn_pcs) ?? '0.00'}}</td>
                <td></td>
            </tr>

        </table>
    </div>
    @php
        $knitFabricCount = array_key_exists('fabricForm', $fabricCostDetails) ?  collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->count() : 0;
        $YarnFabCount = array_key_exists('fabricForm', $fabricCostDetails) ?  collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->count() : 0;
    @endphp
    @if($knitFabricCount > 0 || $YarnFabCount > 0)
        <div style="margin-top: 15px;">
            <table>
                <tr>
                    <th colspan="6">All Fabric Cost</th>
                </tr>
                @if(isset($fabricCostDetails['fabricForm']) && collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->count() > 0)
                    <tr>
                        <th rowspan="{{ isset($fabricCostDetails['fabricForm']) ? count(collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)) + 2 : 0 }}">
                            Knit
                            Fabric
                        </th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Fab. Cons/ DZN</th>
                        <th class="text-center">Rate (USD)</th>
                        <th class="text-center">Amount (USD)</th>
                    </tr>
                    @if(isset($fabricCostDetails['fabricForm']))
                        @foreach(collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1) as $fabric)
                            <tr>
                                <td>{{ $fabric['fabric_composition_value'] }}</td>
                                @if($fabric['fabric_source'] == 1)
                                    <td>Production</td>
                                @elseif($fabric['fabric_source'] == 2)
                                    <td>Purchase</td>
                                @elseif($fabric['fabric_source'] == 3)
                                    <td>Buyer Supplier</td>
                                @elseif($fabric['fabric_source'] == 4)
                                    <td>Stock</td>
                                @endif
                                <td class="text-right">{{ $fabric['fabricConsumptionCalculation']['cons_sum'] ?? '' }}</td>
                                <td class="text-right">{{ $fabric['fabricConsumptionCalculation']['rate_sum'] ?? ''}}</td>
                                <td class="text-right">{{ $fabric['fabricConsumptionCalculation']['amount_sum'] ?? ''}}</td>
                            </tr>
                        @endforeach
                    @endif
                    <tr>
                        <td colspan="2" class="text-center"><b>Sub Total</b></td>
                        <td class="text-right">{{ isset($fabricCostDetails['fabricForm']) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->sum('fabricConsumptionCalculation.cons_sum') : 0}}</td>
                        <td></td>
                        <td class="text-right">{{ isset($fabricCostDetails['fabricForm']) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->sum('fabricConsumptionCalculation.amount_sum') : 0}}</td>
                    </tr>
                    @if(collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->count() > 0)
                        <tr style="height: 25px;">
                            <td colspan="7"></td>
                        </tr>
                    @endif
                @endif

                @if(array_key_exists('fabricForm', $fabricCostDetails) && collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->count() > 0)
                    <tr>
                        <th rowspan="{{ isset($fabricCostDetails['fabricForm']) ? count(collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)) + 1 : 0 }}">
                            Woven Fabric
                        </th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Source</th>
                        <th class="text-center">Fab. Cons/ DZN</th>
                        <th class="text-center">Rate (USD)</th>
                        <th class="text-center">Amount (USD)</th>
                    </tr>
                    @if(isset($fabricCostDetails['fabricForm']))
                        @foreach(collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2) as $fabric)
                            <tr>
                                <td>{{ $fabric['fabric_composition_value'] }}</td>
                                @if($fabric['fabric_source'] == 1)
                                    <td>Production</td>
                                @elseif($fabric['fabric_source'] == 2)
                                    <td>Purchase</td>
                                @elseif($fabric['fabric_source'] == 3)
                                    <td>Buyer Supplier</td>
                                @elseif($fabric['fabric_source'] == 4)
                                    <td>Stock</td>
                                @endif
                                <td class="text-right">{{ $fabric['fabricConsumptionCalculation']['cons_sum'] ?? ''}}</td>
                                <td class="text-right">{{ $fabric['fabricConsumptionCalculation']['rate_sum'] ?? '' }}</td>
                                <td class="text-right">{{ $fabric['fabricConsumptionCalculation']['amount_sum'] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endif

                    <tr>
                        <td colspan="2" class="text-center"><b>Sub Total</b></td>
                        <td></td>
                        <td class="text-right">{{ isset($fabricCostDetails['fabricForm']) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->sum('fabricConsumptionCalculation.cons_sum') : 0 }}</td>
                        <td></td>
                        <td class="text-right">{{ isset($fabricCostDetails['fabricForm']) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->sum('fabricConsumptionCalculation.amount_sum') : 0 }}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3" class="text-center"><b>Total</b></td>
                    <td class="text-right">
                        {{ isset($fabricCostDetails['fabricForm']) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->sum('fabricConsumptionCalculation.cons_sum') + collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->sum('fabricConsumptionCalculation.cons_sum') : 0}}
                    </td>
                    <td></td>
                    <td class="text-right">
                        {{ isset($fabricCostDetails['fabricForm']) ? collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 2)->sum('fabricConsumptionCalculation.amount_sum') + collect($fabricCostDetails['fabricForm'])->where('fabric_nature_id', 1)->sum('fabricConsumptionCalculation.amount_sum') : 0 }}
                    </td>
                </tr>
            </table>
        </div>
    @endif

    @if(count($yarn_costing) > 0)
        <div style="margin-top: 15px;">
            <table>
                <tr>
                    <th rowspan="{{ count($yarn_costing) + 2 }}">Yarn Cost</th>
                    <th class="text-center"><b>Yarn Desc</b></th>
                    <th class="text-center"><b>Yarn Qty</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                </tr>
                @foreach($yarn_costing as $key=>$yarn_costing_data)
                    <tr>
                        <td>
                            {{ $yarn_costing_data['yarn_composition'] ?? ''}} ,
                            {{ $yarn_costing_data['count'] ?? '' }} ,
                            {{ $yarn_costing_data['type'] ?? '' }}
                        </td>
                        <td class="text-right">{{ number_format($yarn_costing_data['cons_qty'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($yarn_costing_data['rate'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($yarn_costing_data['amount'],2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td class="text-center"><b>Total</b></td>
                    <td class="text-right">{{ number_format(collect($yarn_costing)->sum("cons_qty"),2) ?? '0.00' }}</td>
                    <td class="text-right">{{ number_format(collect($yarn_costing)->sum("rate"),2) ?? '0.00' }}</td>
                    <td class="text-right">{{ number_format(collect($yarn_costing)->sum("amount"),2) ?? '0.00' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if(count($conversion_costing) > 0)
        <div style="margin-top: 15px;">
            <table>
                <tr>
                    <th rowspan="{{ count($conversion_costing) + 3 }}">Conversion Cost to Fabric</th>
                    <th class="text-center"><b>Particulars</b></th>
                    <th class="text-center"><b>Process</b></th>
                    <th class="text-center"><b>Cons</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                </tr>
                @foreach($conversion_costing as $key=>$conversion_costing_value)
                    <tr>
                        <td>{{ $conversion_costing_value['fabric_description'] ?? '' }}</td>
                        <td>{{ $conversion_costing_value['process'] ?? '' }}</td>
                        <td class="text-right">{{ number_format($conversion_costing_value['req_qty'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($conversion_costing_value['unit'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($conversion_costing_value['amount'],2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-center"><b>Total</b></td>
                    <td class="text-right">{{ number_format(collect($conversion_costing)->sum("req_qty"),2) ?? '0.00' }}</td>
                    <td class="text-right">{{ number_format(collect($conversion_costing)->sum("unit"),2) ?? '0.00' }}</td>
                    <td class="text-right">{{ number_format(collect($conversion_costing)->sum("amount"),2) ?? '0.00' }}</td>
                </tr>
                <tr>
                    <td colspan="4" class="text-center"><b>Total Fabric Cost</b></td>
                    <td class="text-right"> {{ number_format($total_fabric_cost,2) ?? '0.00' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if(count($trim_costing) > 0)
        <div style="margin-top: 15px;">
            <table>
                <tr>
                    <th class="text-center">Item Group</th>
                    <th class="text-center"><b>Description</b></th>
                    <th class="text-center"><b>Brand/Supp Ref</b></th>
                    <th class="text-center"><b>UOM</b></th>
                    <th class="text-center"><b>Cons/ DZN</b></th>
                    <th class="text-center"><b>Rate (USD)</b></th>
                    <th class="text-center"><b>Amount (USD)</b></th>
                </tr>
                @foreach($trim_costing as $details)
                    <tr>
                        <td>{{ $details['group_name'] }}</td>
                        <td>{{ $details['item_description'] }}</td>
                        <td>{{ $details['nominated_supplier_value'] }}</td>
                        <td>{{ $details['cons_uom_value'] ?? '' }}</td>
                        <td>{{ $details['cons_gmts'] }}</td>
                        <td>{{ $details['rate'] }}</td>
                        <td>{{ $details['amount'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6" class="text-center"><b>Total</b></td>
                    <td>{{ collect($trim_costing)->sum('amount') }}</td>
                </tr>

            </table>
        </div>
    @endif

    @if( count($embellishment_costing) > 0)
        <div style="margin-top: 15px">
            <table>
                <tr>
                    <th colspan="5"><b>Embellishment Details</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Type</th>
                    <th class="text-center">Cons/ DZN</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
                @foreach($embellishment_costing as $key=>$embellishment_costing_value)
                    <tr>
                        <td>{{ $embellishment_costing_value['name'] ?? '' }}</td>
                        <td>{{ $embellishment_costing_value['type'] ?? '' }}</td>
                        <td class="text-right">{{ number_format($embellishment_costing_value['cons_per_dzn'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($embellishment_costing_value['rate'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($embellishment_costing_value['amount'],2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="4" class="text-center"><b>Total</b></td>
                    <td class="text-right">{{ number_format(collect($embellishment_costing)->sum("amount"),2) ?? '0.00' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if(count($commercial_costing) > 0)
        <div style="margin-top: 15px">
            <table>
                <tr>
                    <th colspan="3"><b>Commercial Cost</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
                @foreach($commercial_costing as $key=>$commercial_costing_value)
                    <tr>
                        <td>{{ $commercial_costing_value['name'] ?? '' }}</td>
                        <td class="text-right">{{ number_format($commercial_costing_value['commercial_cost'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($commercial_costing_value['amount'],2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="2" class="text-center"><b>Total</b></td>
                    <td class="text-right">{{ number_format(collect($commercial_costing)->sum("amount"),2) ?? '0.00' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if(count($commission_costing) > 0)
        <div style="margin-top: 15px">
            <table>
                <tr>
                    <th colspan="4"><b>Commission Cost</b></th>
                </tr>
                <tr>
                    <th class="text-center">Particulars</th>
                    <th class="text-center">Commission Basis</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                </tr>
                @foreach($commission_costing as $key=>$commission_costing_value)
                    <tr>
                        <td>{{ $commission_costing_value['particular_name'] ?? '' }}</td>
                        <td>{{ $commission_costing_value['commission_basis_name'] ?? '' }}</td>
                        <td class="text-right">{{ number_format($commission_costing_value['commission_rate'],2) ?? '0.00' }}</td>
                        <td class="text-right">{{ number_format($commission_costing_value['amount'],2) ?? '0.00' }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3" class="text-center"><b>Total</b></td>
                    <td class="text-right">{{ number_format(collect($commission_costing)->sum('amount'),2) ?? '0.00' }}</td>
                </tr>
            </table>
        </div>
    @endif


    <div style="margin-top: 15px">
        <div style="width: 100%">
            <div style="width: 50%;float: left">
                <table>
                    <tr>
                        <th colspan="2"><b>Others Components</b></th>
                    </tr>
                    <tr>
                        <th class="text-center">Particulars</th>
                        <th class="text-center">Amount (USD)</th>
                    </tr>
                    <tr>
                        <td>Gmts Wash</td>
                        <td class="text-center">{{ $price_quotation->gmt_wash ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Lab Test</td>
                        <td class="text-center">{{ $price_quotation->lab_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Inspection Cost</td>
                        <td class="text-center">{{ $price_quotation->inspect_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>CM Cost - IE</td>
                        <td class="text-center">{{ $price_quotation->cm_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Freight Cost</td>
                        <td class="text-center">{{ $price_quotation->freight_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Currier Cost</td>
                        <td class="text-center">{{ $price_quotation->currier_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Certificate Cost</td>
                        <td class="text-center">{{ $price_quotation->certif_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Design Cost</td>
                        <td class="text-center">{{ $price_quotation->design_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Studio Cost</td>
                        <td class="text-center">{{ $price_quotation->studio_cost ?? '0.00' }}</td>
                    </tr>
                    <tr>
                        <td>Office OH</td>
                        <td class="text-center">0.00</td>
                    </tr>
                    <tr>
                        <td>Deprec. & Amort.</td>
                        <td class="text-center">0.00</td>
                    </tr>
                    <tr>
                        <td>Interest</td>
                        <td class="text-center">0.00</td>
                    </tr>
                    <tr>
                        <td>Income Tax</td>
                        <td class="text-center">0.00</td>
                    </tr>
                    <tr>
                        <td class="text-center"><b>Total</b></td>
                        <td class="text-center"> {{ number_format(collect($others_component_data)->sum("cost"),2) ?? '0.00' }}</td>
                    </tr>
                </table>
            </div>
            <div style="width: 40%; float: right">


                @if($price_quotation->image)
                    <img style="height: 300px;margin-left: 10%; width: 250px" class="img-fluid"
                         alt="No Image Attached"
                         src="{{ asset("storage/price_quotation_images/".$price_quotation->image) }}">
                @else
                    <img style="height: 370px;margin-left: 10%" class="img-fluid"
                         alt="No Image Attached"
                         src="{{ public_path("images/no_image.jpg") }}">
                @endif

            </div>
        </div>
    </div>
    <br>

    <div style="margin-top: 15px">
        <table>
            <tr>
                <th colspan="3"><b>CM Details</b></th>
            </tr>
            <tr>
                <th class="text-center">CPM</th>
                <th class="text-center">SMV</th>
                <th class="text-center">Eff%</th>
            </tr>
            <tr>
                <td class="text-right">{{ $financialParameter->cost_per_minute ?? '' }}</td>
                <td class="text-right">{{ number_format($total_smv,2) ?? '0.00' }}</td>
                <td class="text-right">{{ $price_quotation->sew_eff ?? '' }}</td>
            </tr>
        </table>
    </div>
</div>
