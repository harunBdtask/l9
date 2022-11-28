<div class="parentTableFixed">
    <table class="reportTable fixTable" style="width: 100%;">
    <thead>
    <tr>
        <th colspan="8"><b>Particulars</b></th>
        <th colspan="4"><b>Opening</b></th>
        <th colspan="4"><b>Receive</b></th>
        <th colspan="4"><b>Receive Return</b></th>
        <th colspan="4"><b>Issue</b></th>
        <th colspan="4"><b>Issue Return</b></th>
        <th colspan="4"><b>Closing</b></th>
    </tr>
    <tr>
        <th><b>Count</b></th>
        <th><b>Composition</b></th>
        <th><b>Lot</b></th>
        <th><b>Brand</b></th>
        <th><b>Type</b></th>
        <th><b>Certification</b></th>
        <th><b>Origin</b></th>
        <th><b>Color</b></th>
        <th><b>Qty</b></th>
        <th><b>UOM</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        <th style="background: aliceblue;"><b>Qty</b></th>
        <th style="background: aliceblue;"><b>UOM</b></th>
        <th style="background: aliceblue;"><b>Rate</b></th>
        <th style="background: aliceblue;"><b>Value</b></th>
        <th style="background: antiquewhite;"><b>Qty</b></th>
        <th style="background: antiquewhite;"><b>UOM</b></th>
        <th style="background: antiquewhite;"><b>Rate</b></th>
        <th style="background: antiquewhite;"><b>Value</b></th>
        <th style="background: floralwhite;"><b>Qty</b></th>
        <th style="background: floralwhite;"><b>UOM</b></th>
        <th style="background: floralwhite;"><b>Rate</b></th>
        <th style="background: floralwhite;"><b>Value</b></th>
        <th style="background: beige;"><b>Qty</b></th>
        <th style="background: beige;"><b>UOM</b></th>
        <th style="background: beige;"><b>Rate</b></th>
        <th style="background: beige;"><b>Value</b></th>
        <th><b>Qty</b></th>
        <th><b>UOM</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
    </tr>
    </thead>
    <tbody>
    @forelse(collect($reportData)->sortBy('yarn_count') as $key => $value)
        <tr>
            <td style="text-align: left;">{{ $value['yarn_count'] }}</td>
            <td style="text-align: left;">{{ $value['yarn_composition'] }}</td>
            <td style="text-align: left;">{{ $value['yarn_lot'] }}</td>
            <td style="text-align: left;">{{ $value['yarn_brand'] }}</td>
            <td style="text-align: left;">{{ $value['yarn_type'] }}</td>
            <td style="text-align: left;">{{ $value['certification'] }}</td>
            <td style="text-align: left;">{{ $value['origin'] }}</td>
            <td style="text-align: left;">{{ $value['yarn_color'] }}</td>

            <td>{{ $value['opening']['qty'] }}</td>
            <td>{{ $value['uom'] }}</td>
            <td style="text-align: right;">{{ $value['opening']['rate'] }}</td>
            <td style="text-align: right;">{{ $value['opening']['value'] }}</td>

            <td style="background: aliceblue;">{{ $value['receive']['qty'] }}</td>
            <td style="background: aliceblue;">{{ $value['uom'] }}</td>
            <td style="text-align: right; background: aliceblue;">{{ $value['receive']['rate'] }}</td>
            <td style="text-align: right; background: aliceblue;">{{ $value['receive']['value'] }}</td>

            <td style="background: antiquewhite;">{{ $value['receive_return']['qty'] }}</td>
            <td style="background: antiquewhite;">{{ $value['uom'] }}</td>
            <td style="text-align: right; background: antiquewhite;">{{ $value['receive_return']['rate'] }}</td>
            <td style="text-align: right; background: antiquewhite;">{{ $value['receive_return']['value'] }}</td>

            <td style="background: floralwhite;">{{ $value['issue']['qty'] }}</td>
            <td style="background: floralwhite;">{{ $value['uom'] }}</td>
            <td style="text-align: right; background: floralwhite;">{{ $value['issue']['rate'] }}</td>
            <td style="text-align: right; background: floralwhite;">{{ $value['issue']['value'] }}</td>

            <td style="background: beige">{{ $value['issue_return']['qty'] }}</td>
            <td style="background: beige">{{ $value['uom'] }}</td>
            <td style="text-align: right; background: beige">{{ $value['issue_return']['rate'] }}</td>
            <td style="text-align: right; background: beige">{{ $value['issue_return']['value'] }}</td>

            <td>{{ $value['closing']['qty'] }}</td>
            <td>{{ $value['uom'] }}</td>
            <td style="text-align: right;">{{ $value['closing']['rate'] }}</td>
            <td style="text-align: right;">{{ number_format($value['closing']['value'], 2) }}</td>
        </tr>
        @if($loop->last)
            @php
                $reportDataCollection = collect($reportData);
            @endphp
            <tr>
                <td colspan="8"><b>Total: </b></td>
                <td><b>{{ $reportDataCollection->sum('opening.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ $reportDataCollection->sum('opening.value') }}</b></td>

                <td><b>{{ $reportDataCollection->sum('receive.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ $reportDataCollection->sum('receive.value') }}</b></td>

                <td><b>{{ $reportDataCollection->sum('receive_return.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ $reportDataCollection->sum('receive_return.value') }}</b></td>

                <td><b>{{ $reportDataCollection->sum('issue.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ $reportDataCollection->sum('issue.value') }}</b></td>

                <td><b>{{ $reportDataCollection->sum('issue_return.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ $reportDataCollection->sum('issue_return.value') }}</b></td>

                <td><b>{{ $reportDataCollection->sum('closing.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ $reportDataCollection->sum('closing.value') }}</b></td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="32">No data found!</td>
        </tr>
    @endforelse
    </tbody>
</table>
</div>
