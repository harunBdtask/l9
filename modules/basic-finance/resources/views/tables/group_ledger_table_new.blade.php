<style>
    .single-item {
        border-bottom: 1px solid #000;
    }
</style>
<div class="row">
    <div class="col-md-12">
        {!! $header !!}
        <table style="margin-bottom: 5px;" class="borderless">
            <tr>
                <td style="width:150px;border: 1px solid transparent !important;"><b>Account Head:</b></td>
                <td style="border: 1px solid transparent !important;">{{$account_names ?? ''}}</td>
            </tr>
        </table>
        <table class="reportTable">
            <thead class="thead-light">
            <tr>
                <th rowspan="2" class='text-center'>ACC. CODE</th>
                <th rowspan="2" class='text-center'>ACC. HEAD</th>
                <th rowspan="2" class="text-center">OPENING BALANCE</th>
                <th rowspan="2" class='text-center'>DEBIT</th>
                <th rowspan="2" class='text-center'>CREDIT</th>
                <th rowspan="2" class='text-center'>CLOSING BALANCE</th>
                <th rowspan="2" class='text-center'>ACTION</th>

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
                        <td>
                            @if($ledger->is_transactional)
                                @php 
                                    $urlData = http_build_query([
                                        'start_date' => $filter['start_date'],
                                        'end_date' => $filter['end_date'],
                                        'account_id' => $ledger->id,
                                        'factory_id' => $filter['companyId'],
                                        'project_id' => $filter['projectId'],
                                        'unit_id' => $filter['unitId'],
                                        'currency_type_id' => $filter['currency_id']
                                    ]);
                                @endphp

                                <a target="_blank" class="btn btn-info btn-xs" target="_blank" href="{{ url('/basic-finance/ledger?'.$urlData) }}" title="View Ledger">
                                    <i class="fa fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <th colspan="2" class="text-right">Total  {{ $ledger->parentAccountName??'' }}</th>
                        <th>{{ BdtNumFormat(abs($total_opening)) }} {{  $total_opening>0?'Dr':'Cr'; }}</th>
                        <th>{{ BdtNumFormat($total_debit) }}</th>
                        <th>{{ BdtNumFormat($total_credit) }}</th>
                        <th>{{ BdtNumFormat(abs($total_closing)) }} {{  $total_closing>0?'Dr':'Cr'; }}</th>
                        <th></th>
                    </tr>
                @empty
                <tr>
                    <td colspan="7">No data found!</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

