<table>
  <thead>
    <tr>
      <td colspan="22">{{ factoryName() }}</td>
    </tr>
    <tr>
      <th colspan="22">
        Buyer: {{ $buyer ?? '' }} &nbsp;&nbsp;&nbsp;
        Style: {{ $style ?? '' }} &nbsp;&nbsp;&nbsp;
      </th>
    </tr>
    <tr>
      <th>PO</th>
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
    @if(!empty($data))
      @php
      $total_data = $data['total_data'];
      $left_qty = 0;
      $total_po_quantity = 0;
      @endphp
      @foreach($data['po_wise_production_report'] as $report)
        @php
          $po_no = $report->purchaseOrder->po_no ? $report->purchaseOrder->po_no : 'PO';
          $po_quantity = $report->purchaseOrder->po_quantity ? $report->purchaseOrder->po_quantity : 0;
          $total_po_quantity += $po_quantity;
          $left_qty = $po_quantity - $report->cutting_qty;
        @endphp
        <tr>
          <td> {{ $po_no }} </td>
          <td> {{ $po_quantity }} </td>
          <td> {{ $report->todays_cutting }} </td>
          <td> {{ $report->cutting_qty }} </td>
          <td> {{ $report->cutting_rejection }} </td>
          <td> {{ $left_qty }} </td>
          <td> {{ $report->todays_print_sent }} </td>
          <td> {{ $report->print_sent }} </td>
          <td> {{ $report->todays_print_received }} </td>
          <td> {{ $report->print_received }} </td>
          <td> {{ $report->print_rejection }} </td>
          <td> {{ $report->todays_embr_sent }} </td>
          <td> {{ $report->embr_sent }} </td>
          <td> {{ $report->todays_embr_received }} </td>
          <td> {{ $report->embr_received }} </td>
          <td> {{ $report->embr_rejection }} </td>
          <td> {{ $report->todays_input }} </td>
          <td> {{ $report->input_qty }} </td>
          <td> {{ $report->todays_sewing_output }} </td>
          <td> {{ $report->sewing_output_qty }} </td>
          <td> {{ $report->sewing_rejection }} </td>
          <td> {{ $report->sewing_balance }} </td>
        </tr>
      @endforeach
      @php
        $total_left_qty = $total_po_quantity - $total_data['total_cutting'];
      @endphp
      <tr style="font-weight:bold">
        <td>Total</td>
        <td> {{ $total_po_quantity }} </td>
        <td> {{ $total_data['todays_cutting'] }} </td>
        <td> {{ $total_data['total_cutting'] }} </td>
        <td> {{ $total_data['total_cutting_rejection'] }} </td>
        <td> {{ $total_left_qty }} </td>
        <td> {{ $total_data['todays_sent'] }} </td>
        <td> {{ $total_data['total_sent'] }} </td>
        <td> {{ $total_data['todays_received'] }} </td>
        <td> {{ $total_data['total_received'] }} </td>
        <td> {{ $total_data['total_print_rejection'] }} </td>
        <td> {{ $total_data['todays_embr_sent'] }} </td>
        <td> {{ $total_data['total_embr_sent'] }} </td>
        <td> {{ $total_data['todays_embr_received'] }} </td>
        <td> {{ $total_data['total_embr_received'] }} </td>
        <td> {{ $total_data['total_embr_rejection'] }} </td>
        <td> {{ $total_data['todays_input'] }} </td>
        <td> {{ $total_data['total_input'] }} </td>
        <td> {{ $total_data['todays_sewing_output'] }} </td>
        <td> {{ $total_data['total_sewing_output'] }} </td>
        <td> {{ $total_data['total_sewing_rejection'] }} </td>
        <td> {{ $total_data['total_sewing_balance'] }} </td>
      </tr>
    @else
      <tr>
        <td colspan="22" style="text-align: center; font-weight: bold;">Not found</td>
      </tr>
    @endif
  </tbody>
</table>