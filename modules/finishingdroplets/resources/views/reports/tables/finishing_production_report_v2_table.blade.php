@if(count($reportData) > 0)
    @foreach(collect($reportData)->sortBy('buyer') as $data)
        <tr>
            <td>{{ $data['buyer'] }}</td>
            <td>{{ $data['production_date'] }}</td>
            <td>{{ $data['style'] }}</td>
            <td>{{ $data['po_no'] }}</td>
            <td>{{ $data['country'] }}</td>
            <td>{{ $data['color'] }}</td>
            <td class="text-right">{{ $data['order_qty'] }}</td>
            <td class="text-right">{{ round($data['order_qty_one_percentage']) }}</td>
            <td class="text-right">{{ $data['daily_received'] }}</td>
            <td class="text-right">{{ $data['pre_received'] }}</td>
            <td class="text-right">{{ $data['total_received'] }}</td>
            <td class="text-right">{{ $data['daily_iron'] }}</td>
            <td class="text-right">{{ $data['pre_iron'] }}</td>
            <td class="text-right">{{ $data['total_iron'] }}</td>
            <td class="text-right">{{ $data['daily_finish'] }}</td>
            <td class="text-right">{{ $data['pre_finish'] }}</td>
            <td class="text-right">{{ $data['total_finish'] }}</td>
            <td class="text-right">{{ $data['balance_qty'] }}</td>
            <td>{{ $data['finish_floor'] }}</td>
            <td>{{ $data['sewing_floor'] }}</td>
            <td>{{ $data['remarks'] }}</td>
        </tr>
    @endforeach
    <tr style="background-color: gainsboro">
        <td colspan="6" class="text-right">
            <strong>Total</strong>
        </td>
        <td class="text-right" id="totalOrderQty">
            <strong>{{ collect($reportData)->sum('order_qty') }}</strong>
        </td>
        <td></td>
        <td class="text-right" id="totalDailyRcvd">
            <strong>{{ collect($reportData)->sum('daily_received') }}</strong>
        </td>
        <td class="text-right" id="totalPreRcvd">
            <strong>{{ collect($reportData)->sum('pre_received') }}</strong>
        </td>
        <td class="text-right" id="totalRcvd">
            <strong>{{ collect($reportData)->sum('total_received') }}</strong>
        </td>
        <td class="text-right" id="totalDailyIron">
            <strong>{{ collect($reportData)->sum('daily_iron') }}</strong>
        </td>
        <td class="text-right" id="totalPreIron">
            <strong>{{ collect($reportData)->sum('pre_iron') }}</strong>
        </td>
        <td class="text-right" id="totalIron">
            <strong>{{ collect($reportData)->sum('total_iron') }}</strong>
        </td>
        <td class="text-right" id="totalDailyFinish">
            <strong>{{ collect($reportData)->sum('daily_finish') }}</strong>
        </td>
        <td class="text-right" id="totalPreFinish">
            <strong>{{ collect($reportData)->sum('pre_finish') }}</strong>
        </td>
        <td class="text-right" id="totalFinish">
            <strong>{{ collect($reportData)->sum('total_finish') }}</strong>
        </td>
        <td class="text-right" id="totalBalanceQty">
            <strong>{{ collect($reportData)->sum('balance_qty') }}</strong>
        </td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
@endif
