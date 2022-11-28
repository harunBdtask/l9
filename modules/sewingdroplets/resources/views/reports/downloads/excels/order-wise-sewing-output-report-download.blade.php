<table>
  <thead>
  <tr>
    <td colspan="12">{{ factoryName() }}</td>
  </tr>
  <tr>
    <th colspan="12">
      Buyer: {{$buyer}} &nbsp; &nbsp;
      Order/Style: {{$style}} &nbsp; &nbsp;
      PO: {{$order_no}}
    </th>
  </tr>
  <tr>
    <th>Colour Name</th>
    <th>Size</th>
    <th>Order Quantity</th>
    <th>Cutting Production</th>
    <th>WIP In Cutting/Print/Embr.</th>
    <th>Today's Input to Line</th>
    <th>Total Input to Line</th>
    <th>Today's Output</th>
    <th>Total Sewing Output</th>
    <th>Total Rejection</th>
    <th>In_line WIP</th>
    <th>Cut 2 Sewing Ratio(%)</th>
  </tr>
  </thead>
  <tbody>
  @if(!empty($report_size_wise))

    @foreach($report_size_wise as $report)
      <tr>
        <td>{{ $report['color'] }}</td>
        <td>{{ $report['size'] }}</td>
        <td>{{ $report['size_order_qty'] }}</td>
        <td>{{ $report['size_cutting_qty'] }}</td>
        <td>{{ $report['wip'] }}</td>
        <td>{{ $report['today_input'] }}</td>
        <td>{{ $report['total_input'] }}</td>
        <td>{{ $report['today_output'] }}</td>
        <td>{{ $report['total_output'] }}</td>
        <td>{{ $report['rejection'] }}</td>
        <td>{{ $report['in_line_wip'] }}</td>
        <td>{{ $report['cutt_sewing_ratio'] }}%</td>
      </tr>
    @endforeach
    <tr style="font-weight:bold">
      <td colspan="2">Total</td>
      <td>{{ $total_report['total_order_qty'] }}</td>
      <td>{{ $total_report['total_cutting_qty'] }}</td>
      <td>{{ $total_report['total_wip'] }}</td>
      <td>{{ $total_report['total_today_input'] }}</td>
      <td>{{ $total_report['total_total_input'] }}</td>
      <td>{{ $total_report['total_today_output'] }}</td>
      <td>{{ $total_report['total_total_output'] }}</td>
      <td>{{ $total_report['total_rejection'] }}</td>
      <td>{{ $total_report['total_in_line_wip'] }}</td>
      <td></td>
    </tr>
  @else
    <tr>
      <td colspan="12" style="text-align: center;">Not found
      <td>
    </tr>
  @endif
  </tbody>
</table>