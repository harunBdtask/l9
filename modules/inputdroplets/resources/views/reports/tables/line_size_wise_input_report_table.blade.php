<thead>
  <tr>
    <th rowspan="2">Input Date</th>
    <th rowspan="2">Line No</th>
    <th rowspan="2">Buyer</th>
    <th rowspan="2">Style</th>
    <th rowspan="2">PO</th>
    <th rowspan="2">Color</th>
    <th rowspan="2">Color Qty</th>
    <th colspan="{{ count($size_ids) }}">Size Wise Qty</th>
    <th rowspan="2">Total Input Qty</th>
  </tr>
  <tr>
    @if(count($size_ids))
      @foreach ($size_ids as $size_id)
        @php
          ${'g_total_'.$size_id} = 0;
          $size_query = $reports->where('size_id', $size_id)->first();
        @endphp
        <th style="background-color: rgb(148, 218, 251);">{{ $size_query ? $size_query->size->name : '' }}</th>
      @endforeach
    @else
      <td style="background-color: rgb(148, 218, 251);">&nbsp;</td>
    @endif
  </tr>
</thead>
<tbody>
  @if($reports && $reports->count())
    @php
      $g_total_input_qty = 0;
    @endphp
    @foreach ($reports->sortBy('line.sort')->groupBy('line_id') as $reportByLine)
      @foreach ($reportByLine->sortBy('line.sort')->groupBy('purchase_order_id') as $reportByPo)
        @php
          $po_wise_total_input_qty = 0;
        @endphp
        @foreach ($size_ids as $size_id)
          @php
            ${'po_wise_total_'.$size_id} = 0;
          @endphp
        @endforeach
        @foreach ($reportByPo->sortBy('line.sort')->groupBy('color_id') as $reportByColor)
          @foreach ($reportByColor->sortBy('line.sort')->groupBy('production_date') as $reportByDate)
          @php
              $production_date = $reportByDate->first()->production_date;
              $line_no = $reportByLine->first()->line->line_no;
              $buyer_name = $reportByPo->first()->buyer->name;
              $style_name = $reportByPo->first()->order->style_name;
              $po_no = $reportByPo->first()->purchaseOrder->po_no;
              $color = $reportByColor->first()->color->name;
              $purchase_order_id = $reportByPo->first()->purchase_order_id;
              $color_id = $reportByColor->first()->color_id;
              $order_color_qty = SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);
              $color_wise_total_input_qty = 0;
            @endphp
            <tr>
              <td>{{ $production_date ? date('d M Y', strtotime($production_date)) : '' }}</td>
              <td>{{ $line_no }}</td>
              <td>{{ $buyer_name }}</td>
              <td>{{ $style_name }}</td>
              <td>{{ $po_no }}</td>
              <td>{{ $color }}</td>
              <td>{{ $order_color_qty }}</td>
              @foreach ($size_ids as $size_id)
                @php
                  $sewingQty = $reportByDate->where('size_id', $size_id)->sum('sewing_input');
                  $g_total_input_qty += $sewingQty;
                  $po_wise_total_input_qty += $sewingQty;
                  $color_wise_total_input_qty += $sewingQty;
                  ${'g_total_'.$size_id} += $sewingQty;
                  ${'po_wise_total_'.$size_id} += $sewingQty;
                @endphp
                <td>{{ $sewingQty }}</td>
              @endforeach
              <th style="background-color: transparent!important;">{{ $color_wise_total_input_qty }}</th>
            </tr>
          @endforeach
          <tr>
            <th colspan="7">Size Wise Total</th>
            @foreach ($size_ids as $size_id)
              <th>{{ ${'po_wise_total_'.$size_id} }}</th>
            @endforeach
              <th>{{ $po_wise_total_input_qty }}</th>
          </tr>
        @endforeach
      @endforeach
    @endforeach
    <tr>
      <th colspan="7">Grand Total</th>
      @foreach ($size_ids as $size_id)
        <th>{{ ${'g_total_'.$size_id} }}</th>
      @endforeach
      <th>{{ $g_total_input_qty }}</th>
    </tr>
  @else
    <tr>
      <td colspan="9">No Data Found</td>
    </tr>
  @endif
</tbody>