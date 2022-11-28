<table class="reportTable">
    <tbody>
        <tr>
            <td style="height: 70px; text-align: center; font-size: 20px;" colspan="29">Order Wise Stock Report</td>
        </tr>
        <tr>
            <td style="height: 70px; text-align: center; font-size: 15px;" colspan="29">{{ factoryName() }}</td>
        </tr>
    </tbody>
    <thead>
    <tr>
        <th>Date</th>
        <th>Party Name</th>
        <th>Order No</th>
        <th>Operation</th>
        <th>Fabric Description</th>
        <th>Fabric Type</th>
        <th>Color(Order)</th>
        <th>Fabric Dia</th>
        <th>Dia Type</th>
        <th>Gsm</th>
        <th>Received Qty</th>
        <th>Batch Date</th>
        <th>Batch No</th>
        <th>Fabric Dia</th>
        <th>Dia Type</th>
        <th>GSM</th>
        <th>Color</th>
        <th>Batch Qty</th>
        <th>Grey Stock</th>
        <th>Delivery Date</th>
        <th>Grey Delivery</th>
        <th>Finish Delivery Qty</th>
        <th>Balance</th>
        <th>Rate</th>
        <th>Currency</th>
        <th>Total Value</th>
        <th>Shade(%)</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportData->groupBy(['order_no']) as $orderWiseData)
        @php
            $orderLoopCount = 0;
            $rowspan = $orderWiseData->sum('total_rows');
        @endphp
        @foreach($orderWiseData as $data)
            @php
                $batchLoopCount = 0;
                $batchRowSpan = $data['total_rows'];
            @endphp
            @foreach(collect($data['batch_details'])->sortByDesc('total_rows')->values() as $batchDetail)
                @php
                    $deliveryLoopCount = 0;
                    $deliveryRowSpan = $batchDetail['delivery_count'];
                @endphp
                @foreach(collect($batchDetail['delivery_details'])->sortByDesc('delivery_count')->values() as $deliverDetail)
                    <tr>
                        @if($orderLoopCount == 0)
                            <td rowspan="{{ $rowspan }}">{{ $data['date'] }}</td>
                            <td rowspan="{{ $rowspan }}">{{ $data['party_name'] }}</td>
                        @endif
                        @if($orderLoopCount == 0)
                            <td rowspan="{{ $rowspan }}">{{ $data['order_no'] }}</td>
                        @endif
                        @if($batchLoopCount == 0)
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['operation'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['fabric_description'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['fabric_type'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['color'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['fabric_dia'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['dia_type'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['gsm'] }}</td>
                            <td rowspan="{{ $batchRowSpan }}">{{ $data['received_qty'] }}</td>
                        @endif
                        @if($orderLoopCount == 0)
                            <td rowspan="{{ $rowspan }}">{{ $orderWiseData->sum('received_qty') }}</td>
                        @endif

                        @if($deliveryLoopCount == 0)
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['batch_date'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['batch_no'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['fabric_dia'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['dia_type'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['gsm'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['color'] }}</td>
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['batch_qty'] }}</td>
                        @endif
                        <td>{{ $data['grey_stock_qty'] }}</td>
                        <td>{{ $deliverDetail['delivery_date'] }}</td>
                        <td>{{ $deliverDetail['grey_delivery'] }}</td>
                        <td>{{ $deliverDetail['finish_delivery_qty'] }}</td>
                        @if($deliveryLoopCount == 0)
                            <td rowspan="{{ $deliveryRowSpan }}">{{ $batchDetail['delivery_balance'] }}</td>
                        @endif
                        <td>{{ $deliverDetail['rate'] }}</td>
                        <td>{{ $deliverDetail['currency'] }}</td>
                        <td></td>
                        <td>{{ $deliverDetail['shade'] }}</td>
                        <td>{{ $deliverDetail['remarks'] }}</td>
                    </tr>
                    @php
                        $batchLoopCount++;
                        $orderLoopCount++;
                        $deliveryLoopCount++;
                    @endphp
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td colspan="18">TOTAL</td>
            <td>{{ $orderWiseData->sum('total_batch_qty') }}</td>
            <td colspan="10"></td>
        </tr>
    @endforeach
    </tbody>
</table>
