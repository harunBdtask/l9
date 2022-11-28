<table class="reportTable">
    <thead style="background: aliceblue">
    <tr>
        <th>Item</th>
        <th>Description</th>
        <th>Prev. Stock</th>
        <th>Today Receive</th>
        <th>Today Issue</th>
        <th>Balance</th>
        <th>UOM</th>
    </tr>
    </thead>
    <tbody>
    @foreach($report as $data)
        <tr>
            <td>{{ $data['item_name'] }}</td>
            <td>{{ $data['description'] }}</td>
            <td style="text-align: right">{{ $data['prev_stock'] }}</td>
            <td style="text-align: right">{{ $data['today_receive'] }}</td>
            <td style="text-align: right">{{ $data['today_issue'] }}</td>
            <td style="text-align: right">{{ $data['balance'] }}</td>
            <td style="text-align: right">{{ $data['uom'] }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" class="text-right">
            <strong>TOTAL</strong>
        </td>
        <td class="text-right">
            <strong>{{ collect($report)->sum('prev_stock') }}</strong>
        </td>
        <td class="text-right">
            <strong>{{ collect($report)->sum('today_receive') }}</strong>
        </td>
        <td class="text-right">
            <strong>{{ collect($report)->sum('today_issue') }}</strong>
        </td>
        <td class="text-right">
            <strong>{{ collect($report)->sum('balance') }}</strong>
        </td>
        <td></td>
    </tr>
    </tbody>
</table>

