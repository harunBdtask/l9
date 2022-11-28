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
    </tr>
    </thead>
    <tbody>
    @foreach($reportData as $key => $order)
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
        </tr>
    @endforeach
    </tbody>
</table>
