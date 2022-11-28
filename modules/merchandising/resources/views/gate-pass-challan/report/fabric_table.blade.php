<table style="margin-top: 2rem">
    <tr>
        <th class="text-center">Buyer</th>
        <th class="text-center">Fabric Description</th>
        <th class="text-center">Purpose</th>
        <th class="text-center">UOM</th>
        <th class="text-center">DIA</th>
        <th class="text-center">GSM</th>
        <th class="text-center">QTY</th>
        <th class="text-center">Remarks</th>
    </tr>


    @foreach($data['goods_details'] as $key => $detail)
        <tr style="text-align: center">
            <td> {{ $detail['buyer'] ?? null }} </td>
            <td> {{ $detail['new_fabric_composition'] ?? null }} </td>
            <td> {{ $detail['purpose'] ?? null }} </td>
            <td> {{ $detail['uom'] ?? null }} </td>
            <td> {{ $detail['dia'] ?? null }} </td>
            <td> {{ $detail['gsm'] ?? null }} </td>
            <td> {{ $detail['qty'] ?? null }} </td>
            <td> {{ $detail['remarks'] ?? null }} </td>
        </tr>
    @endforeach

    <tr style="text-align: center">
        <th colspan="6">Total</th>
        <th>{{ collect($data['goods_details'])->sum('qty') ?? 0 }}</th>
        <th></th>
    </tr>
</table>
