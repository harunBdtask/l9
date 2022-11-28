
<table style="width:100%" class="reportTable" id="fixTable1">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Order/Style</th>
        <th>PO</th>
        <th>PO Qty</th>
        <th>Input Qty</th>
        <th>Unit</th>
        <th>Line</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @php
        $grandTotalInputQty = 0;
    @endphp
    @forelse($daily_input_status->groupBy('buyer_id') as $key => $value)
        @php
            $totalInputQty = 0;
        @endphp
        @foreach($value as $data)
            <tr>
                <td>{{ $data->buyer->name }}</td>
                <td>{{ $data->order->style_name }}</td>
                <td>{{ $data->purchaseOrder->po_no }}</td>
                <td>{{ $data->purchaseOrder->purchaseOrderDetails->sum('quantity') }}</td>
                <td>{{ $data->sewing_input }}</td>
                <td>{{ $data->floor->floor_no }}</td>
                <td>{{ $data->line->line_no }}</td>
            </tr>

            @php
                $totalInputQty += $data->sewing_input;
            @endphp
        @endforeach
        @if(count($value) > 0)
            <tr>
                <th colspan="4">Total</th>
                <th>{{ $totalInputQty }}</th>
                <th colspan="2"></th>
            </tr>
            @php
                $grandTotalInputQty += $totalInputQty;
            @endphp
        @endif
    @empty
        <tr>
            <td colspan="7"> No data found</td>
        </tr>
    @endforelse
    <tr>
        <th colspan="4">Grand Total</th>
        <th>{{ $grandTotalInputQty }}</th>
        <th colspan="2"></th>
    </tr>
    </tbody>
</table>