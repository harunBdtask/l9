<div class="row">
    <div class="col-lg-10 col-lg-offset-1">
        <table class="table table-borderless">
            <tbody>
            <tr>
                <th>Requisition No :</th>
                <td class="text-left">{{$requisition['requisition_no']}}</td>
                <th>Requisition Date :</th>
                <td class="text-left">
                    {{\Carbon\Carbon::make($requisition['requisition_date'])->toFormattedDateString()}}
                </td>
            </tr>
            <tr>
                <th>Company :</th>
                <td class="text-left">
                </td>
                <th>Expected Receive Date :</th>
                <td class="text-left">
                    {{\Carbon\Carbon::make($requisition['expect_receive_date'])->toFormattedDateString()}}
                </td>
            </tr>
            <tr>
                <th>Unit :</th>
                {{--                <td class="text-left">{{$requisition->unit->unit}}</td>--}}
                <th>Remaining Days :</th>
                <td class="text-left">{{\Carbon\Carbon::today()->diffInDays($requisition['expect_receive_date'])}}
                    Days
                </td>
            </tr>
            </tbody>
        </table>

        <br>
        <br>
        <h5 class="text-center">Fund Requisition</h5>
        <hr>
        <table class="reportTable">
            <thead>
            <tr>
                <th>Date</th>
                <th>Dept</th>
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
            @foreach($requisition['details'] as $index => $detail)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($detail->date)->toFormattedDateString() }}</td>
                    <td>{{$detail->department->name}}</td>
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
                <th class="text-right">{{$requisition['details']->sum('existing_qty')}}</th>
                <th class="text-right">{{$requisition['details']->sum('req_qty')}}</th>
                <th></th>
                <th class="text-right">{{$requisition['details']->sum('amount')}}</th>
                <th></th>
            </tr>
            </tfoot>
        </table>

        <br>
        <br>
        <h5 class="text-center">Audit Approval</h5>
        <hr>
        <table class="reportTable">
            <thead>
            <tr>
                <th>Date</th>
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
            @foreach($requisition['auditApproved'] as $index => $detail)
                @php
                    $total_existing_qty += $detail->detail->existing_qty;
                    $total_req_qty += $detail->detail->req_qty;
                    $total_amount += $detail->detail->amount;
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($detail->date)->toFormattedDateString() }}</td>
                    <td>{{$detail->detail->department->name}}</td>
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

        <br>
        <br>
        <h5 class="text-center">Account Approval</h5>
        <hr>
        <table class="reportTable">
            <thead>
            <tr>
                <th>Date</th>
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
            @foreach($requisition['acApproved'] as $index => $detail)
                @php
                    $total_req_qty += $detail['detail']->req_qty;
                    $total_amount += $detail['detail']->amount;
                    $total_approved_amount += $detail['amount'];
                    $total_approved_qty += $detail['approved_qty'];
                @endphp
                <tr>
                    <td>{{ \Carbon\Carbon::parse($detail['date'])->toFormattedDateString() }}</td>
                    <td>{{$detail['detail']->item->item_group}}</td>
                    <td>{{$detail['detail']->item_description}}</td>
                    <td>{{$detail['detail']->uoms()}}</td>
                    <td class="text-right">{{$detail['detail']->existing_qty}}</td>
                    <td class="text-right">{{$detail['detail']->req_qty}}</td>
                    <td class="text-right">{{$detail['approved_qty']}}</td>
                    <td class="text-right">{{$detail['detail']->rate}}</td>
                    <td class="text-right">{{$detail['rate']}}</td>
                    <td class="text-right">{{$detail['detail']->amount}}</td>
                    <td class="text-right">{{$detail['amount']}}</td>
                    <td>{{$detail['remarks']}}</td>
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
    </div>
</div>
