<table class="reportTable table-responsive">
    <thead>
    <tr>
        <th rowspan="2">Sl No</th>
        <th rowspan="2">Buyer</th>
        <th rowspan="2">Style NO</th>
        <th rowspan="2">Po NO</th>
        <th rowspan="2">Item</th>
        <th rowspan="2">Material Description</th>
        <th rowspan="2">Supplier</th>
        <th rowspan="2">Order Qty</th>
        <th rowspan="2">Booking Qty</th>
        <th rowspan="2">Rate</th>
        <th rowspan="2">Value</th>
        <th rowspan="2">Pl No</th>
        <th rowspan="2">L/C No</th>
        <th rowspan="2">L/C Date</th>
        <th rowspan="2">Recv. Challan NO</th>
        <th rowspan="2">First Recv. Challan Date</th>
        <th rowspan="2">Last Recv. Challan Date</th>
        <th rowspan="1" colspan="4">In House Date</th>
        <th rowspan="1" colspan="2">In House State</th>
        <th rowspan="2">MRR No</th>
        <th rowspan="2">MRR Date</th>
        <th rowspan="2">Issue Challan No</th>
        <th rowspan="2">Issue Challan Date</th>
        <th rowspan="2">Storage Location</th>
        <th rowspan="2">Name of Unit</th>
        <th rowspan="1" colspan="3">Opening Balance</th>
        <th rowspan="1" colspan="4">Receive During In Period</th>
        <th rowspan="1" colspan="3">Available For Us</th>
        <th rowspan="1" colspan="4">Issue During The Period</th>
        <th rowspan="1" colspan="3">Closing Balance</th>
    </tr>

    <tr>
        <th>Life End Date</th>
        <th>Duration Of Life</th>
        <th>Not Earlier</th>
        <th>Not Later</th>
        <th>On Time</th>
        <th>Delayed</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Value</th>
        <th>Day Receive Qty</th>
        <th>Total Receive Qty</th>
        <th>Rate</th>
        <th>Value</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Value</th>
        <th>Day Issue Qty</th>
        <th>Total Issue Qty</th>
        <th>Rate</th>
        <th>Value</th>
        <th>Qty</th>
        <th>Rate</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reportData as $data)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>{{ $data['buyer'] }}</td>
        <td>{{ $data['style_name'] }}</td>
        <td>{{ $data['po_no'] }}</td>
        <td>{{ $data['item_name'] }}</td>
        <td>{{ $data['item_description'] }}</td>
        <td>{{ $data['supplier_name'] }}</td>
        <td>{{ $data['order_qty'] }}</td>
        <td>{{ $data['booking_qty'] }}</td>
        <td>{{ $data['rate'] }}</td>
        <td>{{ $data['booking_amount'] }}</td>
        <td>{{ $data['pl_no'] }}</td>
        <td>{{ $data['lc_no'] }}</td>
        <td>{{ $data['lc_date'] }}</td>
        <td>{{ $data['receive_challan_no'] }}</td>
        <td>{{ $data['first_receive_challan_date'] }}</td>
        <td>{{ $data['last_receive_challan_date'] }}</td>
        <td>{{ $data['life_end_date'] }}</td>
        <td>{{ $data['duration_of_life'] }}</td>
        <td>{{ $data['not_earlier'] }}</td>
        <td>{{ $data['not_later'] }}</td>
        <td>{{ $data['on_time'] }}</td>
        <td>{{ $data['delayed'] }}</td>
        <td>{{ $data['mrr_no'] }}</td>
        <td>{{ $data['mrr_date'] }}</td>
        <td>{{ $data['issue_challan_no'] }}</td>
        <td>{{ $data['issue_challan_date'] }}</td>
        <td>{{ $data['storage_location'] }}</td>
        <td>{{ $data['name_of_unit'] }}</td>
        <td>{{ $data['opening_qty'] }}</td>
        <td>{{ $data['opening_rate'] }}</td>
        <td>{{ $data['opening_amount'] }}</td>
        <td>{{ $data['day_receive_qty'] }}</td>
        <td>{{ $data['total_receive_qty'] }}</td>
        <td>{{ $data['receive_rate'] }}</td>
        <td>{{ $data['receive_amount'] }}</td>
        <td>{{ $data['available_qty'] }}</td>
        <td>{{ $data['available_rate'] }}</td>
        <td>{{ $data['available_amount'] }}</td>
        <td>{{ $data['day_issue_qty'] }}</td>
        <td>{{ $data['total_issue_qty'] }}</td>
        <td>{{ $data['issue_rate'] }}</td>
        <td>{{ $data['issue_amount'] }}</td>
        <td>{{ $data['closing_qty'] }}</td>
        <td>{{ $data['closing_rate'] }}</td>
        <td>{{ $data['closing_amount'] }}</td>
    </tr>
    @endforeach
    </tbody>
</table>
