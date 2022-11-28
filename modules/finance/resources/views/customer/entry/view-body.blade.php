<table style="width: 100%; border: none;">
    <tr style="border: none;">
        <td style="width: 40%; border: none;">
            <table style="width: 100%; border: none; text-align: left;">
                <tr>
                    <th style="border: none;">Invoice No.</th>
                    <td style="border: none;">: &nbsp;{{ $billEntry->bill_no }}</td>
                </tr>
                <tr>
                    <th style="border: none;">Name of Customer</th>
                    <td style="border: none;">: &nbsp;{{ $billEntry->customer->name ?? '' }}</td>
                </tr>
                <tr>
                    <th style="border: none;">Address</th>
                    <td style="border: none;">: &nbsp;{{ $billEntry->customer->address_1 ?? '' }}</td>
                </tr>
            </table>
        </td>
        <td style="width: 20%; border: none;"></td>
        <td style="width: 40%; border: none;">
            <table style="width: 100%; margin-top: 20px; border: none; text-align: right;">
                <tr>
                    <th style="border: none;">Dated</th>
                    <td style="border: none; text-align: left">
                        : &nbsp;&nbsp; {{ date('j-F-Y', strtotime($billEntry->bill_date)) }}
                    </td>
                </tr>
                <tr>
                    <th style="border: none;">Gate Pass No</th>
                    <td style="border: none; text-align: left">: &nbsp;&nbsp; {{ $billEntry->gate_pass_no }}</td>
                </tr>
                <tr>
                    <th style="border: none;">Vehicle No</th>
                    <td style="border: none; text-align: left">: &nbsp;&nbsp; {{ $billEntry->vehicle_no }}</td>
                </tr>
                <tr>
                    <th style="border: none;">Driver Name</th>
                    <td style="border: none; text-align: left">: &nbsp;&nbsp; {{ $billEntry->driver_name }}</td>
                </tr>
                <tr>
                    <th style="border: none;">Currency</th>
                    <td style="border: none; text-align: left">: &nbsp;&nbsp; {{ $billEntry->currency_name }}</td>
                </tr>
                <tr>
                    <th style="border: none;">Conversion Rate</th>
                    <td style="border: none; text-align: left">: &nbsp;&nbsp; {{ $billEntry->cons_rate }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table class="reportTable" style="margin-top: 1.5rem;">
    <thead>
    <tr>
        <th style="text-align: left;">SL No</th>
        <th style="width: 45%; text-align: left;">Description of Goods</th>
        <th style="text-align: right;">Quantity</th>
        <th>Rate</th>
        <th>Per</th>
        <th style="text-align: right;">Amount</th>
    </tr>
    </thead>

    <tbody>
    @foreach($billEntry->details as $detail)
        <tr>
            <td style="text-align: left;">{{ $loop->iteration }}</td>
            <td style="text-align: left;">{{ $detail['fabric_description'] ?? '' }}</td>
            <td style="text-align: right;">{{ $detail['order_qty'] ?? '' }}</td>
            <td style="text-align: center;">{{ $detail['rate'] ?? '' }}</td>
            <td style="text-align: center;">{{ $detail['uom'] ?? '' }}</td>
            <td style="text-align: right;">{{ $detail['total_value'] ?? '' }}</td>
        </tr>
    @endforeach

    @php
        $detailsCollection = collect($billEntry->details);
        $totalValue = $detailsCollection->sum('total_value');
        $netTotal = $totalValue-$billEntry->discount;
    @endphp
    <tr>
        <td colspan="2" style="text-align: right;"><b>Total:</b></td>
        <td style="text-align: right;"><b>{{ number_format($detailsCollection->sum('order_qty'), 2) }} {{ $detailsCollection->pluck('uom')->unique()->values()->join(', ') }}</b></td>
        <td colspan="2"></td>
        <td style="text-align: right;"><b>{{ number_format($totalValue, 2) }}</b></td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right;"><b>Discount:</b></td>
        <td style="text-align: right;"><b>{{ number_format($billEntry->discount, 2) }}</b></td>
    </tr>
    <tr>
        <td colspan="5" style="text-align: right;"><b>Net Total:</b></td>
        <td style="text-align: right;"><b>{{ number_format($netTotal, 2) }}</b></td>
    </tr>
    </tbody>
</table>

<div class="row" style="margin-top: 1rem;">
    <div class="col-md-12">
        @php
            $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        @endphp
        <strong>Amount (in words) : </strong>{{ ucwords($digit->format($netTotal)) }}
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
                <span style="border-top: 1px solid black">Accounts Dept.</span>
            </td>
            <td class="text-center" style="padding: 5px 3px;">
                <span style="border-top: 1px solid black">Approved By</span>
            </td>
        </tr>
        </tbody>
    </table>
</div>
