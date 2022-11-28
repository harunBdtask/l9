<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: #66ffb5">
                <th colspan="2">Factory : {{ $metaData['factory'] }}</th>
                <th colspan="2">Buyer : {{ $metaData['buyer'] }}</th>
                <th colspan="2">Order : {{ $metaData['order'] }}</th>
                <th colspan="2">Color : {{ $metaData['color'] }}</th>
            </tr>
            <tr>
                <th>Factory</th>
                <th>Buyer</th>
                <th>Style</th>
                <th>Color</th>
                <th>Date</th>
                <th>Unit</th>
                <th>Line</th>
                <th>Sewing Qty</th>
            </tr>
            </thead>
            <tbody>
            @php $first=true ; $count = count($data); @endphp
            @foreach(collect($data)->groupBy('order_id') as $orderWiseGroup)
                @foreach(collect($orderWiseGroup)->groupBy('color_id') as $colorWise)
                    @foreach(collect($colorWise)->groupBy('production_date') as $dateWise)
                        @foreach(collect($dateWise)->groupBy('floor_id') as $floorWise)
                            @foreach(collect($floorWise)->groupBy('line_id') as $item)
                                <tr>
                                    @if ($first)
                                        <td rowspan="{{ $count }}">{{ $metaData['factory'] }}</td>
                                    @endif
                                    @if ($first)
                                        <td rowspan="{{ $count }}">{{ $metaData['buyer'] }}</td>
                                    @endif
                                    @if ($first)
                                        <td rowspan="{{ $count }}">{{ $metaData['order'] }}</td>
                                    @endif
                                    @if ($first)
                                        <td rowspan="{{ $count }}">{{ $metaData['color'] }}</td>
                                    @endif
                                    <td>{{ date_format(date_create(collect($item)->first()->production_date), 'd-M-y') }}</td>
                                    <td>{{ collect($item)->first()->floor->floor_no }}</td>
                                    <td>{{ collect($item)->first()->line->line_no }}</td>
                                    <td>{{ (int)collect($item)->sum('sewing_output_qty') }}</td>
                                    @php $first=false @endphp
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>
</div>
