
@php
$sizes = collect($pos[0]['breakdown_data'][0]['sizes'])->pluck('name');
$total_colors = collect($pos)->pluck('breakdown_data')->flatten(1)->map(function($item){
            return collect($item['colors'])->count();
        })->sum();

$total_amount = 0; $particular='Qty.';
@endphp
<table class="reportTable" style="margin-top: 10px; width: 100%">
    <tbody>
    <tr>
        <th>Order No</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO No</th>
        <th>Ex Factory Dt</th>
        <th>COLOR</th>
        @foreach($sizes as $size)
        <th>{{ $size }}</th>
        @endforeach
        <th>Total</th>
    </tr>

    @foreach($pos as $poKey => $po)
        @foreach($po['breakdown_data'] as $breakdownKey => $breakdown)
            @foreach($breakdown['colors'] as $colorKey => $color)
            <tr>
                @if(($poKey==0) & ($colorKey==0))
                <td rowspan="{{ $total_colors  }}">{{ $jobNo ?? 'N/A' }}</td>
                @endif

                @if(($poKey == 0) && ($colorKey == 0))
                        <td rowspan="{{ $total_colors }}">{{ $buyerName->name ?? '' }}</td>
                        <td rowspan="{{ $total_colors }}">{{ $styleName ?? '' }}</td>
                @endif
                @if($colorKey==0)
                <td rowspan="{{ collect($breakdown['colors'])->count() }}">{{ $po['po_no']??'' }}</td>
                <td rowspan="{{ collect($breakdown['colors'])->count() }}"> {{ $po['ship_date'] ? \Carbon\Carbon::make($po['ship_date'])->toFormattedDateString() : 'N/A' }} </td>
                @endif
                <td>{{ $color['name']??'' }}</td>
                @foreach($breakdown['sizes'] as $size)
                    @php
                        $colorId = $color['id'] ?? '' ;
                    @endphp
                    <td class="text-right">{{ collect($breakdown['quantity_matrix'])->where('color_id', $color->id ?? null)->where('size_id', $size->id ?? null)->where('particular', $particular)->first()['value'] ?? 0 }}</td>
                @endforeach
                @php
                    $coltotal = collect($breakdown['quantity_matrix'])->where('color_id', $color->id ?? null)->where('particular', $particular)->sum('value');
                    $total_amount += $coltotal;
                @endphp
                <td class="text-right"><b>{{ round(( $coltotal ?? 0)) }}</b></td>
            </tr>
            @endforeach
        @endforeach
    @endforeach
    </tbody>
    <tfoot>


        <tr>
            <td class="text-right" colspan="6"><b>Total</b></td>
            @foreach($pos as $poKey => $po)
                @foreach($po['breakdown_data'] as $breakdownKey => $breakdown)
                    @foreach($breakdown['sizes'] as $sizeKey=>$size)
                        @if(($poKey==0))
                        <td class="text-right"><b>{{ collect(collect($breakdown['quantity_matrix'])->where('size_id', $size->id ?? null)->where('particular', $particular)->all())->pluck('value')->sum() ?? 0 }}</b></td>
                        @endif
                    @endforeach
                @endforeach
            @endforeach
            <td class="text-right"><b>{{ round(( $total_amount ?? 0)) }}</b></td>
        </tr>

    </tfoot>
</table>
