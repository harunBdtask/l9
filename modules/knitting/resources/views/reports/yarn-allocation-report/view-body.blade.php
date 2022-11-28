<table class="reportTable">
    <thead>
    <tr style="background-color: aliceblue;">
        <th>Buyer</th>
        <th>Style No</th>
        <th>Knitting Party</th>
        <th>Unique Id</th>
        <th>Within Group</th>
        <th>Program No</th>
        <th>Program Qty</th>
        <th>Stitch Length</th>
        <th>Program Date</th>
        <th>Color</th>
        <th>Color Qty</th>
        <th>Yarn Description</th>
        <th>Yarn Count</th>
        <th>Yarn Lot</th>
        <th>Brand</th>
        <th>Ref. No</th>
        <th>Current Stock Qty</th>
        <th>Allocated Qty</th>
        <th>Unallocated Qty</th>
        <th>Issue Qty</th>
        <th>Issuable Qty</th>
        <th>Remarks</th>
    </tr>
    </thead>

    <tbody>

    @php
        $totalStockQty = 0;
        $totalYarnAllocationQty = 0;
        $totalYarnUnallocatedQty = 0;
        $totalIssueQty = 0;
        $issuableQty = 0;
    @endphp

    @foreach($data as $program)
        @php $programRow = true; @endphp
        @foreach(collect($program)->groupBy('color') as $color)
            @foreach($color as $yarn)
                <tr>
                    @if($programRow)
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['buyer'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['style_name'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['party_name'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['unique_id'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['within_group'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['program_no'] }}</td>
                        <td rowspan="{{ count($program) }}">{{ $yarn['program_qty'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['stitch_length'] }}</td>
                        <td rowspan="{{ count($program) }}" class="text-left">{{ $yarn['program_date'] }}</td>
                    @endif

                    @if($loop->first)
                        <td rowspan="{{ count($color) }}" class="text-left">{{ $yarn['color'] }}</td>
                        <td rowspan="{{ count($color) }}">{{ $yarn['program_color_qty'] }}</td>
                    @endif

                    <td class="text-left">{{ $yarn['yarn_description'] }}</td>
                    <td class="text-left">{{ $yarn['yarn_count'] }}</td>
                    <td class="text-left">{{ $yarn['yarn_lot'] }}</td>
                    <td class="text-left">{{ $yarn['yarn_brand'] }}</td>
                    <td class="text-left">{{ $yarn['yarn_ref'] }}</td>
                    <td>{{ number_format($yarn['balance'], 2) }}</td>
                    <td>{{ number_format($yarn['total_allocated_qty'], 2) }}</td>
                    <td>{{ number_format($yarn['unallocated_qty'], 2) }}</td>
                    <td>{{ number_format($yarn['issue_qty'], 2) }}</td>
                    <td>{{ number_format($yarn['rem_issue_qty'], 2) }}</td>
                    <td class="text-left">{{ $yarn['remarks'] }}</td>
                </tr>
                @php
                    $programRow = false;
                    $totalStockQty += $yarn['balance'];
                    $totalYarnAllocationQty += $yarn['total_allocated_qty'];
                    $totalYarnUnallocatedQty += $yarn['unallocated_qty'];
                    $totalIssueQty += $yarn['issue_qty'];
                    $issuableQty += $yarn['rem_issue_qty'];
                @endphp
            @endforeach
        @endforeach
    @endforeach
    </tbody>
    <thead>
    <tr>
        <th colspan="16">Total</th>
        <th>{{ number_format($totalStockQty, 2) }}</th>
        <th>{{ number_format($totalYarnAllocationQty, 2) }}</th>
        <th>{{ number_format($totalYarnUnallocatedQty, 2) }}</th>
        <th>{{ number_format($totalIssueQty, 2) }}</th>
        <th>{{ number_format($issuableQty, 2) }}</th>
        <th></th>
    </tr>
    </thead>
</table>
