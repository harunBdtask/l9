<table class="reportTable">
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Challan No:</b>
        </td>
        <td style="width: 35%;">{{ $challanNo }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Challan Date:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->first()['challan_date'] }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Booking No:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('booking_no')->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Challan Type:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('challan_type')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Issue No:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('issue_no')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Store:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('store_name')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Buyer:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('buyer_name')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Style name:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('style_name')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>PO:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('po_no')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Booking Date:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('booking_date')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>From:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('supplier_name')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>To:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('delivery_to')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Address:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('delivery_address')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Attention:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('attention')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Dealing Merchant:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('dealing_merchant')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>PI Number:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->whereNotNull('pi_no')->pluck('pi_no')->unique()->values()->join(', ') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Booking Qty:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->sum('booking_qty') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Challan Qty:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->sum('challan_qty') }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Season:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->pluck('season_name')->unique()->values()->join(', ') }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Short/Excess Delivery Qty:</b>
        </td>
        <td style="width: 35%;">{{ $deliveryChallans->sum('booking_qty') - $deliveryChallans->sum('challan_qty') }}</td>
    </tr>
</table>

<table class="reportTable" style="margin-top: 30px;">
    <thead>
    <tr>
        <th>Item</th>
        <th>Item Desc.</th>
        <th>Color</th>
        <th>Size</th>
        <th>Garments Qty</th>
        <th>UOM</th>
        <th>Approval Shade/Code</th>
        <th>Floor</th>
        <th>Room</th>
        <th>Rack</th>
        <th>Shelf</th>
        <th>Bin</th>
        <th>Issue Date</th>
        <th>Issue Qty</th>
        <th>Return Qty</th>
        <th>Issue Balanced</th>
        <th>Issue To</th>
        <th>Issue Purpose</th>
        <th>Remarks</th>
    </tr>
    </thead>

    <tbody>
    @foreach($details as $detail)
        <tr>
            <td>{{ $detail['item_name'] }}</td>
            <td>{{ $detail['item_description'] }}</td>
            <td>{{ $detail['color'] }}</td>
            <td>{{ $detail['size'] }}</td>
            <td>{{ $detail['planned_garments_qty'] }}</td>
            <td>{{ $detail['uom_value'] }}</td>
            <td>{{ $detail['approval_shade_code'] }}</td>
            <td>{{ $detail['floor'] }}</td>
            <td>{{ $detail['room'] }}</td>
            <td>{{ $detail['rack'] }}</td>
            <td>{{ $detail['shelf'] }}</td>
            <td>{{ $detail['bin'] }}</td>
            <td>{{ $detail['issue_date'] }}</td>
            <td>{{ $detail['issue_qty'] }}</td>
            <td>{{ $detail['issue_return_qty'] }}</td>
            <td>{{ $detail['issue_balance'] }}</td>
            <td>{{ $detail['issue_to'] }}</td>
            <td>{{ $detail['issue_purpose'] }}</td>
            <td>{{ $detail['remarks'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
