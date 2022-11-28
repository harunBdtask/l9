<thead>
  <tr>
    <th>WH</th>
    <th>Floor</th>
    <th>Line</th>
    <th>Buyer</th>
    <th>Item</th>
    <th>Style</th>
    <th>SMV</th>
    <th>Order Qty</th>
    <th>Input Date</th>
    <th>No of days output running</th>
    <th>Today Input</th>
    <th>Total Input</th>
    <th>Total Production</th>
    <th>Total Line Balance</th>
    <th>WIP</th>
    <th>Yesterday Forecast</th>
    <th>Yesterday Prod</th>
    <th>Yesterday Eff. &#37;</th>
    <th>Forecast Tgt</th>
  </tr>
</thead>
<tbody>
  @if($sewing_outputs && count($sewing_outputs))
    @foreach ($sewing_outputs as $floor => $floor_output)
      @foreach ($floor_output as $line => $line_output)
        @if(!array_key_exists('today_data', $line_output))
          @continue
        @endif
        @php
          $today_data = $line_output['today_data'];
          $total_order_qty = collect($line_output['today_data'])->sum('order_qty') ?? 0;
          $total_input_qty = collect($line_output['today_data'])->sum('total_input') ?? 0;
          $total_output_qty = collect($line_output['today_data'])->sum('total_output') ?? 0;
          $yesterday_forecast = $line_output['day_target'] ?? 0;
          $yesterday_prod = $line_output['total_output'] ?? 0;
          $yesterday_eff = $line_output['line_efficiency'] ? round($line_output['line_efficiency'], 2) : 0;
          $forecast_target = $line_output['today_target'] ?? 0;
          $line_balance = $total_order_qty - $total_output_qty - $forecast_target;
          $wip = $total_input_qty - $total_output_qty;
          $rowspan = count($today_data) <= 0 ? 1 : count($today_data);
        @endphp
        <tr>
          <td rowspan="{{ $rowspan }}">{{ $line_output['today_wh'] }}</td>
          <td rowspan="{{ $rowspan }}">{{ $floor }}</td>
          <td rowspan="{{ $rowspan }}">{{ $line }}</td>
          @if($today_data && count($today_data) > 0)
            @foreach ($today_data as $key => $data)
              @php
                $buyer = array_key_exists('buyer', $data) ? $data['buyer']->name : '';
                $item = array_key_exists('garmentsItem', $data) ? $data['garmentsItem']->name : '';
                $style = array_key_exists('order', $data) ? $data['order']->style_name : '';
                $order_qty = array_key_exists('order_qty', $data) ? $data['order_qty'] : 0;
                $smv = array_key_exists('smv', $data) ? $data['smv'] : '';
                $input_date = array_key_exists('first_input_date', $data) ? $data['first_input_date'] : null;
                $no_days_output = array_key_exists('no_days_output', $data) ? $data['no_days_output'] : null;
                $today_input = array_key_exists('today_input', $data) ? $data['today_input'] : null;
              @endphp
              @if($key > 0)
              <tr>
              @endif
              <td>{{ $buyer }}</td>
              <td>{{ $item }}</td>
              <td>{{ $style }}</td>
              <td>{{ $smv }}</td>
              <td>{{ $order_qty }}</td>
              <td>{{ $input_date }}</td>
              <td>{{ $no_days_output }}</td>
              <td>{{ $today_input }}</td>
              @if($key == 0)
              <td rowspan="{{ $rowspan }}">{{ $total_input_qty }}</td>
              <td rowspan="{{ $rowspan }}">{{ $total_output_qty }}</td>
              <td rowspan="{{ $rowspan }}">{{ $line_balance }}</td>
              <td rowspan="{{ $rowspan }}">{{ $wip }}</td>
              <td rowspan="{{ $rowspan }}">{{ $yesterday_forecast }}</td>
              <td rowspan="{{ $rowspan }}">{{ $yesterday_prod }}</td>
              <td rowspan="{{ $rowspan }}">{{ $yesterday_eff }}</td>
              <td rowspan="{{ $rowspan }}">{{ $forecast_target }}</td>
              @endif
            </tr>
            @endforeach
          @else
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td rowspan="{{ $rowspan }}">{{ $total_input_qty }}</td>
            <td rowspan="{{ $rowspan }}">{{ $total_output_qty }}</td>
            <td rowspan="{{ $rowspan }}">{{ $line_balance }}</td>
            <td rowspan="{{ $rowspan }}">{{ $wip }}</td>
            <td rowspan="{{ $rowspan }}">{{ $yesterday_forecast }}</td>
            <td rowspan="{{ $rowspan }}">{{ $yesterday_prod }}</td>
            <td rowspan="{{ $rowspan }}">{{ $yesterday_eff }}</td>
            <td rowspan="{{ $rowspan }}">{{ $forecast_target }}</td>
          </tr>
          @endif
      @endforeach
    @endforeach
  @endif
</tbody>