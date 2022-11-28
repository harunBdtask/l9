<table class="reportTable" id="fixTable1">
    <thead>
    <tr>
        <th rowspan="2">Sewing Floor</th>
        <th rowspan="2">Line No</th>
        <th rowspan="2">Challan No</th>
        <th rowspan="2">Buyer</th>
        <th rowspan="2">Style</th>
        <th rowspan="2">PO</th>
        <th rowspan="2">Item</th>
        <th rowspan="2">Color</th>
        <th rowspan="2">Order Qty</th>
        <th colspan="{{ $sizes->count() }}">Size</th>
        <th rowspan="2">Today Total</th>
        <th rowspan="2">G. Total</th>
    </tr>
    <tr>
        @foreach($sizes as $size)
            <th>{{ $size->name }}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php
        $totalTodaySum = 0;
    @endphp
    @foreach($reportData as $data)
        <tr>
            <td>{{ $data->floorsWithoutGlobalScopes->floor_no }}</td>
            <td>{{ $data->linesWithoutGlobalScopes->line_no }}</td>
            <td>{{ $data->challan_no }}</td>
            <td>{{ $data->buyer->name }}</td>
            <td>{{ $data->order->style_name }}</td>
            <td>{{ $data->purchaseOrder->po_no }}</td>
            <td>{{ $data->garmentsItem->name }}</td>
            <td>{{ $data->color->name }}</td>
            <td>{{ $data->purchaseOrder->po_quantity }}</td>
            @foreach($sizes as $size)
                @php
                    $totalTodaySum += ($data->sizes[$size->name] ?? 0);
                @endphp
                <td>{{ $data->sizes[$size->name] ?? 0 }}</td>
            @endforeach
            <td>{{ $data->sizes->sum() }}</td>
            <td>{{ $data->previous_total }}</td>
        </tr>
    @endforeach
    <tr>
        <td colspan="9">
            <strong>TOTAL</strong>
        </td>
        @foreach($sizes as $size)
            <td>
                <strong>{{ $reportData->pluck('sizes')->sum($size->name) }}</strong>
            </td>
        @endforeach
        <td>
            <strong>{{ $totalTodaySum }}</strong>
        </td>
        <td>
            <strong>{{ $reportData->sum('previous_total') }}</strong>
        </td>
    </tr>
    </tbody>
</table>
