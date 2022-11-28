<div style="margin-top: 10px;">
    <div style="width: 60%; float: left">
        <table>
            <tr>
                <th align="left">PQ ID</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->quotation_id }}</td>
            </tr>
            <tr>
                <th align="left">DATE</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ date_format(date_create($priceQuotation->quotation_date), 'd-M-Y') }}</td>
            </tr>
            <tr>
                <th align="left">SEASON</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->season->season_name }}</td>
            </tr>
            <tr>
                <th align="left">{{ localizedFor('Style') }}</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->style_name }}</td>
            </tr>
            <tr>
                <th align="left">BUYER/BUYING AGENT</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->buyer->name }}</td>
            </tr>
            <tr>
                <th align="left">COUNTRY OF ORIGIN</th>
                <td colspan="{{ count($priceQuotation->items) }}">Bangladesh</td>
            </tr>
            <tr>
                <th align="left">FACTORY</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ factoryName() }}</td>
            </tr>
            <tr>
                <th align="left">ITEM</th>
                @foreach($priceQuotation->items as $value)
                    <td>{{ $value }}</td>
                @endforeach
            </tr>
            @if($priceQuotation->style_uom === 2)
                <tr>
                    <th align="left">SMV</th>
                    @foreach(collect($priceQuotation->item_details)->slice(0,-1)->pluck('smv')->reverse() as $value)
                        <td>{{ $value }}</td>
                    @endforeach
                </tr>
            @endif
            <tr>
                <th align="left">ITEM DESCRIPTION</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->style_desc }}</td>
            </tr>
            <tr>
                <th align="left">QUANTITY</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->offer_qty }}</td>
            </tr>
            <tr>
                <th align="left">SIZE RANGE</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->season_grp }}</td>
            </tr>
            <tr>
                <th align="left">COLOUR RANGE</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ $priceQuotation->colorRange->name ?? '' }}</td>
            </tr>
            <tr>
                <th align="left">TOTAL SMV</th>
                <td colspan="{{ count($priceQuotation->items) }}">{{ number_format($priceQuotation->item_details[count($priceQuotation->item_details) - 1]['total_smv'] ?? 0, 4) }}</td>
            </tr>
        </table>
    </div>
    <div style="width: 30%; float: right; padding: 20px">
        <div class="col-sm-10">
            @if(($priceQuotation->image) && file_exists(storage_path('app/public/price_quotation_images/' . $priceQuotation->image)))
                <img style="height: auto; width: 100%; object-fit: contain" alt=""
                     src="{{ asset("storage/price_quotation_images/$priceQuotation->image")  }}"
                     class="img-fluid">
            @else
                <img style="height: 150px; width: 150px; object-fit: contain" src="{{ asset('/images/no_image.jpg') }}"
                     alt="No image found">
            @endif
        </div>
    </div>
</div>

<div>
    <table class="borderless">
        <tr>
            <th></th>
        </tr>
    </table>
</div>

<div style="margin-top: 15px">
    <table>
        <thead>
        <tr>
            <th style="width: 16%">FABRIC</th>
            <th style="width: 60%">QUALITY</th>
            <th style="width: 16%">NOM. SUP</th>
            {{--            <th style="width: 3%">DIA</th>--}}
            {{--            <th style="width: 3%">GSM</th>--}}
            <th style="width: 2%">UNIT/PC</th>
            <th style="width: 2%">UNIT</th>
            <th style="width: 2%">CONS/PC</th>
            <th style="width: 2%">AMT/USD</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($fabric_costing['fabricForm']))
            @foreach($fabric_costing['fabricForm'] as $fabricCost)
                <tr>
                    <td style="text-align: left">{{ $fabricCost['body_part_value'] ?? '' }}</td>
                    <td style="text-align: left">
                        {{ $fabricCost['fabric_composition_value'] ?? '' }}
                        {{ isset($fabricCost['code'] ) ? ',CODE-'. $fabricCost['code'] : '' }}
                        {{ isset($fabricCost['fabricConsumptionForm']) ? ',Width/Dia-' . collect($fabricCost['fabricConsumptionForm'])->first()['dia'] : ''}}
                        {{ isset($fabricCost['gsm'])  ? ',GSM-'. $fabricCost['gsm'] : '' }}
                    </td>
                    <td style="text-align: left">{{ $fabricCost['supplier_value'] ?? '' }}</td>
                    {{--                    <td style="text-align: left">{{ $fabricCost['fabricConsumptionForm'] ?  collect($fabricCost['fabricConsumptionForm'])->first()['dia'] : ''}}</td>--}}
                    {{--                    <td style="text-align: left">{{ $fabricCost['gsm'] ?? '' }}</td>--}}
                    <td style="text-align: right">{{ $fabricCost['rate'] ?? '' }}</td>
                    <td style="text-align: center">{{ \SkylarkSoft\GoRMG\Merchandising\Models\CostingDetails::FABRIC_UOM[$fabricCost['uom']] ?? '' }}</td>
                    <td style="text-align: right">{{ $fabricCost['fabric_cons'] ? number_format($fabricCost['fabric_cons'], 4) : '' }}</td>
                    <td style="text-align: right">{{ $fabricCost['amount'] ? number_format($fabricCost['amount'], 4) : '' }}</td>
                </tr>
            @endforeach
            <tr>
                <th style="text-align: right" colspan="5">TOTAL FABRIC COST</th>
                <td style="text-align: right">
                    <b>{{ number_format((float)collect($fabric_costing['fabricForm'] )->sum('fabric_cons'), 4) }}</b>
                </td>
                <td style="text-align: right">
                    <b>{{ number_format((float)collect($fabric_costing['fabricForm'])->sum('amount'), 4) }}</b>
                </td>
            </tr>
        @endif
        </tbody>
    </table>
</div>

<div style="margin-top: 10px">
    <table>
        <thead>
        <tr>
            <th style="width: 20%">EMBL.</th>
            <th style="width: 70%">PLACEMENT</th>
            <th style="width: 3%">UNIT/PC</th>
            <th style="width: 3%">QTY</th>
            <th style="width: 3%">AMT/USD</th>
        </tr>
        </thead>
        <tbody>
        @foreach($embellishment_cost['details'] ?? [] as $embellishment)
            <tr>
                <td style="text-align: left">{{ $embellishment['name'] }}</td>
                <td style="text-align: left">{{ $embellishment['type'] }}</td>
                <td style="text-align: right">{{ number_format((float)$embellishment['rate'], 4) }}</td>
                <td style="text-align: right">{{ $embellishment['cons_per_dzn'] }}</td>
                <td style="text-align: right">{{ number_format((float)$embellishment['amount'], 4) }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="text-align: right" colspan="4">TOTAL</th>
            <td style="text-align: right">
                <b>{{ isset($embellishment_cost['details']) ? number_format((float)collect($embellishment_cost['details'])->sum('amount'), 4) : 0}}</b>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 10px">
    <table>
        <thead>
        <tr>
            <th style="width: 20%">WASH</th>
            <th style="width: 70%">PLACEMENT</th>
            <th style="width: 3%">UNIT/PC</th>
            <th style="width: 3%">QTY</th>
            <th style="width: 3%">AMT/USD</th>
        </tr>
        </thead>
        <tbody>
        @foreach($wash_cost['details'] ?? [] as $wash)
            <tr>
                <td style="text-align: left">{{ $wash['name'] }}</td>
                <td style="text-align: left">{{ $wash['type'] }}</td>
                <td style="text-align: right">{{ number_format((float)$wash['rate'], 4) }}</td>
                <td style="text-align: right">{{ $wash['cons_per_dzn'] }}</td>
                <td style="text-align: right">{{ number_format((float)$wash['amount'], 4) }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="text-align: right" colspan="4">TOTAL</th>
            <td style="text-align: right">
                <b>{{ isset($wash_cost['calculation']['amount_sum']) ? number_format((float)$wash_cost['calculation']['amount_sum'], 4) : 0 }}</b>
            </td>
        </tr>
        </tbody>
    </table>
</div>

<div style="margin-top: 10px">
    <table>
        <thead>
        <tr>
            <th style="width: 20%">SEWING TRIMS</th>
            <th style="width: 45%">PLACEMENT</th>
            <th style="width: 25%">NOM. SUP</th>
            <th style="width: 3%">UNIT</th>
            <th style="width: 3%">UNIT/PC</th>
            <th style="width: 3%">QTY</th>
            <th style="width: 3%">AMT/USD</th>
        </tr>
        </thead>
        <tbody>
        @foreach(collect($trims_costing)->where('type', 'Sewing Trims') as  $trim)
            <tr>
                <td style="text-align: left">{{ $trim['group_name'] }}</td>
                <td style="text-align: left">{{ $trim['item_description'] }}</td>
                <td style="text-align: left">{{ $trim['nominated_supplier_value'] }}</td>
                <td style="text-align: center">{{ $trim['cons_uom_value'] }}</td>
                <td style="text-align: right">{{ number_format((float)$trim['rate'], 4) }}</td>
                <td style="text-align: right">{{ $trim['cons_gmts'] }}</td>
                <td style="text-align: right">{{ number_format($trim['amount'], 4) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>


<div style="margin-top: 10px">
    <table>
        <thead>
        <tr>
            <th style="width: 20%">PACK. & FINISH</th>
            <th style="width: 45%">PLACEMENT</th>
            <th style="width: 25%">NOM. SUP.</th>
            <th style="width: 3%">UNIT</th>
            <th style="width: 3%">UNIT/PC</th>
            <th style="width: 3%">QTY</th>
            <th style="width: 3%">AMT/USD</th>
        </tr>
        </thead>
        <tbody>
        @foreach(collect($trims_costing)->where('type', 'Finishing Trims') as  $trim)
            <tr>
                <td style="text-align: left">{{ $trim['group_name'] ?? '' }}</td>
                <td style="text-align: left">{{ $trim['item_description'] ?? '' }}</td>
                <td style="text-align: left">{{ $trim['nominated_supplier_value'] ?? '' }}</td>
                <td style="text-align: center">{{ $trim['cons_uom_value'] ?? '' }}</td>
                <td style="text-align: right">{{ $trim['rate'] ? number_format((float)$trim['rate'], 4) : '' }}</td>
                <td style="text-align: right">{{ $trim['cons_gmts'] ?? '' }}</td>
                <td style="text-align: right">{{ $trim['amount'] ? number_format($trim['amount'], 4) : '' }}</td>
            </tr>
        @endforeach
        <tr>
            <th style="text-align: right" colspan="6">TOTAL TRIMS & ACCESSORIES COST</th>
            @php
                $finish_costing_sum = collect($trims_costing->where('type', 'Finishing Trims'))->sum('amount');
                $sewing_costing_sum = collect($trims_costing)->where('type', 'Sewing Trims')->sum('amount');
                $total = $finish_costing_sum + $sewing_costing_sum;
            @endphp
            <td style="text-align: right">{{ number_format($total, 4) }}</td>
        </tr>
        <tr>
            <th style="text-align: right" colspan="6">TOTAL MATERIAL COST</th>
            @if($trims_costing)
                {{-- @php $totalMaterialCost = (collect($trims_costing)->sum('amount')) + (collect(isset($fabric_costing['fabricForm']) ? $fabric_costing['fabricForm'] : [])->sum('amount')) + (collect($embellishment_cost['details'] ?? [])->sum('amount')) @endphp --}}
                @php $totalMaterialCost = (collect(isset($fabric_costing['fabricForm']) ? $fabric_costing['fabricForm'] : [])->sum('amount')) @endphp
            @else
                @php $totalMaterialCost = 0 @endphp
            @endif
            <td style="text-align: right">{{ number_format($totalMaterialCost, 4 ) }}</td>
        </tr>
        @php

            @endphp
        @if($priceQuotation->comml_cost && (float)$priceQuotation->comml_cost !== (float) 0 )
            <tr>
                <th style="text-align: right" colspan="6">COMMERCIAL COST</th>
                <td style="text-align: right">{{ number_format($priceQuotation->comml_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->lab_cost && (float)$priceQuotation->lab_cost !== (float) 0 )
            <tr>
                <th style="text-align: right" colspan="6">LAB TEST</th>
                <td style="text-align: right">{{ number_format($priceQuotation->lab_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->inspect_cost && (float)$priceQuotation->inspect_cost !== (float) 0 )
            <tr>
                <th style="text-align: right" colspan="6">INSPECTION COST</th>
                <td style="text-align: right">{{ number_format($priceQuotation->inspect_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->cm_cost && (float)$priceQuotation->cm_cost !== (float) 0)
            <tr>
                <th style="text-align: right" colspan="6">CUT & MAKE COST</th>
                {{--                                    @if(isset($priceQuotation->cm_cost))--}}
                {{--                                        @php $priceQuotationCMCost = $priceQuotation->cm_cost @endphp--}}
                {{--                                    @else--}}
                {{--                                        @php $priceQuotationCMCost = 0 @endphp--}}
                {{--                                    @endif--}}
                <td style="text-align: right">{{ number_format($priceQuotation->cm_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->freight_cost && (float)$priceQuotation->freight_cost !== (float) 0)
            <tr>
                <th style="text-align: right" colspan="6">FREIGHT COST</th>
                <td style="text-align: right">{{ number_format($priceQuotation->freight_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->currier_cost && (float)$priceQuotation->currier_cost !== (float) 0)
            <tr>
                <th style="text-align: right" colspan="6">CURRIER COST</th>
                <td style="text-align: right">{{ number_format($priceQuotation->currier_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->certif_cost && (float)$priceQuotation->certif_cost !== (float) 0)
            <tr>
                <th style="text-align: right" colspan="6">CERTIF. COST</th>
                <td style="text-align: right">{{ number_format($priceQuotation->certif_cost, 4 ) }}</td>
            </tr>
        @endif
        @if($priceQuotation->common_oh && (float)$priceQuotation->common_oh !== (float) 0)
            <tr>
                <th style="text-align: right" colspan="6">SOURCE TAX</th>
                <td style="text-align: right">{{ number_format($priceQuotation->common_oh, 4 ) }}</td>
            </tr>
        @endif
        @php
            $commissionCost = $commission_cost['calculation']['total'] ?? 0;
        @endphp
        <tr>
            <th style="text-align: right" colspan="6">COMMISSION</th>
            <td style="text-align: right">{{ number_format($commissionCost, 4) }}</td>
        </tr>
        <tr>
            <th style="text-align: right" colspan="6">TOTAL COST</th>
            {{--                <td style="text-align: right">{{ number_format($commercialCostAmountSum + $priceQuotationCMCost + $totalMaterialCost, 4) }}</td>--}}
            {{-- <td style="text-align: right">{{ number_format($priceQuotation->prod_cost_dzn + $commissionCost, 4) }}</td> --}}
            @php
                $totalCost = $priceQuotation->fab_cost + $priceQuotation->trims_cost
                + $priceQuotation->embl_cost + $priceQuotation->gmt_wash
                + $priceQuotation->comml_cost + $priceQuotation->lab_cost
                + $priceQuotation->inspect_cost + $priceQuotation->cm_cost
                + $priceQuotation->freight_cost + $priceQuotation->currier_cost
                + $priceQuotation->certif_cost + $priceQuotation->commi_dzn + $priceQuotation->common_oh;
            @endphp
            <td style="text-align: right">{{ number_format($totalCost , 4)}}</td>
        </tr>
        <tr>
            <th style="text-align: right" colspan="6">TOTAL FOB PRICE</th>
            @php
                $totalFobPrice = 0;
                if ($priceQuotation->costing_per == 1) {
                     $totalFobPrice = $priceQuotation->asking_quoted_pc_set * 12;
                } elseif ($priceQuotation->costing_per == 2) {
                     $totalFobPrice = $priceQuotation->asking_quoted_pc_set * 1;
                } elseif ($priceQuotation->costing_per == 3) {
                     $totalFobPrice = $priceQuotation->asking_quoted_pc_set * 24;
                } elseif ($priceQuotation->costing_per == 4) {
                     $totalFobPrice = $priceQuotation->asking_quoted_pc_set * 36;
                } else {
                     $totalFobPrice = $priceQuotation->asking_quoted_pc_set * 48;
                }
            @endphp
            <td style="text-align: right">{{ number_format((float)$totalFobPrice, 4) }}</td>
        </tr>
        <tr>
            <th style="text-align: right" colspan="6">FOB PIECE</th>
            @php

                if ($priceQuotation->costing_per == 1) {
                      $totalFobPiece = $totalFobPrice / 12;
                 } elseif ($priceQuotation->costing_per == 2) {
                      $totalFobPiece = $totalFobPrice / 1;
                 } elseif ($priceQuotation->costing_per == 3) {
                      $totalFobPiece = $totalFobPrice / 24;
                 } elseif ($priceQuotation->costing_per == 4) {
                      $totalFobPiece = $totalFobPrice / 36;
                 } else {
                      $totalFobPiece = $totalFobPrice / 48;
                 }

            @endphp
            <td style="text-align: right">{{ number_format((float)$totalFobPiece, 4) }}</td>
        </tr>
        <tr>
            @php
                $profit = format((float)$totalFobPrice - $priceQuotation->prod_cost_dzn, 4);
                $totalProfit = format((float)($profit * $priceQuotation->offer_qty) / 12, 4);
            @endphp
            <th style="text-align: right" colspan="6">PROFIT</th>
            <td style="text-align: right">{{ $profit }}</td>
        </tr>
        <tr>
            <th style="text-align: right" colspan="6">TOTAL PROFIT</th>
            <td style="text-align: right">{{ $totalProfit }}</td>
        </tr>
        <tr>
            <th style="text-align: left">REMARKS</th>
            <td style="text-align: left" colspan="6">{{ $priceQuotation->remarks }}</td>
        </tr>
        </tbody>
    </table>
</div>
