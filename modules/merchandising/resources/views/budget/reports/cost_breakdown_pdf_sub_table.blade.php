<tr>
    <th style="background-color:#F3E353">EPM</th>
    <td></td>
    <td></td>
    {{--                    <td></td>--}}
    <td></td>
    <td></td>
    <td></td>
    <th style="text-align: right">${{ number_format($epm, 2) }}</th>
    <th style="text-align: right"></th>
</tr>
<tr>
    <th style="text-align: center; background-color:#F3E353">CPM</th>
    <th style="text-align: center">SMV</th>
    <th style="text-align: center">M/C</th>
    <th style="text-align: center">PRODUCTION</th>
    <th style="text-align: center" colspan="2">EFFICIENCY %</th>
    <th style="text-align: center">CM/Pcs</th>
    <th style="text-align: center">CM/Dzn</th>
</tr>
<tr>
    <th style="text-align: center">{{ $cpm }}</th>
    <th style="text-align: center">{{ number_format($smv, 2) }}</th>
    <th style="text-align: center">{{ number_format($machine_line, 2) }}</th>
    <th style="text-align: center" >{{ round($production) }}</th>
    <th style="text-align: center" colspan="2">{{ number_format($sew_efficiency, 2) }}</th>
    <th style="text-align: center">{{ number_format($cm_per_pcs, 2) }}/PCS</th>
    <th style="text-align: center">{{ number_format($cm_per_dzn, 2) }}/DZ</th>
</tr>
{{--                <tr>--}}
{{--                    <td colspan="8" height="5px"/>--}}
{{--                </tr>--}}
<tr>
    <th style="background-color:#F3E353">FACTORY CM / DZ BUDGET</th>
    <td></td>
    <td></td>
    <td></td>
    {{--                    <td></td>--}}
    <td></td>
    <td></td>
    <th style="text-align: right">${{ number_format($cm_per_dzn, 2) }}/DZ</th>
    <th style="text-align: right">{{ number_format($budgetPreCost, 2) }}%</th>
</tr>
{{--                <tr>--}}
{{--                    <td colspan="8" height="5px"/>--}}
{{--                </tr>--}}
<tr>
    <th style="background-color:#F3E353">TOTAL CM</th>
    <td></td>
    {{--                    <td></td>--}}
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <th style="text-align: right">${{ number_format($totalCm, 2) }}/DZ</th>
    <th style="text-align: right">{{ number_format($cmPreCost, 2) * 100 }}%</th>
</tr>
{{--                <tr>--}}
{{--                    <td colspan="8" height="5px"/>--}}
{{--                </tr>--}}
<tr>
    <th style="background-color:#F3E353">NET EARNINGS</th>
    <td></td>
    {{--                    <td></td>--}}
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <th style="text-align: right">${{ number_format($netEarning, 2) }}</th>
    <th style="text-align: right">{{ number_format($netPreCost, 2) * 100 }}%</th>
</tr>
