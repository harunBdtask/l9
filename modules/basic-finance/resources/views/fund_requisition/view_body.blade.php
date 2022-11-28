<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <table class="table table-borderless">
            <tbody>
            <tr>
                <th>Requisition No:</th>
                <td class="text-left">{{$requisition->requisition_no}}</td>
                <th>Requisition Date :</th>
                <td class="text-left">{{\Carbon\Carbon::make($requisition->requisition_date)->toFormattedDateString()}}</td>
            </tr>
            <tr>
                <th>Name :</th>
                <td class="text-left">{{$requisition->name}}</td>
                <th>Expected Receive Date :</th>
                <td class="text-left">{{\Carbon\Carbon::make($requisition->expect_receive_date)->toFormattedDateString()}}</td>
            </tr>
            <tr>
                <th>Designation :</th>
                <td class="text-left">{{$requisition->designation}}</td>
                <th>Project :</th>
                <td class="text-left">{{$requisition->project->project}}</td>
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
                <th>Unit</th>
                <th>Item Name</th>
                <th>Item Des</th>
                <th>UOM</th>
                <th>Existing QTY</th>
                <th>Req QTY</th>
                <th>Rate</th>
                <th>Amount</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @foreach($requisition->details as $index=>$detail)
                <tr>
                    <td>{{$index+1}}</td>
                    <td>{{$detail->unit->unit}}</td>
                    <td>{{$detail->item->item_group}}</td>
                    <td>{{$detail->item_description}}</td>
                    <td>{{$detail->uoms()}}</td>
                    <td class="text-right">{{$detail->existing_qty}}</td>
                    <td class="text-right">{{$detail->req_qty}}</td>
                    <td class="text-right">{{$detail->rate}}</td>
                    <td class="text-right">{{$detail->amount}}</td>
                    <td>{{$detail->remarks}}</td>
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr>
                <th colspan="4"></th>
                <th>Total</th>
                <th class="text-right">{{$requisition->details->sum('existing_qty')}}</th>
                <th class="text-right">{{$requisition->details->sum('req_qty')}}</th>
                <th></th>
                <th class="text-right">{{$requisition->details->sum('amount')}}</th>
                <th></th>
            </tr>
            </tfoot>
        </table>
        @php
            $formatter = new \NumberFormatter( locale_get_default(), \NumberFormatter::SPELLOUT );
        @endphp
        <strong>In words (Total): {{strtoupper($formatter->format($requisition->details->sum('amount')))}} TK</strong>
    </div>
</div>
