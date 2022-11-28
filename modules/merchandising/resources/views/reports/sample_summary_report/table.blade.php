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
        @php
            $detailsCount = collect($sample->details)->count();
            $start = 0;
        @endphp
        @foreach($sample->details as $value)
            @php
                $detail = $value->details[0] ?? [];
                $totalBhQTY = collect($value->details)->sum('bh_qty');
                $totalSelfQTY = collect($value->details)->sum('self_qty');
            @endphp
            <tr>
                @if($start === 0)
                    <td rowspan="{{ $detailsCount }}">{{ $index++ }}</td>
                    <td rowspan="{{ $detailsCount }}">{{ $sample->req_date }}</td>
                    <td rowspan="{{ $detailsCount }}">{{ $sample->merchant->screen_name ?? '' }}</td>
                    <td rowspan="{{ $detailsCount }}">{{ $sample->requisition_id }}</td>
                    <td rowspan="{{ $detailsCount }}">{{ $sample->buyer->name }}</td>
                    <td rowspan="{{ $detailsCount }}">{{ $sample->style_name }}</td>
                @endif
                <td>{{ $detail['sample_name'] ?? '' }}</td>
                <td>{{ $totalBhQTY + $totalSelfQTY }}</td>
                <td>{{ $totalBhQTY }}</td>
                <td>{{ $totalSelfQTY }}</td>
                @if($start === 0)
                    <td rowspan="{{ $detailsCount }}">{{ $sample->department->product_department }}</td>
                    <td rowspan="{{ $detailsCount }}">{{ $sampleStage[$sample->sample_stage] }}</td>
                    <td rowspan="{{ $detailsCount }}">
                        {{ $sample->delivery_date ? 'Yes' : 'No' }}
                    </td>
                    <td style="width: 11%;" rowspan="{{ $detailsCount }}">{{ $sample->remarks }}</td>
                @endif
            </tr>
            @php
                $start++;
            @endphp
        @endforeach
    @endforeach
    </tbody>
</table>
