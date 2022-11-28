<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <table class="reportTable" style="border: none !important;">
            <tr>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td class="text-left">
                                <strong>From: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->factory->factory_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Address: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->factory->factory_address ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Attention: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->attention }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Remarks: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->remarks }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%; border: none !important;"></td>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td class="text-left">
                                <strong>Buyer: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->buyerData->name ?? ''}}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Style: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->style_name }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Booking No: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->booking_no }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Booking Type: </strong>
                            </td>
                            <td class="text-left" style="text-transform: capitalize">{{ $salesOrder->booking_type }}</td>
                        </tr>
                        <tr>
                            <td class="text-left">
                                <strong>Booking Date: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->booking_date }}</td>
                        </tr>

                        <tr>
                            <td class="text-left">
                                <strong>Delivery Date: </strong>
                            </td>
                            <td class="text-left">{{ $salesOrder->delivery_date }}</td>
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
                <th class="text-left">SL</th>
                <th class="text-left">Cus Buyer</th>
                <th class="text-left">Cus Style</th>
                <th class="text-left">Body Part</th>
                <th class="text-left">Fab Description</th>
                <th class="text-left">Color</th>
                <th class="text-left">Color Type</th>
                <th class="text-left">LD No</th>
                <th class="text-left">Fab. GSM</th>
                <th class="text-left">Fab. Dia</th>
                <th class="text-left">Fab Finish Qty</th>
                <th class="text-left">Fab Grey Qty</th>
                <th class="text-left">UOM</th>
                <th class="text-left">Process</th>
                <th class="text-left">Finish Fab IN(YDS)</th>
                <th class="text-left">Remarks</th>
            </thead>
            <tbody>
                @php
                    $finishQtySum = 0;
                    $fabGreyQtySum = 0;
                    $finishFabInSum = 0;
                @endphp
                @foreach($salesOrder->breakdown as $key => $value)
                @if ($value->fabric_dia != 0)
                    @php
                        $finishfab = ((($value->gray_qty / $value->fabric_dia) /$value->fabric_gsm ) * 1550 * 1000) / 36;
                    @endphp
                @else
                    @php
                        $finishfab = 0;
                    @endphp
                @endif
                <tr style="text-align: left;">
                    <td>{{ $loop->iteration ?? ''}}</td>
                    <td>{{ $value->cus_buyer ?? ''}}</td>
                    <td>{{ $value->cus_style ?? ''}}</td>
                    <td>{{ $value->bodyPart->name?? '' }}</td>
                    <td>{{ $value->fabric_description ?? '' }}</td>
                    <td>{{ $value->item_color?? '' }}</td>
                    <td>{{ $value->colorType->color_types ?? '' }}</td>
                    <td>{{ $value->ld_no?? '' }}</td>
                    <td>{{ $value->fabric_gsm ?? '' }}</td>
                    <td>{{ $value->fabric_dia ?? '' }}</td>
                    <td>{{ $value->finish_qty ?? '' }}</td>
                    <td>{{ $value->gray_qty ?? '' }}</td>
                    <td>{{ $value->programUOM->unit_of_measurement ?? '' }}</td>
                    <td>{{ $value->process->process_name ?? '' }}</td>
                    <td>{{ number_format($finishfab, 2, ".", "")  }}</td>
                    <td>{{ $value->remarks ?? '' }}</td>
                </tr>
                @php
                    $finishQtySum += number_format($value->finish_qty, 2, ".","");
                    $fabGreyQtySum += number_format($value->gray_qty, 2, ".","");
                    $finishFabInSum += number_format($finishfab, 3, ".","");
                @endphp
                @endforeach
            <tr style="background-color: #e7e8e9;">
                <td colspan="8" class="text-right"><strong>Total Qty</strong></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"> <b>{{ $finishQtySum }}</b> </td>
                <td style="text-align: left;"> <b>{{ $fabGreyQtySum }}</b> </td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"></td>
                <td style="text-align: left;"> <b>{{ $finishFabInSum }}</b> </td>
                <td style="text-align: left;"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="row" style="margin-top: 30px;">
    <div class="col-md-12">
        <strong>In Word: </strong>
        <span>{{ ucwords($digit->format(number_format($finishFabInSum, 3, ".", "")))}}</span>
    </div>
</div>
