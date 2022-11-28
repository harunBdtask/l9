<table>
    <thead>
    <tr>
        <td colspan="13"><b>Company Name : {{ get_company_details()->name ?? 'PN COMPOSITE LTD' }} </b></td>
    </tr>
    <tr>
        <td colspan="13"><b>Store Name : {{ $store_name }} </b></td>
    </tr>
    <tr>
        <th colspan="13"><b>Address : {{ get_company_details()->address ?? '' }}</b></th>
    </tr>
    <tr>
        <td colspan="13" style="font-weight: 700;">
            <b>
                {{ isset($first_date) ? "For " . \Carbon\Carbon::create($first_date)->format("Y-M-d") : null  }}
                {{ isset($last_date) ? "To ". \Carbon\Carbon::create($last_date)->format("Y-M-d") : null }}
            </b>
        </td>
    </tr>
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

    @if(isset($items))
        <tbody>
        @php
            $opening_grand_value = 0;
            $inwards_grand_value = 0;
            $outwards_grand_value = 0;
            $closing_grand_value = 0;
        @endphp
        @foreach($items as $key => $item)
            @php
                $stockDetails = $item->stock($first_date,$last_date);
                $opening_grand_value += $stockDetails['opening_value'];
                $inwards_grand_value += $stockDetails['inward_value'];
                $outwards_grand_value += $stockDetails['outward_value'];
                $closing_grand_value += $stockDetails['closing_value'];
            @endphp
            @include('inventory::report.table_row',[
                    "first_date"=>$first_date,
                    "last_date"=>$last_date,
                    "stockDetails"=>$stockDetails
                ])
        @endforeach
        @include('inventory::report.table_row_summery',[
                    "opening_grand_value"=>$opening_grand_value,
                    "inwards_grand_value"=>$inwards_grand_value,
                    "outwards_grand_value"=>$outwards_grand_value,
                    "closing_grand_value"=>$closing_grand_value,
                ])
        </tbody>
    @else
        <tbody>
        <tr style="text-align: center">
            <td colspan="13">Data Not Found</td>
        </tr>
        </tbody>
    @endif
</table>
