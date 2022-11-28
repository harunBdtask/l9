<tr>
    <th>TOTAL CM</th>
    <td></td>
    {{--                    <td></td>--}}
    <td></td>
    <td></td>
    <td></td>
    <td class="text-right">{{ number_format($cm_view_2, 2) }}</td>
    <th class="text-right">{{ number_format($totalCm, 2) }}</th>
    <th class="text-right">{{ number_format($cmPreCost, 2) * 100 }}%</th>
</tr>
{{--                <tr>--}}
{{--                    <td colspan="8" height="5px"/>--}}
{{--                </tr>--}}
<tr>
    <th>NET EARNINGS</th>
    <td></td>
    {{--                    <td></td>--}}
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <th style="text-align: right">{{ number_format($netEarning, 2) }}</th>
    <th style="text-align: right">{{ number_format($netPreCost, 2) * 100 }}%</th>
</tr>
