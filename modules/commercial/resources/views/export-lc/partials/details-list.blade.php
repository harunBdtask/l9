<table class="reportTable">
    <thead>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO NO</th>
        <th>PO Quantity</th>
        <th>PO Value</th>
        <th>Attach Qty</th>
        <th>Rate</th>
        <th>Attach Value</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    @php $totalPoValue = 0;@endphp
    @if(count($details))
        @foreach($details as $detail)
            <tr>
                @php
                    $poQty = $detail->po->po_quantity;
                    $poValue = $detail->po->po_quantity * $detail->po->avg_rate_pc_set;
                    $totalPoValue += $poValue;
                @endphp
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->orders->buyer->name }}</td>
                <td>{{ $detail->orders->style_name }}</td>
                <td>{{ $detail->po->po_no }}</td>
                <td>{{ $poQty }}</td>
                <td>{{ $poValue }}</td>
                <td>{{ $detail->attach_qty }}</td>
                <td>{{ $detail->rate }}</td>
                <td>{{ $detail->attach_value }}</td>
                <td>
                    <button style="background-color: #0d47a1; color: #fff;" class="edit-detail"
                            data-po-id="{{ $detail->po->id }}">Edit
                    </button>
                    <button style="background-color: #c1360b; color: #fff;" class="delete-detail"
                            data-id="{{ $detail->id }}">Delete
                    </button>
                </td>
            </tr>
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <td>{{ collect($details)->pluck('po')->sum('po_quantity') }}</td>
            <td>{{ $totalPoValue }}</td>
            <td>{{ collect($details)->sum('attach_qty') }}</td>
            <td></td>
            <td>{{ collect($details)->sum('attach_value') }}</td>
            <td></td>
        </tr>
    @else
        <tr>
            <td colspan="8">No Data Found</td>
        </tr>
    @endif
    </tbody>
</table>
