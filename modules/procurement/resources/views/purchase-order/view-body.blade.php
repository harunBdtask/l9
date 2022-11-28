<div class="row">
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-3">Supplier Name</dt>
            <dd class="col-sm-9">: {{ $purchaseOrder->supplier->name }}</dd>
            <dt class="col-sm-3">Supplier Address</dt>
            <dd class="col-sm-9">: {{ $purchaseOrder->supplier->address_1 }}</dd>
        </dl>
    </div>
    <div class="col-md-6">
        <dl class="row">
            <dt class="col-sm-3">Date</dt>
            <dd class="col-sm-9">{{ date('d M Y', strtotime($purchaseOrder->po_date)) }}</dd>
            <dt class="col-sm-3">PO ID</dt>
            <dd class="col-sm-9">{{ $purchaseOrder->po_number }}</dd>
        </dl>
    </div>
    <div class="col-md-12">
        <table class="reportTable">
            <tr>
                <th>Sl</th>
                <th>Item</th>
                <th>Item Description</th>
                <th>UOM</th>
                <th>Unit Price</th>
                <th>Qty</th>
                <th>Amount</th>
            </tr>
            @php $totalAmount = 0 @endphp
            @forelse ($purchaseOrder->poDetails as $detail)
                @php  $totalAmount += $detail->unit_price * $detail->qty @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $detail->item->item_group }}</td>
                    <td>{{ $detail->quotation->item_description }}</td>
                    <td>{{ $detail->quotation->uom->unit_of_measurement }}</td>
                    <td>{{ $detail->unit_price }}</td>
                    <td>{{ $detail->qty }}</td>
                    <td>{{ $detail->unit_price * $detail->qty }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6"></td>
                </tr>
            @endforelse

            <tr>
                <td colspan="6" class="text-right">Total</td>
                <td>{{ $totalAmount }}</td>
            </tr>
        </table>
    </div>
</div>
