

<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        <table class="reportTable" style="border: none !important;">
            <tr>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td>
                                <strong>Program No: </strong>
                            </td>
                            <td>{{ $data->program->program_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Program Date: </strong>
                            </td>
                            <td>{{ $data->program->program_date ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Booking Type: </strong>
                            </td>
                            <td style="text-transform: capitalize">{{ $data->program->planInfo->booking_type ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Knitting Source: </strong>
                            </td>
                            <td>{{ $knittingSources[$data->program->knitting_source_id] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Knitting Party: </strong>
                            </td>
                            <td>{{ $data->program->party_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Stitch Length: </strong>
                            </td>
                            <td>{{ $data->program->stitch_length ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Start Date: </strong>
                            </td>
                            <td>{{ $data->program->start_date ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>End Date: </strong>
                            </td>
                            <td>{{ $data->program->end_date ?? '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 20%; border: none !important;"></td>
                <td style="width: 40%; border: none !important;">
                    <table class="reportTable">
                        <tr>
                            <td>
                                <strong>Color Range: </strong>
                            </td>
                            <td>{{ $data->program->colorRange->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Finish Fabric Dia: </strong>
                            </td>
                            <td>{{ $data->program->finish_fabric_dia ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Machine Dia: </strong>
                            </td>
                            <td>{{ $data->program->machine_dia ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Machine GG: </strong>
                            </td>
                            <td>{{ $data->program->machine_gg ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Machine Type: </strong>
                            </td>
                            <td>{{ $data->program->machine_type_info ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Feeder: </strong>
                            </td>
                            <td>{{ $data->program['feeder_text'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Remarks: </strong>
                            </td>
                            <td>{{ $data->program->remarks ?? '' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</div>

<div class="row" style="margin-top: 30px;">
    <table class="reportTable">
        <thead>
        <tr style="background-color: #eee; text-align: center;">
            <th>Buyer Name</th>
            <th>Style Name</th>
            <th>Fabric Des</th>
            <th>Requisition No</th>
            <th>Req. Date</th>
            <th>Knitting Floor</th>
            <th>Attention</th>
            <th>Remarks</th>
        </tr>
        </thead>
        <tbody>
        <tr style="text-align: center;">
            <td>{{ $data->program->planInfo->buyer_name ?? '' }}</td>
            <td>{{ $data->program->planInfo->style_name ?? '' }}</td>
            <td>{{ $data->program->planInfo->fabric_description ?? '' }}</td>
            <td>{{ $data->requisition_no }}</td>
            <td>{{ $data->req_date }}</td>
            <td>{{ $data->knittingFloor->name ?? '' }}</td>
            <td>{{ $data->attention }}</td>
            <td>{{ $data->remarks }}</td>
        </tr>
        </tbody>
    </table>
</div>
<div class="row" style="margin-top: 20px;">
    <table class="reportTable">
        <thead>
        <tr style="background-color: #eee; text-align: center;">
            <th>Supplier</th>
            <th>Lot No</th>
            <th>Yarn Description</th>
            <th>Yarn Brand</th>
            <th>Requisition Qty</th>
            <th>Remarks</th>
        </tr>
        </thead>

        <tbody>
            @foreach($data->details as $key => $detail)
                <tr style="text-align: center;">
                    <td>{{ $detail->supplier->name ?? '' }}</td>
                    <td>{{ $detail->yarn_lot }}</td>
                    <td>
                        {{ $detail->composition->yarn_composition ?? '' }},
                        {{ $detail->yarn_count->yarn_count ?? '' }},
                        {{ $detail->type->yarn_type ?? '' }}
                    </td>
                    <td>{{ $detail->yarn_brand }}</td>
                    <td>{{ $detail->requisition_qty }}</td>
                    <td>{{ $detail->remarks }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="text-right" colspan="4"><strong>Total Req. Qty: </strong></td>
                <td class="text-center">{{ collect($data->details)->sum('requisition_qty') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="row" style="margin-top: 20px;">
    <div class="col-md-12">
        @php
            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        @endphp
        <strong>Total Req. Qty In word: </strong> {{ ucwords($digit->format(collect($data->details)->sum('requisition_qty'))) }}
    </div>
</div>
