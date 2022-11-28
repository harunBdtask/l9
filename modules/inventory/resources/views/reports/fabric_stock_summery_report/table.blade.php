<table class="reportTable">
    <thead>
    <tr>
        <th style="background-color: aliceblue;">Pi No</th>
        <th style="background-color: aliceblue;">Buyer</th>
        <th style="background-color: aliceblue;">Style No</th>
        <th style="background-color: aliceblue;">Po No</th>
        <th style="background-color: aliceblue;">Color</th>
        <th style="background-color: aliceblue;">Order Qty Req</th>
        <th style="background-color: aliceblue;">Rec Date</th>
        <th style="background-color: aliceblue;">Rec Qty</th>
        <th style="background-color: aliceblue;">Rec Return Qty</th>
        <th style="background-color: aliceblue;">Rate</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;">Party Name</th>
        <th style="background-color: aliceblue;">Issue Qty</th>
        <th style="background-color: aliceblue;">Issue Return Qty</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;">Stock Qty</th>
        <th style="background-color: aliceblue;">Value</th>
        <th style="background-color: aliceblue;">Remarks</th>
        <th style="background-color: aliceblue;">InHouse Age</th>
        <th style="background-color: aliceblue;">Location</th>
    </tr>
    </thead>
    <tbody>

    @php
        $totalReceiveQtySum = 0;
        $totalReceiveReturnQty = 0;
        $totalRateSum = 0;
        $totalValueSum = 0;
        $totalIssueQtySum = 0;
        $totalIssueReturnQtySum = 0;
        $totalIssueAmountSum = 0;
        $totalBalanceQtySum = 0;
        $totalBalanceAmountSum = 0;
    @endphp

    @foreach ($fabricStockSummary as $summary)

        @php
            $receiveQtySum = 0;
            $receiveReturnQty = 0;
            $rateSum = 0;
            $valueSum = 0;
            $issueQtySum = 0;
            $issueReturnQtySum = 0;
            $issueAmountSum = 0;
            $balanceQtySum = 0;
            $balanceAmountSum = 0;
        @endphp

        @foreach($summary as $data)
        <tr>
            <td></td>
            <td>{{ $data['buyer_name'] }}</td>
            <td>{{ $data['style_name'] }}</td>
            <td>{{ $data['po_no'] }}</td>
            <td>{{ $data['color_name'] }}</td>
            <td></td>
            <td>{{ $data['receive_date'] }}</td>
            <td>{{ $data['total_receive_qty'] }}</td>
            <td>{{ $data['total_receive_return_qty'] }}</td>
            <td>{{ $data['avg_rate'] }}</td>
            <td>{{ $data['total_receive_amount'] }}</td>
            <td></td>
            <td>{{ $data['total_issue_qty'] }}</td>
            <td>{{ $data['total_issue_return_qty'] }}</td>
            <td>{{ $data['total_issue_amount'] }}</td>
            <td>{{ $data['total_balance_qty'] }}</td>
            <td>{{ $data['total_balance_amount'] }}</td>
            <td>{{ $data['remarks'] }}</td>
            <td></td>
            <td></td>
        </tr>

        @php
            $receiveQtySum += $data['total_receive_qty'];
            $receiveReturnQty += $data['total_receive_return_qty'];
            $rateSum += $data['avg_rate'];
            $valueSum += $data['total_receive_amount'];
            $issueQtySum += $data['total_issue_qty'];
            $issueReturnQtySum += $data['total_issue_return_qty'];
            $issueAmountSum += $data['total_issue_amount'];
            $balanceQtySum += $data['total_balance_qty'];
            $balanceAmountSum += $data['total_balance_amount'];
        @endphp

        @endforeach

        <tr>
            <th colspan="7">Sub Total</th>
            <th>{{$receiveQtySum}}</th>
            <th>{{$receiveReturnQty}}</th>
            <th>{{$rateSum}}</th>
            <th>{{$valueSum}}</th>
            <th></th>
            <th>{{$issueQtySum}}</th>
            <th>{{$issueReturnQtySum}}</th>
            <th>{{$issueAmountSum}}</th>
            <th>{{$balanceQtySum}}</th>
            <th>{{$balanceAmountSum}}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        @php
            $totalReceiveQtySum += $receiveQtySum;
            $totalReceiveReturnQty += $receiveReturnQty;
            $totalRateSum += $rateSum;
            $totalValueSum += $valueSum;
            $totalIssueQtySum += $issueQtySum;
            $totalIssueReturnQtySum += $issueReturnQtySum;
            $totalIssueAmountSum += $issueAmountSum;
            $totalBalanceQtySum += $balanceQtySum;
            $totalBalanceAmountSum += $balanceAmountSum;
        @endphp

    @endforeach

        <tr>
            <th colspan="7">Total</th>
            <th>{{$totalReceiveQtySum}}</th>
            <th>{{$totalReceiveReturnQty}}</th>
            <th>{{$totalRateSum}}</th>
            <th>{{$totalValueSum}}</th>
            <th></th>
            <th>{{$totalIssueQtySum}}</th>
            <th>{{$totalIssueReturnQtySum}}</th>
            <th>{{$totalIssueAmountSum}}</th>
            <th>{{$totalBalanceQtySum}}</th>
            <th>{{$totalBalanceAmountSum}}</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

    </tbody>
</table>
