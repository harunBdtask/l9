<table class="reportTable">
    <thead>
    <tr>
        <th>SI</th>
        <th>Date</th>
        <th>Party Name</th>
        <th>UID</th>
        <th>Entry Basis</th>
        <th>Batch No</th>
        <th>Order No</th>
        <th>Color</th>
        <th>Roll Qty</th>
        <th>Grey Delivery(kg)</th>
        <th>Finish Delivery(Kg)</th>
        <th>Total Value</th>
        <th>Process Loss</th>
        <th>Average Rate</th>
        <th>Remarks</th>
    </tr>
    </thead>
    <tbody>
    @php
        $sumAverageRate = 0;
    @endphp
    @foreach($dyeingGoodsDelivery as $key => $delivery)
        @php
            $rowCount = $dyeingGoodsDelivery->count();
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $delivery['date'] }}</td>
            <td>{{ $delivery['party_name'] }}</td>
            <td>{{ $delivery['delivery_uid'] }}</td>
            <td>{{ $delivery['entry_basis'] }}</td>
            <td>{{ $delivery['batch_no'] }}</td>
            <td>{{ $delivery['order_no'] }}</td>
            <td>{{ $delivery['color'] }}</td>
            <td>{{ $delivery['roll_qty'] }}</td>
            <td>{{ $delivery['grey_delivery'] }}</td>
            <td>{{ $delivery['finish_delivery'] }}</td>
            <td>{{ $delivery['total_value'] }}</td>
            <td>{{ $delivery['process_loss'] }}</td>
            <td>{{ $delivery['rate'] }}</td>
            <td>{{ $delivery['remarks'] }}</td>
        </tr>
        @php
            $sumAverageRate += $delivery['rate'];
        @endphp
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td colspan="13">Average Rate</td>
        <td>{{ $sumAverageRate }}</td>
    </tr>
    </tfoot>
</table>
