
    @foreach($data as $colorId => $colorData)
        <strong>{{ $colors[$colorId]['name'] }}</strong>
        <table class="reportTable">
            <thead style="background: rgb(148, 218, 251)">
            <tr>
                <th rowspan="2">Date</th>
                <th rowspan="2">Cut-No</th>
                <th rowspan="2">Colour</th>
                <th rowspan="2">Layer</th>
                <th rowspan="2">Shed/Lot</th>
                <th colspan="{{ count($sizes) }}">Sizes</th>
                <th rowspan="2">Pcs</th>
                <th colspan="{{ count($sizes) }}">Sizes</th>
                <th rowspan="2">Total-Cut</th>
            </tr>
            <tr>
                @foreach($sizes as $value)
                    <th>
                        {{ $value }}
                    </th>
                @endforeach
                @foreach($sizes as $value)
                    <th>
                        {{ $value }}
                    </th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($colorData['data'] as $value)
                <tr>
                    <td>{{ $value['date'] }}</td>
                    <td>{{ $value['cutting_no'] }}</td>
                    <td>{{ $colors[$colorId]['name'] }}</td>
                    <td>{{ $value['layer'] }}</td>
                    <td>{{ $value['lot_no'] }}</td>
                    @foreach($sizes as $size)
                        <td>{{ $value['sizes'][$size] ?? '' }}</td>
                    @endforeach
                    <td>{{ $value['total_size_ratio'] }}</td>
                    @foreach($sizes as $size)
                        <td>
                            {{ $value['po_color_size_quantity'][$size]['actual_quantity'] ?? '' }}
                            <hr style="margin: 5px 0 5px 0">
                            {{ $value['po_color_size_quantity'][$size]['previous_quantity'] ?? '' }}
                        </td>
                    @endforeach
                    <td>
                        {{ $value['total_po_color_size_quantity']['actual'] }}
                        <hr style="margin: 5px 0 5px 0">
                        {{ $value['total_po_color_size_quantity']['with_previous'] }}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endforeach
