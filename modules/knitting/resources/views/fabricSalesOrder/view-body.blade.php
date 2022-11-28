<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <table class="reportTable" style="border: none !important;">
            <tr>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td>
                                <strong>From: </strong>
                            </td>
                            <td>{{ $salesOrder->factory->factory_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Address: </strong>
                            </td>
                            <td>{{ $salesOrder->factory->factory_address ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Attention: </strong>
                            </td>
                            <td>{{ $salesOrder->attention }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Remarks: </strong>
                            </td>
                            <td>{{ $salesOrder->remarks }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%; border: none !important;"></td>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td>
                                <strong>Buyer: </strong>
                            </td>
                            <td>{{ $salesOrder->buyerData->name ?? ''}}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Style: </strong>
                            </td>
                            <td>{{ $salesOrder->style_name }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Booking No: </strong>
                            </td>
                            <td>{{ $salesOrder->booking_no }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Booking Date: </strong>
                            </td>
                            <td>{{ $salesOrder->booking_date }}</td>
                        </tr>
                        
                        <tr>
                            <td>
                                <strong>Delivery Date: </strong>
                            </td>
                            <td>{{ $salesOrder->delivery_date }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row" style="margin-top: 30px;">
    <div class="col-md-12 table-responsive">
        <table class="reportTable">
            <thead>
            <tr style="background-color: #eee">
                <th>SL</th>
                <th>Cus Buyer</th>
                <th>Cus Style</th>
                <th>Body Part</th>
                <th>Description</th>
                <th>Color</th>
                <th>Color Type</th>
                <th>Fab. GSM</th>
                <th>Fab. Dia</th>
                <th>Dia/Width Type</th>
                <th>LD No</th>
                <th>Qty</th>
            </tr>
            </thead>
            <tbody>
            @foreach($salesOrder->breakdown as $key => $value)
                <tr style="text-align: left;">
                    <td>{{ $loop->iteration ?? ''}}</td>
                    <td>{{ $value->cus_buyer ?? ''}}</td>
                    <td>{{ $value->cus_style ?? ''}}</td>
                    <td>{{ $value->bodyPart->name?? '' }},</td>
                    <td>{{ $value->fabric_description ?? '' }}</td>
                    <td>{{ $value->item_color?? '' }}</td>
                    <td>{{ $value->colorType->color_types ?? '' }}</td>
                    <td>{{ $value->fabric_gsm ?? '' }}</td>
                    <td>{{ $value->fabric_dia ?? '' }}</td>
                    <td>{{ SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService::get($value->dia_type_id)['name'] ?? '' }}</td>
                    <td>{{ $value->ld_no ?? '' }}</td>
                    <td style="text-align: right;">{{ number_format($value->gray_qty, 2, ".", "") }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="11" class="text-right"><strong>Total Qty</strong></td>
                <td style="text-align: right;">{{ number_format($salesOrder->breakdown->sum('gray_qty'), 2, ".", "") }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <strong>In Word: </strong>
        <span>{{ ucwords($digit->format(number_format($salesOrder->breakdown->sum('gray_qty'), 2, ".", "")))}}</span>
    </div>
</div>