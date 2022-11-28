<Table class="reportTable">
    <thead>
    <tr>
        <th>PO No</th>
        <th>PO Quantity</th>
        <th>PO Value</th>
        <th>Style Name</th>
        <th>Unique ID</th>
        <th style="width: 50px"></th>
    </tr>
    </thead>
    <tbody>
    @foreach($purchaseOrders as $po)
        <tr>
            <td>{{ $po->po_no }}</td>
            <td>{{ $po->po_quantity }}</td>
            <td>{{ $po->po_quantity * $po->avg_rate_pc_set }}</td>
            <td>{{ $po->order->style_name }}</td>
            <td>{{ $po->order->job_no }}</td>
            <td style="max-width: 50px">
                {{ Form::checkbox('po_id[]', $po->id, false) }}
            </td>
        </tr>
    @endforeach

    @if(count($purchaseOrders))
        <tr>
            <td colspan="5"></td>
            <td>
                <button type="button" class="close-po">
                    CLOSE
                </button>
            </td>
        </tr>
    @endif

    </tbody>

</Table>
