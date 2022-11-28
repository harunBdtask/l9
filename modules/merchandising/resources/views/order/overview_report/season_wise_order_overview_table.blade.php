<table class="reportTable">
    <thead style="background-color: aliceblue">
    <tr>
        <th>Month</th>
        <th>Total Qty</th>
        <th>Total Value</th>

    </tr>
    </thead>
    <tbody>
    @forelse($orders as $index => $item)
        <tr>
            <td style="text-align: left">{{ $item['month_name'] ?? '' }}</td>
            <td style="text-align: center">{{ number_format($item['total_qty'] ?? 0)}}</td>
            <td style="text-align: right">{{ '$'.number_format($item['total_amount'] ?? 0 , 2)}}</td>
        </tr>
    @empty
        <tr>
            <th colspan="3" style="text-align: center">No Data Found!</th>
        </tr>
    @endforelse

        <tr style="background-color: gainsboro">
            <td>Total</td>
            <td style="text-align: center">{{ number_format($item['total_qty'] ?? 0)}}</td>
            <td style="text-align: right">{{ '$'.number_format(collect($orders)->sum('total_amount') , 2)}}</td>
        </tr>
    </tbody>
</table>
