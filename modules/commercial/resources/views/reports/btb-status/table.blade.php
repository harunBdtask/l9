<div class="table-responsive">
    <table >
    																																													
        <tr>
            <th> SL</th>
            <th> BTB LC DATE</th>
            <th> BTB LC NO</th>
            <th> SUPPLIER</th>
            <th> BTB LC AMNT</th>
            <th> BTB LC AMNT VALUE</th>
            <th> PI DATE</th>
            <th> PI NO</th>
            <th> PI AMNT</th>
            <th> IMP Acceptance Amount</th>
            <th> PAYMENT  DATE</th>
            <th> PAYMENT AMOUNT</th>
            <th> PRIMARY CONTRACT NO</th>
            <th> PRIMARY CONTRACT VALUE</th>
            <th> SC NO</th>
            <th> SC VALUE</th>
            <th> BUYING AGENT</th>
            <th> BUYER</th>
        </tr>

        @forelse($data as $key=>$item)

        @php 
            $pi = count(@$item['pi_details']);
            $imp_docs = count(@$item['imp_docs']);
            $contracts = count(@$item['contracts']);
            $max = max([$pi, $imp_docs, $contracts]);
        @endphp

        @for($i=0; $i<=$max-1; $i++)

        <tr>
            @if($i==0)
            <td rowspan="{{ $max }}">{{ ++$key }}</td>
            <td rowspan="{{ $max }}">{{ $item['btb_lc_date']??null }}</td>
            <td rowspan="{{ $max }}">{{ $item['btb_lc_no']??null }}</td>
            <td rowspan="{{ $max }}">{{ $item['supplier']??null }}</td>
            <td rowspan="{{ $max }}">{{ $item['btb_lc_amnt']??null }}</td>
            <td rowspan="{{ $max }}">{{ $item['btb_lc_value']??null }}</td>
            @endif
            <td>{{ $item['pi_details'][$i]['pi_date']??null }}</td>
            <td>{{ $item['pi_details'][$i]['pi_no']??null }}</td>
            <td>{{ $item['pi_details'][$i]['pi_amnt']??null }}</td>
            <td>{{ $item['imp_docs'][$i]['imp_accept_amnt']??null }}</td>
            <td>{{ @$item['imp_docs'][$i]['payments'][0]['payment_date']??null }}</td>
            <td>{{ @$item['imp_docs'][$i]['payments'][0]['payment_amnt']??null }}</td>
            <td>{{ $item['contracts'][$i]['pmc']['pmc_no']??null }}</td>
            <td>{{ $item['contracts'][$i]['pmc']['pmc_value']??null }}</td>
            <td>{{ $item['contracts'][$i]['sc']['sc_no']??null }}</td>
            <td>{{ $item['contracts'][$i]['sc']['sc_value']??null }}</td>
            @php 
                $buying_agent = @$item['contracts'][$i]['sc']['buying_agent']??@$item['contracts'][$i]['pmc']['buying_agent'];
                $buyer = @$item['contracts'][$i]['sc']['buyer']??@$item['contracts'][$i]['pmc']['buyer'];
            @endphp
            <td>{{ $buying_agent??null }}</td>
            <td>{{ $buyer??null }}</td>
        </tr>
        @endfor
        @empty
        <tr>
            <td colspan="18" style="height: 20px" align="center">No Data Found!</td>
        </tr>
        @endforelse
     
    </table>
</div>

