<thead>
<tr>
  <th>Buyer</th>
  <th>Style/Order No</th>
  <th>PO</th>
  <th>Order Qty</th>
  <th>Shipout Qty</th>
  <th>Shipout Balance Qty</th>
  <th>Sewing Balance With 3%</th>
  <th>Inspection Date</th>
  <th>Total Export Value</th>
  <th>Total Shipout Value</th>
  <th>Total Export Value Balance Value</th>
</tr>
</thead>
<tbody>
@if(!empty($buyer_wise_shipment))
  @foreach($buyer_wise_shipment as $report)
    <tr>
      <td>{{ $buyer ?? '' }}</td>
      <td>{{ $report['style'] }}</td>
      <td>{{ $report['order'] }}</td>
      <td>{{ $report['order_qty'] }}</td>
      <td>{{ $report['shipment_qty'] }}</td>
      <td>{{ $report['shipment_balance_qty'] }}</td>
      <td>{{ $report['sewing_balance_qty'] }}</td>
      <td>{{ $report['inspection_date'] }}</td>
      <td>{{ $report['total_export_value'] }}</td>
      <td>{{ $report['total_shipout_value'] }}</td>
      <td>{{ $report['total_export_balance'] }}</td>
    </tr>
  @endforeach
  <tr style="font-weight:bold">
    <td colspan="3"><b>Total</b></td>
    <td>{{ $total_rows['total_color_order_qty'] }} </td>
    <td>{{ $total_rows['total_shipment_qty'] }} </td>
    <td>{{ $total_rows['total_shipment_balance_qty'] }} </td>
    <td>{{ $total_rows['total_sewing_balance_qty'] }} </td>
    <td></td>
    <td>{{ $total_rows['total_total_export_value'] }} </td>
    <td>{{ $total_rows['total_total_shipout_value'] }} </td>
    <td>{{ $total_rows['total_total_export_balance'] }} </td>
  </tr>
@else
  <tr>
    <td colspan="9" style="text-align: center; font-weight: bold;">Not found
    <td>
  </tr>
@endif
</tbody>