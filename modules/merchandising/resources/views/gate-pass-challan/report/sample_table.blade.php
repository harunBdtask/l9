
<table style="margin-top: 2rem">
    <tr>
        <th class="text-center">S/N</th>
        <th class="text-center">Item</th>
        <th class="text-center">Description</th>
        <th class="text-center">Buyer</th>
        <th class="text-center">Style</th>
        <th class="text-center">PO</th>
        <th class="text-center">Color</th>
        <th class="text-center">Size</th>
        <th class="text-center">Quantity</th>
        <th class="text-center">UOM</th>
        <th class="text-center">Unit Price</th>
        <th class="text-center">Total Price</th>
        <th class="text-center">Remarks</th>
    </tr>
    @php
        $totalPrice = 0;
    @endphp
    @foreach($data['goods_details'] as $key => $detail)
        @php
            $price = isset($detail['avg_rate_pc_set']) ? (double)$detail['avg_rate_pc_set'] * (double)$detail['qty'] : 0;
            $totalPrice += $price;
        @endphp
        <tr style="text-align: center">
            <td>{{ $key+1 }}</td>
            <td>{{ $detail['sample_type'] ?? '' }}</td>
            <td>{{ $detail['item_description'] ?? '' }}</td>
            <td>{{ $detail['buyer'] ?? '' }}</td>
            <td>{{ $detail['style_name'] ?? ''  }}</td>
            <td>{{ $detail['po_no'] ?? '' }}</td>
            <td>{{ $detail['color'] ?? '' }}</td>
            <td>{{ $detail['size'] ?? '' }}</td>
            <td>{{ $detail['qty'] ?? '' }}</td>
            <td>{{ $detail['uom'] ?? '' }}</td>
            <td>{{ $detail['avg_rate_pc_set'] ?? 0 }}</td>
            <td>{{ $price }}</td>
            <td>{{ $detail['remarks'] ?? '' }}</td>
        </tr>
    @endforeach
    <tr style="text-align: center">
        <td><strong>Total</strong></td>
        <td colspan="7"></td>
        <td>{{ collect($data['goods_details'])->sum('qty') ?? 0 }}</td>
        <td colspan="2"></td>
        <td>{{ $totalPrice }}</td>
        <td></td>
    </tr>
    <tr>
        @php
            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);

        @endphp
        <td colspan="13" class="text-center">
            <strong>Total Amount in BDT:</strong> {{ ucwords($digit->format(number_format($totalPrice,2))) }}
        </td>
    </tr>
</table>
