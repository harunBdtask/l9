<thead>
  <tr>
    <th>SL</th>
    <th>Floor</th>
    <th>Table</th>
    <th>Merchandiser</th>
    <th>Buyer</th>
    <th>Style</th>
    <th>Item</th>
    <th>Item Group</th>
    <th>PO</th>
    <th>Color</th>
    <th>Combo</th>
    <th>Country</th>
    <th>Order Qty</th>
    <th>ECQ Qty</th>
    <th>Daily Cutting</th>
    <th>Total Cutting</th>
  </tr>
</thead>
<tbody>
  @if($reports && count($reports))
    @php
      $sl = 0;
      $gt_daily_cutting_qty = 0;
      $gt_total_cutting_qty = 0;
      $total_style_count = collect($reports)->groupBy('order_id')->count();
      $total_po_count = collect($reports)->groupBy('purchase_order_id')->count();
    @endphp
    @foreach (collect($reports)->sortBy('cutting_table_id')->groupBy('cutting_floor_id') as $cutting_floor_id => $reportByFloor)
      @php
        $old_table_id = '';
        $new_table_id = '';
        $floor_daily_cutting_qty = 0;
        $floor_total_cutting_qty = 0;
        $floor = $reportByFloor->first()['floor'];
      @endphp
      @foreach (collect($reportByFloor)->groupBy('cutting_table_id') as $cutting_table_id => $reportByTable)
        @php
          $rowspan = $reportByTable->count() + collect($reportByTable)->groupBy('order_id')->count();
          $new_table_id = $cutting_table_id;
          $table = $reportByTable->first()['table'];
        @endphp
        @foreach (collect($reportByTable)->groupBy('order_id') as $order_id => $reportByOrder)
          @php
            $sub_daily_cutting_qty = 0;
            $sub_total_cutting_qty = 0;
            $style = $reportByOrder->first()['style_name'];
          @endphp
          @foreach ($reportByOrder as $report)
            @php
              $daily_cutting_qty = $report['today_cutting_qty'];
              $total_cutting_qty = $report['total_cutting_qty'];
            
              $sub_daily_cutting_qty += $daily_cutting_qty;
              $sub_total_cutting_qty += $total_cutting_qty;
            
              $floor_daily_cutting_qty += $daily_cutting_qty;
              $floor_total_cutting_qty += $total_cutting_qty;
            
              $gt_daily_cutting_qty += $daily_cutting_qty;
              $gt_total_cutting_qty += $total_cutting_qty;
            @endphp
            <tr>
              <td>{{ ++$sl }}</td>
              @if($old_table_id != $new_table_id)
              <td rowspan="{{ $rowspan }}">{{ $report['floor'] }}</td>
              <td rowspan="{{ $rowspan }}">{{ $report['table'] }}</td>
              @endif
              <td>{{ $report['merchandiser'] }}</td>
              <td>{{ $report['buyer_name'] }}</td>
              <td>{{ $report['style_name'] }}</td>
              <td>{{ $report['item'] }}</td>
              <td>{{ $report['item_group'] }}</td>
              <td>{{ $report['po_no'] }}</td>
              <td>{{ $report['color_name'] }}</td>
              <td>{{ $report['combo'] }}</td>
              <td>{{ $report['country'] }}</td>
              <td>{{ $report['order_qty'] }}</td>
              @if($old_table_id != $new_table_id)
                <td rowspan="{{ $rowspan }}">{{ $report['ecq_qty'] }}</td>
              @endif
              <td>{{ $report['today_cutting_qty'] }}</td>
              <td>{{ $report['total_cutting_qty'] }}</td>
            </tr>
            @php
              $old_table_id = $new_table_id;
            @endphp
          @endforeach
          <tr class="yellow-100">
            <th>&nbsp;</th>
            <th colspan="10">Subtotal = {{ $style }}</th>
            <th>{{ $sub_daily_cutting_qty }}</th>
            <th>{{ $sub_total_cutting_qty }}</th>
          </tr>
        @endforeach
      @endforeach
      <tr class="orange-100">
        <th colspan="14">Floor Subtotal = {{ $floor }}</th>
        <th>{{ $floor_daily_cutting_qty }}</th>
        <th>{{ $floor_total_cutting_qty }}</th>
      </tr>
    @endforeach
    <tr class="green-200">
      <th colspan="8">Grand Total</th>
      <th colspan="3">Total Style = {{ $total_style_count }}</th>
      <th colspan="3">Total PO = {{ $total_po_count }}</th>
      <th>{{ $gt_daily_cutting_qty }}</th>
      <th>{{ $gt_total_cutting_qty }}</th>
    </tr>
  @else
    <tr>
      <th colspan="16">No Data Found</th>
    </tr>
  @endif
</tbody>