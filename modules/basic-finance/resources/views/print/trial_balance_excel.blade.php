<div>
    <div>
        <table>
            <tr>
                <td colspan="4" class="text-left" style="background-color: lightblue"><b>{{factoryName()}}</b></td>
            </tr>
            <tr>
                <td colspan="4" class="text-left" style="background-color: lightblue"><b>{{factoryAddress()}}</b></td>
            </tr>
        </table>
    </div>
    <div>
        <table>
            <tr>
                <td colspan="4" style="background-color: lightblue"><h3>Trial Balance Report</h3></td>
            </tr>
        </table>
    </div>
    <div>
        <table>
            <thead class="thead-light">
            <tr>
                <td style="border:1px solid black; background-color: lightgray;" class="text-left">AC CODE</td>
                <td style="border:1px solid black; background-color: lightgray;" class='text-left'>HEAD OF ACCOUNT</td>
                <td style="border:1px solid black; background-color: lightgray;" class="text-right">DEBIT</td>
                <td style="border:1px solid black; background-color: lightgray;" class="text-right">CREDIT</td>
            </tr>
            </thead>
            <tbody>
            @forelse($accounts as $account)
                <tr>
                    <td style="border:1px solid black;" class="text-left">{{ $account->code }}</td>
                    <td style="border:1px solid black;" class='text-left'>{{ $account->name }}</td>
                    <td style="border:1px solid black;" class="text-right">
                        {{ $account->balance >= 0 ? number_format(abs($account->balance), 2) : '' }}
                    </td>
                    <td style="border:1px solid black;" class="text-right">
                        {{ $account->balance < 0 ? number_format(abs($account->balance), 2) : '' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td style="border:1px solid black; background-color: lightgray;" colspan="4"
                        class="text-center text-danger">No transaction
                    </td>
                </tr>
            @endforelse
            <tr>
                <td style="border:1px solid black; background-color: lightgray;" class="text-left" colspan="2"><strong>TOTAL</strong>
                </td>
                <td style="border:1px solid black; background-color: lightgray;" class="text-right">
                    <strong>{{ number_format(abs($total_debit), 2) }}</strong></td>
                <td style="border:1px solid black; background-color: lightgray;" class="text-right">
                    <strong>{{ number_format(abs($total_credit), 2) }}</strong></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>


