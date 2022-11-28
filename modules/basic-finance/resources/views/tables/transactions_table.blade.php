<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead class="thead-light">
            <tr>
                <th class="text-left">Date</th>
                <th class="text-left">Voucher No</th>
                <th class="text-left">Voucher Type</th>
                <th class="text-left">Refrence No</th>
                <th class="text-left">Code</th>
                <th class="text-left">A/C Name</th>
                <th class="text-left">Particulars</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $trn)
                <tr>
                    <td class="text-left">{{ $trn->trn_date->toFormattedDateString() }}</td>
                    <td class="text-left">
                        {{ str_pad($trn->voucher->id, 8, '0', STR_PAD_LEFT) }}
                    </td>
                    <td class="text-left">{{ $trn->voucher->type }}</td>
                    <td class="text-left">{{ $trn->voucher->file_no }}</td>
                    <td class="text-left">{{ $trn->account->code }}</td>
                    <td class="text-left">{{ $trn->account->name }}</td>
                    <td class="text-left">{{ $trn->particulars ?? '' }}</td>
                    <td class="text-right">
                        {{ $trn->trn_type == 'dr' ? number_format($trn->trn_amount, 2) : '' }}
                    </td>
                    <td class="text-right">
                        {{ $trn->trn_type == 'cr' ? number_format($trn->trn_amount, 2) : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-danger">No Voucher Found</td>
                </tr>
            @endforelse
            </tbody>
            <tfoot>
            @if (request()->get('type') != 'excel')
                @if($transactions->total() > 15)
                    <tr>
                        <td colspan="10" align="center">
                            {{ $transactions->appends(request()->except('page'))->links() }}
                        </td>
                    </tr>
                @endif
            @endif
            </tfoot>
        </table>
    </div>
</div>
