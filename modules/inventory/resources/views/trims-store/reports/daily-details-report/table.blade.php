<table class="reportTable">
    <thead>
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>Country</th>
        <th>Color</th>
        <th>Size</th>
        <th>Item</th>
        <th>Booking Quantity</th>
        <th>Unit Price</th>
        <th>Total Value</th>
        <th>Supplier Name</th>
        <th>PI NO</th>
        <th>PI DATE</th>
        <th>Challan No</th>
        <th>Challan Date</th>
        <th>Challan Order Quantity</th>
        <th>Challan Receive Quantity</th>
        <th>Balance</th>
        <th>Expected IN House Date</th>
        <th>Materials Receive No</th>
        <th>Materials Receive Date</th>
        <th>Expected Inventory Date</th>
        <th>Actual Inventory Date</th>
        <th>Inventory Status</th>
        <th>QC Status</th>
        <th>QC DATE</th>
        <th>LC NO</th>
        <th>LC DATE</th>
        <th>Life Time</th>
        <th>Remarks</th>
    </tr>
    </thead>

    <tbody>
    @foreach($reportData as $data)
        <tr>
            <td>{{ $data['buyer'] }}</td>
            <td>{{ $data['style'] }}</td>
            <td>{{ $data['po_no'] }}</td>
            <td>{{ $data['country'] }}</td>
            <td>{{ $data['color'] }}</td>
            <td>{{ $data['size'] }}</td>
            <td>{{ $data['item'] }}</td>
            <td>{{ $data['booking_qty'] }}</td>
            <td>{{ number_format($data['unit_price'], 2) }}</td>
            <td>{{ number_format($data['total_value'], 2) }}</td>
            <td>{{ $data['supplier_name'] }}</td>
            <td>{{ $data['pi_no'] }}</td>
            <td>{{ $data['pi_date'] }}</td>
            <td>{{ $data['challan_no'] }}</td>
            <td>{{ $data['challan_date'] }}</td>
            <td>{{ $data['challan_order_qty'] }}</td>
            <td>{{ $data['challan_receive_qty'] }}</td>
            <td>{{ $data['balance'] }}</td>
            <td>{{ $data['expected_in_house_date'] }}</td>
            <td>{{ $data['material_receive_no'] }}</td>
            <td>{{ $data['material_receive_date'] }}</td>
            <td>{{ $data['expected_inventory_date'] }}</td>
            <td>{{ $data['actual_inventory_date'] }}</td>
            <td>{{ $data['inventory_status'] }}</td>
            <td>{{ $data['qc_status'] }}</td>
            <td>{{ $data['qc_date'] }}</td>
            <td>{{ $data['lc_no'] }}</td>
            <td>{{ $data['lc_date'] }}</td>
            <td>{{ $data['life_time'] }}</td>
            <td>{{ $data['remarks'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
