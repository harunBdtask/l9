<table>
    <thead>
    <tr><td colspan="10">{{ factoryName() }}</td></tr>
    </thead>
</table>
<table class="reportTable">
  <thead>
    <tr>
      <th colspan="10">Section-1 : Challan No. Wise Sewing Input Status</th>
    </tr>
    <tr>
      <th>Unit</th>
      <th>Line</th>
      <th width="23%">Challan No</th>
      <th>Buyer</th>
      <th>Order/Style</th>
      <th>PO</th>
      <th>PO Qty</th>
      <th>Color</th>
      <th>Input Qty</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if(!empty($date_wise_input))
      @php
        $total_input = 0;
        $challan_no = '';
        $input_time = '';
      @endphp
      @foreach($date_wise_input->sortBy('line.sort')->groupBy('floor_id') as $reportByFloor)
        @php
          $floor_total_input = 0;
          $floor_no = $reportByFloor->first()->floor->floor_no;
        @endphp
        @foreach($reportByFloor as $report)
          @php
            $line_no = $report->line->line_no;
            $buyer_name = $report->buyer->name;
            $style_name = $report->order->style_name;
            $po_no = $report->purchaseOrder->po_no;
            $po_qty = $report->purchaseOrder->po_quantity;
            $color = $report->color->name;
            $challan_no = '';
            $input_time = '';
            $challans = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::getCuttingInventoryChallanWithoutGlobalScopes($report->purchase_order_id, $report->color_id, $date, $report->line_id) ?? '';
            $count_challan = count($challans);
            foreach ($challans as $key => $challan) {
                $input_date = date('d/m/Y ', strtotime($date));

                $challan_originial_time = $challan->updated_at;
                $new_challan_time = $input_date.' ';
                  if (date('H', strtotime($challan_originial_time)) < 8) {
                    $new_challan_time .= '08:'.date('i:s', strtotime($challan_originial_time));
                  } elseif (date('H', strtotime($challan_originial_time)) >= 19) {
                    $new_challan_time .= '18:'.date('i:s', strtotime($challan_originial_time));
                  } else {
                    $new_challan_time .= date('H:i:s', strtotime($challan_originial_time));
                  }

                $input_date_time = ' ('.$new_challan_time.')';
                $challan_no .= $challan->challan_no ? $challan->challan_no . $input_date_time : '';
                if($key < $count_challan - 1){
                    $challan_no .= ",<br> ";
                    //$input_time .= ", ";
                }
            }

            $input = $report->sewing_input;
            $total_input += $input;
            $floor_total_input += $input;
          @endphp
          <tr>
            <td>{{ $floor_no }}</td>
            <td>{{ $line_no }}</td>
            <td>{!! $challan_no !!}</td>
            <td>{{ $buyer_name }}</td>
            <td>{{ $style_name }}</td>
            <td title="{{ $po_no }}">{{ substr($po_no, -30) }}</td>
            <td>{{ $po_qty }}</td>
            <td>{{ $color }}</td>
            <td>{{ $input }}</td>
            {{--  <td>{{ $input_time }}</td> --}}
          </tr>
        @endforeach
        <tr class="yellow-200">
          <th colspan="8">Total = {{ $floor_no }}</th>
          <th>{{ $floor_total_input }}</th>
        </tr>
      @endforeach
      <tr class="green-100">
        <th colspan="8">Grand Total</th>
        <th>{{ $total_input }}</th>
      </tr>
    @else
      <tr>
        <td colspan="9" class="text-danger text-center">Not found
        </td>
      </tr>
    @endif
  </tbody>
</table>

<!-- line wise -->
<table class="reportTable">
  <thead>
    <tr>
      <th colspan="3">Section-2 : Line Wise Input Status</th>
    </tr>
    <tr>
      <th>Unit</th>
      <th>Line</th>
      <th>Input Quantity</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if(!empty($date_wise_input))
      @php
        $total_input_line = 0;
      @endphp
      @foreach($date_wise_input->sortBy('line.sort')->groupBy('floor_id') as $report_floor_wise)
        @php
          $floor_total_input_line = 0;
          $floor_no = $report_floor_wise->first()->floor->floor_no ?? '';
        @endphp
        @foreach($report_floor_wise->sortBy('line.sort')->groupBy('line_id') as $report_line_wise)
          @php
            $line_no = $report_line_wise->first()->line->line_no ?? '';
            $input_line = $report_line_wise->sum('sewing_input');
            $total_input_line += $input_line;
            $floor_total_input_line += $input_line;
          @endphp
          <tr>
            <td>{{ $floor_no ?? '' }}</td>
            <td>{{ $line_no ?? '' }}</td>
            <td>{{ $input_line }}</td>
          </tr>
        @endforeach
        <tr class="yellow-200">
          <th colspan="2">Total = {{ $floor_no }}</th>
          <th>{{ $floor_total_input_line }}</th>
        </tr>
      @endforeach
      <tr class="green-100">
        <th colspan="2">Grand Total</th>
        <th>{{ $total_input_line }}</th>
      </tr>
    @else
      <tr>
        <td colspan="3" class="text-danger text-center">Not found
        </td>
      </tr>
    @endif
  </tbody>
</table>