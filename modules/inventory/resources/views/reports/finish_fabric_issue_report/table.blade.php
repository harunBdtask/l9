
    <table class="reportTable">
        <thead>
        <tr>
            <th style="background-color: aliceblue;">SL No</th>
            <th style="background-color: aliceblue;">Delivery Unique ID</th>
            <th style="background-color: aliceblue;">DLV Challan No</th>
            <th style="background-color: aliceblue;">DLV Date</th>
            <th style="background-color: aliceblue;">Party / Supplier Name</th>
            <th style="background-color: aliceblue;">Store Name</th>
            <th style="background-color: aliceblue;">Buyer</th>
            <th style="background-color: aliceblue;">Style/Order No</th>
            <th style="background-color: aliceblue;">PO NO</th>
            <th style="background-color: aliceblue;">Batch No</th>
            <th style="background-color: aliceblue;">F/Type</th>
            <th style="background-color: aliceblue;">Color</th>
            <th style="background-color: aliceblue;">DIA</th>
            <th style="background-color: aliceblue;">GSM</th>
            <th style="background-color: aliceblue;">No Of Roll</th>
            <th style="background-color: aliceblue;">DLV FIN QTY(KG)</th>
            <th style="background-color: aliceblue;">Rate</th>
            <th style="background-color: aliceblue;">Amount</th>
            <th style="background-color: aliceblue;">Remarks</th>
        </tr>
        </thead>
        <tbody>

        @php
            $totalDeliveryFinishQTY = 0;
            $totalRate = 0;
            $totalAmount = 0;
            $iterate = 1;
        @endphp

        @foreach($fabricIssues as $issue)
            @php
                $deliveryFinishQtySum = 0;
                $rateSum = 0;
                $amountSum = 0;
            @endphp

            @foreach($issue as $value)

                <tr>
                    <td>{{ $iterate++}}</td>
                    <td>{{ $value['delivery_unique_id'] }}</td>
                    <td>{{ $value['challan_no'] }}</td>
                    <td>{{ $value['issue_date'] }}</td>
                    <td>{{ $value['supplier_name'] }}</td>
                    <td>{{ $value['store_name'] }}</td>
                    <td>{{ $value['buyer_name'] }}</td>
                    <td>{{ $value['style_no'] }}</td>
                    <td style="word-break:break-word; width: 15%">{{ $value['po_no'] }}</td>
                    <td>{{ $value['batch_no'] }}</td>
                    <td>{{ $value['feb_type'] }}</td>
                    <td>{{ $value['color'] }}</td>
                    <td>{{ $value['dia'] }}</td>
                    <td>{{ $value['gsm'] }}</td>
                    <td>{{ $value['no_of_roll'] }}</td>
                    <td>{{ $value['dlv_fin_qty'] }}</td>
                    <td>{{ $value['rate'] }}</td>
                    <td>{{ $value['amount'] }}</td>
                    <td>{{ $value['remarks'] }}</td>
                </tr>

                @php
                    $deliveryFinishQtySum += $value['dlv_fin_qty'];
                    $rateSum += $value['rate'];
                    $amountSum += $value['amount'];
                @endphp

            @endforeach

            <tr>
                <th colspan="15">Sub Total</th>
                <th>{{$deliveryFinishQtySum}}</th>
                <th>{{$rateSum}}</th>
                <th>{{$amountSum}}</th>
                <th></th>
            </tr>

            @php
                $totalDeliveryFinishQTY += $deliveryFinishQtySum;
                $totalRate += $rateSum;
                $totalAmount += $amountSum;
            @endphp

        @endforeach

            <tr>
                <th colspan="15">Total</th>
                <th>{{$totalDeliveryFinishQTY}}</th>
                <th>{{$totalRate}}</th>
                <th>{{$totalAmount}}</th>
                <th></th>
            </tr>

        </tbody>
    </table>
