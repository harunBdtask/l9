<thead>
<tr>
    <th colspan="10">
        Buyer: {{ $buyer ?? '' }} &nbsp;&nbsp;&nbsp;
        Style/Order No: {{ $order_style_no ?? '' }}
    </th>
</tr>
<tr>
    <th>Order</th>
    <th>PO Quantity</th>
    <th>Cutting Production</th>
    <th>Cutting WIP</th>
    <th>Total Send</th>
    <th>Total Recieved</th>
    <th>Fabric Rejection</th>
    <th>Print Rejection</th>
    <th>Total Rejection</th>
    <th>Print WIP/Short</th>
</tr>
</thead>
<tbody>
@include('printembrdroplets::reports.includes.style-wise-print-send-receive-report-table')
</tbody>