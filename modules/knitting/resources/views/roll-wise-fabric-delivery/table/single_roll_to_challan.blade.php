@if($data && $data->count())
    @foreach($data as $value)
        @php
            $challanRoll = $value;
            $rollData = $challanRoll->knitProgramRoll;
            $book_company = $challanRoll->knittingProgram ? ($challanRoll->knittingProgram->knittingParty->factory_name ?? $challanRoll->knittingProgram->knittingParty->name): '';
            $scanable_barcode = $rollData->id ? str_pad($rollData->id, 9, '0', STR_PAD_LEFT) : '';
        @endphp
        <tr>
            <td>{{ $rollData->factory->factory_name ?? '' }}</td>
            <td>{{ $book_company }}</td>
            <td>{{ $challanRoll->knittingProgram->knitting_source_value ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->buyer_name ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->style_name ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->booking_no ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->body_part ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->color_type ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->fabric_description ?? '' }}</td>
            <td>{{ $challanRoll->planningInfo->item_color ?? '' }}</td>
            <td>{{ $challanRoll->knittingProgram->program_no ?? '' }}</td>
            <td>{{ $rollData->qc_roll_weight ?? $rollData->roll_weight ?? '' }}</td>
            <td>{{ $rollData->production_pcs_total ?? '' }}</td>
            <td><?php echo DNS1D::getBarcodeSVG(($scanable_barcode ?? '1234'), "C128A", 1, 15, '', false); ?></td>
            <td>{{ $scanable_barcode }}</td>
            <td>
                @permission('permission_of_roll_wise_fabric_delivery_delete')
                <button type="button" class="btn btn-danger btn-xs individual-challaned-roll" data-id="{{ $challanRoll->id }}">
                    <i class="fa fa-trash"></i>
                </button>
                @endpermission
            </td>
        </tr>
    @endforeach
@endif
