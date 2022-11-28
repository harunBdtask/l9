@if($reportData && count($reportData))
  @php
    $grand_order_qty = 0;
    $grand_today_print_sent_qty = 0;
    $grand_total_print_sent_qty = 0;
    $grand_today_print_received_qty = 0;
    $grand_total_print_received_qty = 0;
    $grand_total_print_balance_qty = 0;
    $grand_today_embr_sent_qty = 0;
    $grand_total_embr_sent_qty = 0;
    $grand_today_embr_received_qty = 0;
    $grand_total_embr_received_qty = 0;
    $grand_total_embr_balance_qty = 0;
  @endphp
  @foreach ($reportData->groupBy('buyer_id') as $reportByBuyer)
    @php
      $buyer = optional($reportByBuyer->first()['buyer'])->name;
      $sub_order_qty = 0;
      $sub_today_print_sent_qty = 0;
      $sub_total_print_sent_qty = 0;
      $sub_today_print_received_qty = 0;
      $sub_total_print_received_qty = 0;
      $sub_total_print_balance_qty = 0;
      $sub_today_embr_sent_qty = 0;
      $sub_total_embr_sent_qty = 0;
      $sub_today_embr_received_qty = 0;
      $sub_total_embr_received_qty = 0;
      $sub_total_embr_balance_qty = 0;
    @endphp
    @foreach ($reportByBuyer as $report)
      @php
        $sub_order_qty += $report['order_qty'];
        $sub_today_print_sent_qty += $report['print_sent_qty'];
        $sub_total_print_sent_qty += $report['total_print_sent_qty'];
        $sub_today_print_received_qty += $report['print_received_qty'];
        $sub_total_print_received_qty += $report['total_print_received_qty'];
        $sub_total_print_balance_qty += $report['print_blance'];
        $sub_today_embr_sent_qty += $report['embroidery_sent_qty'];
        $sub_total_embr_sent_qty += $report['total_embroidery_sent_qty'];
        $sub_today_embr_received_qty += $report['embroidery_received_qty'];
        $sub_total_embr_received_qty += $report['total_embroidery_received_qty'];
        $sub_total_embr_balance_qty += $report['embr_balance'];
        
        $grand_order_qty += $report['order_qty'];
        $grand_today_print_sent_qty += $report['print_sent_qty'];
        $grand_total_print_sent_qty += $report['total_print_sent_qty'];
        $grand_today_print_received_qty += $report['print_received_qty'];
        $grand_total_print_received_qty += $report['total_print_received_qty'];
        $grand_total_print_balance_qty += $report['print_blance'];
        $grand_today_embr_sent_qty += $report['embroidery_sent_qty'];
        $grand_total_embr_sent_qty += $report['total_embroidery_sent_qty'];
        $grand_today_embr_received_qty += $report['embroidery_received_qty'];
        $grand_total_embr_received_qty += $report['total_embroidery_received_qty'];
        $grand_total_embr_balance_qty += $report['embr_balance'];
      @endphp
      <tr>
        <td>{{ optional($report['buyer'])->name }}</td>
        <td>{{ optional($report['garmentsItem'])->name }}</td>
        <td>{{ optional($report['order'])->reference_no }}</td>
        <td>{{ optional($report['order'])->style_name }}</td>
        <td>{{ optional($report['color'])->name }}</td>
        <td>{{ $report['order_qty'] }}</td>
        <td>{{ $report['print_sent_qty'] }}</td>
        <td>{{ $report['prev_print_sent_qty'] }}</td>
        <td>{{ $report['total_print_sent_qty'] }}</td>
        <td>{{ $report['print_received_qty'] }}</td>
        <td>{{ $report['prev_print_received_qty'] }}</td>
        <td>{{ $report['total_print_received_qty'] }}</td>
        <td>{{ $report['print_blance'] }}</td>
        <td>{{ $report['embroidery_sent_qty'] }}</td>
        <td>{{ $report['prev_embroidery_sent_qty'] }}</td>
        <td>{{ $report['total_embroidery_sent_qty'] }}</td>
        <td>{{ $report['embroidery_received_qty'] }}</td>
        <td>{{ $report['prev_embroidery_received_qty'] }}</td>
        <td>{{ $report['total_embroidery_received_qty'] }}</td>
        <td>{{ $report['embr_balance'] }}</td>
        <td>&nbsp;</td>
      </tr>
    @endforeach
    <tr class="yellow-200">
      <th colspan="5">Subtotal = {{ $buyer }}</th>
      <th>{{ $sub_order_qty }}</th>
      <th>{{ $sub_today_print_sent_qty }}</th>
      <th>&nbsp;</th>
      <th>{{ $sub_total_print_sent_qty }}</th>
      <th>{{ $sub_today_print_received_qty }}</th>
      <th>&nbsp;</th>
      <th>{{ $sub_total_print_received_qty }}</th>
      <th>{{ $sub_total_print_balance_qty }}</th>
      <th>{{ $sub_today_embr_sent_qty }}</th>
      <th>&nbsp;</th>
      <th>{{ $sub_total_embr_sent_qty }}</th>
      <th>{{ $sub_today_embr_received_qty }}</th>
      <th>&nbsp;</th>
      <th>{{ $sub_total_embr_received_qty }}</th>
      <th>{{ $sub_total_embr_balance_qty }}</th>
      <th>&nbsp;</th>
    </tr>
  @endforeach
  <tr class="green-200">
    <th colspan="5">Grand Total</th>
    <th>{{ $grand_order_qty }}</th>
    <th>{{ $grand_today_print_sent_qty }}</th>
    <th>&nbsp;</th>
    <th>{{ $grand_total_print_sent_qty }}</th>
    <th>{{ $grand_today_print_received_qty }}</th>
    <th>&nbsp;</th>
    <th>{{ $grand_total_print_received_qty }}</th>
    <th>{{ $grand_total_print_balance_qty }}</th>
    <th>{{ $grand_today_embr_sent_qty }}</th>
    <th>&nbsp;</th>
    <th>{{ $grand_total_embr_sent_qty }}</th>
    <th>{{ $grand_today_embr_received_qty }}</th>
    <th>&nbsp;</th>
    <th>{{ $grand_total_embr_received_qty }}</th>
    <th>{{ $grand_total_embr_balance_qty }}</th>
    <th>&nbsp;</th>
  </tr>
@else
  <tr>
    <th colspan="21">No Data Found</th>
  </tr>
@endif