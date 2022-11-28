<table class="reportTable">
    <thead>
    <tr>
        <td rowspan="2"><strong>Carton No</strong></td>
        <td rowspan="2"><strong>Total Boxes/CARTON</strong></td>
        <td rowspan="2"><strong>Model/Quality/ART/Destination</strong></td>
        <td rowspan="2"><strong>Color Name</strong></td>
        <td colspan="{{ collect($garmentPackingProduction->sizes)->count() }}">
            <strong>ASSORTMENT RATIO</strong>
        </td>
        <td rowspan="2"><strong>TTL Ratio(CTN)</strong></td>
        <td rowspan="2"><strong>TTL QTY</strong></td>
        <td rowspan="2"><strong>Net WT(KGS)</strong></td>
        <td rowspan="2"><strong>TOTAL Net WT(KGS)</strong></td>
        <td rowspan="2"><strong>Gross WT(KGS)</strong></td>
        <td rowspan="2"><strong>Total Gross WT(KGS)</strong></td>
        <td colspan="3"><strong>CARTON MEASUREMENT(CM)</strong></td>
        <td rowspan="2"><strong>TTL CBM</strong></td>
        <td rowspan="2"><strong>REMARKS</strong></td>
    </tr>
    <tr>
        @foreach($garmentPackingProduction->sizes as $size)
            <td><strong>{{ $size['name'] }}</strong></td>
        @endforeach
        <td><strong>LENGTH</strong></td>
        <td><strong>WIDTH</strong></td>
        <td><strong>HEIGHT</strong></td>
    </tr>
    </thead>
    <tbody>
    @foreach($garmentPackingProduction->carton_details as $cartonData)
        @foreach($cartonData['color_size_ratio'] as $colorSizeRatio)
            @php
                $colorSizeRatioLen = collect($cartonData['color_size_ratio'])->count();
            @endphp
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['carton_from'] }} - {{ $cartonData['carton_to'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['total_carton'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['model_quality_art_destination'] }}
                    </td>
                @endif
                <td>
                    {{ $colorSizeRatio['color_name'] }}
                </td>
                @foreach($garmentPackingProduction->sizes as $sizeKey => $size)
                    @php
                        $sizeRatio = collect($colorSizeRatio['size_wise_ratio'])->where('size_id', $size['id'])->first();
                    @endphp
                    <td>{{ $sizeRatio['size_ratio'] }}</td>
                @endforeach
                <td>{{ $colorSizeRatio['total_color_ratio'] }}</td>
                <td>{{ $colorSizeRatio['total_color_qty_in_carton'] }}</td>
                @if($loop->first)
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['net_wt_in_kg'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['total_net_wt_in_kg'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['gross_wt_in_kg'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['total_gross_wt_in_kg'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['carton_length'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['carton_width'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['carton_height'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['carton_cbm'] }}
                    </td>
                    <td rowspan="{{ $colorSizeRatioLen }}">
                        {{ $cartonData['remarks'] ?? null }}
                    </td>
                @endif
            </tr>
        @endforeach
    @endforeach
    <tr>
        <td>
            <strong>G. Total</strong>
        </td>
        <td>
            <strong>{{ $garmentPackingProduction->grand_total_cartons }}</strong>
        </td>
        <td colspan="{{ 5+collect($garmentPackingProduction->sizes)->count() }}"></td>
        <td>
            <strong>{{ $garmentPackingProduction->grand_total_n_wt }}</strong>
        </td>
        <td></td>
        <td>
            <strong>{{ $garmentPackingProduction->grand_total_g_wt }}</strong>
        </td>
        <td colspan="3"></td>
        <td>
            <strong>{{ $garmentPackingProduction->grand_total_cbm }}</strong>
        </td>
        <td></td>
    </tr>
    </tbody>
</table>
