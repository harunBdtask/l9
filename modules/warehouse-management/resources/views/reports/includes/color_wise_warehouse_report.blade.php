@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
<table class="reportTable {{ $tableHeadColorClass }}" style="border-collapse: collapse;">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Order/Style</th>
        <th>Purchase Order</th>
        <th>Color</th>
        <th>No of Carton</th>
        <th>Garments Qty</th>
        <th>Floors</th>
        <th>Racks</th>
    </tr>
    </thead>
    <tbody>
    @if($reports->count())
        @php
            $total_no_of_carton = 0;
            $total_garments_qty = 0;
        @endphp
        @foreach($reports->groupBy('warehouse_floor_id') as $reportByFloor)
            @php
                $buyer = $reportByFloor->first()->buyer->name;
                $order = $reportByFloor->first()->order->style_name;
                $purchase_order = $reportByFloor->first()->purchaseOrder->po_no;
                $color = $reportByFloor->first()->color_name;
                $warehouse_floor_no = $reportByFloor->first()->warehouseFloor->name;
            @endphp
            @foreach($reportByFloor->groupBy('warehouse_rack_id') as $reportByRack)
                @php
                    $rack = $reportByRack->first()->warehouseRack->name;
                    $no_of_carton = 0;
                    foreach($reportByRack->groupBy('id') as $reportByCartonNo) {
                        $no_of_carton += 1;
                    }
                    $garments_qty = $reportByRack->sum('quantity');

                    $total_no_of_carton += $no_of_carton;
                    $total_garments_qty += $garments_qty;
                @endphp
                <tr>
                    <td>{{ $buyer }}</td>
                    <td>{{ $order }}</td>
                    <td>{{ $purchase_order }}</td>
                    <td>{{ $color }}</td>
                    <td>{{ $no_of_carton }}</td>
                    <td>{{ $garments_qty }}</td>
                    <td>{{ $warehouse_floor_no }}</td>
                    <td>{{ $rack }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{ $total_no_of_carton }}</th>
            <th>{{ $total_garments_qty }}</th>
            <th colspan="2">&nbsp;</th>
        </tr>
    @else
        <tr>
            <td colspan="8">No Data</td>
        </tr>
    @endif
    </tbody>
</table>