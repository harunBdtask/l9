<table class="reportTable">
    <thead>
    <tr>
        <th>PO Qty</th>
        <th>Capacity Req.(minute)</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>{{ $purchase_order->po_quantity }}</td>
        <td>{{ $purchase_order->po_quantity * $smv }}</td>
    </tr>
    </tbody>
</table>