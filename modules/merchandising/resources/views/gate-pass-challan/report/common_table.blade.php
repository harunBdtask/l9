<table>
    <tr style="background-color: #d9d9bf; font-weight: bold; font-size: 18px;">
        <td colspan="10" class="text-center">CHALLAN FOR: {{ $goods[$data['good_id']] }}</td>
    </tr>
    <tr>
        <th>Challan No</th>
        <th>Challan Date</th>
        <th>Vehicle No</th>
        <th>Driver Name</th>
        <th>Lock No</th>
        <th>Bag Quantity</th>
        <th>Returnable</th>
        <th>Sender</th>
        <th>Department</th>
        <th>Contact</th>
    </tr>
    <tr>
        <td>{{ $data['challan_no'] }}</td>
        <td>{{ $data['challan_date'] }}</td>
        <td>{{ $data['vehicle_no'] }}</td>
        <td>{{ $data['driver_name'] }}</td>
        <td>{{ $data['lock_no'] }}</td>
        <td>{{ $data['bag_quantity'] }}</td>
        <td>{{ $data['returnable'] == 1 ? 'Yes' : 'No' }}</td>
        <td>{{ $data['merchant']['screen_name'] ?? '' }}</td>
        <td>{{ $data['department']['product_department'] ?? '' }}</td>
        <td>{{ $data['merchant']['phone_no'] ?? '' }}</td>
    </tr>
</table>
