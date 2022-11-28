<tr>
    @php
        $uom = $item->uomDetails->name ?? '';
    @endphp
    <td class="text-left padding-1">{{ $stockDetails['date'] }}</td>
    <td class="text-left padding-1"></td>
    <td class="text-left padding-1">{{ $stockDetails['voucher_no'] }}</td>
    <td class="text-right padding-1">{{ $stockDetails['inwards'] ." ". $uom }}</td>
    <td class="text-right padding-1">{{ number_format($stockDetails['inward_value'], 2) }}</td>
    <td class="text-right padding-1">{{ $stockDetails['outwards'] ." ". $uom }}</td>
    <td class="text-right padding-1">{{ number_format($stockDetails['outward_value'], 2) }}</td>
    <td class="text-right padding-1">{{ $stockDetails['closing_balance'] ." ". $uom }}</td>
    <td class="text-right padding-1">{{ number_format($stockDetails['closing_value'], 2) }}</td>
</tr>
<style>
    .padding-1 {
        padding: 1%;
    }
</style>