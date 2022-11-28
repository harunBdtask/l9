
<thead>
    <tr class="tr-header-background">
        <th rowspan="2">Particulars</th>
        <th colspan="3">Opening Balance</th>
        <th colspan="3">Inwards</th>
        <th colspan="3">Outwards</th>
        <th colspan="3">Closing Balance</th>
    </tr>
    <tr class="tr-header-background">
        {{-- opening --}}
        <th>Quantity</th>
        <th>Rate</th>
        <th>Value</th>
        {{-- inward --}}
        <th>Quantity</th>
        <th>Rate</th>
        <th>Value</th>
        {{-- Outward --}}
        <th>Quantity</th>
        <th>Rate</th>
        <th>Value</th>
        {{-- Closing --}}
        <th>Quantity</th>
        <th>Rate</th>
        <th>Value</th>
    </tr>
</thead>
<tbody>
    @php
        $grandTotalOpeningValue = 0;
        $grandTotalInwardValue = 0;
        $grandTotalOutwardValue = 0;
        $grandTotalClosingValue = 0;
        $grandTotalOpeningQuantity = 0;
        $grandTotalInwardQuantity = 0;
        $grandTotalOutwardQuantity = 0;
        $grandTotalClosingQuantity = 0;
    @endphp
{{--    @php--}}
{{--        $grandTotalOpeningQuantity = [];--}}
{{--        $grandTotalInwardValue = [];--}}
{{--    @endphp--}}
    @foreach ($buyers as $key => $buyer)
        <tr class="tr-table-data-background">
            <td><b>{{ $buyer->name }}</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @php
            $subTotalOpeningValue = 0;
            $subTotalInwardValue = 0;
            $subTotalOutwardValue = 0;
            $subTotalClosingValue = 0;
            $subTotalOpeningQuantity = 0;
            $subTotalInwardQuantity = 0;
            $subTotalOutwardQuantity = 0;
            $subTotalClosingQuantity = 0;
        @endphp
        @foreach ($buyer->style as $style)
            @php
                $stockDetails = $style->stock($first_date, $last_date);
                $subTotalOpeningValue += $stockDetails['opening_value'];
                $subTotalInwardValue += $stockDetails['inward_value'];
                $subTotalOutwardValue += $stockDetails['outward_value'];
                $subTotalClosingValue += $stockDetails['closing_value'];
                $subTotalOpeningQuantity += $stockDetails['opening_quantity'];
                $subTotalInwardQuantity += $stockDetails['inward_quantity'];
                $subTotalOutwardQuantity += $stockDetails['outward_quantity'];
                $subTotalClosingQuantity += $stockDetails['closing_quantity'];



                $grandTotalOpeningValue += $subTotalOpeningValue;
                $grandTotalInwardValue += $subTotalInwardValue;
                $grandTotalOutwardValue += $subTotalOutwardValue;
                $grandTotalClosingValue += $subTotalClosingValue;
                $grandTotalOpeningQuantity += $subTotalOpeningQuantity;
                $grandTotalInwardQuantity += $subTotalInwardQuantity;
                $grandTotalOutwardQuantity += $subTotalOutwardQuantity;
                $grandTotalClosingQuantity += $subTotalClosingQuantity;


            @endphp
            <tr>
                <td>{{ $style->style_name }}</td>
                <td>{{ $stockDetails['opening_quantity'] }}</td>
                <td>{{ $stockDetails['opening_rate'] }}</td>
                <td>{{ $stockDetails['opening_value'] }}</td>
                <td>{{ $stockDetails['inward_quantity'] }}</td>
                <td>{{ $stockDetails['inward_rate'] }}</td>
                <td>{{ $stockDetails['inward_value'] }}</td>
                <td>{{ $stockDetails['outward_quantity'] }} </td>
                <td>{{ $stockDetails['outward_rate'] }}</td>
                <td>{{ $stockDetails['outward_value'] }}</td>
                <td>{{ $stockDetails['closing_quantity'] }}</td>
                <td>{{ $stockDetails['closing_rate'] }}</td>
                <td>{{ $stockDetails['closing_value'] }}</td>
            </tr>
        @endforeach


{{--        this php block calculate page wise grandTotal--}}
{{--        @php--}}
{{--            $grandTotalOpeningValue += $subTotalOpeningValue;--}}
{{--            $grandTotalInwardValue += $subTotalInwardValue;--}}
{{--            $grandTotalOutwardValue += $subTotalOutwardValue;--}}
{{--            $grandTotalClosingValue += $subTotalClosingValue;--}}
{{--            $grandTotalOpeningQuantity += $subTotalOpeningQuantity;--}}
{{--            $grandTotalInwardQuantity += $subTotalInwardQuantity;--}}
{{--            $grandTotalOutwardQuantity += $subTotalOutwardQuantity;--}}
{{--            $grandTotalClosingQuantity += $subTotalClosingQuantity;--}}
{{--        @endphp--}}


        <tr class="tr-subTotal" style="background: #e6ecf5">
            <td><b>Sub Total</b></td>
            <td><b>{{ $subTotalOpeningQuantity }}</b></td>
            <td><b></b></td>
            <td><b>{{ $subTotalOpeningValue }}</b></td>
            <td><b>{{ $subTotalInwardQuantity }}</b></td>
            <td></td>
            <td><b>{{ $subTotalInwardValue }}</b></td>
            <td><b>{{ $subTotalOutwardQuantity }}</b></td>
            <td></td>
            <td><b>{{ $subTotalOutwardValue }}</b></td>
            <td><b>{{ $subTotalClosingQuantity }}</b></td>
            <td></td>
            <td><b>{{ $subTotalClosingValue }}</b></td>
        </tr>
    @endforeach

{{--    @if($buyers->currentPage() == $buyers->lastPage())--}}
    @if($currentPage == $lastPage)
    <tr class="tr-grandTotal"  style="background: #d0def2;">
        <td><b>Grand Total</b></td>
        <td><b>{{ $grandTotalOpeningQuantity }}</b></td>
        <td></td>
        <td><b>{{ $grandTotalOpeningValue }}</b></td>
        <td><b>{{ $grandTotalInwardQuantity }}</b></td>
        <td></td>
        <td><b>{{ $grandTotalInwardValue }}</b></td>
        <td><b>{{ $grandTotalOutwardQuantity }}</b></td>
        <td></td>
        <td><b>{{ $grandTotalOutwardValue }}</b></td>
        <td><b>{{ $grandTotalClosingQuantity }}</b></td>
        <td></td>
        <td><b>{{ $grandTotalClosingValue }}</b></td>
    </tr>
    @endif

</tbody>
