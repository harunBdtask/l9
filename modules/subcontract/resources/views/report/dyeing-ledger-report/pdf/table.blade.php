<table class="reportTable">
    <thead>
    <tr>
        <td>Date</td>
        <td>Party Name</td>
        <td>Order No</td>
        <td>Challan No</td>
        <td>Operation</td>
        <td>Fabric Description</td>
        <td>Fabric Type</td>
        <td>Color(Order)</td>
        <td>Fabric Dia</td>
        <td>Dia Type</td>
        <td>Gsm</td>
        <td>Received Qty</td>
        <td>Batch Date</td>
        <td>Batch No</td>
        <td>Fabric Dia</td>
        <td>Dia Type</td>
        <td>GSM</td>
        <td>Color</td>
        <td>Batch Qty</td>
        <td>Grey Stock</td>
        <td>Delivery Date</td>
        <td>Grey Delivery</td>
        <td>Finish Delivery Qty</td>
        <td>Balance</td>
        <td>Rate</td>
        <td>Currency</td>
        <td>Total Value</td>
        <td>Shade(%)</td>
        <td>Remarks</td>
    </tr>
    </thead>
    <tbody>
    @foreach($orders as $key => $order)
        <tr>
            <td>{{ $order['date'] }}</td>
            <td>{{ $order['party_name'] }}</td>
            <td>{{ $order['order_no'] }}</td>
            <td>{{ $order['challan_no'] }}</td>
            <td>{{ $order['operation'] }}</td>
            <td>{{ $order['fabric_description'] }}</td>
            <td>{{ $order['fabric_type'] }}</td>
            <td>{{ $order['color'] }}</td>
            <td>{{ $order['fabric_dia'] }}</td>
            <td>{{ $order['dia_type'] }}</td>
            <td>{{ $order['gsm'] }}</td>
            <td>{{ $order['total_receive_qty'] }}</td>
            <td>{{ $order['batch_date'] }}</td>
            <td>{{ $order['batch_no'] }}</td>
            <td>{{ $order['batch_fabric_dia'] }}</td>
            <td>{{ $order['batch_dia_type'] }}</td>
            <td>{{ $order['batch_gsm'] }}</td>
            <td>{{ $order['batch_color'] }}</td>
            <td>{{ $order['batch_qty'] }}</td>
            <td>{{ $order['grey_stock'] }}</td>
            <td>{{ $order['delivery_date'] }}</td>
            <td>{{ $order['grey_delivery'] }}</td>
            <td>{{ $order['finish_delivery_qty'] }}</td>
            <td>{{ $order['balance'] }}</td>
            <td>{{ $order['rate'] }}</td>
            <td>{{ $order['currency'] }}</td>
            <td>{{ $order['total_value'] }}</td>
            <td>{{ $order['shade'] }}</td>
            <td>{{ $order['remarks'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
