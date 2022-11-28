@foreach($stockDetails as $stockDetails)
    <tr>
        @php
            $uom = $item['uomDetails']['name'] ?? '';
        @endphp
        <td class="text-left padding-1">{{ $item['name'] }}</td>
        <td class="text-left padding-1">{{ \Carbon\Carbon::parse($stockDetails['trn_date'])->toFormattedDateString() }}</td>
        <td class="text-left padding-1">{{ \Carbon\Carbon::parse($stockDetails['trn_date'])->addDays($stockDetails['life_end_days'])->toFormattedDateString() }}</td>
        <td class="text-right padding-1">{{ $stockDetails['opening_balance'] ." ". $uom }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['opening_rate'], 2) : $stockDetails['opening_rate'] }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['opening_value'], 2) : $stockDetails['opening_value'] }}</td>
        <td class="text-right padding-1">{{ $stockDetails['inwards'] ." ". $uom }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['inward_rate'], 2) : $stockDetails['inward_rate'] }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['inward_value'], 2) : $stockDetails['inward_value'] }}</td>
        <td class="text-right padding-1">{{ $stockDetails['outwards'] ." ". $uom }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['outward_rate'], 2) : $stockDetails['outward_rate'] }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['outward_value'], 2) : $stockDetails['outward_value'] }}</td>
        <td class="text-right padding-1">{{ $stockDetails['closing_balance'] ." ". $uom }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['closing_rate'], 2) : $stockDetails['closing_rate'] }}</td>
        <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['closing_value'], 2) : $stockDetails['closing_value'] }}</td>
    </tr>
@endforeach
<style>
    .padding-1 {
        padding: 1%;
    }
</style>
