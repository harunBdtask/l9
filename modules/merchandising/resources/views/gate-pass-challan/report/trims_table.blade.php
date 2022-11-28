<table style="margin-top: 2rem">
    <tr>
        <th class="text-center">Buyer</th>
        <th class="text-center">Item Group</th>
        <th class="text-center">Item Description</th>
        <th class="text-center">Style</th>
        <th class="text-center">PO No</th>
        <th class="text-center">Purpose</th>
        <th class="text-center">UOM</th>
        <th class="text-center">QTY</th>
        <th class="text-center">Remarks</th>
    </tr>


    @foreach($data['goods_details'] as $key => $detail)
        <tr style="text-align: center">
            <td> {{ $detail['buyer'] ?? null }} </td>
            <td> {{ $detail['item_group'] ?? null }} </td>
            <td> {{ $detail['item_description'] ?? null }} </td>
            <td> {{ $detail['style_name'] ?? null }} </td>
            <td> {{ $detail['po_no'] ?? null }} </td>
            <td> {{ $detail['purpose'] ?? null }} </td>
            <td> {{ $detail['uom'] ?? null }} </td>
            <td> {{ $detail['qty'] ?? null }} </td>
            <td> {{ $detail['remarks'] ?? null }} </td>
        </tr>
    @endforeach

    <tr style="text-align: center">
        <th colspan="7">Total</th>
        <th>{{ collect($data['goods_details'])->sum('qty') ?? 0 }}</th>
        <th></th>
    </tr>
</table>
