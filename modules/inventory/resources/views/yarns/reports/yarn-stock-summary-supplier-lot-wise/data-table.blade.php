<table class="reportTable">
    <thead>
    <tr>
        <th colspan="7"><b>Particulars</b></th>
        <th colspan="3"><b></b></th>
        <th colspan="3"><b>Opening</b></th>
        <th colspan="3"><b>Receive</b></th>
        <th colspan="3"><b>Issue</b></th>
        <th colspan="3"><b>Closing</b></th>
        <th></th>
        <th></th>
        <th></th>
    </tr>
    <tr>
        <th><b>Count</b></th>
        <th><b>Composition</b></th>
        <th><b>Type</b></th>
        <th><b>Certification</b></th>
        <th><b>Origin</b></th>
        <th><b>Color</b></th>
        <th><b>Lot</b></th>
        <th><b>Brand</b></th>
        <th><b>Supplier</b></th>
        <th><b>PI NO - Date</b></th>
        <th><b>LC NO - Date</b></th>
        <th><b>Qty</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        <th><b>Qty</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        <th><b>Qty</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        <th><b>Qty</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        <th><b>Storage</b></th>
        <th><b>Age</b></th>
    </tr>
    </thead>
    <tbody>
    @forelse($reportData as $key => $value)
        <tr class="text-left">
            <td>
                <div>
                    {{ $value['yarn_count'] }}
                </div>
            </td>
            <td>
                <div style="width: 150px;">
                    {{ $value['yarn_composition'] }}
                </div>
            </td>
            <td><div>{{ $value['yarn_type'] }}</div></td>
            <td style="text-align: left;">{{ $value['certification'] }}</td>
            <td style="text-align: left;">{{ $value['origin'] }}</td>
            <td><div>{{ $value['yarn_color'] }}</div></td>
            <td><div>{{ $value['lot'] }}</div></td>
            <td><div>{{ $value['yarn_brand'] }}</div></td>
            <td><div style="width: 150px;">{{ $value['supplier'] }}</div></td>
            <td><div style="line-break: anywhere; width: 150px;">{{ $value['pi_no'] }}
                    <br>
                    {{ ' - ' . $value['pi_date'] }}
                </div>
            </td>
            <td><div>{{ $value['lc_no'] . ' - ' . $value['lc_date'] }}</div></td>
            <td><div>{{ $value['opening']['qty'] }}</div></td>
            <td style="text-align: right">{{ $value['opening']['rate'] }}</td>
            <td style="text-align: right">{{ $value['opening']['value'] }}</td>
            <td>{{ $value['receive']['qty'] }}</td>
            <td style="text-align: right">{{ $value['receive']['rate'] }}</td>
            <td style="text-align: right">{{ $value['receive']['value'] }}</td>
            <td>{{ $value['issue']['qty'] }}</td>
            <td style="text-align: right">{{ $value['issue']['rate'] }}</td>
            <td style="text-align: right">{{ $value['issue']['value'] }}</td>
            <td>{{ $value['closing']['qty'] }}</td>
            <td style="text-align: right">{{ $value['closing']['rate'] }}</td>
            <td style="text-align: right">{{ $value['closing']['value'] }}</td>
            <td>{{ $value['storage'] }}</td>
            <td>
                @if($value['age'])
                    {{ $value['age'] }} day{{ $value['age'] > 1 ? 's' : '' }}
                @endif
            </td>
        </tr>
        @if($loop->last)
            <tr>
                <td colspan="11"><b>Total: </b></td>
                <td><b>{{ collect($reportData)->sum('opening.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ collect($reportData)->sum('receive.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ collect($reportData)->sum('issue.qty') }}</b></td>
                <td colspan="2"></td>
                <td><b>{{ collect($reportData)->sum('closing.qty') }}</b></td>
                <td colspan="4"></td>
            </tr>
        @endif
    @empty
        <tr>
            <td colspan="25">No data found!</td>
        </tr>
    @endforelse
    </tbody>
</table>
