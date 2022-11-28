<thead>
<tr>
    <th data-toggle="tooltip" title="Session ID" rowspan="2">SID</th>
    <th rowspan="2">Color</th>
    {{--<th data-toggle="tooltip" title="Cutting No" rowspan="2">C. No</th>--}}
    <th rowspan="2">Lot No</th>
    <th colspan="4">Roll</th>
    <th data-toggle="tooltip" title="Average Dia" rowspan="2">Avg. Dia</th>
    <th data-toggle="tooltip" title="Booking Dia" rowspan="2">B. Dia</th>
    <th data-toggle="tooltip" title="Cutting Qunatity" rowspan="2">CQ [PCs]</th>
    <th data-toggle="tooltip" title="Actual Cutting Should Be" rowspan="2">ACSB [PCs]</th>
    <th data-toggle="tooltip" title="Booking Consumption" rowspan="2">BC [KG/Dz]</th>
    <th data-toggle="tooltip" title="Used Consumption" rowspan="2">UC [KG/Dz]</th>
    <th data-toggle="tooltip" title="Quantity Save/Loss" rowspan="2">QSL [PCs]</th>
    <th data-toggle="tooltip" title="Fabric Save/Loss" rowspan="2">FSL [PCs]</th>
    <th rowspan="2">Result</th>
</tr>
<tr>
    <th>No</th>
    <th>Ply</th>
    <th>Weight</th>
    <th>Dia</th>
</tr>
</thead>
<tbody>
@if(!$bundleCardGenerationDetails->getCollection()->isEmpty())
    @foreach($bundleCardGenerationDetails->getCollection() as $genDetail)
        @foreach($genDetail->rolls as $roll)
            <tr>
                @if($loop->first)
                    @php
                        $colors = implode(', ', $genDetail->bundleCardsGetColors->pluck('color.name')->unique()->toArray());
                        $lots = implode(', ', collect($genDetail->lot_ranges)->pluck('lot_no')->unique()->toArray());
                    @endphp
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ $genDetail->sid ?? '' }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ $colors ?? '' }}</td>
                    {{--<td rowspan="{{ count($genDetail->rolls) }}">{{ $genDetail->cutting_no ?? '' }}</td>--}}
                    <td rowspan="{{ count($genDetail->rolls) }}">
                        {{ $lots }}
                    </td>
                @endif

                <td>{{ $roll['roll_no'] }}</td>
                <td>{{ $roll['plys'] }}</td>
                <td>{{ number_format($roll['weight'], 2) }}</td>
                <td>{{ number_format($roll['dia'], 2) }}</td>

                @if($loop->first)
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->roll_summary['average_dia'], 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->booking_dia, 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->bundle_summary['total_quantity'], 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->total_cutting_quantity_should_be, 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->booking_consumption, 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->used_consumption, 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->quantity_save_or_loss, 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}">{{ number_format($genDetail->fabric_save, 2) }}</td>
                    <td rowspan="{{ count($genDetail->rolls) }}"
                        class="{{ $genDetail->result ? 'text-success' : 'text-danger' }}">{{ $genDetail->result ? 'Passed' : 'Failed' }}</td>
                @endif
            </tr>
        @endforeach
    @endforeach
@else
    <tr>
        <td colspan="16" align="center">No Data
        </td>
    </tr>
@endif
</tbody>
<tfoot>
@if($bundleCardGenerationDetails->total() > 15)
    <tr>
        <td colspan="16"
            class="text-center">{{ $bundleCardGenerationDetails->appends(request()->except('page'))->links() }}</td>
    </tr>
@endif
</tfoot>