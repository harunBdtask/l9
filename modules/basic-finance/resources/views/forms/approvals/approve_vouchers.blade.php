<div class="box-body b-t">
    <div class="row">
        <div class="col-md-12" style="color: #0e9b54; font-size: 28px;">
            <span><u><b>APPROVED VOUCHERS</b></u></span>
        </div>
    </div>
</div>


<div class="table-responsive">
    <div class="parentTableFixed" style="overflow: auto">
        <table class="table reportTable fixTable" id="tabular-form" style="width: 100%">
            <thead class="thead-light" style="background-color: deepskyblue;">
            <tr>
                <th>TRAN. DATE</th>
                <th>ENTRY DATE</th>
                <th>VOUCHER NO</th>
                <th>VOUCHER TYPE</th>
                <th>FC CURRENCY</th>
                <th>HOME CURRENCY</th>
                <th>COMPANY</th>
                <th>PROJECT</th>
                <th>UNIT</th>
                <th>ACCOUNTS</th>
                <th>DEPARTMENT</th>
                <th>COST CENTER</th>
                <th>USER</th>
                <th>REFERENCE No</th>
                <th>APPROVED BY</th>
                <th style="width: 13%;">COMMENTS</th>
                <th>BILL NO.</th>
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
                    $accounts = collect($approveVoucher->details->items)->pluck('account_name')->unique()->join(', ');
                    $departments = collect($approveVoucher->details->items)->pluck('department_name')->unique()->join(', ');
                    $costCenters = collect($approveVoucher->details->items)->pluck('const_center_name')->unique()->join(', ');
                    $totalHomeCurrency += $homeCurrency;
                @endphp
                <tr>
                    <td>{{ date('M d, Y', strtotime($approveVoucher->trn_date)) }}</td>
                    <td>{{ date('M d, Y h:i:s a', strtotime($approveVoucher->created_at)) }}</td>
                    <td>{{ $approveVoucher->voucher_no }}</td>
                    <td>{{ ucfirst(\SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::VOUCHER_TYPE[$approveVoucher->type_id]) }}</td>
                    <td>{{ number_format($fcCurrency, 2) }}</td>
                    <td>{{ number_format($homeCurrency, 2) }}</td>
                    <td>{{ $approveVoucher->factory->factory_name }}</td>
                    <td>{{ $approveVoucher->project->project }}</td>
                    <td>{{ $approveVoucher->unit->unit }}</td>
                    <td>{{ $accounts }}</td>
                    <td>{{ $departments }}</td>
                    <td>{{ $costCenters }}</td>
                    <td>{{ $approveVoucher->createdUser->first_name . ' ' . $approveVoucher->createdUser->last_name }}</td>
                    <td>{{ $approveVoucher->reference_no }}</td>
                    <td>
                        @if($approveVoucher->comments)
                            @php  $approveBy = collect($approveVoucher->comments)->where('status_id', $voucher_post_status)->first(); @endphp
                            {{ !empty($approveBy->commenter) ? ($approveBy->commenter->first_name . ' ' . $approveBy->commenter->last_name) : '' }}
                        @endif
                </td>
                    <td style="word-break: break-word;">{{ collect($approveVoucher->comments)->pluck('comment')->join(', ') }}</td>
                    <td>{{ $approveVoucher->bill_no }}</td>
                    <td>{{ \SkylarkSoft\GoRMG\BasicFinance\Models\Voucher::$statuses[$approveVoucher->status_id] }}</td>
                    <td>
                        <a
                            class="btn btn-xs btn-primary"
                            href="{{ url('basic-finance/vouchers/'.$approveVoucher->id) }}"
                            onclick="window.open(this.href,'_blank'); return false;"
                        >
                            <i class="fa fa-eye"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            <tr style="font-weight: bold">
                <td style="text-align: right;" colspan="4">Total</td>
                <td>{{ number_format($totalFcCurrency, 2) }}</td>
                <td>{{ number_format($totalHomeCurrency, 2) }}</td>
                <td colspan="13"></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
