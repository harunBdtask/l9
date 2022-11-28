<table class="reportTable {{ !isset($type) ? $tableHeadColorClass : '' }}" id="fixTable"
       style="border-collapse: collapse">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style/Order</th>
        <th>PO</th>
        <th>PO Qty</th>
        <th>Shipout Qty</th>
        <th>Shipout Balance Qty</th>
        <th>Shipment Date</th>
        <th>Unit Price</th>
        <th>Total Export Value</th>
        <th>Total Shipout Value</th>
        <th>Total Export Value Balance Value</th>
        <th>Reason</th>
    </tr>
    </thead>
    <tbody>
    @if(count($shipments))
        <?php
        $poQtyTotal = 0;
        $shipOutQtyTotal = 0;
        $shipOutBalanceQtyTotal = 0;
        $sewingBalanceTotal = 0;
        $shipOutValueTotal = 0;
        $shipOutBalanceTotal = 0;
        $exportValueTotal = 0;
        $exportValueBalanceTotal = 0;
        ?>
        @foreach($shipments->groupBy('buyer_id') as $buyers)
            @foreach($buyers->groupBy('order_id') as $orders)
                <?php
                $poQtySubTotal = 0;
                $sewingBalanceSubTotal = 0;
                $shipOutQtySubTotal = 0;
                $shipOutValueSubTotal = 0;
                $shipOutBalanceQtySubTotal = 0;
                $exportValueSubTotal = 0;
                $exportValueBalanceSubTotal = 0;
                ?>
                @foreach($orders->groupBy('purchase_order_id') as $pos)
                    <?php
                    $buyerName = $pos[0]->buyer->name;
                    $styleName = $pos[0]->order->style_name;
                    $poNo = $pos[0]->purchaseOrder->po_no;
                    $shipmentDate = $pos[0]->purchaseOrder->ex_factory_date;
                    $unitPrice = $pos[0]->purchaseOrder->unit_price;
                    $shipmentDateFormatted = $shipmentDate ? \Carbon\Carbon::parse($shipmentDate)
                        ->format('Y M d') : null;
                    $poQuantity = $pos[0]->purchaseOrder->po_quantity;

                    $exportValue = $unitPrice * $poQuantity;
                    $exportValueSubTotal += $exportValue;

                    $poQtySubTotal += $poQuantity;
                    $shipmentQty = $pos->sum('ship_quantity');
                    $shipOutQtySubTotal += $shipmentQty;

                    $shipOutValue = $unitPrice * $shipmentQty;
                    $shipOutValueSubTotal += $shipOutValue;

                    $sewingBalanceQty = ($poQuantity > 0) ? ($poQuantity + ($poQuantity * 3) / 100) - $shipmentQty : 0;
                    $sewingBalanceSubTotal += $sewingBalanceQty;

                    $shipOutBalanceQty = $poQuantity - $shipmentQty;
                    $shipOutBalanceQtySubTotal += $shipOutBalanceQty;
                    $exportBalance = $exportValue - $shipOutValue;
                    $exportValueBalanceSubTotal += $exportBalance;
                    ?>
                    <tr>
                        <td>{{ $buyerName }}</td>
                        <td>{{ $styleName }}</td>
                        <td>{{ $poNo }}</td>
                        <td>{{ $poQuantity }}</td>
                        <td>{{ $shipmentQty }}</td>
                        <td>{{ $shipOutBalanceQty }}</td>
                        <td>{{ $shipmentDateFormatted }}</td>
                        <td>{{ $unitPrice }}</td>
                        <td>{{ $exportValue }}</td>
                        <td>{{ $shipOutValue }}</td>
                        <td>{{ $exportBalance }}</td>
                        <td>{{ $pos[0]->remarks }}</td>
                    </tr>
                @endforeach

                <tr>
                    <td></td>
                    <td colspan="2"><b>Sub Total</b></td>
                    <td><b>{{ $poQtySubTotal }}</b></td>
                    <td><b>{{ $shipOutQtySubTotal }}</b></td>
                    <td><b>{{ $shipOutBalanceQtySubTotal }}</b></td>
                    <td></td>
                    <td></td>
                    <td><b>{{ $exportValueSubTotal }}</b></td>
                    <td><b>{{ $shipOutValueSubTotal }}</b></td>
                    <td><b>{{ $exportValueBalanceSubTotal }}</b></td>
                    <td></td>
                </tr>

                <?php
                $poQtyTotal += $poQtySubTotal;
                $shipOutQtyTotal += $shipOutQtySubTotal;
                $shipOutBalanceQtyTotal += $shipOutBalanceQtySubTotal;
                $sewingBalanceTotal += $sewingBalanceSubTotal;
                $shipOutValueTotal += $shipOutValueSubTotal;
                $shipOutBalanceTotal += $shipOutBalanceQtySubTotal;
                $exportValueTotal += $exportValueSubTotal;
                $exportValueBalanceTotal += $exportValueBalanceSubTotal;
                ?>
            @endforeach
        @endforeach
        <tr>
            <td></td>
            <td colspan="2"><b>Total</b></td>
            <td>{{ $poQtyTotal }}</td>
            <td>{{ $shipOutQtyTotal }}</td>
            <td>{{ $shipOutBalanceQtyTotal }}</td>
            <td></td>
            <td></td>
            <td>{{ $exportValueTotal }}</td>
            <td>{{ $shipOutValueTotal }}</td>
            <td>{{ $exportValueBalanceTotal }}</td>
            <td></td>
        </tr>
    @else
        <tr>
            <td colspan="11">Not Found</td>
        </tr>
    @endif

    </tbody>
</table>
