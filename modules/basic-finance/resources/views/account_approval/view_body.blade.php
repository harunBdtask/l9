<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <table class="table table-borderless">
            <tbody>
            <tr>
                <th>Requisition No :</th>
                <td class="text-left">{{$approval->first()->requisition->requisition_no}}</td>
                <th>Requisition Date :</th>
                <td class="text-left">{{\Carbon\Carbon::parse($approval->first()->requisition->requisition_date)->toFormattedDateString()}}</td>
            </tr>
            <tr>
                <th>Approval Date :</th>
                <td class="text-left">
                    {{\Carbon\Carbon::parse($approval->first()->date)->toFormattedDateString()}}
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
                <th>Item Name</th>
                <th>Item Des</th>
                <th>UOM</th>
                <th>Existing QTY</th>
                <th>Req QTY</th>
                <th>Appr QTY</th>
                <th>Rate</th>
                <th>Appr Rate</th>
                <th>Amount</th>
                <th>Appr Amount</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @php
                $total_existing_qty=0;
                $total_approved_qty=0;
                $total_req_qty=0;
                $total_approved_amount=0;
                $total_amount=0;
            @endphp
            @foreach($approval as $index=>$detail)
                @php
                    $total_req_qty+=$detail->detail->req_qty;
                    $total_amount+=$detail->detail->amount;
                    $total_approved_amount+=$detail->amount;
                    $total_approved_qty+=$detail->approved_qty;
                @endphp
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$detail->detail->item->item_group}}</td>
                    <td>{{$detail->detail->item_description}}</td>
                    <td>{{$detail->detail->uoms()}}</td>
                    <td class="text-right">{{$detail->detail->existing_qty}}</td>
                    <td class="text-right">{{$detail->detail->req_qty}}</td>
                    <td class="text-right">{{$detail->approved_qty}}</td>
                    <td class="text-right">{{$detail->detail->rate}}</td>
                    <td class="text-right">{{$detail->rate}}</td>
                    <td class="text-right">{{$detail->detail->amount}}</td>
                    <td class="text-right">{{$detail->amount}}</td>
                    <td>{{$detail->remarks}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4"></th>
                <th>Total</th>
                <th class="text-right">{{$total_req_qty}}</th>
                <th class="text-right">{{$total_approved_qty}}</th>
                <th></th>
                <th></th>
                <th class="text-right">{{$total_amount}}</th>
                <th class="text-right">{{$total_approved_amount}}</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        @php
            $formatter = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
        @endphp
        <strong>In words
            (Total): {{strtoupper($formatter->format($total_approved_amount))}}
            TK</strong>
    </div>
</div>
