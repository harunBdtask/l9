<div class="row p-x-1">
    <div class="col-md-12">

        <table class="reportTable" style="margin-top: 50px;">
            <thead>
            <tr>
                <td style="width: 150px;" class="text-left">
                    <strong>Factory :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $receive->factory->factory_name }} </td>

                <td style="width: 150px;" class="text-left">
                    <strong>Buyer :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $receive->buyer->name }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Store :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->store->name }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->booking_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Inventory No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->trimsInventory->bin_no }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->booking_date }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Total Receive Qty :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ collect($receive->details)->sum('receive_qty') }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>PI No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->pi_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>PI Receive Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->pi_receive_date }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Challan No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->challan_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Remarks :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $receive->remarks }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong> </strong>
                </td>
                <td style="padding-left: 30px;"
                >  </td>
            </tr>
            </thead>
        </table>

        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>Current Date</th>
                <th>Item</th>
                <th>Item Description</th>
                <th>Color</th>
                <th>Size</th>
                <th>Garments Qty</th>
                <th>UOM</th>
                <th>Booking Qty</th>
                <th>Receive Qty</th>
                <th>Receive Date</th>
                <th>Short/Excess Qty</th>
                <th>Rate</th>
                <th>Total Receive Amount</th>
                <th>Approval Shade Code</th>
                <th>Remarks</th>
            </tr>
            </thead>

            <tbody>
                @php
                    $totalBookingQty = 0;
                    $totalReceiveQty = 0;
                    $totalShortExcessQty = 0;
                    $totalReceiveAmountQty = 0;
                @endphp
                @foreach($receive->details as $key=> $detail)
                <tr>
                    <td class="text-center">{{ $detail->current_date }}</td>
                    <td class="text-center">{{ $detail->itemGroup->item_group }}</td>
                    <td class="text-center">{{ $detail->item_description }}</td>
                    <td class="text-center">{{ $detail->color->name }}</td>
                    <td class="text-center">{{ $detail->size }}</td>
                    <td class="text-center">{{ $detail->planned_garments_qty }}</td>
                    <td class="text-center">{{ $detail->uom->unit_of_measurement }}</td>
                    <td class="text-center">{{ $detail->booking_qty }}</td>
                    <td class="text-center">{{ $detail->receive_qty }}</td>
                    <td class="text-center">{{ $detail->receive_date }}</td>
                    <td class="text-center">{{ $detail->excess_qty }}</td>
                    <td class="text-center">{{ $detail->rate }}</td>
                    <td class="text-center">{{ $detail->total_receive_amount }}</td>
                    <td class="text-center">{{ $detail->approval_shade_code }}</td>
                    <td class="text-center">{{ $detail->remarks }}</td>
                </tr>
                @php
                    $totalBookingQty += $detail->booking_qty;
                    $totalReceiveQty += $detail->receive_qty;
                    $totalShortExcessQty += $detail->excess_qty;
                    $totalReceiveAmountQty += $detail->total_receive_amount;
                @endphp
                @endforeach
            </tbody>
            <tfoot>
            <tr>
                <td colspan="6">Total</td>
                <td class="text-center">{{ $totalBookingQty }}</td>
                <td class="text-center">{{ $totalReceiveQty }}</td>
                <td class="text-center"></td>
                <td class="text-center">{{ $totalShortExcessQty }}</td>
                <td class="text-center"></td>
                <td class="text-center">{{ $totalReceiveAmountQty }}</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
            </tr>
            </tfoot>
        </table>

    </div>
</div>
