<div class="body-section" style="margin-top: 0px;">
    <table>
        <tbody>
        <tr>
            <th>Beneficiary</th>
            <td>{{Arr::get($contract,'factory.factory_name')}}</td>
            <td></td>
            <th>Buyer</th>
            <td>{{Arr::get($contract,'buyers')}}</td>
        </tr>
        <tr>
            <th>Contract NO</th>
            <td>{{$contract->contract_number}}</td>
            <td></td>
            <th>Lien Bank</th>
            <td>{{ $contract->lienBank->name??null }}</td>
        </tr>
        <tr>
            <th>Contract DATE</th>
            <td>{{ date('d-m-Y', strtotime($contract->contract_date)) }}</td>
            <td></td>
            <th>Last Shipment Date</th>
            <td>{{ $contract->last_shipment_date?date('d-m-Y', strtotime($contract->last_shipment_date)):'' }}</td>
        </tr>
        <tr>
            <th>Contract VALUE</th>
            <td>{{ number_format($contract->contract_value, 2) }}</td>
            <td>{{Arr::get($contract,'currency.currency_name')}}</td>
            <th>Incoterm</th>
            <td>{{ strtoupper($contract->inco_term)}}</td>
        </tr>
        <tr>
            <th>Contract Expiry Date</th>
            <td>{{ $contract->expiry_date?date('d-m-Y', strtotime($contract->expiry_date)):'' }}</td>
            <td></td>
            <th>Shipping Mode</th>
            <td>{{ucfirst($contract->shipping_mode)}}</td>
        </tr>
        @if($contract->buying_agent_id)
        <tr>
            <th>Buying Agent</th>
            <td>{{ $contract->buyingAgent->buying_agent_name??null }}</td>
            <td></td>
            <th>Buying Agent Address</th>
            <td>{{ $contract->buyingAgent->address??null }}</td>
        </tr>
        @endif
        </tbody>
    </table>
    <div style="margin-top: 40px"></div>
    <table >
        <thead style="background-color: #c8f6c2;">
        <tr>
            <th>Style No</th>
            <th>PO</th>
            <th>Contract Quantity</th>
            <th>Contract Value</th>
            <th>Attach Qty</th>
            <th>PC/SET</th>
            <th>Rate</th>
            <th>Attach Value</th>
        </tr>
        </thead>
        <tbody>
        @php $totalContactValue = 0; @endphp
        @foreach($contract->details as $key=>$details)
            @php $totalContactValue = $totalContactValue + ($details->po->po_quantity * $details->po->avg_rate_pc_set) ;@endphp
            <tr>
                
                <td>{{$details->orders->style_name}}</td>
                <td>{{$details->po->po_no}}</td>
                <td>{{$details->po->po_quantity}}</td>
                <td>{{$details->po->po_quantity * $details->po->avg_rate_pc_set}}</td>
                <td class="text-right">{{$details->attach_qty}}</td>
                <td class="text-right">{{$details->orders->uom->unit_of_measurement}}</td>
                <td class="text-right">{{$details->rate}}</td>
                <td class="text-right">{{$details->attach_value}}</td>
            </tr>
        @endforeach
        <tr style="background-color: #fcffc6;">
            <th class="text-right" colspan="7">Total Attached Value</th>
            <th class="text-right">{{ round($contract->details->sum('attach_value'),4) }}</th>
        </tr>
        <tr style="background-color: #fcffc6;">
            <th class="text-right" colspan="7">Contract Value</th>
            {{-- <th class="text-right">{{ round($totalContactValue, 4) }}</th> --}}
            <th class="text-right">{{ round($contract->contract_value, 4) }}</th>
        </tr>
        @php  $discrepancy = round($contract->details->sum('attach_value'),4) - round($contract->contract_value, 4); @endphp
        <tr style="background-color: #fcffc6;">
            <th class="text-right" colspan="7">Discrepancy</th>
            <th class="text-right">{{ round($discrepancy ,4) }}</th>
        </tr>
        </tbody>
    </table>
    <div style="margin-top: 20px"></div>
    <?php /*
    @php
        $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
    @endphp
    <div class="row">
        <div class="col-sm-12">
            <table class="borderless">
                <tr>
                    <th style="width:250px; text-align:left !important;">SUM Amount In Words:</th>
                    <th style="text-align:left;">{{ucfirst($f->format($contract->details->sum('attach_value')))}} {{Arr::get($contract,'currency.currency_name')}}</th>
                </tr>
                <tr>
                    <th style="width:250px; text-align:left !important;">CONTRACT Amount In Words:</th>
                    <th style="text-align:left;">{{ucfirst($f->format($totalContactValue))}} {{Arr::get($contract,'currency.currency_name')}}</th>
                </tr>
                <tr>
                    <th style="width:250px; text-align:left !important;">DISCREPANCY Amount In Words:</th>
                    <th style="text-align:left;">{{ucfirst($f->format(($contract->details->sum('attach_value') - $totalContactValue)))}} {{Arr::get($contract,'currency.currency_name')}}</th>
                </tr>
            </table>
        </div>
    </div>
    */ ?>
</div>
