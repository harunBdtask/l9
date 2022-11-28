<table>
    <thead>
    <tr>
        <td colspan="7"><b>Company Name : {{ get_company_details()->name ?? 'PN COMPOSITE LTD' }} </b></td>
    </tr>
    <tr>
        <td colspan="7"><b>Store Name : {{ $store_name }} </b></td>
    </tr>
    <tr>
        <th colspan="7"><b>Address : {{ get_company_details()->address ?? '' }}</b></th>
    </tr>
    <tr>
        <th colspan="7">Daily Report</th>
    </tr>
    <tr>
        <td colspan="7" style="font-weight: 700;">
            <b>{{ isset($first_date) ? "For " . \Carbon\Carbon::create($first_date)->format("Y-M-d") : null  }}</b>
        </td>
    </tr>
    <tr>
        <th rowspan="2">Particulars</th>
        <th colspan="3">Inwards</th>
        <th colspan="3">Outwards</th>
    </tr>
    <tr>


        {{--inward--}}
        <th>Quantity</th>
        <th>Rate</th>
        <th>Value</th>
        {{--Outward--}}
        <th>Quantity</th>
        <th>Rate</th>
        <th>Value</th>
    </tr>
    </thead>

    @if(isset($items))
        <tbody>
            @php
                $inwards_grand_value = 0;
                $outwards_grand_value = 0;
            @endphp
            @foreach($items as $key => $item)
            @php
                $stockDetails = $item->stock($first_date,$last_date);
                $inwards_grand_value += $stockDetails['inward_value'];
                $outwards_grand_value += $stockDetails['outward_value'];
            @endphp
                @include('inventory::report.daily_report_table_row',[
                    "stockDetails"=>$stockDetails
                ])
            @endforeach
            @include('inventory::report.daily_report_table_row_summery',[
                "inwards_grand_value"=>$inwards_grand_value,
                "outwards_grand_value"=>$outwards_grand_value,
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
