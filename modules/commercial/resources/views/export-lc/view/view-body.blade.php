<div class="body-section" style="margin-top: 0px;">
    <table>
        <tbody>
        <tr>
            <th>Beneficiary</th>
            <td>{{Arr::get($contract,'factory.factory_name')}}</td>
            <td></td>
            <th>Buyer</th>
            <td>{{ $contract->buyers }}</td>
        </tr>
        <tr>
            <th>LC NO</th>
            <td>{{$contract->lc_number}}</td>
            <td></td>
            <th>Lien Bank</th>
            <td>{{Arr::get($contract, 'lienBank.name')}}</td>
        </tr>
        <tr>
            <th>LC DATE</th>
            <td>{{$contract->lc_date}}</td>
            <td></td>
            <th>Last Shipment Date</th>
            <td>{{$contract->last_shipment_date}}</td>
        </tr>
        <tr>
            <th>LC VALUE</th>
            <td>{{$contract->lc_value}}</td>
            <td>{{Arr::get($contract,'currency.currency_name')}}</td>
            <th>Incoterm</th>
            <td>{{$contract->inco_term}}</td>
        </tr>
        <tr>
            <th>LC Expiry Date</th>
            <td>{{$contract->lc_expiry_date}}</td>
            <td></td>
            <th>Shipping Mode</th>
            <td>{{ucfirst($contract->shipping_mode)}}</td>
        </tr>
        </tbody>
    </table>
    <div style="margin-top: 40px"></div>
    <table >
        <thead style="background-color: #c8f6c2;">
        <tr>
            <th>PO NO</th>
            <th>PO Quantity</th>
            <th>PO Value</th>
            <th>Attach Qty</th>
            <th>Rate</th>
            <th>Attach Value</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contract->details as $key=>$details)
            <tr>
                <td>{{$details->po->po_no}}</td>
                <td>{{$details->po->po_quantity}}</td>
                <td>{{$details->po->po_quantity * $details->po->avg_rate_pc_set}}</td>
                <td class="text-right">{{$details->attach_qty}}</td>
                <td class="text-right">{{$details->rate}}</td>
                <td class="text-right">{{$details->attach_value}}</td>
            </tr>
        @endforeach
        <tr style="background-color: #fcffc6;">
            <th class="text-right" colspan="5">Sum</th>
            <th class="text-right">{{$contract->details->sum('attach_value')}}</th>
        </tr>
        </tbody>
    </table>
    <div style="margin-top: 20px"></div>
    @php
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    @endphp
    <p><b>Amount In
            Words: {{ucfirst($f->format($contract->details->sum('attach_value')))}} {{Arr::get($contract,'currency.currency_name')}}</b>
    </p>
</div>
