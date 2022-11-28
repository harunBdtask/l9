<style>
    table thead {
        display: table-row-group;
    }

    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
    table th, table td {
        font-size: 14px !important;
    }
</style>

<table class="reportTable" style="width: 100%;">
    <thead>
    <tr>
        <th>Date</th>
        <th>Project</th>
        <th>Unit</th>
        <th>Ledger Name</th>
        <th>Paid To/ Paid From</th>
        <th>Voucher No</th>
        <th>Cheque No</th>
        <th>Debit</th>
        <th>Credit</th>
        <th>Cumulative Balance</th>
    </tr>
    </thead>
    <tbody>
        @php
            $total_debit = ($opening_balance >= 0 ? abs($opening_balance): 0);
            $total_credit = ($opening_balance < 0 ? abs($opening_balance): 0);
            $cumulative_balance = $opening_balance
        @endphp
        <tr>
            <td><b>{{ \Carbon\Carbon::parse($fromDate)->format('d M y') }}</b></td>
            <td></td>
            <td></td>
            <td><b>Balance b/d</b></td>
            <td></td>
            <td></td>
            <td></td>

            <td><b>{{ ($opening_balance >= 0? BdtNumFormat(abs($opening_balance)):'') }}</b></td>
            <td><b>{{ ($opening_balance < 0? BdtNumFormat(abs($opening_balance)):'') }}</b></td>
            <td><b>{{ BdtNumFormat(abs($opening_balance)) }} {{ $opening_balance > 0 ? 'Dr': 'Cr' }}</b></td>
        </tr>
        @forelse ($journalUnitWiseData as $item)

            @php
                $total_debit = $total_debit + ($item->trn_type=='dr'?$item->trn_amount:0);
                $total_credit = $total_credit + ($item->trn_type=='cr'?$item->trn_amount:0);
                $cumulative_balance = $cumulative_balance + ($item->trn_type=='dr'?$item->trn_amount:0)  - ($item->trn_type=='cr'?$item->trn_amount:0);
            @endphp

            <tr>
                <td>{{  \Carbon\Carbon::parse($item->trn_date)->format('d M y') }}</td>
                <td>{{ $item->project->project }}</td>
                <td>{{ $item->unit->unit }}</td>
                <td>{{ $item->account->name }}</td>
                <td>{{ ($item->voucher->to??$item->voucher->from) }}</td>
                <td><a target="_blank" href="{{ url('/basic-finance/vouchers/'.$item->voucher_id) }}">{{  $item->voucher_no }}</a></td>
                <td>{{ ($item->voucher->cheque_no?($item->voucher->cheque->chequeBook->cheque_book_no.'-'.$item->voucher->cheque->cheque_no):$item->voucher->receive_cheque_no) }}</td>
                <td>{{  $item->trn_type=='dr'?BdtNumFormat($item->trn_amount):'' }} </td>
                <td>{{  $item->trn_type=='cr'?BdtNumFormat($item->trn_amount):'' }}</td>
                <td> {{ BdtNumFormat(abs($cumulative_balance)) }} {{ $cumulative_balance>0?'Dr':'Cr' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="10">No Data Found!</td>
            </tr>
        @endforelse

            <tr>
                <th colspan="7"></th>
                <th>{{ BdtNumFormat($total_debit) }}</th>
                <th>{{ BdtNumFormat($total_credit) }}</th>
                <th></th>
            </tr>

            <tr>
                <th>{{  \Carbon\Carbon::parse($toDate)->format('d M y')  }}</th>
                <th></th>
                <th></th>
                <th>Balance c/d</th>
                <th></th>
                <th></th>
                <th></th>
                <th>{{ $closing_balance < 0 ? BdtNumFormat(abs($closing_balance)):'' }}</th>
                <th>{{ $closing_balance > 0 ? BdtNumFormat(abs($closing_balance)):'' }}</th>
                <th></th>
            </tr>

            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th>Total</th>
                <th></th>
                <th></th>
                <th></th>
                @php
                    $total_balance_debit = $closing_balance < 0 ? $total_debit + abs($closing_balance) : $total_debit;
                    $total_balance_credit = $closing_balance > 0 ? $total_credit + abs($closing_balance) : $total_credit;
                @endphp
                <th>{{ BdtNumFormat($total_balance_debit) }}</th>
                <th>{{ BdtNumFormat($total_balance_credit) }}</th>
                <th></th>
            </tr>

    </tbody>
</table>
<br>
