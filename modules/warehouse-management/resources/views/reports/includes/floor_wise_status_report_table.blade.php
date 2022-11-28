<thead>
<tr>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>Purchase Order</th>
    <th>Garments Qty</th>
    <th>No of Carton</th>
    <th>Rack No</th>
    <th>Rack Position</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
    @php
        $t_carton_qty = 0;
        $t_garments_qty = 0;
    @endphp
    @foreach($reports as $report)
        @php
            $carton_qty = 1;
            $t_carton_qty += $carton_qty;
            $t_garments_qty += $report->warehouseCarton->garments_qty;
        @endphp
        <tr>
            <td>{{ $report->warehouseCarton->buyer->name }}</td>
            <td>{{ $report->warehouseCarton->order->style_name }}</td>
            <td>{{ $report->warehouseCarton->purchaseOrder->po_no }}</td>
            <td>{{ $report->warehouseCarton->garments_qty }}</td>
            <td>{{ $carton_qty }}</td>
            <td>{{ $report->warehouseRack->name }}</td>
            <td>{{ $report->position_no }}</td>
        </tr>
    @endforeach
    <tr>
        <th colspan="3">Total</th>
        <th>{{ $t_garments_qty }}</th>
        <th>{{ $t_carton_qty }}</th>
        <th colspan="2">&nbsp;</th>
    </tr>
@else
    <tr>
        <td colspan="7">No Data</td>
    </tr>
@endif
</tbody>