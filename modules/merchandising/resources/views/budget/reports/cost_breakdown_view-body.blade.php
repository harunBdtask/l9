<div>
    <div class="body-section">
        <div>
            <table>
                <tr>
                    <td class="text-center" colspan="5"
                        style="border-left-style:hidden;border-top-style:hidden;border-bottom-style:hidden; background: #C8C8D9;text-align: center">
                        <span style="font-size: 12pt; font-weight: bold;"><b>COST BREAKDOWN SHEET</b></span>
                    </td>
                    <td rowspan="9" class="text-center">
                        @if(request('page')!='excel')
                            @if($mainPartData['image'] && file_exists(storage_path('app/public/' . $mainPartData['image'])))
                                <img src="{{ asset("storage/".$mainPartData['image'])  }}" alt="" width="200">
                            @else
                                <img src="{{ asset('/images/no_image.jpg') }}" alt="" width="100">
                            @endif
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="6" style="border-left-style:hidden;height: 20px;"></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">PRE-COSTING DATE</td>
                    <td colspan="2">{{ $mainPartData['costing_date'] ?? '' }}</td>
                    <td style="font-weight: bold;">STYLE REF:</td>
                    <td>{{ $mainPartData['style_name'] ?? '' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">POST-COSTING DATE</td>
                    <td colspan="2"></td>
                    <td style="font-weight: bold;">COLORWAY:</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">BUYER /AGENT:</td>
                    <td colspan="2">{{ $mainPartData['buyer_name'] ?? '' }}</td>
                    <td style="font-weight: bold;">MASTER LC REF</td>
                    <td></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">DEPT:</td>
                    <td colspan="2">{{ $mainPartData['product_department'] ?? '' }}</td>
                    <th>UNIQUE ID</th>
                    <td>{{ $mainPartData['unique_id'] ?? '' }}</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">TOTAL ORDER QNTY:</td>
                    <td>{{ $mainPartData['order_qty'] ?? '' }}</td>
                    <td>{{ $mainPartData['uom'] ?? '' }}</td>
                    <td colspan="2" style="border-bottom-style:hidden"/>
                </tr>
                <tr>
                    <td style="font-weight: bold;">AVG FOB PRICE:</td>
                    <td>{{ isset($mainPartData['fob_price']) ? number_format($mainPartData['fob_price'], 2) : 0.00 }}</td>
                    <td>{{ $orderCurrency }}</td>
                    <td colspan="2" style="border-bottom-style:hidden"/>
                </tr>
                <tr>
                    <td style="font-weight: bold;">TOTAL REVENUE:</td>
                    <td>{{ isset($mainPartData['revenue']) ? number_format($mainPartData['revenue'], 2) : 0.00 }}</td>
                    <td>{{ $orderCurrency }}</td>
                    <td colspan="2" style="border-bottom-style:hidden"/>
                </tr>
                <tr>
                    <td colspan="6" style="border-left-style:hidden;border-right-style:hidden;height: 20px;"></td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">NUMBER OF PCS PER PACK</td>
                    <td colspan="2">1</td>
                    <td style="font-weight: bold;" colspan="2">ORDER QNTYS IN {{ ($mainPartData['uom']) ?? '' }}</td>
                    <td>{{ $mainPartData['order_qty'] ?? '' }}</td>
                </tr>

            </table>

        </div>

        <div style="margin-top: 10px">
            <table>
                <tr>
                    <th style="font-weight: bold;">DESCRIPTION - FABRIC</th>
                    <th style="font-weight: bold;">Supplier Name</th>
                    <th style="font-weight: bold;">Fabric Width</th>
                    <th style="font-weight: bold;">Unit Price ({{ $currency }})</th>
                    <th style="font-weight: bold;">Consmup (dz)</th>
                    <th style="font-weight: bold;">W%</th>
                    <th style="font-weight: bold;">Total Qty</th>
                    <th style="font-weight: bold;">Total Cost({{ $currency }})</th>
                    <th style="font-weight: bold;">PRE-COST %</th>
                </tr>

                @if(count($yarnCostData) > 0)
                    @foreach(collect($yarnCostData)->groupBy('uom') as $uom => $data)
                        @php
                            $totalQty = 0;
                            $totalAmount = 0;
                            $totalPreCost = 0;
                        @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item['description'] }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                <td>{{ $item['fabric_width'] }}</td>
                                <td class="text-right">{{ number_format($item['rate'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['cons'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['wastage'], 1) }}%</td>

                                <td class="text-right">{{ sprintf("%.2f", $item['total_qty']) }} {{ $uom }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
                        {{--                        @if($uom == 'Kg')--}}
                        {{--                            <tr>--}}
                        {{--                                <th style="font-weight: bold;">Sub Total Yarn Cost</th>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
                        {{--                                <td class="text-right">${{ number_format($totalAmount, 2) }}</td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalPreCost, 2) }}%</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                    @endforeach
                @endif

                <tr>
                    <td style="font-weight: bold;">Total Yarn Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format(collect($yarnCostData)->sum('total_amount'), 2) }}</th>
                    <th class="text-right">{{ number_format(collect($yarnCostData)->sum('pre_cost'), 2) }}%</th>
                </tr>
                @if(count($knitCostData) > 0)
                    @foreach(collect($knitCostData)->groupBy('uom') as $uom => $data)
                        @php
                            $totalQty = 0;
                            $totalAmount = 0;
                            $totalPreCost = 0;
                        @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>{{ 'KNITTING-' .  $item['description'] }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                <td></td>
                                <td class="text-right">{{ number_format($item['rate'], 2) }}</td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{ number_format($item['total_qty'], 2) }} {{ $uom }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
                        {{--                        @if($uom == 'Kg')--}}

                        {{--                            <tr>--}}
                        {{--                                <th>Sub Total knitting Cost</th>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
                        {{--                                <td class="text-right">${{ number_format($totalAmount, 2) }}</td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalPreCost, 2) }}%</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                    @endforeach
                @endif
                <tr>
                    <td style="font-weight: bold;">Total knitting Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format(collect($knitCostData)->sum('total_amount'), 2) }}</th>
                    <th class="text-right">{{ number_format(collect($knitCostData)->sum('pre_cost'), 2) }}%</th>
                </tr>
                @if(count($dyingCostData) > 0)
                    @foreach(collect($dyingCostData)->groupBy('uom') as $uom => $data)
                        @php
                            $totalQty = 0;
                            $totalAmount = 0;
                            $totalPreCost = 0;
                        @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>{{ 'DYING-' .  $item['description'] }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                <td></td>
                                <td class="text-right">{{ number_format($item['rate'], 2) }}</td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{ number_format($item['total_qty'], 2) }} {{ $uom }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
                        {{--                        @if($uom == 'Kg')--}}
                        {{--                            <tr>--}}
                        {{--                                <th>Sub Total Dyeing Cost</th>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
                        {{--                                <td class="text-right">${{ number_format($totalAmount, 2) }}</td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalPreCost, 2) }}%</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                    @endforeach
                @endif

                <tr>
                    <td style="font-weight: bold;">Total Dyeing Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ number_format(collect($dyingCostData)->sum('total_amount'), 2) }}</td>
                    <td class="text-right">{{ number_format(collect($dyingCostData)->sum('pre_cost'), 2) }}%</td>
                </tr>
                @if(count($otherProcessCost) > 0)
                    @foreach(collect($otherProcessCost)->groupBy('uom') as $uom => $data)
                        @php
                            $totalQty = 0;
                            $totalAmount = 0;
                            $totalPreCost = 0;
                        @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item['process'] .'-' .  $item['description'] }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                <td></td>
                                <td class="text-right">{{ number_format((float)$item['rate'], 2) }}</td>
                                <td></td>
                                <td></td>
                                <td class="text-right">{{ number_format($item['total_qty'], 2) }} {{ $uom }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
                        {{--                        @if($uom == 'Kg')--}}
                        {{--                            <tr>--}}
                        {{--                                <td style="font-weight: bold;">Sub Total Other Process Cost</td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
                        {{--                                <td class="text-right">${{ number_format($totalAmount, 2) }}</td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalPreCost, 2) }}%</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                    @endforeach
                @endif
                <tr>
                    <td style="font-weight: bold;">Total Other Conversion Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ number_format(collect($otherProcessCost)->sum('total_amount'), 2) }}</td>
                    <td class="text-right">{{ number_format(collect($otherProcessCost)->sum('pre_cost'), 2) }}%</td>
                </tr>
                @php
                    $grandFabricCost = collect($otherProcessCost)->sum('total_amount') + collect($yarnCostData)->sum('total_amount') + collect($knitCostData)->sum('total_amount') + collect($dyingCostData)->sum('total_amount');
                    $grandFabricQty = collect($otherProcessCost)->sum('total_qty') + collect($yarnCostData)->sum('total_qty') + collect($knitCostData)->sum('total_qty') + collect($dyingCostData)->sum('total_qty');
                    $grandFabricPreCost = collect($otherProcessCost)->sum('pre_cost') + collect($yarnCostData)->sum('pre_cost') + collect($knitCostData)->sum('pre_cost') + collect($dyingCostData)->sum('pre_cost');
                @endphp
                <tr>
                    <td style="font-weight: bold;">Total Fabric Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format($grandFabricCost, 2) }}</th>
                    <th class="text-right">{{ number_format($grandFabricPreCost, 2) }}%</th>
                </tr>
            </table>
        </div>
        <br/>
        <div style="marign-top: 10px">
            <table>
                <tr>
                    <th style="font-weight: bold;">Accessories - Description</th>
                    <th style="font-weight: bold;">Supplier Name</th>
                    <th style="font-weight: bold;">Unit Price ({{ $currency }})</th>
                    <th style="font-weight: bold;">Unit (in number)</th>
                    <th style="font-weight: bold;">Consumption/ {{ $mainPartData['costing_per'] }}</th>
                    <th style="font-weight: bold;">W%</th>
                    <th style="font-weight: bold;">Total Qty</th>
                    <th style="font-weight: bold;">Total Cost({{ $currency }})</th>
                    <th style="font-weight: bold;">PRE-COST %</th>
                </tr>
                @if(count($trimsCostData) > 0)
                    @foreach(collect($trimsCostData)->groupBy('uom') as $uom => $data)
                        @php
                            $totalQty = 0;
                            $totalAmount = 0;
                            $totalPreCost = 0;
                        @endphp
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $item['group_name'] }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                <td class="text-right">{{ number_format($item['rate'], 2) }}</td>
                                <td></td>
                                <td class="text-right">{{ number_format($item['cons'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['wastage'], 1) }}%</td>
                                <td>{{ sprintf("%.2f", $item['total_qty']) }} {{ $uom }}</td>
                                <td class="text-right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += sprintf("%.2f", $item['total_qty']);
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
                        {{--                        @if($uom == 'Kg')--}}
                        {{--                            <tr>--}}
                        {{--                                <th>Sub Total Trims Cost</th>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td></td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
                        {{--                                <td class="text-right">${{ number_format($totalAmount, 2) }}</td>--}}
                        {{--                                <td class="text-right">{{ number_format($totalPreCost, 2) }}%</td>--}}
                        {{--                            </tr>--}}
                        {{--                        @endif--}}
                    @endforeach
                @endif
                <tr>
                    <td style="font-weight: bold;">Total Trims/Accessories Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format(collect($trimsCostData)->sum('total_amount'), 2) }}</th>
                    <th class="text-right">{{ number_format(collect($trimsCostData)->sum('pre_cost'), 2) }}%</th>
                </tr>

                @php
                    $totalEmblCost = $embellishmentCost['totalEmblCost'] ?? 0;
                    $emblPreCost = $embellishmentCost['pre_cost'] ?? 0;
                    $totalCommercialCost = $commercialCost['totalCommercialAmount'] ?? 0;
                    $totalCommercialPreCost = $commercialCost['pre_cost'] ?? 0;
                    $totalOtherCost = collect($othersCost)->sum('amount') ?? 0;
                    $totalOtherPreCost = collect($othersCost)->sum('pre_cost') ?? 0;
                    $grandTotal = $grandFabricCost + $totalEmblCost + $totalCommercialCost +  $totalOtherCost + collect($trimsCostData)->sum('total_amount');
                    $grandPreCost = $grandFabricPreCost + $emblPreCost + $totalCommercialPreCost +  $totalOtherPreCost +  collect($trimsCostData)->sum('pre_cost');
                    $revenue = $mainPartData['revenue'] ?? 0;
                    $orderQty = $mainPartData['order_qty'] ?? 0;
                    $inHandAmount = $orderQty !=0 ? (($revenue-$grandTotal)/$orderQty) * 12 : 0;
                    $inHandPreCost = (100-$grandPreCost);
                    $fobValue = $mainPartData['fob_price'] ?? 0;
                    $cpm = $mainPartData['cpm'] ?? 0;
                    $smv = (double)$mainPartData['smv'] ?? 0;
                    $machine_line = (double)$mainPartData['machine_line'] ?? 0;
                    $sew_efficiency = (double)$mainPartData['sew_efficiency'] ?? 0;
                    $epm = $smv != 0 ? ($fobValue * $inHandPreCost * 0.01) : 0;
                    $production = $smv != 0 ? ((($machine_line * 10 * 60) / $smv) * 0.01 * $sew_efficiency) * 1.2 : 0;
                    //$cm_per_pcs = ($cpm * $smv);
                    //$cm_per_dzn = ($cm_per_pcs * 12);
                    $costing_mul = $mainPartData['costing_mul'] ?? 0;
                    $cm_per_dzn =  $mainPartData['cm_view_2'] ?? 0;
                    $cm_per_pcs = $costing_mul !=0 ? ($cm_per_dzn/$costing_mul) : 0;
                    $budgetPreCost = $fobValue !=0 ? ($cm_per_dzn/($fobValue * 12)) * 100 : 0;
                    $cm_view_2 = $mainPartData['cm_view_2'] ?? 0;
                    if ($type == 'view-1'){
                        $totalCm = $revenue !=0 ? ($cm_per_dzn * $orderQty) / 12 : 0;
                    }elseif ($type == 'view-2'){
                        $totalCm = $cm_view_2 !=0 ? ($orderQty/$costing_mul) * $cm_view_2 : 0;
                    }else{
                        $totalCm = 0;
                    }
                    $cmPreCost = $revenue !=0 ?  $totalCm / $revenue : 0;
                    $netEarning = $revenue - $grandTotal - $totalCm;
                    $netPreCost = $revenue !=0 ? $netEarning/ $revenue : 0;
                @endphp
                @if(count($embellishmentCost['embelishmentCostData']) > 0)
                    @foreach($embellishmentCost['embelishmentCostData'] as $key => $item)
                        <tr>
                            <td>{{ $item['details'] ?? ''}}</td>
                            <td></td>
                            <td class="text-right">{{ number_format($item['rate'], 2) }}</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="text-right">{{ number_format($item['total_amount'], 2) }}</td>
                            <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td style="font-weight: bold;">Total Embellishment Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format($totalEmblCost, 2) }}</th>
                    <th class="text-right">{{ number_format($emblPreCost, 2) }}%</th>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Finance/Commercial/Logistic Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ number_format($totalCommercialCost, 2) }}</td>
                    <td class="text-right">{{ number_format($totalCommercialPreCost, 2) }}%</td>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Finance/Commercial Cost</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format($totalCommercialCost, 2) }}</th>
                    <th class="text-right">{{ number_format($totalCommercialPreCost, 2) }}%</th>
                </tr>
                @foreach(collect($othersCost) as $key => $item)
                    <tr>
                        <td style="font-weight: bold;">{{$key}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="text-right">{{ number_format($item['amount'], 2) }}</td>
                        <td class="text-right">{{ number_format($item['pre_cost'], 2) }}%</td>
                    </tr>
                @endforeach
                <tr>
                    <td style="font-weight: bold;">Other Costs</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format($totalOtherCost, 2) }}</th>
                    <th class="text-right">{{ number_format($totalOtherPreCost, 2) }}%</th>
                </tr>
                <tr>
                    <td colspan="9" height="20px"/>
                </tr>
                <tr>
                    <td style="font-weight: bold;">Grand Total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format($grandTotal, 2) }}</th>
                    <th class="text-right">{{ number_format($grandPreCost, 2) }}%</th>
                </tr>
                <tr>
                    <td colspan="9" height="20px"/>
                </tr>
                <tr>
                    <td style="font-weight: bold;">INHAND VALUE / PACK</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th class="text-right">{{ number_format($inHandAmount, 2) }}/DZ</th>
                    <th class="text-right">{{ number_format($inHandPreCost, 2) }}%</th>
                </tr>

                @if($type == 'view-1')
                    @include('merchandising::budget.reports.costing_breakdown_view1_sub_table')
                @endif
                @if($type == 'view-2')
                    @include('merchandising::budget.reports.cost_breakdown_view2_sub_table')
                @endif
            </table>
        </div>
        <br>
        @if($type == 'view-akcl')
            @include('merchandising::budget.reports.cost_breakdown_view-akcl_sub_table')
        @endif
    </div>


</div>
