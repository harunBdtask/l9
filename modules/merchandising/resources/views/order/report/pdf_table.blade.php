{{--@if(count($pos) == 1)--}}
<style>
    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>

<table class="reportTable" style="width: 100%">
    <thead>
    <tr>
        <th>Unique ID</th>
        <th>Buyer</th>
        <th>Prod. Dept</th>
        <th>Merchant</th>
        <th>Style</th>
        <th>Combo</th>
        <th>SMV</th>
        <th>Ship Date</th>
        <th>T.Style Qty</th>
        @if($team ==  1)
            <th>Team</th>
            <th>League</th>
        @endif
        <th>Required hanger</th>
        @if( count(collect($pos)->pluck('order.style')) < 30  && count(collect($pos)->pluck('order.style_name')->unique()->values()) == 1 )
            <th>Image</th>
        @endif
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ $jobNo ?? 'N/A' }}</td>
        <td>{{ $pos[0]['buyer'] ?? 'N/A' }}</td>
        @if(count($pos) > 0)
            <td>{{ $pos[0]['order'] ? optional($pos[0]['order']->productDepartment) ? optional($pos[0]['order']->productDepartment)->product_department : 'N/A' : 'N/A' }}</td>
            <td>{{ $pos[0]['order'] ? $pos[0]['order']->dealingMerchant ? $pos[0]['order']->dealingMerchant->full_name : 'N/A' : 'N/A' }}</td>
            @if($team == 1)
                <td>{{ $pos[0]['order'] ? $pos[0]['order']->dealingMerchant ? $pos[0]['order']->dealingMerchant->team ? $pos[0]['order']->dealingMerchant->team->team_name : 'N/A' : 'N/A' : 'N/A' }}</td>
                <td>{{ $pos[0]['league'] ?? 'N/A' }}</td>
            @endif
        @else
            <td colspan="4"></td>
        @endif
        <td>{{ $request->style_name ?? 'N/A' }}</td>
        <td>
            <b>{{ isset($pos[0]) && $pos[0]['order'] ?  $pos[0]['order']['combo'] ?? 'N/A': 'N/A' }}</b>
        </td>
        <td>
            {{ isset($pos[0]) && $pos[0]['order'] ?  number_format($pos[0]['order']['smv'], 2) ?? 'N/A': 'N/A' }}
        </td>
        <td>{{ isset($pos[0]) && collect($pos)->first()['ship_date'] ? \Carbon\Carbon::make(optional(collect($pos)->first())['ship_date'])->toFormattedDateString() : 'N/A' }}</td>
        <td class="text-right">{{ isset($pos[0]) ? $pos->sum('po_quantity') ?? 0 : 0 }}</td>


        <td>{{ $pos[0]['required_hanger'] ?? 'N/A' }}</td>
        @if( count(collect($pos)->pluck('order.style')) < 30  && count(collect($pos)->pluck('order.style_name')->unique()->values()) == 1 )
            <th>
                @php
                    $image = $pos[0]['order']->images;
                    $imagePath = isset($image) ? $image : null;
                @endphp
                @if($imagePath && File::exists('storage/'. $imagePath))
                    <img src="{{ asset('storage/' . $imagePath)  }}" alt="" height="50" width="50">
                @else
                    <img src="{{ asset('images/no_image.jpg') }}" height="50" width="50"
                         alt="no image">
                @endif
            </th>
        @endif
    </tr>
    </tbody>
</table>
{{--@endif--}}

@php $totalActual = 0; $totalPlan = 0; $totalValueSum= 0; @endphp
@foreach($pos as $poKey => $po)
    @php
        $gmts_item_group = $po['order'] ? $po['order']->garmentsItemGroup->name : 0;
        $fabric_composition = $po['order'] ? $po['order']->fabric_composition : 'N/A';
        $fabric_type = $po['order'] ? $po['order']->fabric_type : 'N/A';
    @endphp
    @if(isset($po['breakdown_data']))
        @foreach($po['breakdown_data'] as $breakdownKey => $breakdown)
            @if(count($breakdown['colors']) && count($breakdown['particulars']))
                <table class="reportTable" style="margin-top: 10px; width: 100%">
                    <tbody>
                    <tr>
                        <th>PO Number</th>
                        <th>PO Qnty.</th>
                        <th>FOB</th>
                        <th>Item</th>
                        <th>Gmts Item Group</th>
                        <th>Fab Comp.</th>
                        <th>Fab Type/GSM</th>
                        <th>Country</th>
                        @if($team == 1)
                            <th>Team</th>
                        @else
                            <th>Color</th>
                        @endif
                        <th>Particulars</th>
                        @foreach($breakdown['sizes'] as $size)
                            <th>{{ $size->name }}</th>
                        @endforeach
                        <th>Total</th>
                        <th>T. Actual Cut</th>
                        <th>T. Plan Cut</th>
                        <th>Total Value</th>
                    </tr>
                    @foreach($breakdown['colors'] as $colorKey => $color)
                        @foreach($breakdown['particulars'] as $particularKey => $particular)
                            <tr>
                                @if($colorKey == 0 && $particularKey == 0)
                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">{{ $po['po_no'] ?? 'N/A' }}</td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">{{ $po['po_quantity'] ?? 'N/A' }}</td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">{{ $po['fob'] }}</td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">{{ $breakdown['garment_item'] ?? 'N/A' }}</td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">
                                        {{ $gmts_item_group ?? 'N/A' }}
                                    </td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">{{  $fabric_composition ?? 'N/A' }} </td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">  {{  $fabric_type ?? 'N/A' }} </td>

                                    <td rowspan="{{ count($breakdown['colors']) * 2 }}">{{ $po['country'] ?? 'N/A' }}</td>
                                @endif

                                @if($particularKey == 0)
                                    <td rowspan="2">{{ $color['name'] ?? 'N/A' }}</td>
                                @endif

                                <td>{{ $particular ?? 'N/A' }}</td>

                                @foreach($breakdown['sizes'] as $size)
                                    <td class="text-right">{{ collect($breakdown['quantity_matrix'])->where('color_id', $color->id)->where('size_id', $size->id)->where('particular', $particular)->first()['value'] ?? 0 }}</td>
                                @endforeach

                                <td class="text-right">{{ round((collect($breakdown['quantity_matrix'])->where('color_id', $color->id ?? null)->where('particular', $particular)->sum('value') ?? 0)) }}</td>

                                @if($colorKey == 0 && $particularKey == 0)
                                    @php
                                        $actual = collect($breakdown['quantity_matrix'])->where('particular', $breakdown['particulars'][0])->sum('value') ?? 0;
                                        $totalActual = (double)$totalActual + (double)$actual;
                                        $fob = $po['fob'];


                                        $uomId = collect($pos)->pluck('order_uom_id')[0];
                                        $totalValue = (double)$po['po_quantity'] * (double)$po['fob'];
                                        $plan = collect($breakdown['quantity_matrix'])->where('particular', $breakdown['particulars'][1])->sum('value') ?? 0;
                                        $totalPlan = (double)$totalPlan + (double)$plan;
                                        if ($uomId === 2) {
                                            $totalValueSum = $totalValue;
                                        } else {
                                            $totalValueSum += $totalValue;
                                        }
                                    @endphp
                                    <td class="text-right"
                                        rowspan="{{ count($breakdown['colors']) * 2 }}">{{ round($actual) }}</td>

                                    <td class="text-right"
                                        rowspan="{{ count($breakdown['colors']) * 2 }}">{{ round($plan) }}</td>
                                    <td class="text-right"
                                        rowspan="{{ count($breakdown['colors']) * 2 }}">{{ "$".number_format($totalValue,4) }}</td>
                                @endif
                            </tr>
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
                <hr>
            @endif
        @endforeach
    @endif
@endforeach
@if(count($pos))
    <table class="reportTable">
        <tbody>
        <tr>
            <th style="text-align: right; padding: 4px">G.T. Actual Cut</th>
            <td class="text-right" style="width: 10%">{{ round($totalActual) }}</td>
        </tr>
        <tr>
            <th style="text-align: right; padding: 4px">G.T. Plan Cut</th>
            <td class="text-right" style="width: 10%">{{ round($totalPlan) }}</td>
        </tr>
        <tr>
            <th style="text-align: right; padding: 4px">Grand Total Value</th>
            <td class="text-right" style="width: 10%">{{ "$".number_format($totalValueSum,4) }}</td>
        </tr>
        </tbody>
    </table>
@endif
