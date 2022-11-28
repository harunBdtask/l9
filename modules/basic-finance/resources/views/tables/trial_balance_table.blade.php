<div class="row">
    <div class="col-md-12">
        <table class="reportTable">
            <thead class="thead-light">
            <tr>
                <th class="text-left">AC CODE</th>
                <th class='text-left'>HEAD OF ACCOUNT</th>
                <th class="text-right">DEBIT</th>
                <th class="text-right">CREDIT</th>
            </tr>
            </thead>
            <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td class="text-left">{{ $account->code }}</td>
                    <td class='text-left'>{{ $account->name }}</td>
                    <td class="text-right">
                        {{ $account->balance >= 0 ? number_format(abs($account->balance), 2) : '' }}
                    </td>
                    <td class="text-right">
                        {{ $account->balance < 0 ? number_format(abs($account->balance), 2) : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-danger">No transaction</td>
                </tr>
            @endforelse
            <tr>
                <td class="text-left" colspan="2"><strong>TOTAL</strong></td>
                <td class="text-right"><strong>{{ number_format(abs($total_debit), 2) }}</strong></td>
                <td class="text-right"><strong>{{ number_format(abs($total_credit), 2) }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
