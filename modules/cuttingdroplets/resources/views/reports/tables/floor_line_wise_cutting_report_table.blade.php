@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
<table class="reportTable" id="fixTable" style="border-collapse: collapse;">
  <thead>
  @isset($type)
    <tr><th colspan="12">{{ factoryName() }}</th></tr>
    <tr><th colspan="12">Line Wise Input In Hand Report</th></tr>
  @endisset
  <tr>
    <th>Buyer</th>
    <th>Style</th>
    <th>Order/Style</th>
    <th>Floor Line</th>
    <th>Color</th>
    <th>Order Qty Ex.%</th>
    <th>Total Cutting</th>
    <th>C.Balance</th>
    <th>Today's Input</th>
    <th>Total Input</th>
    <th>In-Hand</th>
    <th>Balance</th>
  </tr>
  </thead>
  <tbody>
  @if($floor_line_wise_reports && $floor_line_wise_reports->count())
    @php
      $grand_total_order_qty_excess = 0;
			$grand_total_cutting = 0;
			$grand_total_c_balance = 0;
			$grand_today_input_qty = 0;
			$grand_total_input_qty = 0;
			$grand_total_inhand = 0;
			$grand_total_balance = 0;
    @endphp
    @foreach($floor_line_wise_reports->groupBy('order_id') as $reportByOrder)
      @php
        $order_total_order_qty_excess = 0;
				$order_total_cutting = 0;
				$order_total_c_balance = 0;
				$order_today_input_qty = 0;
				$order_total_input_qty = 0;
				$order_total_inhand = 0;
				$order_total_balance = 0;
      @endphp
      @foreach($reportByOrder->groupBy('color_id') as $reportByColor)
        @php
          $order = $reportByColor->first()->order;
					$color_id = $reportByColor->first()->color_id;
					$calculate_order_excess_qty = clone $order;
					$excess_cutting_percent = $order->excess_cutting_percent;

					$order_excess_cut_qty = 0;
					$calculate_order_excess_qty->purchase_orders->each(function($item, $item_key) use($excess_cutting_percent, $color_id, &$order_excess_cut_qty) {
						$purchase_id = $item->id;
						$order_excess_cut_qty += SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantityWithExcessCuttingPercent($purchase_id,$color_id, $excess_cutting_percent);
					});
					$total_production = $reportByColor->first()->totalProductionReportsByOrderColor;
					$calc_cutting_qty = clone $total_production;
					$calc_cutting_rejection_qty = clone $total_production;
					$total_cutting_qty = $calc_cutting_qty->sum('total_cutting') - $calc_cutting_rejection_qty->sum('total_cutting_rejection');

					$c_balance = $order_excess_cut_qty - $total_cutting_qty;

					$order_color_wise_input = $reportByColor->first()->orderColorWiseInput;
					$calc_todays_sewing_input = clone $order_color_wise_input;
					$calc_total_sewing_input = clone $order_color_wise_input;

					$todays_sewing_input = $calc_todays_sewing_input->where('production_date', date('Y-m-d'))->sum('sewing_input');
					$total_sewing_input = $calc_total_sewing_input->sum('sewing_input');

					$floor_line = '';
					$reportByColor->groupBy('floor_id')->each(function ($floor_group, $floor_id) use(&$floor_line) {
						$floor_line .= $floor_group->first()->floor->floor_no.'['.$floor_group->unique('line_id')->implode('line.line_no',',').']<br>';
					});
					$in_hand = $total_cutting_qty - $total_sewing_input;

					$balance = $c_balance + $in_hand;

					$order_total_order_qty_excess += $order_excess_cut_qty;
					$order_total_cutting += $total_cutting_qty;
					$order_total_c_balance += $c_balance;
					$order_today_input_qty += $todays_sewing_input;
					$order_total_input_qty += $total_sewing_input;
					$order_total_inhand += $in_hand;
					$order_total_balance += $balance;

					$grand_total_order_qty_excess += $order_excess_cut_qty;
					$grand_total_cutting += $total_cutting_qty;
					$grand_total_c_balance += $c_balance;
					$grand_today_input_qty += $todays_sewing_input;
					$grand_total_input_qty += $total_sewing_input;
					$grand_total_inhand += $in_hand;
					$grand_total_balance += $balance;
        @endphp
        <tr>
          <td>{{ $reportByColor->first()->buyer->name }}</td>
          <td>{{ $reportByColor->first()->order->style_name }}</td>
          <td>{{ $reportByColor->first()->order->order_style_no }}</td>
          <td>{!! $floor_line !!}</td>
          <td>{{ $reportByColor->first()->color->name }}</td>
          <td>{{ $order_excess_cut_qty }}</td>
          <td>{{ $total_cutting_qty }}</td>
          <td>{{ $c_balance }}</td>
          <td>{{ $todays_sewing_input }}</td>
          <td>{{ $total_sewing_input }}</td>
          <td>{{ $in_hand }}</td>
          <td>{{ $balance }}</td>
        </tr>
      @endforeach
      <tr>
        <th colspan="5">Total</th>
        <th>{{ $order_total_order_qty_excess }}</th>
        <th>{{ $order_total_cutting }}</th>
        <th>{{ $order_total_c_balance }}</th>
        <th>{{ $order_today_input_qty }}</th>
        <th>{{ $order_total_input_qty }}</th>
        <th>{{ $order_total_inhand }}</th>
        <th>{{ $order_total_balance }}</th>
      </tr>
    @endforeach
    <tr>
      <th colspan="5">Grand Total</th>
      <th>{{ $grand_total_order_qty_excess }}</th>
      <th>{{ $grand_total_cutting }}</th>
      <th>{{ $grand_total_c_balance }}</th>
      <th>{{ $grand_today_input_qty }}</th>
      <th>{{ $grand_total_input_qty }}</th>
      <th>{{ $grand_total_inhand }}</th>
      <th>{{ $grand_total_balance }}</th>
    </tr>
  @else
    <tr class="tr-height">
      <td colspan="12" class="text-danger text-center">Not found</td>
    </tr>
  @endif
  </tbody>
</table>
