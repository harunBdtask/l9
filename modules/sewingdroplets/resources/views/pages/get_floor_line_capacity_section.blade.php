<table class="reportTable">
    <thead>
    <tr>
        <th>Floor No</th>
        <th>Line No</th>
        <th>Capacity Available(minutes)</th>
        <th>Capacity Available(pcs)</th>
    </tr>
    </thead>
    <tbody id="lineTable">
    @if($sewing_line_capacity && $sewing_line_capacity->count())
        @foreach($sewing_line_capacity->groupBy('floor_id') as $reportByFloor)
            <tr>
                <td rowspan="{{ $reportByFloor->count() }}">{{ $reportByFloor->first()->floor->floor_no }}</td>
            @php
                $t_available_minutes = 0;
                $t_available_pcs = 0;
            @endphp
            @foreach($reportByFloor as $sewing_line)
                @php
                    $available_pcs = ($smv && $smv > 0) ? round($sewing_line->capacity_available_minutes / $smv) : 0;
                    $t_available_pcs += $available_pcs;
                    $t_available_minutes += $sewing_line->capacity_available_minutes;
                @endphp
                    @if(!$sewing_line->first())
                        <tr>
                    @endif
                    <td>{{ $sewing_line->line->line_no }}</td>
                    <td>{{ $sewing_line->capacity_available_minutes }}</td>
                    <td>{{ $available_pcs }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="2">{{ $reportByFloor->first()->floor->floor_no }} = Total</th>
                <th>{{ $t_available_minutes }}</th>
                <th>{{ $t_available_pcs }}</th>
            </tr>
        @endforeach
    @else
        <tr>
            <th colspan="4">No Data</th>
        </tr>
    @endif
    </tbody>
</table>