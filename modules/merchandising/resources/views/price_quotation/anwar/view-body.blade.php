<div>

    <div>
        <table>
            <tr>
                <th colspan="4" style="text-align: left">Ref. Based on Model:</th>
            </tr>
            <tr>
                <th colspan="2" style="text-align: left">Buyer Name</th>
                <th colspan="2" style="text-align: left; border-left-style: hidden">{{ $buyer ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-align:left">Construction:</th>
                <th style="min-width: 200px; text-align:left">{{ $additional['construction'] ?? '' }}</th>
                <th style="text-align:left">Formation:</th>
                <th style="min-width: 200px; text-align:left">{{ $additional['formation'] ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-align:left">GSM:</th>
                <th style="text-align:left">{{ $additional['gsm'] ?? '' }}</th>
                <th style="text-align:left">GSM Range:</th>
                <th style="text-align:left">{{ $additional['gsm_range'] ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-align:left">Shrinkage Acceptability:</th>
                <th style="text-align:left">{{ $additional['shrinkage_acceptability'] ?? '' }}</th>
                <th style="text-align:left">Yarn Count:</th>
                <th style="text-align:left">{{ $additional['yarn_count'] ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-align:left">Price Based On Technical Sheet As of Dated:</th>
                <th style="text-align:left">{{ $additional['price'] ?? '' }}</th>
                <th style="text-align:left">Colour:</th>
                <th style="text-align:left">{{ $additional['color'] ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-align:left">Special Treatment:</th>
                <th style="text-align:left">{{ $additional['special_treatment'] ?? '' }}</th>
                <th style="text-align:left">Shipment Term:</th>
                <th style="text-align:left">{{ $additional['shipment_term'] ?? '' }}</th>
            </tr>
            <tr>
                <th style="text-align:left">Carton Packing:</th>
                <th style="text-align:left">{{ $additional['carton_packing'] ?? '' }}</th>
                <th style="text-align:left">Per Day Productivity:</th>
                <th style="text-align:left">{{ $additional['per_day_productivity'] ?? '' }}</th>
            </tr>
        </table>
    </div>

    @php
        $sizes = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->pluck('gmts_size')->unique()->values();
    @endphp

    <div style="margin-top: 10px">
        <table>
            <tr>
                <th>Sizes:</th>
                @foreach($sizes as $size)
                    <th>{{ $size }}</th>
                @endforeach
            </tr>
            <tr>
                <th>**</th>
                <th style="text-align: center" colspan="{{ count($sizes) }}">Measurement With Addition (If Any)</th>
            </tr>

            @forelse($fabric_costing as $index => $item )
                <tr>
                    <td>{{ $item['body_part_value'] ?? '' }}</td>
                    @foreach($sizes as $size)
                        @php
                            $sizeWiseItem = collect($item['fabricConsumptionForm'])->where('gmts_size', $size)->first();
                        @endphp
                        <td>{{ $sizeWiseItem['cons'] ?? 0 }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <th colspan="{{ count($sizes) + 2 }}" style="text-align: center">No Data Found</th>
                </tr>
            @endforelse
            <tr>
                <th colspan="{{ count($sizes) + 1 }}" style="height: 15px;border-left-style: hidden; border-right-style: hidden;"></th>
            </tr>
            <tr>
                <th>**</th>
                <th style="text-align: center" colspan="{{ count($sizes) }}">Measurement as of Technical Sheet</th>
            </tr>
            @forelse($fabric_costing as $index => $item )
                <tr>
                    <td>{{ $item['body_part_value'] ?? '' }}</td>
                    @foreach($sizes as $size)
                        @php
                            $sizeWiseItem = collect($item['fabricConsumptionForm'])->where('gmts_size', $size)->first();
                        @endphp
                        <td>{{ $sizeWiseItem['cons'] ?? 0 }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <th colspan="{{ count($sizes) + 2 }}" style="text-align: center">No Data Found</th>
                </tr>
            @endforelse


            <tr>
                <th colspan="{{ count($sizes) + 1 }}" style="height: 15px;border-left-style: hidden; border-right-style: hidden;"></th>
            </tr>

            <tr>
                <td>Grammage</td>
                @php
                    $grammage = $additional['grammage'] ?? 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $grammage }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Misc</td>
                @php
                    $mis = isset($additional['misc_breakdown']) ? collect($additional['misc_breakdown'])->sum() : 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $mis ?? 0 }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Wastage</td>
                @php
                    $wastage = isset($additional['wastage_breakdown']) ? collect($additional['wastage_breakdown'])->sum() : 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $wastage ?? 0  }}</td>
                @endforeach
            </tr>
            <tr>
                <td>Allowance</td>
                @foreach($sizes as $size)
                    <td>{{ $additional['allowance'] ?? 0  }}</td>
                @endforeach
            </tr>
            <tr>
                <th>Consumption</th>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1
                    @endphp
                    <td>{{ number_format($sizeWiseCons, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <th colspan="{{ count($sizes) + 1 }}" style="height: 15px;border-left-style: hidden; border-right-style: hidden;"></th>
            </tr>

            <tr>
                <td>Yarn Rate/Kg</td>
                @php
                    $yarn_rate = $additional['yarn_rate'] ?? 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{  $yarn_rate }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Knitting Rate/Kg</td>
                @php
                    $knitting_rate = $additional['knitting_rate'] ?? 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $knitting_rate  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Regular Dyeing Rate/Kg</td>
                @php
                    $regular_dyeing_rate = $additional['regular_dyeing_rate'] ?? 0 ;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $regular_dyeing_rate }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Stenter + Slitting Rate/Kg</td>
                @php
                    $stenter_slitting_rate = $additional['stenter_slitting_rate'] ?? 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $stenter_slitting_rate  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Single Enzyme Rate/Kg</td>
                @php
                    $single_enzyme_rate = $additional['single_enzyme_rate'] ?? 0;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $single_enzyme_rate  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Others Rate/Kg</td>
                @php
                    $others_rate_kg = $additional['others_rate_kg'] ?? 0 ;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $others_rate_kg  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Special Treatment Rate/Kg</td>
                @php
                    $special_treatment_rate = $additional['special_treatment_rate'] ?? 0 ;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $special_treatment_rate }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Others (if Any) Rate</td>
                @php
                    $others_if_any = $additional['others_if_any'] ?? 0 ;
                @endphp
                @foreach($sizes as $size)
                    <td>{{ $others_if_any }}</td>
                @endforeach
            </tr>

            <tr>
                <th colspan="{{ count($sizes) + 1 }}" style="height: 15px;border-left-style: hidden; border-right-style: hidden;"></th>
            </tr>

            <tr>
                <td>Yarn Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $yarnCost = ($sizeWiseCons * $yarn_rate);
                        $totalSizeWiseSum['yarn_cost'][$size] = sprintf('%0.4f', $yarnCost);
                    @endphp
                    <td>{{ number_format($yarnCost, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Knitting Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $knittingCost = ($sizeWiseCons * $knitting_rate);
                        $totalSizeWiseSum['knitting_cost'][$size] = sprintf('%0.4f', $knittingCost);
                    @endphp
                    <td>{{ number_format($knittingCost, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Dyeing + Finishing Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $regularDeyingCost = ($sizeWiseCons * $regular_dyeing_rate);
                        $totalSizeWiseSum['regular_deying_cost'][$size] = sprintf('%0.4f', $regularDeyingCost);
                    @endphp
                    <td>{{ number_format($regularDeyingCost, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Compacting Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $compactingCost = ($sizeWiseCons * $stenter_slitting_rate);
                        $totalSizeWiseSum['compacting_cost'][$size] = sprintf('%0.4f', $compactingCost);

                    @endphp
                    <td>{{ number_format($compactingCost, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Stenter Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $stenterCost = ($sizeWiseCons * $single_enzyme_rate);
                        $totalSizeWiseSum['stenter_cost'][$size] = sprintf('%0.4f', $stenterCost);
                    @endphp
                    <td>{{ number_format($stenterCost, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>slitting Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $slittingCost = ($sizeWiseCons * $others_rate_kg);
                        $totalSizeWiseSum['slitting_cost'][$size] = sprintf('%0.4f', $slittingCost);
                    @endphp
                    <td>{{ number_format($slittingCost, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Special Treatment Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $specialTreatmentCost = ($sizeWiseCons * $special_treatment_rate);
                        $totalSizeWiseSum['special_treatment_cost'][$size] = sprintf('%0.4f', $specialTreatmentCost);
                    @endphp
                    <td>{{ number_format($specialTreatmentCost, 4) }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Others (If Any) Cost</td>
                @foreach($sizes as $size)
                    @php
                        $sizeWiseSum = collect($fabric_costing)->pluck('fabricConsumptionForm')->collapse()->where('gmts_size', $size)->sum('cons') ?? 0;
                        $sizeWiseCons = (((((($sizeWiseSum * 2 ) * $grammage)/10000)/1000)*12) * 1.05) * 1.1;
                        $othersIfAnyCost = ($sizeWiseCons * $others_if_any);
                        $totalSizeWiseSum['other_if_any_cost'][$size] = sprintf('%0.4f', $othersIfAnyCost);
                    @endphp
                    <td>{{ $othersIfAnyCost  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>CM</td>
                @foreach($sizes as $size)
                    @php
                        $cmCost = $additional['cm_cost'] ?? 0;
                        $totalSizeWiseSum['cm_cost'][$size] = sprintf('%0.4f', $cmCost);
                    @endphp
                    <td>{{ $cmCost  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Trims</td>
                @foreach($sizes as $size)
                    @php
                        $trims = isset($additional['trims_breakdown']) ? collect($additional['trims_breakdown'])->sum() : 0;
                        $totalSizeWiseSum['trims_cost'][$size] = sprintf('%0.4f', $trims);
                    @endphp
                    <td>{{ $trims ?? 0  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Collar Cuff Making Cost/Dozn</td>
                @foreach($sizes as $size)
                    @php
                        $collarCuffMakingCost = $additional['collar_cuff_making_cost'] ?? 0;
                        $totalSizeWiseSum['collar_cuff_making_cost'][$size] = sprintf('%0.4f', $collarCuffMakingCost);
                    @endphp
                    <td>{{ $collarCuffMakingCost  }}</td>
                @endforeach
            </tr>

            <tr>
                <td>New Wages</td>
                @foreach($sizes as $size)
                    @php
                        $newWagesCost = $additional['new_wages'] ?? 0 ;
                        $totalSizeWiseSum['new_wages_cost'][$size] = sprintf('%0.4f', $newWagesCost);
                    @endphp
                    <td>{{ $newWagesCost }}</td>
                @endforeach
            </tr>

            <tr>
                <td>Burgain Buffer</td>
                @foreach($sizes as $size)
                    @php
                        $burgainBufferCost = $additional['bargain_buffer'] ?? 0;
                        $totalSizeWiseSum['burgain_buffer_cost'][$size] = sprintf('%0.4f', $burgainBufferCost);
                    @endphp
                    <td>{{  $burgainBufferCost }}</td>
                @endforeach
            </tr>

            <tr>
                <th colspan="{{ count($sizes) + 1 }}" style="height: 15px;"></th>
            </tr>

            <tr>
                <th>Price per dozen</th>
                @foreach($sizes as $size)
                    @php
                        $price_per_dzn = collect($totalSizeWiseSum)->values()->pluck($size)->sum() ?? 0;
                    @endphp
                    <td>{{ number_format($price_per_dzn, 4)  }}</td>
                @endforeach
            </tr>

            <tr>
                <th>Per Pcs</th>
                @foreach($sizes as $size)
                    @php
                        $price_per_pcs = collect($totalSizeWiseSum)->values()->pluck($size)->sum() / 12 ?? 0;
                    @endphp
                    <td>{{ number_format($price_per_pcs, 4) }}</td>
                @endforeach
            </tr>
        </table>
    </div>

    <div style="margin-top: 10px">
        <table>
            <tr>
                <th colspan="2" style="text-align: center">Wastage % Breakdown</th>
                <th colspan="2" style="text-align: center">Misc. % Breakdown</th>
                <th colspan="4" style="text-align: center">Trims Breakdown</th>
            </tr>
            <tr>
                <td rowspan="2">Knitting + Dyeing + Cutting + Sewing + Finishing</td>
                <td rowspan="2" style="width: 75px; text-align: right">{{ $additional['wastage_breakdown']['knitting_dyeing_cutting_sewing_finishing'] ?? 0 }}%</td>
                <td>Rib</td>
                <td style="width: 75px; text-align: right; text-align: right">{{ $additional['misc_breakdown']['rib'] ?? 0 }}%</td>
                <td>Printed Gum Tape</td>
                <td style="width: 75px; text-align: right">{{ $additional['trims_breakdown']['printed_gum_tape'] ?? 0 }}</td>
                <td>Print</td>
                <td style="width: 75px; text-align: right">{{ $additional['trims_breakdown']['print'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Cuff</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['cuff'] ?? 0 }}%</td>
                <td>Satin Label</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['satin_label'] ?? 0 }}</td>
                <td>3 Buttons</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['three_buttons'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Print</td>
                <td style="text-align: right">{{ $additional['wastage_breakdown']['print'] ?? 0 }}%</td>
                <td>Placket</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['placket'] ?? 0 }}%</td>
                <td>Blister Poly Bag</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['blister_poly_bag'] ?? 0 }}</td>
                <td>Deo String</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['deo_string'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Embroidery</td>
                <td style="text-align: right">{{ $additional['wastage_breakdown']['embroidiery'] ?? 0 }}%</td>
                <td>Pocket</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['pocket'] ?? 0 }}%</td>
                <td>Barcode</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['barcode'] ?? 0 }}</td>
                <td>Card Borad</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['card_board'] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Special Treatment</td>
                <td style="text-align: right">{{ $additional['wastage_breakdown']['special_treatment'] ?? 0 }}%</td>
                <td>Back Tape</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['back_tape'] ?? 0 }}%</td>
                <td>Sewing Thread</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['sweing_thread'] ?? 0 }}</td>
                <td>Tissue Paper</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['tissue_paper'] ?? 0 }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right">0%</td>
                <td>SS Tape</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['ss_tape'] ?? 0 }}%</td>
                <td>Elastic</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['elastic'] ?? 0 }}</td>
                <td>Hologram</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['hologram'] ?? 0 }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right">0%</td>
                <td>Back Moon</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['back_moon'] ?? 0 }}%</td>
                <td>Stiffners</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['stiffners'] ?? 0 }}</td>
                <td>Carton</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['carton'] ?? 0 }}</td>
            </tr>
            <tr>
                <td></td>
                <td style="text-align: right">0%</td>
                <td>Others If Any)</td>
                <td style="text-align: right">{{ $additional['misc_breakdown']['others'] ?? 0 }}%</td>
                <td>Hanger Ring</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['hanger_ring'] ?? 0 }}</td>
                <td>Fusing Tape</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['fusing_tape'] ?? 0 }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <th style="text-align: right">{{ $wastage ?? 0 }}%</th>
                <th>Total</th>
                <th style="text-align: right">{{ $mis ?? 0}}%</th>
                <td>Hangtag</td>
                <td style="text-align: right">{{ $additional['trims_breakdown']['hang_tag'] ?? 0 }}</td>
                <th>Total</th>
                <th style="text-align: right">{{ $trims ?? 0 }}</th>
            </tr>
        </table>
    </div>

</div>
