<tr>
    <td class="text-left padding-1"><b>Grand Total</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($opening_grand_value,2) : $opening_grand_value }}</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($inwards_grand_value,2) : $inwards_grand_value }}</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($outwards_grand_value,2) : $outwards_grand_value }}</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($closing_grand_value,2) : $closing_grand_value }}</b></td>
</tr>

<style>
    .padding-1 {
        padding: 1%;
    }
</style>
