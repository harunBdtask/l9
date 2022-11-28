<table style="width: 100%; border: none;">
    <tr style="border: none;">
        <td style="width: 40%; border: none;">
            <b>Bill No.</b> : {{ $billEntry->bill_number }}
        </td>
        <td style="width: 20%; border: none;"></td>
        <td style="width: 40%; border: none; text-align: right;">
            <b>Bill Date </b> : {{ $billEntry->bill_date ? date('j-F-Y', strtotime($billEntry->bill_date)) : '' }}
        </td>
    </tr>
</table>

<table class="reportTable" style="margin-top: 1.5rem;">
    <thead>
    <tr>
        <th style="text-align: left;">Particulars</th>
        <th style="text-align: right;">Debit</th>
        <th style="text-align: right;">Credit</th>
    </tr>
    </thead>

    <tbody>
    @php
        $detailsCollection = collect($billEntry->details);
        $totalValue = $detailsCollection->sum('total_price');
    @endphp
    @if($billEntry->details)
        @foreach($billEntry->details as $detail)
            <tr>
                <td style="text-align: left;">
                    {{ $detail['control_account_name'] ?? '' }} : &nbsp; {{ $detail['ledger_account_name'] ?? '' }} <br>
                    <span style="margin-left: 20%; font-weight: bold;">Project</span><br>
                    <span style="margin-left: 20%; font-weight: bold;">{{ $billEntry->project->project ?? '' }}</span>
                    <span style="margin-left: 40%; font-weight: bold;">{{ $detail['net_price'] ?? '' }} Dr</span>
                </td>
                <td style="text-align: right;">{{ $detail['net_price'] ?? '' }}</td>
                <td style="text-align: right;"></td>
            </tr>
        @endforeach
    @endif

    @php
        $totalCredit = $billEntry->vat_type == 2 ? ((double)$billEntry->total_tds + (double)$billEntry->total_vat + (double)$billEntry->party_payable) : (double)$billEntry->party_payable;
        $totalDebit = $billEntry->vat_type == 2 ? $totalValue+(double)$billEntry->total_vat : $totalValue;
    @endphp

    @if($billEntry->vat_type == 2)
        <tr>
            <td style="text-align: left;">VAT Expense</td>
            <td style="text-align: right;">{{ number_format($billEntry->total_vat, 2) }}</td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: left;">VAT Payable</td>
            <td></td>
            <td style="text-align: right;">{{ number_format($billEntry->total_vat, 2) }}</td>
        </tr>
        <tr>
            <td style="text-align: left;">TDS Payable</td>
            <td></td>
            <td style="text-align: right;">{{ number_format($billEntry->total_tds, 2) }}</td>
        </tr>
    @endif

    @if($billEntry->supplier_id)
        <tr>
            <td style="text-align: left;">{{ $billEntry->supplier->name ?? '' }}</td>
            <td></td>
            <td style="text-align: right;">{{ number_format(($billEntry->party_payable), 2) }}</td>
        </tr>
    @endif

    <tr>
        <td style="text-align: right;"><b>Total:</b></td>
        <td style="text-align: right;"><b>{{number_format(($totalDebit), 2)}}</b></td>
        <td style="text-align: right;"><b>{{ number_format($totalCredit, 2) }}</b></td>
    </tr>
    </tbody>
</table>

<div class="row" style="margin-top: 1rem;">
    <div class="col-md-12">
        @php
            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        @endphp
        <strong>Amount (in words) : </strong>{{ ucwords($digit->format($totalDebit)) }}<br>
        <strong>Narration : </strong>{{ $detailsCollection->pluck('item')->unique()->values()->join(', ') }}
        {{ $detailsCollection->pluck('description')->unique()->values()->join(', ') }},
        {{ $detailsCollection->sum('qty') }}
        {{ $detailsCollection->pluck('uom')->unique()->values()->join(' & ') }}
    </div>
</div>

<div style="margin-top: 20mm">
    <table class="borderless" style="width: 100%; border-collapse: collapse;">
        <tbody>
        <tr>
            <td class="text-center" style="padding: 5px 3px;">
                <span style="border-top: 1px solid black">Prepared By</span>
            </td>
            <td class="text-center" style="padding: 5px 3px;">
                <span style="border-top: 1px solid black">Dept.Head</span>
            </td>
            <td class="text-center" style="padding: 5px 3px;">
                <span style="border-top: 1px solid black">Approved By</span>
            </td>
        </tr>
        </tbody>
    </table>
</div>
