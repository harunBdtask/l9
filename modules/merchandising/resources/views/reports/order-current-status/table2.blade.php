<div class="row" style="width: 50%">
{!! $header !!}
</div>
<table class="reportTable">
    <thead>
    <tr>
        <td colspan="26" style="text-align: center"><b>Order Status Report</b></td>
    </tr>
    <tr>
        <td><b>Sl</b></td>
        <td><b>Ex-Factory Date</b></td>
        <td><b>Order Entry</b></td>
        <td><b>TNA</b></td>
        <td><b>Budget</b></td>
        <td><b>Fabric Booking</b></td>
        <td><b>Trims Booking</b></td>
        <td><b>Embellishment Booking</b></td>
        <td><b>PP Meeting</b></td>
        <td><b>Sewing Compolite</b></td>
        <td><b>Inspection</b></td>
        <td><b>Comments/Remarks</b></td>
    </tr>
    </thead>
    <tbody>
    @if(isset($orders) && count($orders) > 0)
        @foreach($orders as $item)
            <tr>
                <td style="text-align: left">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $item['shipment_date'] }}</td>
                <td>{{ $item['order_entry'] }}</td>
                <td>{{ $item['tna'] }}</td>
                <td>{{ $item['budget'] }}</td>
                <td>{{ $item['fabric_booking'] }}</td>
                <td>{{ $item['trims_booking'] }}</td>
                <td>{{ $item['embl_booking'] }}</td>
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
