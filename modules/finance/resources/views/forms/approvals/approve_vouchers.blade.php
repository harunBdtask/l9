<div class="box-body b-t">
    <div class="row">
        <div class="col-md-12" style="color: #0e9b54; font-size: 28px;">
            <span><u><b>APPROVE VOUCHERS</b></u></span>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table reportTable" id="tabular-form">
        <thead class="thead-light" style="background-color: deepskyblue;">
        <tr>
            <th>DATE</th>
            <th>VOUCHER NO</th>
            <th>VOUCHER TYPE</th>
            <th>FC CURRENCY</th>
            <th>HOME CURRENCY</th>
            <th>COMPANY</th>
            <th>PROJECT</th>
            <th>UNIT</th>
            <th>USER</th>
            <th>APPROVED BY</th>
            <th style="width: 13%;">COMMENTS</th>
            <th>VOUCHER STATUS</th>
            <th>ACTION</th>
        </tr>
        </thead>
        <tbody>
            @php
                $totalFcCurrency = 0;
                $totalHomeCurrency = 0;
            @endphp
        @foreach($approveVouchers as $approveVoucher)
            @php
                $fcCurrency = $approveVoucher->details->total_debit_fc ?? $approveVoucher->details->total_credit_fc;
                $totalFcCurrency += $fcCurrency;
                $homeCurrency = $approveVoucher->details->total_debit ?? $approveVoucher->details->total_credit;
                $totalHomeCurrency += $homeCurrency;
            @endphp
            <tr>
                <td>{{ $approveVoucher->trn_date }}</td>
                <td>{{ $approveVoucher->voucher_no }}</td>
                <td>{{ ucfirst(\SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::VOUCHER_TYPE[$approveVoucher->type_id]) }}</td>
                <td>{{ number_format($fcCurrency, 2) }}</td>
                <td>{{ number_format($homeCurrency, 2) }}</td>
                <td>{{ $approveVoucher->factory->factory_name }}</td>
                <td>{{ $approveVoucher->project->project }}</td>
                <td>{{ $approveVoucher->unit->unit }}</td>
                <td>{{ $approveVoucher->createdUser->first_name . ' ' . $approveVoucher->createdUser->last_name }}</td>
                <td>{{ $approveVoucher->createdUser->first_name . ' ' . $approveVoucher->createdUser->last_name }}</td>
                <td style="word-break: break-word;">{{ collect($approveVoucher->comments)->pluck('comment')->join(', ') }}</td>
                <td>{{ \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::$statuses[$approveVoucher->status_id] }}</td>
                <td>
                    <a
                        class="btn btn-xs btn-primary"
                        href="{{ url('finance/vouchers/'.$approveVoucher->id) }}"
                        onclick="window.open(this.href,'_blank'); return false;"
                    >
                        <i class="fa fa-eye"></i>
                    </a>
                </td>
            </tr>
        @endforeach
        <tr style="font-weight: bold">
            <td style="text-align: right;" colspan="3">Total</td>
            <td>{{ number_format($totalFcCurrency, 2) }}</td>
            <td>{{ number_format($totalHomeCurrency, 2) }}</td>
            <td colspan="8"></td>
        </tr>
        </tbody>
    </table>
</div>
