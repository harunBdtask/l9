@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
<table class="reportTable" id="fixTable" style="border-collapse: collapse;">
  <thead>
  @isset($type)
    <tr>
      <th colspan="12">Floor &amp; Line Wise Input, Output Report</th>
    </tr>
    <tr>
      <th colspan="12">
        Floor : {{ $floor_line_wise_report->first()->floor->floor_no ?? '' }},
        From Date: {{ $from_date ?? '' }}, To Date: {{ $to_date ?? '' }}
      </th>
    </tr>
  @endisset
  <tr>
    <th>Floor</th>
    <th>Line</th>
    <th>Buyer</th>
    <th>Style/Order</th>
    <th>Order/Style</th>
    <th>Color</th>
    <th>Today's Input</th>
    <th>Total Input</th>
    <th>Today's Output</th>
    <th>Total Output</th>
    <th>Rejection</th>
    <th>WIP</th>
  </tr>
  </thead>
  <tbody>
  @if(!empty($floor_line_wise_report))
    @php
      $grand_total_input_qty = 0;
      $grand_total_output_qty = 0;
      $grand_total_rejection_qty = 0;
    @endphp
    @foreach($floor_line_wise_report->groupBy('line_id') as $groupByLine)
      @foreach($groupByLine->groupBy('order_id') as $groupByOrder)
        @php
          $order_total_input_qty = 0;
          $order_total_output_qty = 0;
          $order_total_rejection_qty = 0;
        @endphp
        @foreach($groupByOrder->groupBy('color_id') as $groupByColor)
          @php
            $singlerow = $groupByColor->first();
            $line_id = $singlerow->line_id;
            $order_id = $singlerow->order_id;
            $color_id = $singlerow->color_id;

            $todays_sewing_input = $groupByColor->where('production_date', date('Y-m-d'))->sum('sewing_input');
            /*
            $total_sewing_input = $groupByColor->sum('sewing_input');
            $total_sewing_output = $groupByColor->sum('sewing_output');
            $sewing_rejection = $groupByColor->sum('sewing_rejection');
            */
            $quantities = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::orderColorLineWiseQuantities($order_id, $color_id, $line_id);
            $total_sewing_input = $quantities['sewing_input'];
            /*$total_sewing_input = \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventory::orderColorLineWiseTotalInputQty($order_id, $color_id, $line_id)->input_qty;*/
            $todays_sewing_output = $groupByColor->where('production_date', date('Y-m-d'))->sum('sewing_output');
            $total_sewing_output = $quantities['sewing_output'];
            $sewing_rejection = $quantities['sewing_rejection'];

            $order_total_input_qty += $total_sewing_input;
            $order_total_output_qty += $total_sewing_output;
            $order_total_rejection_qty += $sewing_rejection;

            $grand_total_input_qty += $total_sewing_input;
            $grand_total_output_qty += $total_sewing_output;
            $grand_total_rejection_qty += $sewing_rejection;

            $buyer = isset($type) ? $singlerow->buyer->name : substr($singlerow->buyer->name, 0, 15);
            $booking = isset($type) ? $singlerow->order->booking_no : substr($singlerow->order->booking_no, 0, 15);
            $order = isset($type) ? $singlerow->order->order_style_no : substr($singlerow->order->order_style_no, 0, 5);
            $color = isset($type) ? $singlerow->color->name : substr($singlerow->color->name, 0, 18);
          @endphp
          <tr>
            <td>{{ $singlerow->floor->floor_no ?? '' }}</td>
            <td style="background-color: #a8f5ff;">{{ $singlerow->line->line_no ?? '' }}</td>
            <td title="{{ $singlerow->buyer->name ?? '' }}">{{ $buyer }}</td>
            <td title="{{ $singlerow->order->booking_no ?? '' }}">{{ $booking }}</td>
            <td title="{{ $singlerow->order->order_style_no ?? '' }}">{{ $order }}</td>
            <td title="{{ $singlerow->color->name ?? '' }}">{{ $color }}</td>
            <td>{{ $todays_sewing_input }}</td>
            <td>{{ $total_sewing_input }}</td>
            <td>{{ $todays_sewing_output }}</td>
            <td style="background-color: #cbffb5;">{{ $total_sewing_output }}</td>
            <td style="background-color: #ffd1d1;">{{ $sewing_rejection }}</td>
            <td style="background-color: #FFC04A;">{{ $total_sewing_input - $total_sewing_output - $sewing_rejection }}</td>
          </tr>
        @endforeach
        <tr style="background-color: #e0f7fa;">
          <th colspan="6" align="right"> Total</th>
          <th>{{ $groupByOrder->where('production_date', date('Y-m-d'))->sum('sewing_input') }}</th>
          <th>{{ $order_total_input_qty }}</th>
          <th>{{ $groupByOrder->where('production_date', date('Y-m-d'))->sum('sewing_output') }}</th>
          <th>{{ $order_total_output_qty }}</th>
          <th>{{ $order_total_rejection_qty }}</th>
          <th>
            {{
              $order_total_input_qty
              - $order_total_output_qty
              - $order_total_rejection_qty
            }}
          </th>
        </tr>
      @endforeach
    @endforeach
    <tr class="tr-height" style="background-color: #fbf6de;">
      <th colspan="6" align="right"> Grand Total</th>
      <th>{{ $floor_line_wise_report->where('production_date', date('Y-m-d'))->sum('sewing_input') }}</th>
      <th>{{ $grand_total_input_qty }}</th>
      <th>{{ $floor_line_wise_report->where('production_date', date('Y-m-d'))->sum('sewing_output') }}</th>
      <th>{{ $grand_total_output_qty }}</th>
      <th>{{ $grand_total_rejection_qty }}</th>
      <th>
        {{
          $grand_total_input_qty
          - $grand_total_output_qty
          - $grand_total_rejection_qty
        }}
      </th>
    </tr>
  @else
    <tr class="tr-height">
      <td colspan="12" class="text-danger text-center">Not found</td>
    </tr>
  @endif
  </tbody>
</table>
