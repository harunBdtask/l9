<table class="reportTable">
    <thead>
    <tr>
        <th>SI</th>
        <th>Date</th>
        <th>Merchandiser</th>
        <th>Requisition ID</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>Sample Name</th>
        <th>Sample Required Quantity</th>
        <th>BH Quantity</th>
        <th>Self Quantity</th>
        <th>Sample Department</th>
        <th>Sample Stage</th>
        <th>Delivery Status</th>
        <th>Remark</th>
    </tr>
    </thead>
    <tbody>
    @php
        $index = 1;
    @endphp
    @foreach($samples as $key => $sample)
        @foreach($sample->details as $value)
            @php
               $detail = $value->details[0] ?? [];
               $totalBhQTY = collect($value->details)->sum('bh_qty');
               $totalSelfQTY = collect($value->details)->sum('self_qty');
            @endphp
            <tr>
                <td>{{ $index++ }}</td>
                <td>{{ $sample->req_date }}</td>
                <td>{{ $sample->merchant->screen_name ?? '' }}</td>
                <td>{{ $sample->requisition_id }}</td>
                <td>{{ $sample->buyer->name }}</td>
                <td>{{ $sample->style_name }}</td>
                <td>{{ $detail['sample_name'] ?? '' }}</td>
                <td>{{ $totalBhQTY + $totalSelfQTY }}</td>
                <td>{{ $totalBhQTY }}</td>
                <td>{{ $totalSelfQTY }}</td>
                <td>{{ $sample->department->product_department }}</td>
                <td>{{ $sampleStage[$sample->sample_stage] }}</td>
                <td>
                    {{ $sample->delivery_date ? 'Yes' : 'No' }}
                </td>
                <td style="width: 11%;">{{ $sample->remarks }}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
