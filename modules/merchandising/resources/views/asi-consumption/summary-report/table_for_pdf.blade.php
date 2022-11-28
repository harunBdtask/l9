@foreach(collect($consumptions)->groupBy('buyer_id') as $buyer_id => $consumption)
    @php($val = collect($consumption)->pluck('details')->flatten(1))

    @if(count($val) > 0)
        <table class="reportTable" style="width: 100%">
            <thead>
            <tr>
                <th/>
                <th>Buyer</th>
                <th colspan="3">{{ collect($consumption)->first()['buyer']['name'] }}</th>
                <th colspan="14" style="text-align:center"> ASI- Consumption Summary</th>

            </tr>
            <tr>
                <th rowspan="2" style="width: 3px">SL</th>
                <th rowspan="2" style="width: 45px;text-align: left">Created At</th>
                <th rowspan="2" style="width: 45px;text-align: left">Update At</th>
                <th rowspan="2" style="width: 30px">Season</th>
                <th rowspan="2" style="width: 50px">Style</th>
                <th rowspan="2" style="min-width: 70px">Group</th>
                <th rowspan="2" style="width: 200px; word-break: keep-all">Item Description</th>
                <th rowspan="2">Body Part</th>
                <th rowspan="2"  style="width: 200px;word-break: keep-all">Fabrication</th>
                <th rowspan="2">Embl Type</th>
                <th rowspan="2">Fab.  width</th>
                <th colspan="2">Shrinkage</th>
                <th colspan="3">Consumption</th>
                <th rowspan="2" style="width:50px">Maker Eff. %</th>
                <th rowspan="2" style="width:50px">Marker Type</th>
                <th rowspan="2" style="width:30px">REMARKS</th>
            </tr>
            <tr>
                {{--                <th>Fabric <br> width</th>--}}
                <th style="width:30px">Length%</th>
                <th style="width:30px">Width%</th>
                <th style="width:30px">UoM</th>
                <th>Cons Per Pcs</th>
                <th>Cons Per Dzn%</th>

            </tr>

            </thead>
            <tbody>
            @php($index = 1)
            @foreach($consumption as $key => $item)
                @foreach(collect($item)['details'] as  $details)
                    <tr>
                        <td>{{ $index++  }}</td>
                        <td style="min-width: 75px; text-align: left">{{ $item->created_date ?? '' }}</td>
                        <td style="min-width: 75px; text-align: left">{{ $item->updated_date ?? '' }}</td>
                        <td>{{ $item->season->season_name ?? '' }}</td>
                        <td>{{ $item->style_name ?? '' }}</td>
                        <td>{{ $details['group_id'] ? collect($groups)->where('id', $details['group_id'])->first()['name'] : '' }}</td>
                        <td>{{ $details['gmts_item']['name'] }}</td>
                        <td>{{ $details['body_part_id'] ? $details['body_part']['name'] : '' }}</td>
                        <td style="min-width: 50px; text-align: left">{{ $details['fabric_description'] ?? '' }}</td>
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



