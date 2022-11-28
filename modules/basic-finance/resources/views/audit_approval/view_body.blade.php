<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <table class="table table-borderless">
            <tbody>
            <tr>
                <th>Requisition No :</th>
                <td class="text-left">{{$approval->first()->requisition->requisition_no}}</td>
                <th>Requisition Date :</th>
                <td class="text-left">{{\Carbon\Carbon::make($approval->first()->requisition->requisition_date)->toFormattedDateString()}}</td>
            </tr>
            <tr>
                <th>Audit Date :</th>
                <td class="text-left">
                    {{\Carbon\Carbon::make($approval->first()->audit_date)->toFormattedDateString()}}
                </td>
                <th></th>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12">
        <table class="reportTable">
            <thead>
            <tr>
                <th>SL</th>
                <th>Dept</th>
                <th>Item Name</th>
                <th>Item Des</th>
                <th>UOM</th>
                <th>Existing QTY</th>
                <th>Req QTY</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Remarks</th>
                <th>Audit Comment</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total_existing_qty=0;
                $total_req_qty=0;
                $total_amount=0;
            @endphp
            @foreach($approval as $index=>$detail)
                @php
                    $total_existing_qty+=$detail->detail->existing_qty;
                    $total_req_qty+=$detail->detail->req_qty;
                    $total_amount+=$detail->detail->amount;
                @endphp
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$detail->detail->unit->unit}}</td>
                    <td>{{$detail->detail->item->item_group}}</td>
                    <td>{{$detail->detail->item_description}}</td>
                    <td>{{$detail->detail->uoms()}}</td>
                    <td class="text-right">{{$detail->detail->existing_qty}}</td>
                    <td class="text-right">{{$detail->detail->req_qty}}</td>
                    <td class="text-right">{{$detail->detail->rate}}</td>
                    <td class="text-right">{{$detail->detail->amount}}</td>
                    <td>{{$detail->detail->remarks}}</td>
                    <td>{{$detail->comment}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4"></th>
                <th>Total</th>
                <th class="text-right">{{$total_existing_qty}}</th>
                <th class="text-right">{{$total_req_qty}}</th>
                <th></th>
                <th class="text-right">{{$total_amount}}</th>
                <th colspan="2"></th>
            </tr>
            </tfoot>
        </table>
        @php
            $formatter = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
        @endphp
        <strong>In words
            (Total): {{strtoupper($formatter->format($total_amount))}}
            TK</strong>
    </div>
</div>
