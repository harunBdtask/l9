@if($data && $data->count())
    @foreach($data as $key => $rollData)
        @php
            $book_company = $rollData->knittingProgram ? ($rollData->knittingProgram->knittingParty->factory_name ?? $rollData->knittingProgram->knittingParty->name): '';
            $scanable_barcode = $rollData->id ? str_pad($rollData->id, 9, '0', STR_PAD_LEFT) : '';
        @endphp
        <tr title="Select/Deselect" class="roll-data-tr">
            {{--td>{{ $rollData->factory->factory_name ?? '' }}</td>--}}
            {{--<td>{{ $book_company }}</td>--}}
            <td style="width: 6%">{{ $rollData->knittingProgram->knitting_source_value ?? '' }}</td>
            <td style="width: 4%; text-transform: capitalize">{{ $rollData->planningInfo->booking_type ?? '' }}</td>
            <td style="width: 4%">{{ $rollData->planningInfo->buyer_name ?? '' }}</td>
            <td style="width: 7%">{{ $rollData->planningInfo->style_name ?? '' }}</td>
            {{--<td style="width: 7%">{{ $rollData->planningInfo->unique_id ?? '' }}</td>--}}
            {{--<td style="width: 7%">{{ $rollData->planningInfo->po_no ?? '' }}</td>--}}
            <td style="width: 7%">{{ $rollData->planningInfo->booking_no ?? '' }}</td>
            <td style="width: 7%">{{ $rollData->planningInfo->bodyPart->name ?? '' }}</td>
            <td style="width: 7%">{{ $rollData->planningInfo->colorType->color_types ?? '' }}</td>
            <td style="width: 6%">{{ $rollData->planningInfo->fabric_description ?? '' }}</td>
            <td>{{ $rollData->planningInfo->item_color ?? '' }}</td>
            <td style="width: 5%">{{ $rollData->knittingProgram->program_no ?? '' }}</td>
            <td>{{ $rollData->knitCard->knit_card_no ?? '' }}</td>
            <td style="width: 4%; text-align: right">{{ $rollData->qc_roll_weight ?? $rollData->roll_weight ?? '' }}</td>
            <td style="width: 5%; text-align: right">{{ $rollData->production_pcs_total ?? '' }}</td>
            <td style="width: 7%"><?php echo DNS1D::getBarcodeSVG(($scanable_barcode ?? '1234'), "C128A", 1, 15, '', false); ?></td>
            <td style="width: 4%">{{ $scanable_barcode }}</td>
            <td>

                @permission('permission_of_roll_wise_fabric_delivery_add')
                <input type="checkbox" name="name[]"
                       class="roll-data text-left"
                       data-id="{{ $rollData->id }}"
                       data-knit-card-id="{{ $rollData->knit_card_id }}"
                       data-program-id="{{ $rollData->knitting_program_id }}"
                       data-plan-info-id="{{ $rollData->plan_info_id }}"
                       data-selected="0">
                @endpermission
            </td>
        </tr>
    @endforeach
    <tr class="paginate-tr hide">
        <td colspan="17">
            {{ $data->appends(request()->except('page'))->links() }}
            <input type="hidden" name="current_page" class="current_page" value="{{ $data->currentPage() }}">
            <input type="hidden" name="last_page" class="last_page" value="{{ $data->lastPage() }}">
        </td>
    </tr>
@else
    <tr>
        <th colspan="17">No Data Found</th>
    </tr>
@endif
