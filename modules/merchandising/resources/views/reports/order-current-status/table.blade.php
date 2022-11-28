<div class="row" style="width: 50%">
{!! $header !!}
</div>
<table class="reportTable">
    <thead>
    <tr>
        <td colspan="26" style="text-align: center"><b>Current Order Status Report</b></td>
    </tr>
    <tr>
        <td><b>Sl</b></td>
        <td><b>Buyer</b></td>
        <td><b>INQUIRY NO (Unique Id)</b></td>
        <td><b>Style No</b></td>
        <td><b>Shipment Date</b></td>
        <td><b>Dealing Merchant</b></td>
        <td><b>Price Quat.</b></td>
        <td><b>Order Entry</b></td>
        <td><b>TNA</b></td>
        <td><b>Budget</b></td>
        <td><b>Fabric Booking</b></td>
        <td><b>Trims Booking</b></td>
        <td><b>Embellishment Booking</b></td>
        <td><b>PI Bunch</b></td>
        <td><b>BOM Comply</b></td>
        <td><b>Size Set</b></td>
        <td><b>PP Meeting</b></td>
        <td><b>Finish Fabric Pick Up Date</b></td>
        <td><b>Pre Embellishment (Print/Embr)</b></td>
        <td><b>RFI</b></td>
        <td><b>Line Allocation</b></td>
        <td><b>Sewing Compolite</b></td>
        <td><b>PL</b></td>
        <td><b>Inspection</b></td>
        <td><b>EX Factory</b></td>
        <td><b>Comments/Remarks</b></td>
    </tr>
    </thead>
    <tbody>
    @if(isset($orders) && count($orders) > 0)
        @foreach($orders as $item)
            <tr>
                <td style="text-align: left">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item['buyer_name'] }}</td>
                <td>{{ $item['unique_id'] }}</td>
                <td>{{ $item['style_name'] }}</td>
                <td>{{ $item['shipment_date'] }}</td>
                <td>{{ $item['dealing_merchant'] }}</td>
                <td>
                    {{ $item['price_quotation'] }} <br>
                    {{ $item['price_quotation_created_at'] ? date("F j, Y, g:i a", strtotime($item['price_quotation_created_at'])) : '' }}
                </td>
                <td>
                    {{ $item['order_entry'] }} <br>
                    {{ $item['order_created_at'] ? date("F j, Y, g:i a", strtotime($item['order_created_at'])) : '' }}
                </td>
                <td>{{ $item['tna'] }}</td>
                <td>
                    {{ $item['budget'] }} <br>
                    {{ $item['budget_created_at'] ? date("F j, Y, g:i a", strtotime($item['budget_created_at'])) : '' }}
                </td>
                <td>{{ $item['fabric_booking'] }}</td>
                <td>{{ $item['trims_booking'] }}</td>
                <td>{{ $item['embl_booking'] }}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach

    @else
        <tr>
            <td colspan="26" style="text-align: center">No data available</td>
        </tr>
    @endif
    </tbody>
</table>
