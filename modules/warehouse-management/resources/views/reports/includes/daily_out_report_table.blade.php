<thead>
<tr>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>PO</th>
    <th>No of Carton</th>
    <th>Garments Qty</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
    @php
        $t_carton_qty = 0;
        $t_garments_qty = 0;
    @endphp
    @foreach($reports->groupBy('purchase_order_id') as $reportByPO)
        @php
            $buyer = $reportByPO->first()->buyer->name;
            $order = $reportByPO->first()->order->style_name;
            $purchase_order = $reportByPO->first()->purchaseOrder->po_no;
            $carton_qty = $reportByPO->sum('out_carton_qty');
            $garments_qty = $reportByPO->sum('out_garments_qty');

            $t_carton_qty += $carton_qty;
            $t_garments_qty += $garments_qty;
        @endphp
        <tr>
            <td>{{ $buyer }}</td>
            <td>{{ $order }}</td>
            <td>{{ $purchase_order }}</td>
            <td>{{ $carton_qty }}</td>
            <td>{{ $garments_qty }}</td>
        </tr>
    @endforeach
    <tr>
        <th colspan="3">Total</th>
        <th>{{ $t_carton_qty }}</th>
        <th>{{ $t_garments_qty }}</th>
    </tr>
@else
    <tr>
        <td colspan="5">No Data</td>
    </tr>
@endif
</tbody>
