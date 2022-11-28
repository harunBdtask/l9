<table class="reportTable">
    <thead>
    <tr>
        <td style="background-color: aliceblue" rowspan="2"><b>BUYER</b></td>
        <td style="background-color: aliceblue" rowspan="2"><b>ORDER NO</b></td>
        <td style="background-color: aliceblue" colspan="{{count($dates)}}"><b>CUTTING DATE</b></td>
        <td style="background-color: aliceblue" rowspan="2"><b>TOTAL CUTTING</b></td>
        <td style="background-color: aliceblue" rowspan="2"><b>REMARKS</b></td>
    </tr>
    <tr>
        @foreach($dates as $date)
            <td style="background-color: #a1c9ed"><b>{{ \Carbon\Carbon::make($date)->format('d') }}</b></td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @foreach($data as $buyerWiseData)
        @foreach($buyerWiseData as $reportData)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ count($buyerWiseData) }}">{{ $reportData['buyer'] }}</td>
                @endif
                <td>
                    {{ $reportData['style'] }}
                </td>
                @foreach($dates as $date)
                    <td class="text-right">
                        {{ collect($reportData['dates'])->where('date', $date->format('Y-m-d'))->first()['qty'] ?? 0 }}
                    </td>
                @endforeach
                <td class="text-right">
                    {{ collect($reportData['dates'])->sum('qty') }}
                </td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2" style="text-align: right; background-color:gainsboro" class="text-right">
                <b>{{ collect($buyerWiseData)->first()['buyer'] }} TOTAL</b></td>
            @php
                $orderDateWiseQty = collect($buyerWiseData)->pluck('dates')->collapse();
            @endphp
            @foreach($dates as $date)
                <td style="background-color:gainsboro"
                    class="text-right">
                    <b>{{ collect($orderDateWiseQty)->where('date', $date->format('Y-m-d'))->sum('qty') ?? 0 }}</b></td>
            @endforeach
            <td style="background-color:gainsboro" class="text-right">
                <b>{{ collect($orderDateWiseQty)->sum('qty') }}</b></td>
            <td style="background-color: gainsboro"></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="2" style="text-align: right; background-color:#c3c3c3">
            <b>GRAND TOTAL</b></td>
        @foreach($dates as $date)
            @php
                $date = \Carbon\Carbon::make($date)->format('Y-m-d')
            @endphp
            <td style="background-color:#c3c3c3" class="text-right">
                <b>{{ $totals[$date] ?? 0 }}</b>
            </td>
        @endforeach
        <td style="background-color:#c3c3c3" class="text-right">
            <b>{{ collect($totals)->sum() }}</b>
        </td>
        <td style="background-color: #c3c3c3"></td>
    </tr>
    </tbody>
</table>
