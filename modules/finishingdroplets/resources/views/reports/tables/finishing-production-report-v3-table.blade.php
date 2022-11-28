<table class="reportTable">
    <thead>
    <tr>
        <th>#</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO No</th>
        <th>Country</th>
        <th>Color</th>
        <th>Order Qty</th>
        <th>Order Qty + 1%</th>
        <th>Daily Rcvd</th>
        <th>Pre Rcvd</th>
        <th>Total Rcvd</th>
        <th>Daily Iron</th>
        <th>Pre Iron</th>
        <th>Total Iron</th>
        <th>Daily Finish</th>
        <th>Pre Finish</th>
        <th>Total Finish</th>
        <th>Balance Qty</th>
        <th>Ship Qty</th>
        <th>Finish Floor</th>
        <th>Sewing Floor</th>
    </tr>
    </thead>
    <tbody>
    @if(collect($productions)->count() > 0)
        @foreach($productions as $key => $production)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $production['buyer'] }}</td>
                <td>{{ $production['style_name'] }}</td>
                <td>{{ $production['po_no'] }}</td>
                <td>{{ $production['country'] }}</td>
                <td>{{ $production['color'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ round($production['order_qty'], 2) }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ round($production['order_qty_ex'], 2) }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['daily_received'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['prev_received'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['total_received'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['daily_iron'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['prev_iron'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['total_iron'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['daily_finish'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['prev_finish'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['total_finish'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['balance_qty'] }}</td>
                <td class="text-right" style="padding-right: 4px;">{{ $production['ship_qty'] }}</td>
                <td>{{ $production['finish_floor'] }}</td>
                <td>{{ $production['sewing_floor'] }}</td>
            </tr>
        @endforeach
        <tr style="background: gainsboro">
            <td colspan="6"><strong>Total</strong></td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('order_qty') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('order_qty_ex') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('daily_received') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('prev_received') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('total_received') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('daily_iron') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('prev_iron') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('total_iron') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('daily_finish') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('prev_finish') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('total_finish') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('balance_qty') }}</strong>
            </td>
            <td class="text-right" style="padding-right: 4px;">
                <strong>{{ collect($productions)->sum('ship_qty') }}</strong>
            </td>
            <td class="text-right" colspan="2"></td>
        </tr>
    @else
        <tr>
            <td colspan="21"><b>No Data Found</b></td>
        </tr>
    @endif

    </tbody>
</table>
