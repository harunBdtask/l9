<thead>
<tr style="font-size: 12px !important">
    <th>Floor</th>
    <th>Line</th>
    <th>Buyer</th>
    <th>Style</th>
    <th>Order</th>
    <th>Order Qty</th>
    <th>Color</th>
    <th>Item</th>
    <th>Del.<br/>Date</th>
    <th>Input<br/>Date</th>
    <th>Tgt.<br/>(/Hr)</th>
    <th>P.W<br/>(Hr)</th>
    <th>Sewing<br/>Achv.</th>
    <th style="background: #A0FFA0">Today<br/>Input</th>
    <th style="background: #A0FFA0">Today<br/>Output</th>
    <th style="background: #A0FFA0">Today<br/>Finish<br/>Poly</th>
    <th>Total<br/>Cutting</th>
    <th style="background: #00ff80">Total<br/>Input</th>
    <th style="background: #00ff80">Total<br/>Output</th>
    <th style="background: #00ff80">Total<br/>Finish<br/>Poly</th>
    <th>WIP</th>
    <th>Tgt.vs<br/> Achv.</th>
    <th>Line<br/> MP</th>
    <th>SMV</th>
    <th>Day<br/>Line <br/>Eff. %</th>
    <th>Daily <br/>Flr. Eff.</th>
    <th>Remarks</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
    @php
        $g_today_input = 0;
        $g_today_output = 0;
        $g_total_input = 0;
        $g_total_output = 0;
        $g_total_wip = 0;
    @endphp
    @foreach($reports->groupBy('floor_id') as $reportByFloor)
        @php
            $floor_no = $reportByFloor->first()->floorWithoutGlobalScopes->floor_no;
            $f_today_input = 0;
            $f_today_output = 0;
            $f_total_input = 0;
            $f_total_output = 0;
            $f_total_wip = 0;
        @endphp
        @foreach($reportByFloor->sortBy('line_sort') as $report)
            @php
                $line_no = $report->lineWithoutGlobalScopes->line_no;
                $buyer = $report->buyerWithoutGlobalScopes->name;
                $style = $report->orderWithoutGlobalScopes->order_style_no;
                $po = $report->purchaseOrderWithoutGlobalScopes->po_no;
                $po_qty = $report->purchaseOrderWithoutGlobalScopes->po_quantity;
                $color = $report->colorWithoutGlobalScopes->name;
                $items = '';
                $report->orderWithoutGlobalScopes->itemsWithoutGlobalScopes->each(function ($value, $key) use(&$items) {
                    $items .= $value->itemWithoutGlobalScopes->item_name.' ';
                });
                $delivery_date = $report->purchaseOrderWithoutGlobalScopes->ex_factory_date ? date('d-M-Y', strtotime($report->purchaseOrderWithoutGlobalScopes->ex_factory_date)) : '';
                $input_date_fetch = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::poColorLineWiseFirstInputDateWithoutGlobalScopes($report->purchase_order_id, $report->color_id, $report->line_id);
                $input_date = $input_date_fetch ? date('d-M-Y', strtotime($input_date_fetch)) : '';

                $target = 0;
                $pw = 0;
                $line_mp = '';
                $remarks = '';
                $sewing_line_target_query = \SkylarkSoft\GoRMG\Inputdroplets\Models\SewingLineTarget::withoutGlobalScopes()->whereDate('target_date', $date)->where([
                    'purchase_order_id' => $report->purchase_order_id,
                    'line_id' => $report->line_id
                ])->first();
                if($sewing_line_target_query){
                    $target = $sewing_line_target_query->target ?? 0;
                    $pw = $sewing_line_target_query->wh ?? 0;
                    $line_mp = ($sewing_line_target_query->operator ?? 0) + ($sewing_line_target_query->helper ?? 0);
                    $remarks = $sewing_line_target_query->remarks ?? '';
                }
                $sewing_achieve = ($target > 0 && $report->sewing_output > 0) ? number_format(($report->sewing_output / $target), 2) : 0;
                $today_input = $report->sewing_input;
                $today_output = $report->sewing_output;

                $quantity_fetch_related_query = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::withoutGlobalScopes()->whereDate('production_date', '<=', $date)->where([
                    'purchase_order_id' => $report->purchase_order_id,
                    'color_id' => $report->color_id,
                ]);
                $cutting_query = clone $quantity_fetch_related_query;
                $poly_query = clone $quantity_fetch_related_query;
                $today_poly = $quantity_fetch_related_query->whereDate('production_date', $date)->sum('poly_qty');
                $total_poly = $poly_query->sum('poly_qty');
                $total_cutting = $cutting_query->sum('cutting_qty') - $cutting_query->sum('cutting_rejection_qty');
                $total_line_input = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::poColorLineWiseTotalInputQtyWithoutGlobalScopes($report->purchase_order_id, $report->color_id, $report->line_id, $date);
                $total_line_output = \SkylarkSoft\GoRMG\Inputdroplets\Models\FinishingProductionReport::poColorLineWiseTotalOutputQtyWithGlobalScopes($report->purchase_order_id, $report->color_id, $report->line_id, $date);
                $wip = $total_line_input - $total_line_output;
                $target_vs_achieve = $today_output - $target;
                $smv = $report->colorWithoutGlobalScopes->smv;
                $day_line_efficiency = 0;

                if(isset($report->colorWithoutGlobalScopes->smv)
                    && ($today_output > 0)
                    && (isset($sewing_line_target_query->operator))
                    && ($sewing_line_target_query->operator > 0)
                    && isset($sewing_line_target_query->wh)
                    && ($sewing_line_target_query->wh > 0)) {

                    $day_line_efficiency = 100 * number_format((($today_output * $smv) / 60) / ($sewing_line_target_query->wh * ($sewing_line_target_query->operator + $sewing_line_target_query->helper)), 2);
                  }
                $daily_floor_efficiency = '';

                $f_today_input += $today_input;
                $f_today_output += $today_output;
                $f_total_input += $total_line_input;
                $f_total_output += $total_line_output;
                $f_total_wip += $wip;

                $g_today_input += $today_input;
                $g_today_output += $today_output;
                $g_total_input += $total_line_input;
                $g_total_output += $total_line_output;
                $g_total_wip += $wip;
            @endphp
            <tr>
                <td>{{ $floor_no }}</td>
                <td>{{ $line_no }}</td>
                <td>{{ $buyer }}</td>
                <td>{{ $style }}</td>
                <td>{{ $po }}</td>
                <td>{{ $po_qty }}</td>
                <td>{{ $color }}</td>
                <td>{{ $items }}</td>
                <td>{{ $delivery_date }}</td>
                <td>{{ $input_date }}</td>
                <td>{{ $target }}</td>
                <td>{{ $pw }}</td>
                <td>{{ $sewing_achieve }}</td>
                <td style="background: #A0FFA0">{{ $today_input }}</td>
                <td style="background: #A0FFA0">{{ $today_output }}</td>
                <td style="background: #A0FFA0">{{ $today_poly }}</td>
                <td>{{ $total_cutting }}</td>
                <td style="background: #A0FFA0">{{ $total_line_input }}</td>
                <td style="background: #A0FFA0">{{ $total_line_output }}</td>
                <td style="background: #A0FFA0">{{ $total_poly }}</td>
                <td>{{ $wip }}</td>
                <td>{{ $target_vs_achieve }}</td>
                <td>{{ $line_mp }}</td>
                <td>{{ $smv }}</td>
                <td>{{ $day_line_efficiency }} %</td>
                <td>&nbsp;</td>
                <td>{{ $remarks }}</td>
            </tr>
        @endforeach
        <tr style="font-weight: bold; background: #4FD1D1">
            <td colspan="5">TOTAL</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $f_today_input }}</td>
            <td>{{ $f_today_output }}</td>
            <td></td>
            <td></td>
            <td>{{ $f_total_input }}</td>
            <td>{{ $f_total_output }}</td>
            <td></td>
            <td>{{ $f_total_wip }}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
    @endforeach
    <tr style="font-weight: bold;font-size: 13px; height: 40px !important">
        <td colspan="5">GRAND TOTAL</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $g_today_input }}</td>
        <td>{{ $g_today_output }}</td>
        <td></td>
        <td></td>
        <td>{{ $g_total_input }}</td>
        <td>{{ $g_total_output }}</td>
        <td></td>
        <td>{{ $g_total_wip }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
@else
    <tr>
        <th colspan="27" class="text-danger text-center">No Data</th>
    </tr>
@endif
</tbody>