<table class="reportTable">
    <thead>
    <tr>
        <th>SL</th>
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
    @if(count($details))
        @foreach($details as $detail)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $detail->po->po_no }}</td>
                <td>{{ $detail->po->po_quantity }}</td>
                <td>{{ $detail->po->po_quantity * $detail->po->avg_rate_pc_set }}</td>
                <td>{{ $detail->attach_qty }}</td>
                <td>{{ $detail->rate }}</td>
                <td>{{ $detail->attach_value }}</td>
                <td>
                    <button style="background-color: #0d47a1; color: #fff;" class="edit-detail" data-po-id="{{ $detail->po->id }}">Edit</button>
                    <button style="background-color: #c1360b; color: #fff;" class="delete-detail" data-id="{{ $detail->id }}">Delete</button>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="8">No Data Found</td>
        </tr>
    @endif
    </tbody>
</table>
