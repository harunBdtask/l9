<div class="table-responsive">
    <table >
        <tr>
            <th> SL</th>
            <th>BUYING AGENT</th>
            <th>BUYER</th>
            <th>PRIMARY CONTRACT DATE</th>
            <th>PRIMARY CONTRACT NO</th>
            <th>PRIMARY CONTRACT VALUE</th>
            <th>PRI AMND DATE</th>
            <th>PRI AMND NO</th>
            <th>PRI AMND VALUE</th>
            <th>SC DATE</th>
            <th>SC NO</th>
            <th>SC VALUE</th>
            <th>SC AMND DATE</th>
            <th>SC AMND NO</th>
            <th>SC AMND VALUE</th>
            <th>EX LC DATE</th>
            <th>EX. LC NO</th>
            <th>EX LC VALUE</th>
            <th>LC AMND DATE</th>
            <th>LC AMND NO</th>
            <th>LC AMND VALUE</th>
            <th>QTY/PCS</th>
            <th>EXPIRY DATE</th>
        </tr>

       
        @php $sl = 0; @endphp
        @foreach($data as $key=>$item)

            @if($item['type'] == 'PMC')


                @php

                $rowspan2 = count($item['sc_list']);
                $rowspan3 = count($item['lc_list']);
                $rowspan4 = count($item['pmc_amends']);

                $rowspan5 = collect($item['sc_list'])->map(function($a){
                    return count($a['sc_amends']);
                })->max();
                $rowspan6 = collect($item['lc_list'])->map(function($a){
                    return count($a['lc_amends']);
                })->max();

                $rowspan_count = max($rowspan2,$rowspan3, $rowspan4, $rowspan5,$rowspan6);
                $rowspan = $rowspan_count==0?1:$rowspan_count;
                @endphp

                @for($index = 0; $index <= $rowspan-1; $index++)
                    <tr>
                    @if($index==0)
                        <td rowspan="{{ $rowspan }}">{{ ++$sl }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['buying_agent']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['buyer']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['pmc_date']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['pmc_no']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['pmc_value']??null }}</td>
                    @endif
                        <td>{{ $item['pmc_amends'][$index]['pmc_amt_date']??null }}</td>
                        <td>{{ $item['pmc_amends'][$index]['pmc_amt_no']??null }}</td>
                        <td>{{ $item['pmc_amends'][$index]['pmc_amt_value']??null }}</td>

                        <td>{{ $item['sc_list'][$index]['sc_date']??null }}</td>
                        <td>{{ $item['sc_list'][$index]['sc_no']??null }}</td>
                        <td>{{ $item['sc_list'][$index]['sc_value']??null }}</td>


                        <td>{{ $item['sc_list'][0]['sc_amends'][$index]['amd_date']??null }}</td>
                        <td>{{ $item['sc_list'][0]['sc_amends'][$index]['amd_no']??null }}</td>
                        <td>{{ $item['sc_list'][0]['sc_amends'][$index]['amd_value']??null }}</td>


                        <td>{{ $item['lc_list'][$index]['lc_date']??null }}</td>
                        <td>{{ $item['lc_list'][$index]['lc_no']??null }}</td>
                        <td>{{ $item['lc_list'][$index]['lc_value']??null }}</td>

                        <td>{{ $item['lc_list'][0]['lc_amends'][$index]['amd_date']??null }}</td>
                        <td>{{ $item['lc_list'][0]['lc_amends'][$index]['amd_no']??null }}</td>
                        <td>{{ $item['lc_list'][0]['lc_amends'][$index]['amd_value']??null }}</td>

                        <td>{{ $item['sc_list'][$index]['qnty']??null }}</td>
                        <td>{{ $item['sc_list'][$index]['expiry_date']??null }}</td>
                    </tr>
                @endfor


            @else
                @php
                    $rowspan_count = ($item['type']=='SC')?count($item['sc_amends']):count($item['lc_amends']);
                    $rowspan = $rowspan_count==0?1:$rowspan_count;
                @endphp

                @for($index = 0; $index <= $rowspan-1; $index++)
                    <tr>
                        @if($index==0)
                        <td rowspan="{{ $rowspan }}">{{ ++$sl }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['buying_agent']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['buyer']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['pmc_date']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['pmc_no']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['pmc_value']??null }}</td>
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}">{{ $item['sc_date']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['sc_no']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['sc_value']??null }}</td>
                        @endif
                        @if($item['type']=='SC')
                        <td>{{ $item['sc_amends'][$index]['amd_date']??null }}</td>
                        <td>{{ $item['sc_amends'][$index]['amd_no']??null }}</td>
                        <td>{{ $item['sc_amends'][$index]['amd_value']??null }}</td>
                        @elseif($index==0)
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}"></td>
                        @endif
                        @if($index==0)
                        <td rowspan="{{ $rowspan }}">{{ $item['lc_date']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['lc_no']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['lc_value']??null }}</td>
                        @endif
                        @if($item['type']=='LC')
                        <td>{{ $item['lc_amends'][$index]['amd_date']??null }}</td>
                        <td>{{ $item['lc_amends'][$index]['amd_no']??null }}</td>
                        <td>{{ $item['lc_amends'][$index]['amd_value']??null }}</td>
                        @elseif($index==0)
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}"></td>
                        <td rowspan="{{ $rowspan }}"></td>
                        @endif
                        @if($index==0)
                        <td rowspan="{{ $rowspan }}">{{ $item['qnty']??null }}</td>
                        <td rowspan="{{ $rowspan }}">{{ $item['expiry_date']??null }}</td>
                        @endif
                    </tr>
                @endfor
            @endif
        @endforeach

    </table>
</div>

