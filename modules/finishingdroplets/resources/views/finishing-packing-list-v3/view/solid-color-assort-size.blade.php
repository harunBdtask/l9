<table class="reportTable">
    <thead>
    <tr>
        <td rowspan="2"><strong>Carton No</strong></td>
        <td rowspan="2"><strong>Total Boxes/CARTON</strong></td>
        <td rowspan="2"><strong>Model/Quality/ART</strong></td>
        <td rowspan="2"><strong>Color Name</strong></td>
        <td colspan="{{ collect($garmentPackingProduction->sizes)->count() }}"><strong>ASSORTMENT RATIO</strong></td>
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
        <tr>
            <td>{{ $cartonData['carton_from'] }} - {{ $cartonData['carton_to'] }}</td>
            <td>{{ $cartonData['total_carton'] }}</td>
            <td>{{ $cartonData['model_quality_art_destination'] }}</td>
            <td>{{ $cartonData['colors'][0]['name'] }}</td>
            @foreach($garmentPackingProduction->sizes as $sizeKey => $size)
                @php
                    $sizeRatio = collect($cartonData['color_size_ratio'][0]['size_wise_ratio'])->where('size_id', $size['id'])->first();
                @endphp
                <td>{{ $sizeRatio['size_ratio'] }}</td>
            @endforeach
            <td>{{ $cartonData['total_qty_in_carton'] }}</td>
            <td>{{ $cartonData['color_size_ratio'][0]['total_color_qty_in_carton'] }}</td>
            <td>{{ $cartonData['net_wt_in_kg'] }}</td>
            <td>{{ $cartonData['total_net_wt_in_kg'] }}</td>
            <td>{{ $cartonData['gross_wt_in_kg'] }}</td>
            <td>{{ $cartonData['total_gross_wt_in_kg'] }}</td>
            <td>{{ $cartonData['carton_length'] }}</td>
            <td>{{ $cartonData['carton_width'] }}</td>
            <td>{{ $cartonData['carton_height'] }}</td>
            <td>{{ $cartonData['carton_cbm'] }}</td>
            <td>{{ $cartonData['remarks'] ?? null }}</td>
        </tr>
    @endforeach
    <tr>
        <td>
            <strong>G. Total</strong>
        </td>
        <td>
            <strong>{{ $garmentPackingProduction->grand_total_cartons }}</strong>
        </td>
        <td colspan="{{ 2+collect($garmentPackingProduction->sizes)->count() }}"></td>
        <td>
            <strong>{{ collect($garmentPackingProduction->carton_details)->sum('total_qty_in_carton') }}</strong>
        </td>
        <td>
            <strong>{{ collect($garmentPackingProduction->carton_details)->sum('color_size_ratio.0.total_color_qty_in_carton') }}</strong>
        </td>
        <td></td>
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
