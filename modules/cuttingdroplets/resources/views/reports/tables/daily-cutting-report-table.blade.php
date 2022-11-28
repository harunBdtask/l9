@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
<table id="fixTable" class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
  <thead>
  @if(request()->route('type') && request()->route('type') != 'pdf')
    <tr>
      <th colspan="7">Daily Cutting Production Report ({{ date('d/m/Y', strtotime($date)) }})</th>
    </tr>
    <tr>
      <th colspan="7">{{ sessionFactoryName() }}</th>
    </tr>
  @endif
  <tr style="background: #98FB98;">
    <th>Buyer</th>
    <th>Style</th>
    <th>PO</th>
    <th>Ex Factory/ Shipment Date</th>
    <th>Color</th>
    <th>OQ</th>
    <th>Today Cutting</th>
    <th>Total Cutting</th>
    <th>Cutting &#37;</th>
    <th>Cutting Balance</th>
  </tr>
  </thead>
  <tbody>
  @if($cutting_report && $cutting_report->count())
    @php
      $g_t_order_qty = 0;
      $g_t_today_cutting_qty = 0;
      $g_t_total_cutting_qty = 0;
      $g_t_cutting_balance = 0;
      $g_t_input_ready = 0;
    @endphp
    @foreach($cutting_report->sortBy('buyer_id')->groupBy('cutting_floor_id') as $reportByCuttingFloor)
      @php
        $cutting_floor = $reportByCuttingFloor->first()->cuttingFloor->floor_no ?? '';
      @endphp
      <tr>
        <td colspan="10"><span style="font-weight: bold;font-size: 14px;">{{ $cutting_floor }}</span></td>
      </tr>
      @php
        $t_order_qty = 0;
        $t_today_cutting_qty = 0;
        $t_total_cutting_qty = 0;
        $t_cutting_balance = 0;
        $t_input_ready = 0;
      @endphp
      @foreach($reportByCuttingFloor as $report)
        @php
          $total_cutting_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard::where([
            'purchase_order_id' => $report->purchase_order_id,
            'color_id' => $report->color_id,
            'cutting_floor_id' => $report->cutting_floor_id,
            'status' => 1
          ])->value(DB::raw("SUM(quantity - total_rejection)")) ?? 0;

          $orderQty = SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($report->purchase_order_id, $report->color_id) ?? 0;
          $reference_no = $report->order->reference_no ?? null;
          $cut_percentage = $orderQty > 0 ? round(($total_cutting_qty * 100) / $orderQty) : 0;
          $cutting_balance = ($total_cutting_qty - $orderQty) ?? 0;
          $t_order_qty += $orderQty ?? 0;
          $t_today_cutting_qty += $report->total_cutting_qty ?? 0;
          $t_total_cutting_qty += $total_cutting_qty;
          $t_cutting_balance += $cutting_balance;

          $g_t_order_qty += $orderQty ?? 0;
          $g_t_today_cutting_qty += $report->total_cutting_qty ?? 0;
          $g_t_total_cutting_qty += $total_cutting_qty;
          $g_t_cutting_balance += $cutting_balance;
        @endphp
        <tr>
          <td style="text-align: left;padding-left: 1%">{{ $report->buyer->name }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $report->order->style_name }} {{ $reference_no ? ' - '. $reference_no : null}}</td>
          <td style="text-align: left;padding-left: 1%">{{ $report->purchaseOrder->po_no }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $report->purchaseOrder->ex_factory_date ? date('d-m-Y', strtotime($report->purchaseOrder->ex_factory_date)) : null }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $report->color->name }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $orderQty ?? 0 }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $report->total_cutting_qty  ?? 0 }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $total_cutting_qty }}</td>
          <td style="text-align: left;padding-left: 1%">{{ $cut_percentage }}&#37;</td>
          <td style="text-align: left;padding-left: 1%">{{ $cutting_balance }}</td>
        </tr>
      @endforeach
      <tr style="font-weight:bold;">
        <td colspan="5">{{ $cutting_floor }} = Total</td>
        <td>{{ $t_order_qty }}</td>
        <td>{{ $t_today_cutting_qty }}</td>
        <td>{{ $t_total_cutting_qty }}</td>
        <td>&nbsp;</td>
        <td>{{ $t_cutting_balance }}</td>
      </tr>
    @endforeach
  @else
    <tr class="tr-height">
      <td colspan="10" class="text-danger text-center">No Data Found</td>
    </tr>
  @endif
  </tbody>
  <tfoot>
  @if(isset($cutting_report) && count($cutting_report) > 0)
    <tr style="height:50px;font-size:16px; font-weight:bold;text-align: center;">
      <td colspan="5">Total</td>
      <td>{{ $g_t_order_qty }}</td>
      <td>{{ $g_t_today_cutting_qty }}</td>
      <td>{{ $g_t_total_cutting_qty }}</td>
      <td>&nbsp;</td>
      <td>{{ $g_t_cutting_balance }}</td>
    </tr>
  @endif
  </tfoot>
</table>
