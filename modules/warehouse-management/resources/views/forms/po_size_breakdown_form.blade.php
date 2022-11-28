<table class="reportTable">
    <thead>
    <th>Colors / Sizes</th>
    @if(count($sizes) && count($colors))
        @foreach($sizes as $key=>$size)
            <th>{{$size}}</th>
        @endforeach
    @endif
    </thead>
    <tbody>
    @if(count($sizes) && count($colors))
        @php
            $count  = 0;
        @endphp
        @foreach($colors as $color_id => $color)
            <tr>
                <td style="">{{ $color }}</td>
                @foreach($sizes as $size_id => $size)
                    @php
                        $color_size_quantity = 0;
                        if ($warehouse_carton_details && $warehouse_carton_details->count()) {
                            $warehouse_carton_details_clone = clone $warehouse_carton_details;
                            $color_size_query = $warehouse_carton_details_clone->where([
                                'color_id' => $color_id,
                                'size_id' => $size_id,
                            ])->first();
                            if ($color_size_query) {
                                $color_size_quantity = $color_size_query->quantity;
                            }
                        }
                    @endphp
                    <td>
                        {!! Form::hidden('color_id[]', $color_id, [ 'id' => 'color_id']) !!}
                        {!! Form::hidden('size_id[]', $size_id, [ 'id' => 'size_id']) !!}
                        {!! Form::number('quantity[]', $color_size_quantity, [ 'class' => 'color_size_qty', 'color' => $color, 'size' => $size,'style'=>'width:50%']) !!}
                        <br>
                        <span class="text-danger quantity_{{ $count }}"></span>
                    </td>
                    @php
                        $count++;
                    @endphp
                @endforeach
            </tr>
        @endforeach
    @else
        <td>No Data</td>
    @endif
    </tbody>
</table>