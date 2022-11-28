<p>Dear Sir,</p>
<p>There is an fabric cons. failure of Buyer ({{ $data->buyerWithoutGlobalScope->name }}) Style
    ({{ $data->orderWithoutGlobalScope->style_name }}) Item ({{ $data->garmentsItem->name }}).</p>
<p>For further bundle card generation need to approve. Click the link below for approving fabric cons failure.</p>
<a href="{{ $url }}">Approval Link</a>

<table style="margin-top: 20px" class="table">
    <tbody>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Max Qty / Bundle</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['max_quantity'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Booking Consumption</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['booking_consumption'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Booking Dia</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['booking_dia'] }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Buyer Name</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['buyer']['name'] ?? '' }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Style.</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['order']['style_name'] ?? '' }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>PO</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data->bundleCards->pluck('purchaseOrder.po_no')->unique()->implode(', ') ?? '' }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Item</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data->garmentsItem->name }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Color</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data->bundleCards->pluck('color.name')->unique()->implode(', ') ?? '' }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Cutting No.</strong></td>
        <td style="text-align: left; border: 1px solid black;">
            @php
                $cuttingNo = $data['cutting_no'];

                if ($data['colors']) {
                    $cuttingNosWithColor = explode('; ', $cuttingNo);

                    $cuttingNo = '';
                    foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
                        $cutting = explode(': ', $cuttingNoWithColor);
                        $cuttingNo .= $cutting[1] . '; ';
                    }
                    $cuttingNo = rtrim($cuttingNo, '; ');
                }
            @endphp
            {{ $cuttingNo }}
        </td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Cutting Table</strong></td>
        <td style="text-align: left; border: 1px solid black;">
            {{ $data['cuttingTableWithoutGlobalScope']['table_no'] }}
        </td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Part</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['partWithoutGlobalScope']['name'] ?? '' }}</td>
    </tr>
    <tr>
        <td style="text-align: left; border: 1px solid black;"><strong>Type</strong></td>
        <td style="text-align: left; border: 1px solid black;">{{ $data['typeWithoutGlobalScope']['name'] ?? '' }}</td>
    </tr>
    </tbody>
</table>

<table style="margin-top: 15px" class="table">
    <thead>
    <tr>
        <th style="text-align: center;border: 1px solid black">Marker Piece</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="text-align: center;border: 1px solid black">{{ $data->marker_piece }}</td>
    </tr>
    </tbody>
</table>

<table style="margin-top: 15px" class="table">
    <thead>
    <tr>
        <th style="text-align: center;border: 1px solid black">Lot No</th>
        <th style="text-align: center;border: 1px solid black" colspan="2">Range</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data['lot_ranges'] as $lot_range)
        <tr>
            <td style="text-align: center;border: 1px solid black">{{ $lot_range['lot_no'] }}</td>
            <td style="text-align: center;border: 1px solid black">{{ $lot_range['from'] }}</td>
            <td style="text-align: center;border: 1px solid black">{{ $lot_range['to'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table style="margin-top: 15px" class="table">
    <thead>
    <tr>
        <th style="border: 1px solid black; text-align: center"></th>
        <th style="border: 1px solid black; text-align: center">DIA</th>
        <th style="border: 1px solid black; text-align: center">GSM</th>
        <th style="border: 1px solid black; text-align: center">CONS</th>
        <th style="border: 1px solid black; text-align: center">COMMENTS</th>
        <th style="border: 1px solid black; text-align: center">RESULT</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="border: 1px solid black; text-align: center">BOOKING</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['booking']['dia'] }}</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['booking']['gsm'] }}</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['booking']['consumption'] }}</td>
        <td style="border: 1px solid black; text-align: center" rowspan="3">{{ $summaryReport['comments'] }}</td>
        <td style="border: 1px solid black; text-align: center" rowspan="3">{{ $summaryReport['result'] }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid black; text-align: center">ACTUAL</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['actual']['dia'] }}</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['actual']['gsm'] }}</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['actual']['consumption'] }}</td>
    </tr>
    <tr>
        <td style="border: 1px solid black; text-align: center">DEVIATION</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['deviation']['dia'] }}</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['deviation']['gsm'] }}</td>
        <td style="border: 1px solid black; text-align: center">{{ $summaryReport['deviation']['consumption'] }}</td>
    </tr>
    </tbody>
</table>

