<tr>
    @php
        $uom = $item['uomDetails']['name'] ?? '';
    @endphp
    <td class="text-left padding-1">{{ $item['name'] }}</td>
    <td class="text-right padding-1">{{ $stockDetails['inwards'] ." ". $uom }}</td>
    <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['inward_rate'], 2) : $stockDetails['inward_rate'] }}</td>
    <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['inward_value'], 2) : $stockDetails['inward_value'] }}</td>
    <td class="text-right padding-1">{{ $stockDetails['outwards'] ." ". $uom }}</td>
    <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['outward_rate'], 2) : $stockDetails['outward_rate'] }}</td>
    <td class="text-right padding-1">{{ $type !== "excel" ? number_format($stockDetails['outward_value'], 2) : $stockDetails['outward_value'] }}</td>
</tr>
<style>
    .padding-1 {
        padding: 1%;
    }
</style>
