<table class="reportTable">
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
        @foreach($orderWiseData as $data)
            @foreach(collect($data['batch_details'])->sortByDesc('total_rows')->values() as $batchDetail)
                @foreach(collect($batchDetail['delivery_details'])->sortByDesc('delivery_count')->values() as $deliverDetail)
                    <tr>
                        <td>{{ $data['date'] }}</td>
                        <td>{{ $data['party_name'] }}</td>
                        <td>{{ $data['order_no'] }}</td>
                        <td>{{ $data['operation'] }}</td>
                        <td>{{ $data['fabric_description'] }}</td>
                        <td>{{ $data['fabric_type'] }}</td>
                        <td>{{ $data['color'] }}</td>
                        <td>{{ $data['fabric_dia'] }}</td>
                        <td>{{ $data['dia_type'] }}</td>
                        <td>{{ $data['gsm'] }}</td>
                        <td>{{ $data['received_qty'] }}</td>
                        <td>{{ $batchDetail['batch_date'] }}</td>
                        <td>{{ $batchDetail['batch_no'] }}</td>
                        <td>{{ $batchDetail['fabric_dia'] }}</td>
                        <td>{{ $batchDetail['dia_type'] }}</td>
                        <td>{{ $batchDetail['gsm'] }}</td>
                        <td>{{ $batchDetail['color'] }}</td>
                        <td>{{ $batchDetail['batch_qty'] }}</td>
                        <td>{{ $data['grey_stock_qty'] }}</td>
                        <td>{{ $deliverDetail['delivery_date'] }}</td>
                        <td>{{ $deliverDetail['grey_delivery'] }}</td>
                        <td>{{ $deliverDetail['finish_delivery_qty'] }}</td>
                        <td>{{ $batchDetail['delivery_balance'] }}</td>
                        <td>{{ $deliverDetail['rate'] }}</td>
                        <td>{{ $deliverDetail['currency'] }}</td>
                        <td></td>
                        <td>{{ $deliverDetail['shade'] }}</td>
                        <td>{{ $deliverDetail['remarks'] }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <td colspan="17">TOTAL</td>
            <td>{{ $orderWiseData->sum('total_batch_qty') }}</td>
            <td colspan="11"></td>
        </tr>
    @endforeach
    </tbody>
</table>
