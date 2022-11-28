@if($data && $data->count())
    @foreach($data as $knitCard)
        @php
            $programData = $knitCard->program;
            $book_company = $programData->knittingParty ? ($programData->knittingParty->factory_name ?? $programData->knittingParty->name): '';
            $balance_qty = $programData->program_qty - $programData->production_qty;
        @endphp
        <tr>
            <td>{{ $programData->factory->factory_name ?? '' }}</td>
            <td style="text-transform: capitalize">{{ $programData->planInfo->booking_type ?? '' }}</td>
            <td>{{ $book_company }}</td>
            <td>{{ $programData->planInfo->buyer_name ?? '' }}</td>
            <td>{{ $programData->planInfo->style_name ?? '' }}</td>
            <td>{{ $programData->planInfo->unique_id ?? '' }}</td>
            <td>{{ $programData->planInfo->po_no ?? '' }}</td>
            <td>{{ $programData->planInfo->booking_no ?? '' }}</td>
            <td>{{ $programData->planInfo->bodyPart->name ?? '' }}</td>
            <td>{{ $programData->planInfo->colorType->color_types ?? '' }}</td>
            <td>{{ $programData->planInfo->fabric_description ?? '' }}</td>
            <td>{{ $programData->planInfo->item_color ?? '' }}</td>
            <td>{{ $programData->program_no ?? '' }}</td>
            <td>{{ $knitCard->knit_card_no ?? '' }}</td>
            <td>{{ $programData->program_qty ?? '' }}</td>
            <td>{{ $production_qty ?? '' }}</td>
            <td>{!! $balance_qty !!}</td>
            <td>
                @permission('permission_of_knitting_qc_add')
                <button type="button" class="btn btn-sm btn-success knit-card-qc-action-btn"
                        data-id="{{ $knitCard->id }}">Roll QC
                </button>
                @endpermission
            </td>
        </tr>
    @endforeach
    <tr class="paginate-tr hide">
        <td colspan="16">
            {{ $data->appends(request()->except('page'))->links() }}
            <input type="hidden" name="current_page" class="current_page" value="{{ $data->currentPage() }}">
            <input type="hidden" name="last_page" class="last_page" value="{{ $data->lastPage() }}">
        </td>
    </tr>
@else
    <tr>
        <th colspan="16">No Data Found</th>
    </tr>
@endif
