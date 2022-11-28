<thead>
<tr>
  <th>Buyer</th>
  <th>Order/Style</th>
  <th>PO</th>
  <th>Sustainable Material</th>
  <th>Color</th>
  <th>Color Wise PO Quantity</th>
  <th>Cutting Production</th>
  <th>WIP In Cutting/Print/Embr.</th>
  <th>Today's Input to Line</th>
  <th>Total Input to Line</th>
  <th>Today's Output</th>
  <th>Total Sewing Output</th>
  <th>Sewing Rejection</th>
  <th>Total Rejection</th>
  <th>In_line WIP</th>
  <th>Cut 2 Sewing Ratio</th>
</tr>
</thead>
<tbody>
@if(!$order_wise_report->getCollection()->isEmpty())
  @php
    $total_quantity = 0;
    $total_cutting = 0;
    $total_wip = 0;
    $todays_input = 0;
    $total_input = 0;
    $todays_sewing_output = 0;
    $total_sewing_output = 0;
    $total_sewing_rejection = 0;
    $total_rejection = 0;
    $total_in_line_wip = 0;
  @endphp
  @foreach($order_wise_report->groupBy('purchase_order_id') as $reportByPurchaseOrder)
    @php
      if (!$reportByPurchaseOrder->first()->buyer || !$reportByPurchaseOrder->first()->order || !$reportByPurchaseOrder->first()->purchaseOrder) {
          continue;
      }
      $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
      $style_name = $reportByPurchaseOrder->first()->order->style_name ?? 'Order';
      $sustainable_material_name = $reportByPurchaseOrder->first()->order->sustainable_material_name ?? '';
      $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
      $purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id;
      $color_row_span = $reportByPurchaseOrder->groupBy('color_id')->count() + 1;

      $po_total_qty = 0;
      $po_total_cutting_of_order = 0;
      $po_total_cutting_rejection_of_order = 0;
      $po_total_sent_of_order = 0;
      $po_total_received_of_order = 0;
      $po_total_print_rejection_of_order = 0;
      $po_todays_input_of_order = 0;
      $po_total_input_of_order = 0;
      $po_todays_sewing_output_of_order = 0;
      $po_total_sewing_output_of_order = 0;
      $po_total_sewing_rejection_of_order = 0;
      $po_total_rejection_of_order = 0;
      $po_total_wip_in_cutting = 0;
      $po_total_in_line_wip_of_order = 0;
    @endphp
    <tr>
      <td rowspan="{{ $color_row_span }}">{{ $buyer_name ?? '' }}</td>
      <td rowspan="{{ $color_row_span }}">{{ $style_name ?? '' }}</td>
      <td rowspan="{{ $color_row_span }}">{{ $po_no ?? '' }}</td>
      <td rowspan="{{ $color_row_span }}">{{ $sustainable_material_name }}</td>
    @foreach($reportByPurchaseOrder as $report)
      @php
        $color = $report->color->name ?? '';
        $color_id = $report->color_id;

        $color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);
        $po_total_qty += $color_wise_po_qty;
        $total_quantity += $color_wise_po_qty;

        $total_cutting_of_order = $report->total_cutting ?? 0;
        $total_cutting_rejection_of_order = $report->total_cutting_rejection ?? 0;
        $total_sent_of_order = $report->total_sent ?? 0;
        $total_received_of_order = $report->total_received ?? 0;
        $total_print_rejection_of_order = $report->total_print_rejection ?? 0;
        $todays_input_of_order = $report->todays_input ?? 0;
        $total_input_of_order = $report->total_input ?? 0;
        $todays_sewing_output_of_order = $report->todays_sewing_output ?? 0;
        $total_sewing_output_of_order = $report->total_sewing_output ?? 0;
        $total_sewing_rejection_of_order = $report->total_sewing_rejection ?? 0;
        $wip_in_cutting = $total_cutting_of_order - $total_sent_of_order ?? 0;

        $po_total_cutting_of_order += $total_cutting_of_order;
        $po_total_cutting_rejection_of_order += $total_cutting_rejection_of_order;
        $po_total_sent_of_order += $total_sent_of_order;
        $po_total_received_of_order += $total_received_of_order;
        $po_total_print_rejection_of_order += $total_print_rejection_of_order;
        $po_todays_input_of_order += $todays_input_of_order;
        $po_total_input_of_order += $total_input_of_order;
        $po_todays_sewing_output_of_order += $todays_sewing_output_of_order;
        $po_total_sewing_output_of_order += $total_sewing_output_of_order;
        $po_total_sewing_rejection_of_order += $total_sewing_rejection_of_order;
        $po_total_wip_in_cutting += $wip_in_cutting ?? 0;

        $total_cutting += $total_cutting_of_order ?? 0;
        $total_wip += $wip_in_cutting ?? 0;
        $todays_input += $todays_input_of_order ?? 0;
        $total_input += $total_input_of_order ?? 0;
        $todays_sewing_output += $todays_sewing_output_of_order ?? 0;
        $total_sewing_output += $total_sewing_output_of_order ?? 0;
        $total_sewing_rejection += $total_sewing_rejection_of_order ?? 0;
        $total_rejection_of_order = $total_cutting_rejection_of_order + $total_print_rejection_of_order + $total_sewing_rejection_of_order ?? 0;

        $po_total_rejection_of_order += $total_rejection_of_order;

        $total_rejection += $total_rejection_of_order ?? 0;
        $total_in_line_wip_of_order = $total_input_of_order - $total_sewing_output_of_order ?? 0;
        $total_in_line_wip += $total_in_line_wip_of_order ?? 0;
        $po_total_in_line_wip_of_order += $total_in_line_wip_of_order ?? 0;
      @endphp
      @if(!$loop->first)
        <tr>
          @endif
          <td>{{ $color ?? '' }}</td>
          <td>{{ $color_wise_po_qty ?? '' }}</td>
          <td>{{ $total_cutting_of_order }}</td>
          <td>{{ $wip_in_cutting }}</td>
          <td>{{ $todays_input_of_order }}</td>
          <td>{{ $total_input_of_order }}</td>
          <td>{{ $todays_sewing_output_of_order }}</td>
          <td>{{ $total_sewing_output_of_order }}</td>
          <td>{{ $total_sewing_rejection_of_order }}</td>
          <td>{{ $total_rejection_of_order }}</td>
          <td>{{ $total_in_line_wip_of_order }}</td>
          <td>
            @if($total_sewing_output_of_order > 0 && $total_cutting_of_order > 0)
              {{ round(($total_sewing_output_of_order / $total_cutting_of_order)*100,2) }}%
            @endif
          </td>
        </tr>
        @endforeach
        <tr>
          <th>Total</th>
          <th>{{ $po_total_qty }}</th>
          <th>{{ $po_total_cutting_of_order }}</th>
          <th>{{ $po_total_wip_in_cutting }}</th>
          <th>{{ $po_todays_input_of_order }}</th>
          <th>{{ $po_total_input_of_order }}</th>
          <th>{{ $po_todays_sewing_output_of_order }}</th>
          <th>{{ $po_total_sewing_output_of_order }}</th>
          <th>{{ $po_total_sewing_rejection_of_order }}</th>
          <th>{{ $po_total_rejection_of_order }}</th>
          <th>{{ $po_total_in_line_wip_of_order }}</th>
          <th>{{ '' }}</th>
        </tr>
        @endforeach
        <tr style="font-weight: bold">
          <td colspan="5">Total</td>
          <td>{{ $total_quantity }}</td>
          <td>{{ $total_cutting }}</td>
          <td>{{ $total_wip }}</td>
          <td>{{ $todays_input }}</td>
          <td>{{ $total_input }}</td>
          <td>{{ $todays_sewing_output }}</td>
          <td>{{ $total_sewing_output }}</td>
          <td>{{ $total_sewing_rejection }}</td>
          <td>{{ $total_rejection }}</td>
          <td>{{ $total_in_line_wip }}</td>
          <td></td>
        </tr>
        @if($order_wise_report->total() > PAGINATION && $download == 1)
          <tr class="hide-when-download">
            <td colspan="15" align="center">{{ $order_wise_report->appends(request()->except('page'))->links() }}</td>
          </tr>
        @endif
      @else
        <tr>
          <td colspan="15" class="text-danger text-center">Not found
          <td>
        </tr>
      @endif
</tbody>
