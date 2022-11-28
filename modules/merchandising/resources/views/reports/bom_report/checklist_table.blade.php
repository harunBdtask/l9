<style>
    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>

<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
               id="fixTable">
            <tr>
                <th style="background-color: #cae4f1;">Buyer</th>
                <td>{{ $buyer['name'] ?? ''}}</td>

                <th style="background-color: #cae4f1;">Garments Item</th>
                <td>{{ $items ?? ''}}</td>
                <th style="background-color: #cae4f1;">Total Style Value</th>
                <td>{{ ($order['pq_qty_sum'] ?? 0.00)*(number_format($costing_per_pcs ?? 0, 4) ?? 0.00) }}</td>
                <td rowspan="4" style="text-align: center">
                    @if(file_exists(asset("storage/$image")))
                        <img src="{{ asset("storage/$image")  }}" alt="" height="50" width="50">
                    @else
                        <img src="{{ asset('/images/no_image.jpg') }}" alt="" height="50" width="50">
                    @endif
                </td>
            </tr>

            <tr>
                <th style="background-color: #cae4f1;"><b>Style Name</b></th>
                <td>{{ $style_name }}</td>

                <th style="background-color: #cae4f1;"><b>Costing Per</b></th>
                <td>{{ $costingPer[(int)$costing_per] ?? '' }}</td>


                <th style="background-color: #cae4f1;"><b>Plan Cut Qty</b></th>
                <td>{{ $plan_cut_qty ?? 0.00 }}</td>
            </tr>

            <tr>
                <th style="background-color: #cae4f1;"><b>PO Qty</b></th>
                <td>{{ $order['pq_qty_sum'] ?? 0.00 }} {{ $order_uom_id ? $styleUom[(int)$order_uom_id]  : '' }}</td>

                <th style="background-color: #cae4f1;"><b>Price Per Unit</b></th>
                <td>{{ number_format($costing_per_pcs, 4) ?? 0.00 }}</td>

                <th style="background-color: #cae4f1;"><b>Shipment Date</b></th>
                <td>{{ formatDate($shipment_date) ?? '' }}</td>
            </tr>
            <tr>
                <th style="background-color: #cae4f1;">PO No</th>
                <td colspan="5" class="text-left" style ="text-align:left;padding-left: 5%;">{{ $po_no }}</td>
            </tr>
        </table>
        <div style="margin-top: 15px;">
        </div>
        <div style="margin-top: 15px;">
            @php
                $amount_all = $total_amt_all = [];
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
                $total_amount = $amount_all[] = $knit_amount_calculate + $woven_amount_calculate;                
                $grand_total_amount = $total_amt_all[]= $knit_total_amount_calculate + $woven_total_amount_calculate;
            @endphp
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <tr>
                    <th>Sl</th>
                    <th class="text-center">Item Description
                    <th class="text-center">Consumption</th>
                    <th class="text-center">Ex-Purchase</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-center">UOM</th>
                    <th class="text-center">Rate (USD)</th>
                    <th class="text-center">Amount (USD)</th>
                    <th class="text-center">Total Amount</th>
                    <th class="text-center">Remarks</th>
                </tr>
                @php $sl = 1; @endphp
                @if(count($knit_fabrics) > 0)
                    @foreach($knit_fabrics as $knit_key=>$knit_fabric)
                    @php
                        $process_loss = $knit_fabric['greyConsForm']['calculation']['process_loss_avg'];
                    @endphp
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td class="text-left">
                                {{ $knit_fabric['body_part_value'] ?? '' }},
                                {{ $knit_fabric['color_type_value'] }},
                                {{ $knit_fabric['gsm'] }},
                                {{ $knit_fabric['fabric_composition_value'] }}
                            </td>                           
                            <td class="text-right">{{ $knit_fabric['grey_cons'] ??  0.00 }}</td>
                            <td class="text-right">{{ ($process_loss? floatval($process_loss): 0) }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_total_quantity'] ?? 0 }}</td>
                            @php
                                $knitUom = $uom[(int)$knit_fabric['uom']];
                            @endphp
                            <td class="text-right">{{ $knitUom ? $knitUom : ''}}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_rate'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['grey_cons_total_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $knit_fabric['remarks'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @endif
                @if(count($woven_fabrics) > 0)
                    @foreach($woven_fabrics as $woven_key=>$woven_fabric)
                    @php
                        $process_loss = $woven_fabric['greyConsForm']['calculation']['process_loss_avg'];
                    @endphp
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td>
                                {{ $woven_fabric['body_part_value'] }},
                                {{ $woven_fabric['color_type_value'] }},
                                {{ $woven_fabric['gsm'] }},
                                {{ $woven_fabric['fabric_composition_value'] }}
                            </td>
                            <td class="text-right">{{ $woven_fabric['grey_cons'] ?? 0.00 }}</td>
                            <td class="text-right">{{ ($process_loss? floatval($process_loss): 0) }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_total_quantity'] ?? 0 }}</td>
                            @php
                                $wovenUom = $uom[(int)$woven_fabric['uom']] ?? '';
                            @endphp
                            <td class="text-right">{{ $wovenUom ? $wovenUom : '' }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_rate'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['grey_cons_total_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $woven_fabric['remarks'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @endif
                @php
                  $yarn_qty_sum = collect($yarn_costing)->sum('cons_qty');
                  $yarn_rate_sum = collect($yarn_costing)->sum('rate');
                  $yarn_amount_sum = $amount_all[] = collect($yarn_costing)->sum('amount');
                  $yarn_total_qty_sum = 0;
                  $yarn_total_amount_sum = 0;
                @endphp

                @if( count($yarn_costing) >0)
                    @foreach($yarn_costing as $yarn_key=>$yarn_value)
                    <tr>
                        <td>{{ $sl++ }}</td>
                        <td class="text-left">
                            {{ $yarn_value['fabric_description'] ?? ''}}
                            {{ $yarn_value['count_value'] ?? ''}},
                            {{ $yarn_value['yarn_composition_value'] ?? '' }},
                            {{ $yarn_value['type'] ?? '' }}
                        </td>
                         <td></td>                          
                         <td class="text-right">0</td>                          
                        
                        <td class="text-right">
                            {{ $yarn_value['cons_qty'] ?? '' }}
                        </td>
                        <td></td>
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
                            $totalyarnamount = $total_amt_all[] = $totalyarnqty * $yarnAmount;
                            $yarn_total_qty_sum += $totalyarnqty;
                            $yarn_total_amount_sum += $totalyarnamount;
                        @endphp
                        <td class="text-right">{{   number_format($totalyarnamount, 4) }}</td>
                        <td class="text-right">{{ $yarn_value['remarks'] ?? '' }}</td>
                    </tr>
                    @endforeach
                @endif

                @php
                    $trims_cons_gmts = collect($trims_details)->sum('cons_gmts');
                    $trims_rate = collect($trims_details)->sum('rate');
                    $trims_amount = $amount_all[] = collect($trims_details)->sum('amount');
                    $total_trims_qty_sum = collect($trims_details)->pluck('total_quantity')->map(function ($val){
                        return (float)$val;
                    })->sum();
                
                    $total_trims_amount_sum = $total_amt_all[] =  collect($trims_details)->pluck('total_amount')->map(function ($val){
                        return (float)$val;
                    })->sum();
                @endphp

                @if( count($trims_details) >0)
                    @foreach($trims_details as $trim_key=>$trim_value)
                    @php 
                        $ex_cons_percent = $trim_value['breakdown']['details'][0]['ex_cons_percent'];
                    @endphp
                        <tr>
                            <td>{{ $sl++ }}</td>
                            <td class="text-left">{{ $trim_value['group_name'] ?? '' }} {{ $trim_value['description'] ?? ''}}</td>
                            <td class="text-right">{{ $trim_value['cons_gmts'] ?? '' }}</td>
                            <td class="text-right">{{ ($ex_cons_percent? floatval($ex_cons_percent):0) }}</td>
                            <td class="text-right">{{ $trim_value['total_quantity'] ?? 0 }}</td>
                            <td class="text-right">{{ $trim_value['cons_uom_value'] ?? ''}}</td>
                            <td class="text-right">{{ $trim_value['rate'] ?? ''}}</td>
                            <td class="text-right">{{ $trim_value['amount'] ?? '' }}</td>
                            <td class="text-right">{{ $trim_value['total_amount'] ?? 0.00 }}</td>
                            <td class="text-right">{{ $trim_value['remarks'] ?? '' }}</td>
                        </tr>
                    @endforeach
                @endif
                <tr>
                    <td colspan="7" class="text-right"><b>Total</b></td>
                    <td class="text-right"><b>{{ number_format(array_sum($amount_all), 4) }}</b></td>
                    <td class="text-right"><b>{{ number_format(array_sum($total_amt_all), 4) }}</b></td>
                    <td></td>
                </tr>

        </table>
    </div>
</div>
