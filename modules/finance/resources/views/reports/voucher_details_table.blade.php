<table class="reportTable">
    <thead>
        <tr>
            <th>ACC. CODE</th>
            <th style="text-align: left;">ACC. HEAD</th>
            <th style="text-align: left;">PARTICULARS</th>
            <th style="text-align: right;">AMOUNT</th>
        </tr>
    </thead>

    <tbody>
        @php
            $totalDebit = 0;
            $totalCredit = 0;
        @endphp
        @foreach($voucher->details->items as $key=>$item)
            @if($account != $item->account_id)
            <tr>
                <td>{{ $item->account_code }}</td>
                <td style="text-align: left;">{{ $item->account_name  }}</td>
                <td style="text-align: left;">{{ $item->narration ?? ''  }}</td>
                @if($item->debit > 0)
                    @php
                        $totalDebit += $item->debit;
                    @endphp
                    <td style="text-align: right;">{{ number_format($item->debit, 2).' Dr'  }}</td>
                @elseif($item->credit > 0)
                    @php
                        $totalCredit += $item->credit;
                    @endphp
                    <td style="text-align: right;">{{ number_format($item->credit, 2).' Cr'  }}</td>
                @else
                    <td style="text-align: right;">0</td>
                @endif
            </tr>
            @endif
        @endforeach
        <tr style="font-weight: bold">
            <td style="text-align: right;" colspan="3">Total Debit</td>
            <td style="text-align: right;">{{ number_format($totalDebit, 2) }}</td>
        </tr>
        <tr style="font-weight: bold">
            <td style="text-align: right;" colspan="3">Total Credit</td>
            <td style="text-align: right;">{{ number_format($totalCredit, 2) }}</td>
        </tr>
    </tbody>
</table>
