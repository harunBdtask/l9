@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
<table class="reportTable" id="fixTable" style="border-collapse: collapse;">
  <thead>
  @isset($type)
    <tr><th colspan="12">Floor &amp; Line Wise Input, Output Report</th></tr>
    <tr>
      <th colspan="12">
        Floor : {{ $floor_line_wise_report->first()->floor->floor_no ?? '' }},
        From Date: {{ $from_date ?? '' }},  To Date: {{ $to_date ?? '' }}
      </th>
    </tr>
  @endisset
  <tr>
    <th>Floor</th>

    <th>Buyer</th>
    <th>Order/Style</th>
    <th>Line</th>
    <th>Color</th>
    <th>Total Cutting</th>
    <th>Today's Input</th>
    <th>Total Input</th>
    <th>Balance</th>


  </tr>
  </thead>
  <tbody>
  @if(!empty($floor_line_wise_report))
    @php
      $grand_total_input_qty = 0;
      $grand_total_cutting = 0;
      $grand_total_output_qty = 0;
      $grand_total_rejection_qty = 0;
      $grand_total_blance = 0;
       $overall_balance = 0;
    @endphp
    @foreach($floor_line_wise_report->groupBy('line_id') as $groupByLine)
      @foreach($groupByLine->groupBy('order_id') as $groupByOrder)
        @php
          $order_total_input_qty = 0;
          $order_total_output_qty = 0;
          $order_total_rejection_qty = 0;
          $subTotal = 0;
        $rowsum_cutting=0;
        @endphp

        @foreach($groupByOrder->groupBy('color_id') as $groupByColor)
          @php
            $singlerow = $groupByColor->first();
            $line_id = $singlerow->line_id;
            $order_id = $singlerow->order_id;
            $color_id = $singlerow->color_id;

            $po_wise_production_report = SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport::with('purchaseOrder:id,po_no,po_quantity')
          ->selectRaw(
              'purchase_order_id,
              sum(todays_cutting - todays_cutting_rejection) as todays_cutting,
              sum(total_cutting - total_cutting_rejection) as cutting_qty'
          )->where('order_id', $order_id)
          ->where('color_id', $color_id)
          ->groupBy('purchase_order_id')
          ->get();



          $total_cutting = $po_wise_production_report->sum('cutting_qty');

            $todays_sewing_input = $groupByColor->where('production_date', date('Y-m-d'))->sum('sewing_input');
            $total_sewing_input = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\TotalProductionReport::orderColorLineWiseTotalInputQty($order_id, $color_id, $line_id);
            $todays_sewing_output = $groupByColor->where('production_date', date('Y-m-d'))->sum('sewing_output');
            $total_sewing_output = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::orderColorLineWiseTotalOutputQty($order_id, $color_id, $line_id);
            $sewing_rejection = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::orderColorLineWiseTotalRejectionQty($order_id, $color_id, $line_id);

            $order_total_input_qty += $total_sewing_input;
            $order_total_output_qty += $total_sewing_output;
            $order_total_rejection_qty += $sewing_rejection;
            $grand_total_cutting += $total_cutting;

            $grand_total_input_qty += $total_sewing_input;
            $grand_total_output_qty += $total_sewing_output;
            $grand_total_rejection_qty += $sewing_rejection;


            $cutting_balance = $total_cutting - $total_sewing_input;
            $overall_balance += $cutting_balance;
            $grand_total_blance +=$cutting_balance;
            $subTotal += $cutting_balance;
            $buyer = isset($type) ? $singlerow->buyer->name : substr($singlerow->buyer->name, 0, 15);
            $order = isset($type) ? $singlerow->order->style_name : substr($singlerow->order->style_name, 0, 5);
            $color = isset($type) ? $singlerow->color->name : substr($singlerow->color->name, 0, 18);
          @endphp
          <tr>
            <td>{{ $singlerow->floor->floor_no ?? '' }}</td>
            <td title="{{ $singlerow->buyer->name ?? '' }}">{{ $buyer }}</td>
            <td title="{{ $singlerow->order->style_name ?? '' }}">{{ $order }}</td>
            <td style="background-color: #a8f5ff;">{{ $singlerow->line->line_no ?? '' }}</td>
            <td title="{{ $singlerow->color->name ?? '' }}">{{ $color }}</td>
            <td style="background-color: #cbffb5;">{{ $total_cutting }}</td>
            <td>{{ $todays_sewing_input }}</td>
            <td>{{ $total_sewing_input }}</td>
            <th>{{ $cutting_balance }}</th>
            @php
             $rowsum_cutting += $total_cutting;
            @endphp
          </tr>
        @endforeach

        <tr style="background-color: #e0f7fa;">
          <th colspan="5" align="right"> Total </th>
          <th>{{ $rowsum_cutting }}</th>
          <th>{{ $groupByOrder->where('production_date', date('Y-m-d'))->sum('sewing_input') }}</th>
          <th>{{ $order_total_input_qty }}</th>
          <th>{{ $subTotal }}</th>
        </tr>
      @endforeach
    @endforeach
    <tr class="tr-height" style="background-color: #fbf6de;">
      <th colspan="5" align="right"> Grand Total </th>
      <th>{{ $grand_total_cutting }}</th>
      <th>{{ $floor_line_wise_report->where('production_date', date('Y-m-d'))->sum('sewing_input') }}</th>
      <th>{{ $grand_total_input_qty }}</th>
      <th>{{ $grand_total_blance }}</th>
    </tr>
  @else
    <tr class="tr-height">
      <td colspan="11" class="text-danger text-center">Not found</td>
    </tr>
  @endif
  </tbody>
</table>
