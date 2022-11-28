<tr>
    <td class="text-left padding-1"><b>Grand Total</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($total_opening_grand_value, 2) : $total_opening_grand_value }}</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($total_inwards_grand_value, 2) : $total_inwards_grand_value }}</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($total_outwards_grand_value, 2) : $total_outwards_grand_value }}</b></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"></td>
    <td class="text-right padding-1"><b>{{ $type !== "excel" ? number_format($total_closing_grand_value, 2) : $total_closing_grand_value }}</b></td>
</tr>
<style>
    .padding-1 {
        padding: 1%;
    }
</style>