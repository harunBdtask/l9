<table>
  <thead>
    <tr>
      <td colspan="22">{{ factoryName() }}</td>
    </tr>
    <tr>
      <th colspan="22">
        Buyer: {{$buyer}} &nbsp; &nbsp;
        Style: {{$style}} &nbsp; &nbsp;
        PO: {{$po_no}} &nbsp; &nbsp;
        Color: {{ $color }}
      </th>
    </tr>
    <tr>
      <th>Size</th>
      <th>OQ</th>
      <th>Tdy Cutt.</th>
      <th>T. Cutt.</th>
      <th>Cutt Rej.</th>
      <th>Left Qty</th>
      <th>Tdy Print Sent</th>
      <th>T. Print Sent</th>
      <th>Tdy Print Recv.</th>
      <th>T. Print Recv.</th>
      <th>Print Rej.</th>
      <th>Tdy Embr Sent</th>
      <th>T. Embr Sent</th>
      <th>Tdy Embr Recv.</th>
      <th>T. Embr Recv.</th>
      <th>Embr Rej.</th>
      <th>Tdy Input</th>
      <th>T. Input</th>
      <th>Tdy Output</th>
      <th>T. Output</th>
      <th>T. Sewing Rej.</th>
      <th>Sewing Balance</th>
    </tr>
  </thead>
  <tbody>
    @if(!empty($size_wize_input_data))
    @foreach($size_wize_input_data as $report)
    <tr>
      <td> {{ $report['size_name'] }} </td>
      <td> {{ $report['size_wise_order_qty'] }} </td>
      <td> {{ $report['todays_cutting_qty'] }} </td>
      <td> {{ $report['cutting_qty'] }} </td>
      <td> {{ $report['cutting_rejection'] }} </td>
      <td> {{ $report['left_qty'] }} </td>
      <td> {{ $report['todays_print_sent_qty'] }} </td>
      <td> {{ $report['print_sent_qty'] }} </td>
      <td> {{ $report['todays_print_received_qty'] }} </td>
      <td> {{ $report['print_received_qty'] }} </td>
      <td> {{ $report['print_rejection_qty'] }} </td>
      <td> {{ $report['todays_embr_sent_qty'] }} </td>
      <td> {{ $report['embr_sent_qty'] }} </td>
      <td> {{ $report['todays_embr_received'] }} </td>
      <td> {{ $report['embr_received_qty'] }} </td>
      <td> {{ $report['embr_rejection_qty'] }} </td>
      <td> {{ $report['todays_input_qty'] }} </td>
      <td> {{ $report['input_qty'] }} </td>
      <td> {{ $report['todays_output_qty'] }} </td>
      <td> {{ $report['output_qty'] }} </td>
      <td> {{ $report['sewing_rejection_qty'] }} </td>
      <td> {{ $report['sewing_balance'] }} </td>
    </tr>
    @endforeach
    @if(!empty($size_wise_total_input_data))
    <tr style="font-weight:bold">
      <td>Total</td>
      <td> {{ $size_wise_total_input_data['total_size_wise_order_qty'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_cutting'] }} </td>
      <td> {{ $size_wise_total_input_data['total_cutting'] }} </td>
      <td> {{ $size_wise_total_input_data['total_cutting_rejection'] }} </td>
      <td> {{ $size_wise_total_input_data['total_left_qty'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_print_sent_qty'] }} </td>
      <td> {{ $size_wise_total_input_data['total_print_sent_qty'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_received'] }} </td>
      <td> {{ $size_wise_total_input_data['total_print_received_qty'] }} </td>
      <td> {{ $size_wise_total_input_data['total_print_rejection'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_embr_received'] }} </td>
      <td> {{ $size_wise_total_input_data['total_embr_sent'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_embr_received'] }} </td>
      <td> {{ $size_wise_total_input_data['total_embr_rejection'] }} </td>
      <td> {{ $size_wise_total_input_data['total_embr_rejection'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_input'] }} </td>
      <td> {{ $size_wise_total_input_data['total_input'] }} </td>
      <td> {{ $size_wise_total_input_data['total_todays_sewing_output'] }} </td>
      <td> {{ $size_wise_total_input_data['total_sewing_output'] }} </td>
      <td> {{ $size_wise_total_input_data['total_sewing_rejection'] }} </td>
      <td> {{ $size_wise_total_input_data['total_sewing_balance'] }} </td>
    </tr>
    @endif
    @else
    <tr>
      <td colspan="22" style="text-align: center;">Not found</td>
    </tr>
    @endif
  </tbody>
</table>