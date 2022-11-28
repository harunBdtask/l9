<div>

    <div class="body-section" style="margin-top: 0px;">
        <table>
            <tr>
                {{--                color: #40A954;--}}
                <td colspan="4" class="text-center"><span
                        style="font-size: 12pt; font-weight: bold;">{{ factoryName() }}</span></td>
                <td rowspan="4" style="width: 200px">
                    <img src="{{ asset('Leadtime-logo.png') }}" alt="" width="200">

                </td>
            </tr>
            <tr>
                <th style="width:150px">Buyer:</th>
                <td>{{ $order['buyer_name'] }}</td>
                <th style="width:150px">Factory:</th>
                <td>{{ $order['assign_factory_name'] }}</td>
            </tr>
            <tr>
                <th style="width:150px">Team:</th>
                <td>{{ $order['team_name'] }}</td>
                <th style="width:150px">Buying agent:</th>
                <td>{{ $order['buying_agent'] }}</td>
            </tr>
            <tr>
                <th style="width:150px">Merchandiser:</th>
                <td>{{ $order['merchandiser_name'] }}</td>
                <th style="width:150px">Factory Merchant:</th>
                <td>{{ $order['factory_merchandiser_name'] }}</td>
            </tr>
        </table>

        @php
            $totalOrder = 0;
            $totalOrderAmount = 0;
        @endphp
        <div style="margin-top:10px">
            <table>
                <tr>
                    <th colspan="6" class="text-center">COST COMPONENT BREAKDOWN -FINAL</th>
                    <th colspan="3">LC/SC NO: <span>{{ $lc['lc_number'] }} </span></th>
                </tr>
                <tr>
                    <th>SL.</th>
                    <th>PO NUMBER</th>
                    <th>STYLE</th>
                    <th>DETAILS</th>
                    <th>QTY</th>
                    <th>SHIPMENT</th>
                    <th>UNIT PRICE</th>
                    <th>TOTAL PRICE</th>
                    <th>REMARKS</th>
                </tr>
                @forelse($order['poDetails'] as $key => $po)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $po['po_no'] }}</td>
                        <td>{{ $po['style_name'] }}</td>
                        <td>{{ $po['style_description'] }}</td>
                        <td>{{ $po['po_qty'] }} {{ $po['uom'] }}</td>
                        <td>{{ $po['shipment_date'] }}</td>
                        <td>{{ $po['unit_price'] }}</td>
                        @php
                            $total = ($po['po_qty']*$po['unit_price']);
                            $totalOrder += $po['po_qty'];
                            $totalOrderAmount += $total;
                        @endphp
                        <td>${{ number_format($total, 2) }}</td>
                        <td>{{ $po['remarks'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">No Data Found</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="4" class="text-center"><b>TOTAL QTY</b></td>
                    <td>{{ $totalOrder }} {{ $po['uom'] }}</td>
                    <td colspan="2"></td>
                    <td>${{ number_format($totalOrderAmount, 2) }}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div style="margin-top:10px">
            <table>
                <tr>
                    <th>(A)</th>
                    <th>FABRICS</th>
                    <th>CONS./DZ</th>
                    <th>ORDER QTY</th>
                    <th>EX %</th>
                    <th colspan="2">TOTAL REQ.</th>
                    <th>UNIT PRICE</th>
                    <th>TOTAL</th>
                    <th>SUPPLIER</th>
                </tr>
                @forelse($fabricDetails as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item['fabric_description'] }}</td>
                        <td>{{ $item['cons'] }}</td>
                        <td>{{ $totalOrder }}</td>
                        <td>{{ $item['extra'] }}</td>
                        <td>{{ $item['total_qty'] }}</td>
                        <td>{{ $item['fabricUom'] }}</td>
                        <td>${{ number_format($item['rate'], 2) }}</td>
                        <td>${{ number_format($item['total_amount'], 2) }}</td>
                        <td>{{ $item['supplier'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No Data</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="8" class="text-center"><b>TOTAL FABRIC COST</b></td>
                    <td>${{ number_format($fabricDetails->sum('total_amount'), 2) }}</td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div style="margin-top:10px">
            <table class="table table-responsive">
                <tr>
                    <th style="width: 5%">(B)</th>
                    <th style="width: 10.5%">ACCESSORIES</th>
                    <th style="width: 10.5%">CONS <br>/DZ</th>
                    <th style="width: 10.5%">ORDER QTY</th>
                    <th style="width: 10.5%">EX %</th>
                    <th style="width: 21%" colspan="2" class="text-center">TOTAL REQ.</th>
                    <th style="width: 10.5%">UNIT PRICE</th>
                    <th style="width: 10.5%">TOTAL</th>
                    <th style="width: 10.5%">SUPPLIER</th>
                </tr>
                @forelse($trimsDetails as $key => $item)
                    <tr>
                        <td>{{ ++$key }}</td>
                        <td>{{ $item['trims_description'] }}</td>
                        <td>{{ $item['cons'] }}</td>
                        <td>{{ $totalOrder }}</td>
                        <td>{{ $item['extra'] ?? 0 }}</td>
                        <td>{{ $item['total_qty'] }}</td>
                        <td>{{ $item['fabricUom'] }}</td>
                        <td>${{ number_format($item['rate'], 2) }}</td>
                        <td>${{ number_format($item['total_amount'], 2) }}</td>
                        <td style="font-size: 10px">{{ $item['supplier'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center">No Data</td>
                    </tr>
                @endforelse
                <tr>
                    <td colspan="8" class="text-center"><b>TOTAL TRIMS COST</b></td>
                    <td><b>${{ number_format($trimsDetails->sum('total_amount'),2) }} </b></td>
                    <td></td>
                </tr>
            </table>
        </div>


        @php
            $totalFabricCost = ($fabricDetails->sum('total_amount'));
            $totalTrimsCost  = ($trimsDetails->sum('total_amount'));
            $othersCost = ($otherCostings['sumOthers']);
            $grandSum = $totalFabricCost + $totalTrimsCost + $otherCostings['lab_inspection']  + $otherCostings['emb_cost_total']
            + $otherCostings['commercial_cost_total'] + $otherCostings['cm_cost_total'];
            $profit_loss = $totalOrderAmount - $grandSum;
        @endphp

        <div style="margin-top: 10px">
            <table>
                <tr>
                    <th>PHOTO OF SAMPLE</th>
                    <th colspan="3">TOTAL FAB COST</th>
                    <th>:</th>
                    <td>${{ number_format($fabricDetails->sum('total_amount'),2) }}</td>
                </tr>
                <tr>
                    <td rowspan="{{ 7 + count($otherCostings['emb_cost']) }}" style="width: 200px">
                        <img src="{{ asset('storage/' . $order['budgetImage']) }}" alt="" height="220px" width="180">
                    </td>
                    <th colspan="3">TOTAL ACCESS COST</th>
                    <th>:</th>
                    <td>${{ number_format($trimsDetails->sum('total_amount'), 2) }}</td>
                </tr>
                <tr>
                    <th colspan="3">LOCAL COST +INSPECTION+TEST ETC</th>
                    <th>:</th>
                    <td>${{ number_format($otherCostings['lab_inspection'], 2) }}</td>
                </tr>
                @if(count($otherCostings['emb_cost']) > 0 )
                    @foreach($otherCostings['emb_cost'] as $key => $item)
                        <tr>
                            <th>EMB-{{ $item['name'] ?? '' }}</th>
                            <td>{{  $item['per_pcs'] ?? 0 }} PCS</td>
                            <td>{{  $item['per_dzn'] ?? 0}} Dzn</td>
                            <td>{{ $item['emb_cost'] ?? 0 }}</td>
                            <td>${{ number_format($item['emb_cost_total'], 2) }}</td>
                        </tr>
                    @endforeach
                @endif

                {{--                <tr>--}}
                {{--                    <th>PRINT</th>--}}
                {{--                    <td>{{  '0 PCS' }}</td>--}}
                {{--                    <td>{{ '0 Dzn' }}</td>--}}
                {{--                    <td>{{ $otherCostings['printing_cost'] }}</td>--}}
                {{--                    <td>${{ number_format($otherCostings['printing_cost_total'], 2) }}</td>--}}
                {{--                </tr>--}}
                <tr>
                    <th>COMMERCIAL COST</th>
                    <td>{{ $otherCostings['orderQtyPerPcs'] . ' PCS' }}</td>
                    <td>{{ number_format($otherCostings['orderQtyPerDzn'], 2) . ' Dzn' }}</td>
                    <td>{{ $otherCostings['commercial_cost'] }}</td>
                    <td>${{ number_format($otherCostings['commercial_cost_total'], 2) }}</td>
                </tr>
                <tr>
                    <th>CM COST</th>
                    <td>{{ $otherCostings['orderQtyPerPcs'] . ' PCS' }}</td>
                    <td>{{ number_format($otherCostings['orderQtyPerDzn'], 2) . ' Dzn' }}</td>
                    <td>${{ number_format($otherCostings['cm_cost'], 2) }}</td>
                    <td>${{ number_format($otherCostings['cm_cost_total'], 2) }}</td>
                </tr>
                <tr>
                    <th colspan="4">TOTAL COST</th>
                    <td>${{ number_format($grandSum, 2)}}</td>
                </tr>
                <tr>
                    <th colspan="4">PROFIT/LOSS</th>
                    <td>${{ number_format($profit_loss, 2) }}</td>
                </tr>
                <tr>
                    <th colspan="4">LC VALUE/ SC VALUE</th>
                    <td>${{ number_format($lc['lc_sc_total_value'], 2) }}</td>
                </tr>
            </table>

        </div>

        <div class="col-md-7" style="margin-top: 10px;">
            <table>
                <tr>
                    <th>SL</th>
                    <th>BENEFICIARIES</th>
                    <th>PRODUCT</th>
                    <th>AMOUNT</th>
                    <th>REMARKS</th>
                </tr>
                @php($index = 0)
                @if(count($supplierWiseSales) > 0)
                    @foreach(collect($supplierWiseSales)->groupBy('type') as $key => $items)
                        @foreach($items as $item)
                            <tr>
                                <td>{{ ++$index }}</td>
                                <td>{{ $item['supplier'] }}</td>
                                @if($loop->first)
                                    <td rowspan="{{ count($items) }}">{{ $key }}</td>
                                @endif
                                <td>${{ number_format($item['total_amount'], 2) }}</td>
                                <td>{{ $item['remarks'] }}</td>

                            </tr>
                        @endforeach
                    @endforeach
                @endif
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ 'Commercial Cost' }}</td>
                    <td></td>
                    <td>${{ number_format($otherCostings['commercial_cost_total'], 2) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ ++$index }}</td>
                    <td>{{ 'CM Cost' }}</td>
                    <td></td>
                    <td>${{ number_format($otherCostings['cm_cost_total'], 2) }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ ++$index  }}</td>
                    <td>TOTAL COST</td>
                    <td></td>
                    <td>${{ $grandSum }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ ++$index  }}</td>
                    <td>PROFIT/LOSS</td>
                    <td></td>
                    <td>${{ $profit_loss }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td>{{ ++$index  }}</td>
                    <td>LC VALUE</td>
                    <td></td>
                    <td>${{ number_format($lc['lc_sc_total_value'], 2) }}</td>
                    <td></td>
                </tr>
            </table>
        </div>

    </div>

    <div style="margin-top: 16mm">
        <table class="borderless">
            <tbody>
            <tr>
                <td class="text-center"><u>Merchandiser</u></td>
                <td class='text-center'><u>Team Coordinator</u></td>
                <td class="text-center"><u>General Manager</u></td>
                <td class="text-center"><u>DMD</u></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
