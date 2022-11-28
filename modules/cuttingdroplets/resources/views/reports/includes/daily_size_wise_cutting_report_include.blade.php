<table class="reportTable">
    <thead>
    <tr>
        <td style="font-weight: bold; background-color: aliceblue">BUYER</td>
        <td style="font-weight: bold; background-color: aliceblue">STYLE</td>
        <td style="font-weight: bold; background-color: aliceblue">PO NO</td>
        <td style="font-weight: bold; background-color: aliceblue">ITEM</td>
        <td style="font-weight: bold; background-color: aliceblue">COLOR</td>
        <td style="font-weight: bold; background-color: aliceblue">ORDER QTY</td>
        @foreach($sizes as $size)
            <td style="font-weight: bold; background-color: aliceblue">{{ $size['name'] }}</td>
        @endforeach
        <th style="font-weight: bold; background-color: aliceblue">DAILY CUTTING</th>
        <th style="font-weight: bold; background-color: aliceblue">TOTAL CUTTING</th>
        <th style="font-weight: bold; background-color: aliceblue">CUTTING BALANCE</th>
        <th style="font-weight: bold; background-color: aliceblue">CUTTING FLOORS</th>
        <th style="font-weight: bold; background-color: aliceblue">REMARKS</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $reportData)
        @php
            $balanceQty = $reportData['order_qty'] - $reportData['total_cutting'];
        @endphp
        <tr>
            <td>{{ $reportData['buyer'] }}</td>
            <td>{{ $reportData['style_name'] }}</td>
            <td>{{ $reportData['po_no'] }}</td>
            <td>{{ $reportData['item'] }}</td>
            <td>{{ $reportData['color'] }}</td>
            <td class="text-right">{{ $reportData['order_qty'] }}</td>
            @foreach($sizes as $size)
                <td class="text-right">{{ collect($reportData['sizes'])->where('size', $size['id'])->first()['qty'] ?? 0 }}</td>
            @endforeach
            <td class="text-right">{{ $reportData['daily_cutting'] }}</td>
            <td class="text-right">{{ $reportData['total_cutting'] }}</td>
            <td class="text-right">{{ $balanceQty ?? 0 }}</td>
            <td>{{ $reportData['cutting_floors'] }}</td>
            <td></td>
          </tr>
    @endforeach
    <tr style="background-color: gainsboro">
        <td colspan="5" style="text-align: right"><b>Total</b></td>
        <td class="text-right"><b>{{ collect($data)->sum('order_qty') }}</b></td>
        @php
            $reportsSizes = collect($data)->pluck('sizes')->collapse()->values();
        @endphp
        @foreach($sizes as $size)
            @php
                $sizeWiseSum = collect($reportsSizes)->where('size', $size['id'])->sum('qty');
            @endphp
            <td class="text-right"><b>{{ $sizeWiseSum }}</b></td>
        @endforeach
        <td class="text-right"><b>{{ collect($data)->sum('daily_cutting') }}</b></td>
        <td class="text-right"><b>{{ collect($data)->sum('total_cutting') }}</b></td>
        <td class="text-right"><b>{{ collect($data)->sum('order_qty') - collect($data)->sum('total_cutting') }}</b></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
