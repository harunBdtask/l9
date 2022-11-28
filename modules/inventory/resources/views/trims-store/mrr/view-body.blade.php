<div class="row p-x-1">
    <div class="col-md-12">

        <table class="reportTable" style="margin-top: 50px;">
            <thead>
            <tr>
                <td style="width: 150px;" class="text-left">
                    <strong>Factory :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $mrr->factory->factory_name }} </td>

                <td style="width: 150px;" class="text-left">
                    <strong>Buyer :</strong>
                </td>
                <td style="width: 350px;"
                > {{ $mrr->buyer->name }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Store :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->store->name }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->booking_no }} </td>
            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Mrr No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->mrr_no }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Mrr Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->mrr_date }} </td>


            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->booking_date }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Booking Amount :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->booking_amount }} </td>

            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Delivery Amount :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->delivery_amount }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Delivery Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->delivery_date }} </td>

            </tr>
            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>PI No :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->pi_no }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong>Pi Receive Date :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->pi_receive_date }} </td>

            </tr>

            <tr>
                <td style="padding-left: 0;" class="text-left">
                    <strong>Others :</strong>
                </td>
                <td style="padding-left: 30px;"
                > {{ $mrr->others }} </td>

                <td style="padding-left: 0;" class="text-left">
                    <strong></strong>
                </td>
                <td style="padding-left: 30px;"
                >  </td>

            </tr>
            </thead>
        </table>

        <table class="reportTable" style="width: 100%;margin-top: 30px;">
            <thead>
            <tr>
                <th>Item Name</th>
                <th>Item Description</th>
                <th>Color</th>
                <th>Size</th>
                <th>Garments Qty</th>
                <th>UOM</th>
                <th>Actual Consumption</th>
                <th>Total Consumption</th>
                <th>Actual Qty</th>
                <th>Total Delivery Qty</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Approval Shade Code</th>
                <th>Remarks</th>
            </tr>
            </thead>

            <tbody>

            @foreach($mrr->details as $detail)
                <tr>
                    <td class="text-center">{{ $detail->itemGroup->item_group }}</td>
                    <td class="text-center">{{ $detail->item_description }}</td>
                    <td class="text-center">{{ $detail->color->name }}</td>
                    <td class="text-center">{{ $detail->size }}</td>
                    <td class="text-center">{{ $detail->planned_garments_qty }}</td>
                    <td class="text-center">{{ $detail->uom->unit_of_measurement }}</td>
                    <td class="text-center">{{ $detail->actual_consumption }}</td>
                    <td class="text-center">{{ $detail->total_consumption }}</td>
                    <td class="text-center">{{ $detail->actual_qty }}</td>
                    <td class="text-center">{{ $detail->total_delivered_qty }}</td>
                    <td class="text-center">{{ $detail->rate }}</td>
                    <td class="text-center">{{ $detail->amount }}</td>
                    <td class="text-center">{{ $detail->approval_shade_code }}</td>
                    <td class="text-center">{{ $detail->remarks }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
</div>
