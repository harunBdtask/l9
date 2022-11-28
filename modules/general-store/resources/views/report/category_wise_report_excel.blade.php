<table>
    <thead>
    <tr>
        <th rowspan="2"><b>Particulars</b></th>
        <th colspan="3"><b>Opening Balance</b></th>
        <th colspan="3"><b>Inwards</b></th>
        <th colspan="3"><b>Outwards</b></th>
        <th colspan="3"><b>Closing Balance</b></th>
    </tr>
    <tr>
        {{--opening--}}
        <th><b>Quantity</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        {{--inward--}}
        <th><b>Quantity</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        {{--Outward--}}
        <th><b>Quantity</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
        {{--Closing--}}
        <th><b>Quantity</b></th>
        <th><b>Rate</b></th>
        <th><b>Value</b></th>
    </tr>
    </thead>

    @if(isset($categories))
        <tbody>
            @php
                $total_opening_grand_value = 0;
                $total_inwards_grand_value = 0;
                $total_outwards_grand_value = 0;
                $total_closing_grand_value = 0;
            @endphp
            @foreach($categories as $key => $category)
                <tr>
                    <td class="text-left padding-1"><b>{{ $category->name }}</b></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                    <td class="text-left padding-1"></td>
                </tr>
                @php
                    $opening_grand_value = 0;
                    $inwards_grand_value = 0;
                    $outwards_grand_value = 0;
                    $closing_grand_value = 0;
                @endphp
                @foreach ($items as $item)
                    @if ($item['category_id'] == $category->id)
                        @php
                            $stockDetails = $item->stock($first_date, $last_date);
                            $opening_grand_value += $stockDetails['opening_value'];
                            $inwards_grand_value += $stockDetails['inward_value'];
                            $outwards_grand_value += $stockDetails['outward_value'];
                            $closing_grand_value += $stockDetails['closing_value'];
                        @endphp
                        @include('inventory::report.category_wise_report_table_row', [
                            "type" => $type,
                            "items" => $items,
                            "first_date"=> $first_date,
                            "last_date"=> $last_date,
                        ])
                    @endif
                @endforeach
                @php
                    $total_opening_grand_value += $opening_grand_value;
                    $total_inwards_grand_value += $inwards_grand_value;
                    $total_outwards_grand_value += $outwards_grand_value;
                    $total_closing_grand_value += $closing_grand_value;
                @endphp
                <tr>
                    <td class="text-left padding-1"><b>Sub Total</b></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"><b>{{ $opening_grand_value }}</b></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"><b>{{ $inwards_grand_value }}</b></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"><b>{{ $outwards_grand_value }}</b></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"></td>
                    <td class="text-right padding-1"><b>{{ $closing_grand_value }}</b></td>
                </tr>
            @endforeach
            @include('inventory::report.category_wise_report_summery',[
                "total_opening_grand_value"=> $total_opening_grand_value,
                "total_inwards_grand_value"=> $total_inwards_grand_value,
                "total_outwards_grand_value"=> $total_outwards_grand_value,
                "total_closing_grand_value"=> $total_closing_grand_value,
            ])
        </tbody>
    @else
        <tbody>
            <tr>
                <td colspan="13">Data Not Found</td>
            </tr>
        </tbody>
    @endif
</table>