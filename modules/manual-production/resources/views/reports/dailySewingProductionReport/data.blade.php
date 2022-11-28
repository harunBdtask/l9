<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: #66ffb5">
                <th colspan="4">Factory : {{ $metaData['factory'] }}</th>
                <th colspan="4">Unit : {{ $metaData['floor'] }}</th>
                <th colspan="4">Date : {{ $metaData['date'] }}</th>
            </tr>
            <tr>
                <th>Line</th>
                <th>Buyer</th>
                <th>Order/Style</th>
                <th>Item</th>
                <th>Color Name</th>
                <th>Order Qty</th>
                <th>Daily Input</th>
                <th>Total Input</th>
                <th>Daily Output</th>
                <th>Total Output</th>
                <th style="color: red">Balance</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @php
              $grand_total_input = 0;
              $grand_total_output = 0;
              $grand_balance = 0;
            @endphp
            @foreach(collect($data)->groupBy('line_id') as $lineWiseGroup)
              @php
                $line_wise_total_input = 0;
                $line_wise_total_output = 0;
                $line_wise_balance = 0;
              @endphp
              @foreach(collect($lineWiseGroup)->groupBy('buyer_id') as $buyerWise)
                @foreach(collect($buyerWise)->groupBy('order_id') as $orderWise)
                    @foreach(collect($orderWise)->groupBy('garments_item_id') as $garmentsItemWise)
                      @foreach(collect($garmentsItemWise)->groupBy('color_id') as $item)
                        @php
                          $line_id = $item->first()->line_id;
                          $order_id = $item->first()->order_id;
                          $garments_item_id = $item->first()->garments_item_id;
                          $color_id = $item->first()->color_id;
                          
                          $total_input = SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport::getLineOrderItemColorWiseTotalInputQty($line_id, $order_id, $garments_item_id, $color_id);
                          $total_output = SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDateWiseSewingReport::getLineOrderItemColorWiseTotalOutputQty($line_id, $order_id, $garments_item_id, $color_id);
                          $balance_qty = $total_input - $total_output;

                          $line_wise_total_input += $total_input;
                          $line_wise_total_output += $total_output;
                          $line_wise_balance += $balance_qty;

                          $grand_total_input += $total_input;
                          $grand_total_output += $total_output;
                          $grand_balance += $balance_qty;
                        @endphp
                          <tr>
                              <td>{{ collect($item)->first()->line->line_no }}</td>
                              <td>{{ collect($item)->first()->buyer->name }}</td>
                              <td>{{ collect($item)->first()->order->style_name }}</td>
                              <td>{{ collect($item)->first()->item->name }}</td>
                              <td>{{ collect($item)->first()->color->name }}</td>
                              <td>{{ collect($item)->first()->order->pq_qty_sum }}</td>
                              <td>{{ (int)collect($item)->sum('input_qty') }}</td>
                              <td>{{ $total_input }}</td>
                              <td>{{ (int)collect($item)->sum('sewing_output_qty') }}</td>
                              <td>{{ $total_output }}</td>
                              <td>{{ $balance_qty }}</td>
                              <td>&nbsp;</td>
                          </tr>
                      @endforeach
                    @endforeach
                @endforeach
              @endforeach
              <tr style="background-color: #dbffff; color: #007488">
                  <th colspan="5">Line No - {{ collect($item)->first()->line->line_no }}</th>
                  <th>Total</th>
                  <th>{{ (int)collect($lineWiseGroup)->sum('input_qty') }}</th>
                  <th>{{ $line_wise_total_input }}</th>
                  <th>{{ (int)collect($lineWiseGroup)->sum('sewing_output_qty') }}</th>
                  <th>{{ $line_wise_total_output }}</th>
                  <th>{{ $line_wise_balance }}</th>
                  <td></td>
              </tr>
            @endforeach
            <tr style="background-color: lightpink">
                <th colspan="6">Grand Total</th>
                <th>{{ (int)collect($data)->sum('input_qty') }}</th>
                <th>{{ $grand_total_input }}</th>
                <th>{{ (int)collect($data)->sum('sewing_output_qty') }}</th>
                <th>{{ $grand_total_output }}</th>
                <th>{{ $grand_balance }}</th>
                <td></td>
            </tr>
            <tr>
                <td colspan="12">&nbsp;</td>
            </tr>
            <tr>
                <th colspan="3">Total Input</th>
                <th colspan="3">{{ $grand_total_input }}</th>
                <th colspan="3">Total Output</th>
                <th colspan="3">{{ $grand_total_output }}</th>
            </tr>
            </tbody>
        </table>
    </div>
</div>
