<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th class="text-center" colspan="20">Yarn Requisition List</th>
            </tr>
            <tr><td> </td></tr>
            <tr class="table-header">
                <th>SL</th>
                <th>Requisition No</th>
                <th>Buyer</th>
                <th>Style</th>
                <th>Booking No</th>
                <th>Sales Order No</th>
                <th>Within Group</th>
                <th>Knitting Source</th>
                <th>Program No</th>
                <th>Date</th>
                <th>Knitting Floor</th>
                <th>Yarn Count</th>
                <th>Yarn Composition</th>
                <th>Yarn Type</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Lot</th>
                <th>Issue Qty</th>
                <th>Attention</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @forelse($data as $key => $value)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $value->requisition_no }}</td>
                    <td>{{ $value->program->planInfo->buyer_name ?? '' }}</td>
                    <td>{{ $value->program->planInfo->style_name ?? '' }}</td>
                    <td>{{ $value->program->planInfo->booking_no ?? '' }}</td>
                    <td>{{ $value->program->planInfo->programmable->sales_order_no ?? '' }}</td>
                    <td>{{ $value->program->planInfo->programmable->within_group_text ?? '' }}</td>
                    <td>{{ $value->program->knitting_source_value ?? '' }}</td>
                    <td>{{ $value->program->program_no ?? '' }}</td>
                    <td>{{ $value->req_date }}</td>
                    <td>{{ optional($value->knittingFloor)->name }}</td>
                    <td>
                        {{ $value->details->pluck('yarn_count')->pluck('yarn_count')->implode(', ') }}
                    </td>
                    <td>
                        {{ $value->details->pluck('composition')->pluck('yarn_composition')->implode(', ') }}
                    </td>
                    <td>
                        {{ $value->details->pluck('type')->pluck('name')->implode(', ') }}
                    </td>
                    <td>
                        {{ $value->details->pluck('yarn_brand')->implode(', ') }}
                    </td>
                    <td>
                        {{ $value->details->pluck('yarn_color')->implode(', ') }}
                    </td>
                    <td>
                        {{ $value->details->pluck('yarn_lot')->implode(', ') }}
                    </td>
                    <td>{{ $value->yarn_issue_sum_issue_qty }}</td>
                    <td>{{ $value->attention }}</td>
                    <td>{{ $value->remarks }}</td>
                </tr>
            @empty
                <tr>
                    <td class="text-center p-a" colspan="14">No Data Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
