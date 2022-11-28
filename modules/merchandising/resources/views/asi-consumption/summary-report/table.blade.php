@foreach(collect($consumptions)->groupBy('buyer_id') as $buyer_id => $consumption)
    @php($val = collect($consumption)->pluck('details')->flatten(1))

    @if(count($val) > 0)
        <table class="reportTable" style="width: 100%">
            <thead>
            <tr>
                <th/>
                <th>Buyer</th>
                <th>{{ collect($consumption)->first()['buyer']['name'] }}</th>
                <th colspan="16"> ASI- Consumption Summary</th>

            </tr>
            <tr>
                <th rowspan="2">SL</th>
                <th rowspan="2">Created <br> Date</th>
                <th rowspan="2">Update <br> Date</th>
                <th rowspan="2">Season</th>
                <th rowspan="2">Style</th>
                <th rowspan="2">Group</th>
                <th rowspan="2">Item <br> Description</th>
                <th rowspan="2">Body Part</th>
                <th rowspan="2">Fabrication</th>
                <th rowspan="2">Embellishment <br> Type</th>
                <th rowspan="2">Fabric <br> width</th>
                <th colspan="2">Shrinkage</th>
                <th colspan="3">Consumption</th>
                <th rowspan="2">Maker Efficiency %</th>
                <th rowspan="2">Marker Type</th>
                <th rowspan="2">REMARKS</th>
            </tr>
            <tr>
{{--                <th>Fabric <br> width</th>--}}
                <th>Length <br>%</th>
                <th>Width <br>%</th>
                <th>UoM</th>
                <th>Cons <br>Per Pcs</th>
                <th>Cons <br>Per Dzn%</th>

            </tr>

            </thead>
            <tbody>
            @php($index = 1)
            @foreach($consumption as $key => $item)
                @foreach(collect($item)['details'] as  $details)
                    <tr>
                        <td>{{ $index++  }}</td>
                        <td>{{ $item->created_date ?? '' }}</td>
                        <td>{{ $item->updated_date ?? '' }}</td>
                        <td>{{ $item->season->season_name ?? '' }}</td>
                        <td>{{ $item->style_name ?? '' }}</td>
                        <td>{{ $details['group_id'] ? collect($groups)->where('id', $details['group_id'])->first()['name'] : '' }}</td>
                        <td>{{ $details['gmts_item']['name'] }}</td>
                        <td>{{ $details['body_part_id'] ? $details['body_part']['name'] : '' }}</td>
                        <td style="min-width: 50px">{{ $details['fabric_description'] ?? '' }}</td>
                        <td>{{ $details['embellishment_type']['type'] ?? '' }}</td>
                        <td>{{ $details['fabric_dia'] ?? '' }}</td>
                        <td>{{ $details['length'] ?? '' }}</td>
                        <td>{{ $details['width'] ?? '' }}</td>
                        <td>{{ $details['uom']['unit_of_measurement'] ?? '' }}</td>
                        <td>{{ $details['cons_per_pcs'] ?? '' }}</td>
                        <td>{{ $details['cons_per_dzn'] ?? '' }}</td>
                        <td>{{ $details['efficiency'] ?? '' }}</td>
                        <td>{{ $details['marker_type'] ?? '' }}</td>
                        <td>{{ $details['remarks'] ?? '' }}</td>
                    </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
        <br>
    @endif

@endforeach



