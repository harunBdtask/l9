<thead>
  <tr>
    <th>Buyer</th>
    <th>Order/Style</th>
    <th>PO</th>
    <th>Ex-Factory Date</th>
    <th>PO Qty</th>
    <th>Color</th>
    <th>Today Input</th>
    <th>Ttl. Input</th>
    <th>Input &#37;</th>
    <th>Balance</th>
  </tr>
</thead>
<tbody>
  @if($reports && count($reports))
    @php
      $gtOrderQty = 0;
      $gtTodaySewingInputQty = 0;
      $gtSewingInputQty = 0;
    @endphp
    @foreach ($reports as $report)
      @php
        $exFactoryDate = $report['purchaseOrder']->ex_factory_date ?? null;
        $poQty = $report['order_qty'];
        $gtOrderQty += $poQty;
        $gtTodaySewingInputQty += $report['today_sewing_input'];
        $gtSewingInputQty += $report['total_input'];
      @endphp
      <tr>
        <td>{{ $report['buyer']->name }}</td>
        <td>{{ $report['order']->style_name }}</td>
        <td>{{ $report['purchaseOrder']->po_no }}</td>
        <td>{{ $exFactoryDate ? date('d-m-Y', strtotime($exFactoryDate)): '' }}</td>
        <td>{{ $poQty }}</td>
        <td>{{ $report['color']->name }}</td>
        <td>{{ $report['today_sewing_input'] }}</td>
        <td>{{ $report['total_input'] }}</td>
        <td>{{ $report['input_percentage'] }}</td>
        <td>{{ $report['balance'] }}</td>
      </tr>
    @endforeach
    <tr>
      <th colspan="4">Grand Total</th>
      <th>{{ $gtOrderQty }}</th>
      <th>&nbsp;</th>
      <th>{{ $gtTodaySewingInputQty }}</th>
      <th>{{ $gtSewingInputQty }}</th>
      <th colspan="2">&nbsp;</th>
    </tr>
  @endif
</tbody>