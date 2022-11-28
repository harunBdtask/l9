<div>
    <div>
        <div>
            <table>
                <tr>
                    <td colspan="9" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
                </tr>
                <tr>
                    <td colspan="9" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b></td>
                </tr>
            </table>
        </div>
        <div>
            <table>
                <tr>
                    <td colspan="9" style="background-color: lightblue"><h3>Trial Balance Report</h3></td>
                </tr>
            </table>
        </div>
    <div>
        <table>
            <thead class="thead-light">
            <tr>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left">Date</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left" nowrap>Voucher No</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left" nowrap>Voucher Type</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left" nowrap>Reference No</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left" nowrap>Code</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left" nowrap>A/C Name</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-left">Particulars</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-right">Debit</td>
                <td style="border: 1px solid black; background-color: lightgray;" class="text-right">Credit</td>
            </tr>
            </thead>
            <tbody>
            @forelse($transactions as $trn)
                <tr>
                    <td style="border: 1px solid black;" class="text-left" nowrap>{{ $trn->trn_date->toFormattedDateString() }}</td>
                    <td style="border: 1px solid black;" class="text-left" nowrap>
                        {{ str_pad($trn->voucher->id, 8, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="border: 1px solid black;" class="text-left" nowrap>{{ $trn->voucher->type }}</td>
                    <td style="border: 1px solid black;" class="text-left">{{ $trn->voucher->file_no }}</td>
                    <td style="border: 1px solid black;" class="text-left" nowrap>{{ $trn->account->code }}</td>
                    <td style="border: 1px solid black;" class="text-left" nowrap>{{ $trn->account->name }}</td>
                    <td style="border: 1px solid black;" class="text-left">{{ $trn->particulars ?? '' }}</td>
                    <td style="border: 1px solid black;" class="text-right">
                        {{ $trn->trn_type == 'dr' ? number_format($trn->trn_amount, 2) : '' }}
                    </td>
                    <td style="border: 1px solid black;" class="text-right">
                        {{ $trn->trn_type == 'cr' ? number_format($trn->trn_amount, 2) : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td style="border: 1px solid black;" colspan="9" class="text-center text-danger">No Voucher Found</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

