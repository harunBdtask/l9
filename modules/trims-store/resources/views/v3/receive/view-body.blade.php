<table class="reportTable">
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Company Name:</b>
        </td>
        <td style="width: 35%;">{{ $receive->factory->factory_name ?? '' }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Challan No:</b>
        </td>
        <td style="width: 35%;">{{ $receive->challan_no }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Address:</b>
        </td>
        <td style="width: 35%;">{{ $receive->factory->factory_address ?? '' }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Receive Date:</b>
        </td>
        <td style="width: 35%;">{{ $receive->receive_date }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Receive Basis:</b>
        </td>
        <td style="width: 35%;">{{ $receive->receive_basis }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>PI Number:</b>
        </td>
        <td style="width: 35%;">{{ $receive->pi_numbers }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Store:</b>
        </td>
        <td style="width: 35%;">{{ $receive->store->name ?? '' }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>PI Rcv Date:</b>
        </td>
        <td style="width: 35%;">{{ $receive->pi_receive_date }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Pay Mode:</b>
        </td>
        <td style="width: 35%;">{{ $receive->pay_mode }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>LC NO:</b>
        </td>
        <td style="width: 35%;">{{ $receive->lc_no }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Remarks:</b>
        </td>
        <td style="width: 35%;">{{ $receive->remarks }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>LC Rcv Date:</b>
        </td>
        <td style="width: 35%;">{{ $receive->lc_receive_date }}</td>
    </tr>
</table>

<table class="reportTable" style="margin-top: 30px;">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style Name</th>
        <th>PO Numbers</th>
        <th>Unique ID</th>
        <th>Garments Item Name</th>
        <th>Item Name</th>
        <th>Item Code</th>
        <th>Supplier Name</th>
        <th>Sup. Address</th>
        <th>Brand/Sup Ref.</th>
        <th>Item Description</th>
        <th>Gmts. Color</th>
        <th>Gmts. Size</th>
        <th>Gmts Ord QTY.</th>
        <th>BK WO/PI Qty.</th>
        <th>Receive Qty.</th>
        <th>Reject Qty.</th>
        <th>Over Rcv.Qty.</th>
        <th>Order UOM</th>
        <th>Currency</th>
        <th>Unit Price</th>
        <th>Exchange Rate</th>
        <th>Amount</th>
        <th>Floor</th>
        <th>Room</th>
        <th>Rack</th>
        <th>Shelf</th>
        <th>Bin</th>
        <th>Remarks</th>
    </tr>
    </thead>

    <tbody>
    @foreach($receive->details as $detail)
        <tr>
            <td>{{ $detail->buyer->name ?? '' }}</td>
            <td>{{ $detail->order->style_name ?? '' }}</td>
            <td>{{ $detail->po_numbers }}</td>
            <td>{{ $detail->unique_id }}</td>
            <td>{{ $detail->garments_item_name }}</td>
            <td>{{ $detail->itemGroup->item_group }}</td>
            <td>{{ $detail->item_code }}</td>
            <td>{{ $detail->supplier->name }}</td>
            <td>{{ $detail->supplier->address_1 }}</td>
            <td>{{ $detail->brand_name }}</td>
            <td>{{ $detail->item_description }}</td>
            <td>{{ $detail->color->name }}</td>
            <td>{{ $detail->size->name }}</td>
            <td>{{ $detail->order_qty }}</td>
            <td>{{ $detail->wo_qty }}</td>
            <td>{{ $detail->receive_qty }}</td>
            <td>{{ $detail->reject_qty }}</td>
            <td>{{ $detail->over_receive_qty }}</td>
            <td>{{ $detail->uom->unit_of_measurement }}</td>
            <td>{{ $detail->currency->currency_name }}</td>
            <td>{{ $detail->rate }}</td>
            <td>{{ $detail->exchange_rate }}</td>
            <td>{{ (double)$detail->rate * (double)$detail->receive_qty }}</td>
            <td>{{ $detail->floor->name }}</td>
            <td>{{ $detail->room->name }}</td>
            <td>{{ $detail->rack->name }}</td>
            <td>{{ $detail->shelf->name }}</td>
            <td>{{ $detail->bin->name }}</td>
            <td>{{ $detail->remarks }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
