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
    <tr>
        @if($reports->count())
            @php
                $temp_floor = '';
                $temp_rack = '';
                $floor_i = 0;
                $rack_i = 0;
                $floors_array = [];
                $racks_array = [];

                foreach ($reports as $report) {
                    if ($temp_floor != $report->warehouse_floor_id) {
                        $floors_array[$floor_i] = $report->warehouseFloor->name;
                        $floor_i++;
                    }
                    if ($temp_rack != $report->warehouse_rack_id) {
                        $racks_array[$rack_i] = $report->warehouseRack->name;
                        $rack_i++;
                    }
                    $temp_floor = $report->warehouse_floor_id;
                    $temp_rack = $report->warehouse_rack_id;
                }
                $floors = implode(', ', $floors_array);
                $racks = implode(', ', $racks_array);
            @endphp
            <td>{{ $reports->first()->buyer->name }}</td>
            <td>{{ $reports->first()->order->style_name }}</td>
            <td>{{ $reports->first()->purchaseOrder->po_no }}</td>
            <td>All</td>
            <td>{{ $reports->count() }}</td>
            <td>{{ $reports->sum('garments_qty') }}</td>
            <td>{{ $floors }}</td>
            <td>{{ $racks }}</td>
        @else
            <td colspan="8">No Data</td>
        @endif
    </tr>
    </tbody>
</table>