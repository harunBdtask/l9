<div>
    <div class="body-section">
        <div>
            <table>
                <tr>
                    {{--                    {{ dd($type) }}--}}
                    <td class="text-center" colspan="6"
                        style="border-left-style:hidden;border-top-style:hidden;">
                        <span style="font-size: 12pt; font-weight: bold;">COST BREAKDOWN SHEET</span>
                        <br>
                    </td>
                    <td rowspan="7" class="text-center" style="width: 100px; height: 60px">
                        @if($mainPartData['image'] && file_exists(storage_path('app/public/' . $mainPartData['image'])))
                            @if($printType == 'print')
                                <img src="{{ asset("storage/".$mainPartData['image'])  }}" alt="" width="200">
                            @endif
                            @if($printType == 'pdf')
                                <img src="{{ storage_path('app/public/' . $mainPartData['image']) }}" alt="" height="50"
                                     width="90">
                            @endif

                        @else
                            @if($printType == 'pdf')
                                <img src="{{ public_path('/images/no_image.jpg') }}" alt="" height="50" width="90">
                            @endif
                            @if($printType == 'print')
                                <img src="{{ asset('/images/no_image.jpg') }}" alt="" width="100">
                            @endif
                        @endif
                    </td>
                </tr>
                {{--                <tr>--}}
                {{--                    <td colspan="7" style="border-left-style:;height: 5px;"></td>--}}
                {{--                </tr>--}}
                <tr>
                    <td style="width: 150px">PRE-COSTING DATE</td>
                    <td colspan="2">{{ $mainPartData['costing_date'] ?? '' }}</td>
                    <td style="width: 150px">STYLE REF:</td>
                    <td colspan="2">{{ $mainPartData['style_name'] ?? '' }}</td>
                </tr>
                <tr>
                    <td style="width: 150px">UNIQUE ID</td>
                    <td colspan="2">{{ $mainPartData['unique_id'] ?? '' }}</td>
                    <td style="width: 150px"></td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td style="width: 20%">POST-COSTING DATE</td>
                    <td colspan="2"></td>
                    <td>COLORWAY:</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>BUYER /AGENT:</td>
                    <td colspan="2">{{ $mainPartData['buyer_name'] ?? '' }}</td>
                    <td>MASTER LC REF</td>
                    <td colspan="2"></td>
                </tr>
                <tr>
                    <td>TOTAL ORDER QTY:</td>
                    <td>{{ $mainPartData['order_qty'] ?? '' }}</td>
                    <td>{{ $mainPartData['uom'] ?? '' }}</td>
                    <td>DEPT:</td>
                    <td colspan="2">{{ $mainPartData['product_department'] ?? '' }}</td>

                </tr>
                {{--                <tr>--}}
                {{--                    <td>ORDER QNTY:</td>--}}
                {{--                    <td>{{ $mainPartData['order_qty'] ?? '' }}</td>--}}
                {{--                    <td>{{ $mainPartData['uom'] ?? '' }}</td>--}}
                {{--                    <td colspan="2" style="border-bottom-style:hidden"/>--}}
                {{--                </tr>--}}
                <tr>
                    <td>AVG FOB PRICE:</td>
                    <td>{{ isset($mainPartData['fob_price']) ? number_format($mainPartData['fob_price'], 2) : 0.00 }}</td>
                    <td>{{ $orderCurrency }}</td>
                    <td>TOTAL REVENUE:</td>
                    <td>{{ isset($mainPartData['revenue']) ? number_format($mainPartData['revenue'], 2) : 0.00 }}</td>
                    <td>{{ $orderCurrency }}</td>
                </tr>
                {{--                <tr>--}}
                {{--                    <td>REVENUE:</td>--}}
                {{--                    <td>{{ isset($mainPartData['revenue']) ? number_format($mainPartData['revenue'], 2) : 0.00 }}</td>--}}
                {{--                    <td>{{ 'USD' }}</td>--}}
                {{--                    <td colspan="2" style="border-bottom-style:hidden"/>--}}
                {{--                </tr>--}}
                {{--                <tr>--}}
                {{--                    <td colspan="7" style="border-left-style:hidden;border-right-style:hidden;height: 20px;"></td>--}}
                {{--                </tr>--}}
                <tr>
                    <td colspan="2">NUMBER OF PCS PER PACK</td>
                    <td>1</td>
                    <td colspan="3">ORDER QTYS IN {{ ($mainPartData['uom']) ?? '' }}</td>
                    <td>{{ $mainPartData['order_qty'] ?? '' }}</td>
                </tr>

            </table>

        </div>

        <div style="margin-top: 10px">
            <table>
                <tr>
                    <th width="250px">FABRIC-DESCRIPTION</th>
                    <th width="80px">SUP. NAME</th>
                    {{--                    <th>Fabric Width</th>--}}
                    <th width="5px">RATE({{ $currency }})</th>
                    <th width="7px">CONS.(DZ)</th>
                    <th width="3px">W%</th>
                    <th>TOTAL QTY</th>
                    <th>TOTAL COST({{ $currency }})</th>
                    <th>%</th>
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
                                <td>{{ $item['description'] }} width-{{ $item['fabric_width'] ?? 0 }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                {{--                                <td>{{ $item['fabric_width'] }}</td>--}}
                                <td style="text-align: right">{{ number_format($item['rate'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['cons'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['wastage'], 1) }}%</td>

                                <td style="text-align: right">{{ sprintf("%.2f", $item['total_qty']) }} {{ $uom }}</td>
                                <td style="text-align: right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
{{--                        @if($uom == 'Kg')--}}
{{--                            <tr>--}}
{{--                                <th>SUB TOTAL YARN COST</th>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
{{--                                <td style="text-align: right">${{ number_format($totalAmount, 2) }}</td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalPreCost, 2) }}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                    @endforeach
                @endif

                <tr>
                    <th style="background-color:#F3E353">TOTAL YARN COST</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">
                        {{ number_format(collect($yarnCostData)->sum('total_amount'), 2) }}</th>
                    <th style="text-align: right">{{ number_format(collect($yarnCostData)->sum('pre_cost'), 2) }}%</th>
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
                                <td style="text-align: right">{{ number_format($item['rate'], 2) }}</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">{{ number_format($item['total_qty'], 2) }} {{ $uom }}</td>
                                <td style="text-align: right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
{{--                        @if($uom == 'Kg')--}}

{{--                            <tr>--}}
{{--                                <th>SUB TOTAL KNITTING COST</th>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
{{--                                <td style="text-align: right">${{ number_format($totalAmount, 2) }}</td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalPreCost, 2) }}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                    @endforeach
                @endif
                <tr>
                    <th style="background-color:#F3E353">TOTAL KNITTING COST</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">
                        {{ number_format(collect($knitCostData)->sum('total_amount'), 2) }}</th>
                    <th style="text-align: right">{{ number_format(collect($knitCostData)->sum('pre_cost'), 2) }}%</th>
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
                                <td style="text-align: right">{{ number_format($item['rate'], 2) }}</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">{{ number_format($item['total_qty'], 2) }} {{ $uom }}</td>
                                <td style="text-align: right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
{{--                        @if($uom == 'Kg')--}}
{{--                            <tr>--}}
{{--                                <th>SUB TOTAL DYEING COST</th>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
{{--                                <td style="text-align: right">${{ number_format($totalAmount, 2) }}</td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalPreCost, 2) }}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                    @endforeach
                @endif

                <tr>
                    <th style="background-color:#F3E353">TOTAL DYEING COST</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">
                        {{ number_format(collect($dyingCostData)->sum('total_amount'), 2) }}</td>
                    <td style="text-align: right">{{ number_format(collect($dyingCostData)->sum('pre_cost'), 2) }}%</td>
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
                                <td style="text-align: right">{{ number_format($item['rate'], 2) }}</td>
                                <td></td>
                                <td></td>
                                <td style="text-align: right">{{ number_format($item['total_qty'], 2) }} {{ $uom }}</td>
                                <td style="text-align: right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += $item['total_qty'];
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
{{--                        @if($uom == 'Kg')--}}
{{--                            <tr>--}}
{{--                                <th>SUB TOTAL OTHER PROCESS COST</th>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
{{--                                <td style="text-align: right">${{ number_format($totalAmount, 2) }}</td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalPreCost, 2) }}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                    @endforeach
                @endif
                <tr>
                    <th style="background-color:#F3E353">TOTAL OTHER CONVERSION COST</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">
                        {{ number_format(collect($otherProcessCost)->sum('total_amount'), 2) }}</td>
                    <td style="text-align: right">{{ number_format(collect($otherProcessCost)->sum('pre_cost'), 2) }}%
                    </td>
                </tr>
                @php
                    $grandFabricCost = collect($otherProcessCost)->sum('total_amount') + collect($yarnCostData)->sum('total_amount') + collect($knitCostData)->sum('total_amount') + collect($dyingCostData)->sum('total_amount');
                    $grandFabricQty = collect($otherProcessCost)->sum('total_qty') + collect($yarnCostData)->sum('total_qty') + collect($knitCostData)->sum('total_qty') + collect($dyingCostData)->sum('total_qty');
                    $grandFabricPreCost = collect($otherProcessCost)->sum('pre_cost') + collect($yarnCostData)->sum('pre_cost') + collect($knitCostData)->sum('pre_cost') + collect($dyingCostData)->sum('pre_cost');
                @endphp
                <tr>
                    <th style="background-color:#F3E353">TOTAL FABRIC COST</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">{{ number_format($grandFabricCost, 2) }}</th>
                    <th style="text-align: right">{{ number_format($grandFabricPreCost, 2) }}%</th>
                </tr>
            </table>
        </div>
        <br/>
        <div style="marign-top: 10px">
            <table>
                <tr>
                    <th>ACCESSORIES - DESCRIPTION</th>
                    <th>SUP NAME</th>
                    <th>RATE({{ $currency }})</th>
                    {{--                    <th>Unit (in number)</th>--}}
                    <th>CONS/ PC</th>
                    <th>W%</th>
                    <th>TOTAL QTY</th>
                    <th>TOTAL COST({{ $currency }})</th>
                    <th>%</th>
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
                                <td style="text-align: right">{{ number_format($item['rate'], 2) }}</td>
                                {{--                                <td></td>--}}
                                <td style="text-align: right">{{ number_format($item['cons'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['wastage'], 1) }}%</td>
                                <td style="text-align: right">{{ sprintf("%.2f", $item['total_qty']) }} {{ $uom }}</td>
                                <td style="text-align: right">{{ number_format($item['total_amount'], 2) }}</td>
                                <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                                @php
                                    $totalQty += sprintf("%.2f", $item['total_qty']);
                                    $totalAmount += $item['total_amount'];
                                    $totalPreCost += $item['pre_cost'];
                                @endphp
                            </tr>
                        @endforeach
{{--                        @if($uom == 'Kg')--}}

{{--                            <tr>--}}
{{--                                <th>SUB TOTAL TRIMS COST</th>--}}
{{--                                --}}{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td></td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalQty, 2) }} {{ $uom }}</td>--}}
{{--                                <td style="text-align: right">${{ number_format($totalAmount, 2) }}</td>--}}
{{--                                <td style="text-align: right">{{ number_format($totalPreCost, 2) }}%</td>--}}
{{--                            </tr>--}}
{{--                        @endif--}}
                    @endforeach
                @endif
                <tr>
                    <th style="background-color:#F3E353">TOTAL TRIMS/ACCESSORIES COST</th>
                    <td></td>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">
                        {{ number_format(collect($trimsCostData)->sum('total_amount'), 2) }}</th>
                    <th style="text-align: right">{{ number_format(collect($trimsCostData)->sum('pre_cost'), 2) }}%</th>
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
                    $smv = $mainPartData['smv'] ?? 0;
                    $machine_line = $mainPartData['machine_line'] ?? 0;
                    $sew_efficiency = $mainPartData['sew_efficiency'] ?? 0;
                    $epm = $smv !=0 ? ($fobValue * $inHandPreCost * 0.01) : 0;
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
                            <td style="text-align: right">{{ number_format($item['rate'], 2) }}</td>
                            {{--                            <td></td>--}}
                            <td></td>
                            <td></td>
                            <td></td>
                            <td style="text-align: right">{{ number_format($item['total_amount'], 2) }}</td>
                            <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <th style="background-color:#F3E353">TOTAL EMBELLISHMENT COST</th>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">{{ number_format($totalEmblCost, 2) }}</th>
                    <th style="text-align: right">{{ number_format($emblPreCost, 2) }}%</th>
                </tr>
                <tr>
                    <td>FINANCE/COMMERCIAL/LOGISTIC COST</td>
                    <td></td>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align: right">{{ number_format($totalCommercialCost, 2) }}</td>
                    <td style="text-align: right">{{ number_format($totalCommercialPreCost, 2) }}%</td>
                </tr>
                <tr>
                    <th style="background-color:#F3E353">FINANCE/COMMERCIAL COST</th>
                    <td></td>
                    <td></td>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">{{ number_format($totalCommercialCost, 2) }}</th>
                    <th style="text-align: right">{{ number_format($totalCommercialPreCost, 2) }}%</th>
                </tr>
                @foreach(collect($othersCost)->where('amount', '!=', 0) as $key => $item)
                    <tr>
                        <td>{{$key}}</td>
                        <td></td>
                        {{--                        <td></td>--}}
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right">{{ number_format($item['amount'], 2) }}</td>
                        <td style="text-align: right">{{ number_format($item['pre_cost'], 2) }}%</td>
                    </tr>
                @endforeach
                <tr>
                    <th style="background-color:#F3E353">OTHER COSTS</th>
                    <td></td>
                    <td></td>
                    <td></td>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <th style="text-align: right">{{ number_format($totalOtherCost, 2) }}</th>
                    <th style="text-align: right">{{ number_format($totalOtherPreCost, 2) }}%</th>
                </tr>
                {{--                <tr>--}}
                {{--                    <td colspan="8" height="5px"/>--}}
                {{--                </tr>--}}
                <tr>
                    <th style="background-color:#F3E353">GRAND TOTAL</th>
                    <td></td>
                    <td></td>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">{{ number_format($grandTotal, 2) }}</th>
                    <th style="text-align: right">{{ number_format($grandPreCost, 2) }}%</th>
                </tr>
                {{--                <tr>--}}
                {{--                    <td colspan="8" height="5px"/>--}}
                {{--                </tr>--}}
                <tr>
                    <th style="background-color:#F3E353">INHAND VALUE / PACK</th>
                    <td></td>
                    {{--                    <td></td>--}}
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <th style="text-align: right">{{ number_format($inHandAmount, 2) }}/DZ</th>
                    <th style="text-align: right">{{ number_format($inHandPreCost, 2) }}%</th>
                </tr>

                @if($type == 'view-1')
                    @include('merchandising::budget.reports.cost_breakdown_pdf_sub_table')
                @endif
                @if($type == 'view-2')
                    @include('merchandising::budget.reports.costing_breakdown_pdf2_sub_table')
                @endif
            </table>
            @if($type == 'view-akcl')
                @include('merchandising::budget.reports.cost_breakdown_view-akcl_sub_table')
            @endif
        </div>
    </div>


</div>
