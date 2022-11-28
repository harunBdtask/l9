<table class="reportTable">
    <thead>
    <tr>
        <th>Sl</th>
        <th>Floor</th>
        <th>Rack</th>
        <th>Rack Position</th>
        <th>Buyer</th>
        <th>Order/ Style</th>
        <th>Purchase Order</th>
        <th>Garments Quantity</th>
    </tr>
    </thead>
    <tbody>
    @if($rack_carton_positions->count())
        @php
            $quantity = 0;
        @endphp
        @foreach($rack_carton_positions as $rack_carton_position)
            @php
                $quantity += $rack_carton_position->warehouseCarton->garments_qty;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $rack_carton_position->warehouseFloor->name }}</td>
                <td>{{ $rack_carton_position->warehouseRack->name }}</td>
                <td>{{ $rack_carton_position->position_no }}</td>
                <td>{{ $rack_carton_position->warehouseCarton->buyer->name }}</td>
                <td>{{ $rack_carton_position->warehouseCarton->order->style_name }}</td>
                <td>{{ $rack_carton_position->warehouseCarton->purchaseOrder->po_no }}</td>
                <td>{{ $rack_carton_position->warehouseCarton->garments_qty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="7">Total</th>
            <th>{{ $quantity }}</th>
        </tr>
    @else
        <tr>
            <th colspan="8">No Carton is allocated</th>
        </tr>
    @endif
    </tbody>
</table>