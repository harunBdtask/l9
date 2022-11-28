<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <table class="reportTable">
            <tbody>
            <tr>
                <td class="text-left"><b>Buyer</b></td>
                <td class="text-left">{{ $buyer }}</td>
                <td class="text-left"><b>Shipment Date</b></td>
                <td class="text-left">{{ $shipment_date }}</td>
            </tr>
            <tr>
                <td class="text-left"><b>Buying Agent</b></td>
                <td class="text-left">{{ $buying_agent }}</td>
                <td class="text-left"><b>Order Qty</b></td>
                <td class="text-left">{{ $order_qty }}</td>
            </tr>
            <tr>
                <td class="text-left"><b>{{ localizedFor('Style') }}</b></td>
                <td class="text-left">{{ $style_name }}</td>
                <td class="text-left"><b>Total Number of POs</b></td>
                <td class="text-left">{{ $total_po_no }}</td>
            </tr>
            <tr>
                <td class="text-left"><b>Booking No</b></td>
                <td class="text-left">{{ $booking_no }}</td>
                <td class="text-left"><b>Item</b></td>
                <td class="text-left">{{ $item }}</td>
            </tr>
            <tr>
                <td class="text-left"><b>Repeat No</b></td>
                <td class="text-left">{{ $repeat_no }}</td>
                <td class="text-left"><b>Fabric Booking No</b></td>
                <td class="text-left">{{ $fabric_booking_no }}</td>
            </tr>
            <tr>
                <td class="text-left"><b>Dealing Merchant</b></td>
                <td class="text-left">{{ $dealing_merchant }}</td>
                <td class="text-left"><b>Fabrication</b></td>
                <td class="text-left">{{ $fabrication }}</td>
            </tr>
            <tr>
                <td class="text-left"><b>Team Name</b></td>
                <td class="text-left">{{ $team_name }}</td>
                <td class="text-left"><b>Excess Cutting Percent</b></td>
                <td class="text-left">{{ $ex_cut_percent }}%</td>
            </tr>
            <tr>
                <td class="text-left"><b>Season</b></td>
                <td class="text-left">{{ $season }}</td>
                <td class="text-left"><b>Remarks</b></td>
                <td class="text-left">{{ $remarks }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<br><br>
<table class="reportTable">
    <thead>
    <tr style="background: aliceblue">
        <th>COLOR</th>
        <th>PO/NO</th>
        @foreach($sizes as $size)
            <th>{{ strtoupper($size->name) }}</th>
        @endforeach
        <th>TOTAL</th>
    </tr>
    </thead>
    <tbody>
    @php
        $grandSizeWiseQty = [];
    @endphp
    @foreach($color_wise_po as $poWise)
        @php
            $colorSizeWiseQty = [];
        @endphp
        @foreach($poWise as $data)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $poWise->count() }}" style="text-align: left;">{{ $data['color'] }}</td>
                @endif
                <td style="text-align: left;">{{ $data['po_no'] }}</td>
                @foreach($sizes as $size)
                    @php
                        $colorSizeWiseQty[$size->name] =
                            ($colorSizeWiseQty[$size->name] ?? 0) + $data[$size->name]['qty'];
                        $grandSizeWiseQty[$size->name] =
                            ($grandSizeWiseQty[$size->name] ?? 0) + $data[$size->name]['qty'];
                    @endphp
                    <td style="text-align: right;">{{ $data[$size->name]['qty'] }}</td>
                @endforeach
                <td style="text-align: right;">{{ $data['total_qty'] }}</td>
            </tr>
        @endforeach
        <tr style="background: gainsboro">
            <td colspan="2"><b>TOTAL</b></td>
            @foreach($sizes as $size)
                <td style="text-align: right;">
                    <b>{{ $colorSizeWiseQty[$size->name] }}</b>
                </td>
            @endforeach
            <td style="text-align: right;"><b>{{ array_sum($colorSizeWiseQty) }}</b></td>
        </tr>
    @endforeach
    <tr style="background: #C7C7C7FF">
        <td colspan="2"><b>GRAND TOTAL</b></td>
        @foreach($sizes as $size)
            <td style="text-align: right;">
                <b>{{ $grandSizeWiseQty[$size->name] }}</b>
            </td>
        @endforeach
        <td style="text-align: right;"><b>{{ array_sum($grandSizeWiseQty) }}</b></td>
    </tr>
    </tbody>
</table>
