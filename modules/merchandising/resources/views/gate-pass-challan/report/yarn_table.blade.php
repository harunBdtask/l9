<table style="margin-top: 2rem">
    <tr>
        <th class="text-center">Yarn Count</th>
        <th class="text-center">Yarn Composition</th>
        <th class="text-center">Yarn Type</th>
        <th class="text-center">Yarn Brand</th>
        <th class="text-center">Lot</th>
        <th class="text-center">Purpose</th>
        <th class="text-center">UOM</th>
        <th class="text-center">QTY</th>
        <th class="text-center">Remarks</th>
    </tr>


    @foreach($data['goods_details'] as $key => $detail)
        <tr style="text-align: center">
            <td> {{ $detail['yarn_count'] ?? null }} </td>
            <td> {{ $detail['yarn_composition'] ?? null }} </td>
            <td> {{ $detail['yarn_type'] ?? null }} </td>
            <td> {{ $detail['yarn_brand'] ?? null }} </td>
            <td> {{ $detail['lot'] ?? null }} </td>
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
