<div>
    <table>
        <tr>
            <th>SL No</th>
            <th>Buying Agent</th>
            <th>Primary Contract / ELC no.</th>
            <th>Pri Contract / ELC Date</th>
            <th>UNIT PRICE</th>
            <th>QTY/PCS</th>
            <th>Contract / ELC Value</th>
            <th>Total Cont Value</th>
            <th>BTB LC No</th>
            <th>BTB LC Date</th>
            <th>Beneficiary Name</th>
            <th>Material/Item</th>
            <th>Local</th>
            <th>Foreign</th>
            <th>BTB LC Value</th>
            <th>BTB Shipping Date</th>
            <th>BTB LC Expiry Date</th>
        </tr>

        @php $sl = 0; @endphp
        @forelse( $data as $key => $item )
            @php
                $pi = isset($item['pi_details']) ? count($item['pi_details']) : 0;
                $pmc = isset($item['pmc_data']) ? count($item['pmc_data']) : 0;
                $max = max( [ $pi,  $pmc]);
            @endphp

            @for($index = 0; $index < $max; $index++)
                <tr>
                    <td>{{ ++$sl }}</td>
                    @if( $item['pmc_data'] &&  ($index < count($item['pmc_data'])))
                        <td>{{ $item['pmc_data'][$index]['buying_agent'] ?? '' }}</td>
                        <td>{{ $item['pmc_data'][$index]['pmc_id'] ?? '' }}</td>
                        <td>{{ $item['pmc_data'][$index]['lc_date'] ?? '' }}</td>
                        <td>{{ $item['pmc_data'][$index]['rate'] ?? '' }}</td>
                        <td>{{ $item['pmc_data'][$index]['order_qty'] ?? '' }}</td>
                        <td>{{ $item['pmc_data'][$index]['order_value'] ?? '' }}</td>
                        <td>0</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif

                    @if($index == 0)
                        <td rowspan="{{ $max }}">{{ $item['btb_lc_no'] ?? '' }}</td>
                        <td rowspan="{{ $max }}">{{ $item['btb_lc_date'] ?? '' }}</td>
                        <td rowspan="{{ $max }}">{{ $item['beneficiary'] ?? '' }}</td>
                        <td rowspan="{{ $max }}">{{ $item['material_item'] ?? '' }}</td>
                    @endif

                    @if($item['pi_details'] && ($index < count($item['pi_details'])))
                        <td>{{ $item['pi_details'][$index]['type'] == 1 ? $item['pi_details'][$index]['amount'] : null }}</td>
                        <td>{{ $item['pi_details'][$index]['type'] == 2 ? $item['pi_details'][$index]['amount'] : null }}</td>
                        {{--                        <td>{{ $item['pi_details'][$index]['amount'] ?? 0 }}</td>--}}
                    @else
                        <td></td>
                        <td></td>
                        {{--                        <td></td>--}}
                    @endif

                    @if($index == 0)
                        <td rowspan="{{ $max }}">{{ $item['btb_lc_value'] ?? '' }}</td>
                        <td rowspan="{{ $max }}">{{ $item['shipping_date'] ?? '' }}</td>
                        <td rowspan="{{ $max }}">{{ $item['lc_expiry_date'] ?? '' }}</td>
                    @endif
                </tr>
            @endfor
            <tr>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
                <td style="height:25px"></td>
            </tr>
        @empty
            <tr>
                <td colspan="17" style="height: 20px" align="center">No Data Found!</td>
            </tr>
        @endforelse
    </table>
</div>
