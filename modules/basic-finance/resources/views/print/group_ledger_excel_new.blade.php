<div>
    <table>
        <tr>
            <td colspan="5" class="text-center" style="height: 30px;font-size: 20pt; font-weight: bold;">
                <b>{{factoryName()}}</b></td>
        </tr>
        <tr>
            <td colspan="5" class="text-center" style="height: 20px;"><h3>Group Report</h3></td>
        </tr>
    </table>
</div>
<div>
    <table style="margin-bottom: 5px;" class="borderless">
        <tr>
            <td style="width:150px;">
                <b>Account Head:</b>
            </td>
            <td>
                {{$account_names}}

            </td>
        </tr>
        <tr>
            <td style="width:102px;">
                <b>Date:</b>
            </td>
            <td>
                {{Carbon\carbon::parse($start_date)->format('F d, Y')}} - {{Carbon\carbon::parse($end_date)->format('F d, Y')}}

            </td>
        </tr>
    </table>
</div>
<div>

    <div class="row">
        <div class="col-md-12">
            <table class="reportTable">
                <thead class="thead-light">
                <tr>
                    <th class='text-left'>ACCOUNT CODE</th>
                    <th class='text-left'>ACCOUNT HEAD</th>
                    <th class="text-right">OPENING BALANCE</th>
                    <th class="text-right">DEBIT</th>
                    <th class="text-right">CREDIT</th>
                    <th class="text-right">CLOSING BALANCE</th>
                </tr>
                </thead>
                <tbody>
                
                    @forelse($ledgersData as $ledgerGroup)
                        @php
                            $total_opening = $total_debit = $total_credit = $total_closing = 0;
                        @endphp
                        @foreach($ledgerGroup as $ledger)
                            @php
    
                            $total_opening += $ledger->opening_balance;
                            $total_debit += $ledger->debit;
                            $total_credit += $ledger->credit;
                            $total_closing += $ledger->closing_balance;
    
                            @endphp
                        <tr>
                            <td>{{ $ledger->code }}</td>
                            <td class="text-left">{{ $ledger->name }}</td>
                            <td>{{ BdtNumFormat(abs($ledger->opening_balance)) }} {{  $ledger->opening_balance>0?'Dr':'Cr'; }}</td>
                            <td>{{ BdtNumFormat($ledger->debit) }}</td>
                            <td>{{ BdtNumFormat($ledger->credit) }}</td>
                            <td>{{ BdtNumFormat(abs($ledger->closing_balance)) }} {{  $ledger->closing_balance>0?'Dr':'Cr'; }}</td>

                        </tr>
                        @endforeach
                        <tr>
                            <th colspan="2" class="text-right">Total  {{ $ledger->parentAccountName??'' }}</th>
                            <th>{{ BdtNumFormat(abs($total_opening)) }} {{  $total_opening>0?'Dr':'Cr'; }}</th>
                            <th>{{ BdtNumFormat($total_debit) }}</th>
                            <th>{{ BdtNumFormat($total_credit) }}</th>
                            <th>{{ BdtNumFormat(abs($total_closing)) }} {{  $total_closing>0?'Dr':'Cr'; }}</th>
                        </tr>
                    @empty
                    <tr>
                        <td colspan="6">No data found!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
