<table class="reportTable">
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>From Company / Factory Name:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->factory->factory_name ?? '' }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Gate Pass No:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->gate_pass_no }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Location:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->factory->factory_address ?? '' }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Return Date:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->return_date }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Returned Source:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->source }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Unique ID:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->unique_id }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Return Basis:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->return_basis }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>From Store Name:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->store->name ?? '' }}</td>
    </tr>
    <tr>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Ready To Approve:</b>
        </td>
        <td style="width: 35%;">{{ $receiveReturn->ready_to_approve_value }}</td>
        <td style="width: 15%; text-align: left; padding-left: 5px;">
            <b>Remarks:</b>
        </td>
        <td colspan="3">{{ $receiveReturn->remarks }}</td>
    </tr>
</table>

<table class="reportTable" style="margin-top: 30px;">
    <thead>
    <tr>
        <th>Returned To</th>
        <th>Address</th>
        <th>Buyer</th>
        <th>Style Name</th>
        <th>PO Numbers</th>
        <th>PI Number</th>
        <th>Garments Item Name</th>
        <th>Item Name</th>
        <th>Item Code</th>
        <th>Sensitivity</th>
        <th>Brand/Sup Ref.</th>
        <th>Item Description</th>
        <th>Gmts. Color</th>
        <th>Gmts. Size</th>
        <th>Gmts Ord QTY.</th>
        <th>BK WO/PI Qty.</th>
        <th>Receive Return Qty.</th>
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
    @foreach($receiveReturn->details as $detail)
        <tr>
            <td></td>
            <td></td>
            <td>{{ $detail->buyer->name ?? '' }}</td>
            <td>{{ $detail->order->style_name ?? '' }}</td>
            <td>{{ $detail->po_numbers }}</td>
            <td>{{ $detail->pi_numbers }}</td>
            <td>
                {{ collect($detail['order']['item_details']['details'])
                    ->pluck('item_name')
                    ->join(', ') ?? null }}
            </td>
            <td>{{ $detail->itemGroup->item_group }}</td>
            <td>{{ $detail->item_code }}</td>
            <td></td>
            <td>{{ $detail->brand_name }}</td>
            <td>{{ $detail->item_description }}</td>
            <td>{{ $detail->color->name }}</td>
            <td>{{ $detail->size->name }}</td>
            <td>{{ $detail->order_qty }}</td>
            <td>{{ $detail->wo_qty }}</td>
            <td>{{ $detail->receive_return_qty }}</td>
            <td>{{ $detail->uom->unit_of_measurement }}</td>
            <td>{{ $detail->currency->currency_name }}</td>
            <td>{{ $detail->rate }}</td>
            <td>{{ $detail->exchange_rate }}</td>
            <td>{{ $detail->amount }}</td>
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
