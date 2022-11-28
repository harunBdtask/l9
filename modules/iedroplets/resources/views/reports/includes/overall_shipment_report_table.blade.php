<table class="reportTable" style="border-collapse: collapse" id="fixTable">
    <thead class="text-center">
    <tr>
        <th>Buyer</th>
        <th>Order/Style No</th>
        <th>PO</th>
        <th>PO Qty</th>
        <th>Shipment Qty</th>
        <th>Total Shipment Value</th>
        <th>Plus Shipment</th>
        <th>Rejection/Short Qty</th>
        <th>Shipment Date</th>
        <th>Reason</th>
        {{--<th>Created At</th>--}}
    </tr>
    </thead>
    <tbody>
    @if(count($shipments))
        <?php
        $gTotalShipmentValue = 0;
        $gTotalPlusShipment = 0;
        ?>
        @foreach($shipments->groupBy('order_id') as $shipmentByOrder)
            <?php
            $totalShipmentValue = 0;
            $totalPlusShipment = 0;
            ?>
            @foreach($shipmentByOrder as $shipment)
                <tr>
                    <td>{{ $shipment->buyer->name ?? '-' }}</td>
                    <td>{{ $shipment->order->style_name ?? '-' }}</td>
                    <td>{{ $shipment->purchaseOrder->po_no }}</td>
                    <td>{{ $shipment->purchaseOrder->po_quantity }}</td>
                    <td>{{ $shipment->ship_quantity }}</td>
                    <?php
                    $totalValue = ($shipment->ship_quantity ?? 0) * ($shipment->purchaseOrder->unit_price ?? 0);
                    $plusShipment = $shipment->ship_quantity - $shipment->purchaseOrder->po_quantity;


                    $totalShipmentValue += $totalValue;
                    $totalPlusShipment += $plusShipment >= 0 ? $plusShipment : 0;

                    $gTotalShipmentValue += $totalValue;
                    $gTotalPlusShipment += $plusShipment >= 0 ? $plusShipment : 0;
                    ?>
                    <td>{{ $totalValue }}</td>
                    <td>{{ $plusShipment >= 0 ? $plusShipment : 0}}</td>
                    <td>{{ $shipment->short_reject_qty }}</td>
                    <td>{{ $shipment->purchaseOrder->ex_factory_date}}</td>
                    <td>{{ $shipment->remarks }}</td>
                    {{--<td>{{ $shipment->created_at }}</td>--}}
                </tr>
            @endforeach
            <tr>
                <td colspan="3"><b>Sub Total</b></td>
                <td><b>{{ $shipmentByOrder->sum('purchaseOrder.po_quantity') }}</b></td>
                <td><b>{{ $shipmentByOrder->sum('ship_quantity') }}</b></td>
                <td><b>{{ $totalShipmentValue }}</b></td>
                <td><b>{{ $totalPlusShipment }}</b></td>
                <td><b>{{ $shipmentByOrder->sum('short_reject_qty') }}</b></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><b>Total</b></td>
            <td><b>{{ $shipments->sum('purchaseOrder.po_quantity') }}</b></td>
            <td><b>{{ $shipments->sum('ship_quantity') }}</b></td>
            <td><b>{{ $gTotalShipmentValue }}</b></td>
            <td><b>{{ $gTotalPlusShipment }}</b></td>
            <td><b>{{ $shipments->sum('short_reject_qty') }}</b></td>
            <td></td>
            <td></td>
        </tr>
    @else
        <tr>
            <td colspan="10">Data Not Found!</td>
        </tr>
    @endif
    </tbody>

</table>
