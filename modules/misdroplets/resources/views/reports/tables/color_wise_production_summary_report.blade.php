<thead @if($print == 1) style="font-size: 6px !important" @endif>
<tr>
    <th colspan="6">General Info</th>
    <th colspan="3">Cutting</th>
    <th colspan="3">Print Send</th>
    <th colspan="3">Print Received</th>
    <th colspan="3">Sewing Input</th>
    <th colspan="3">Sewing Output</th>
    <th colspan="3">Wash Send</th>
    <th colspan="3">Wash Received</th>
    <th colspan="3">Poly</th>
    <th colspan="7">Rejection</th>
    <th colspan="3">Shipment</th>
    <th colspan="2">Shipment %</th>
</tr>
<tr @if($print == 1) style="font-size: 6px !important" @endif>
    <th>Buyer</th>
    <th>Style/Order</th>
    <th>PO</th>
    <th>Color</th>
    <th>PO Qty</th>
    <th>PO Qty + 3%</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Cutting</th>
    <th>Print</th>
    <th>Embroidary</th>
    <th>Sewing</th>
    <th>Washing</th>
    <th>Finishing</th>
    <th>Total</th>
    <th>Day</th>
    <th>Total</th>
    <th>Balance</th>
    <th>Order2Ship</th>
    <th>Cut2Ship</th>
</tr>
</thead>
<tbody @if($print == 1) style="font-size: 6px !important" @endif>
@if(isset($reportData) && count($reportData) > 0)
    @php
        $g_color_wise_order_qty = 0;
        $g_color_wise_order_qty_plus_three_percent = 0;
        $g_todays_cutting_production = 0;
        $g_todays_print_send_qty = 0;
        $g_todays_print_received_qty = 0;
        $g_todays_sewing_input_qty = 0;
        $g_todays_sewing_output_qty = 0;
        $g_todays_washing_sent_qty = 0;
        $g_todays_washing_received_qty = 0;
        $g_todays_poly_qty = 0;
        $g_todays_ship_qty = 0;
        $g_total_cutting = 0;
        $g_cutting_balance = 0;
        $g_total_print_sent_qty = 0;
        $g_total_print_sent_balance = 0;
        $g_total_print_received_qty = 0;
        $g_total_print_received_balance = 0;
        $g_total_input_qty = 0;
        $g_total_input_balance = 0;
        $g_total_sewing_output_qty = 0;
        $g_total_sewing_output_balance = 0;
        $g_total_washing_sent_qty = 0;
        $g_total_washing_sent_balance = 0;
        $g_total_washing_received_qty = 0;
        $g_total_washing_received_balance = 0;
        $g_total_poly_qty = 0;
        $g_total_poly_balance = 0;
        $g_total_ship_qty = 0;
        $g_total_ship_balance = 0;

        $g_total_cutting_rejection = 0;
        $g_total_print_rejection = 0;
        $g_total_embr_rejection = 0;
        $g_total_sewing_rejection = 0;
        $g_total_washing_rejection = 0;
        $g_total_finishing_rejection = 0;
        $g_total_rejection = 0;
    @endphp
    @foreach($reportData->groupBy('purchase_order_id') as $orderGroup)
        @php
            $buyer = $orderGroup->first()->buyer->name ?? '';
            $order_style_no = $orderGroup->first()->order->style_name ?? '';
            $po_no = $orderGroup->first()->purchaseOrder->po_no ?? '';
            $purchase_order_id = $orderGroup->first()->purchase_order_id;
        @endphp
        @foreach($orderGroup->groupBy('color_id') as $colorGroup)
            @php
                $color_name = $colorGroup->first()->color->name ?? '';
                $color_id = $colorGroup->first()->color_id;
                $color_wise_order_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);
                $color_wise_order_qty_plus_three_percent = round($color_wise_order_qty + (($color_wise_order_qty * 3) /100));

                $total_cutting = 0;
                $total_print_sent_qty = 0;
                $total_print_received_qty = 0;
                $total_input_qty = 0;
                $total_sewing_output_qty = 0;
                $total_washing_sent_qty = 0;
                $total_washing_received_qty = 0;
                $total_poly_qty = 0;
                $total_cutting_rejection = 0;
                $total_print_rejection = 0;
                $total_embr_rejection = 0;
                $total_sewing_rejection = 0;
                $total_washing_rejection = 0;
                $total_finishing_rejection = 0;
                $total_rejection = 0;
                $total_ship_qty = 0;
                foreach($colorGroup as $report) {
                  $todays_cutting_production = $report->production_date == date('Y-m-d') ? $report->cutting_qty - $report->cutting_rejection_qty : 0;
                  $todays_print_send_qty = $report->production_date == date('Y-m-d') ? $report->print_sent_qty : 0;
                  $todays_print_received_qty = $report->production_date == date('Y-m-d') ? $report->print_received_qty : 0;
                  $todays_sewing_input_qty = $report->production_date == date('Y-m-d') ? $report->input_qty : 0;
                  $todays_sewing_output_qty = $report->production_date == date('Y-m-d') ? $report->sewing_output_qty : 0;
                  $todays_washing_sent_qty = $report->production_date == date('Y-m-d') ? $report->washing_sent_qty : 0;
                  $todays_washing_received_qty = $report->production_date == date('Y-m-d') ? $report->washing_received_qty : 0;
                  $todays_poly_qty = $report->production_date == date('Y-m-d') ? $report->poly_qty : 0;
                  $todays_ship_qty = $report->production_date == date('Y-m-d') ? $report->ship_qty : 0;

                  $total_cutting += $report->cutting_qty - $report->cutting_rejection_qty;
                  $total_print_sent_qty += $report->print_sent_qty;
                  $total_print_received_qty += $report->print_received_qty;
                  $total_input_qty += $report->input_qty;
                  $total_sewing_output_qty += $report->sewing_output_qty;
                  $total_washing_sent_qty += $report->washing_sent_qty;
                  $total_washing_received_qty += $report->washing_received_qty;

                  $total_cutting_rejection += $report->cutting_rejection_qty;
                  $total_print_rejection += $report->print_rejection_qty;
                  $total_embr_rejection += 0;
                  $total_sewing_rejection += $report->sewing_rejection_qty;
                  $total_washing_rejection += $report->washing_rejection_qty;
                  $total_finishing_rejection += $report->poly_rejection ?? 0;

                  $total_poly_qty += $report->poly_qty;
                  $total_ship_qty += $report->ship_qty;
                }

                $cutting_balance = $total_cutting - $color_wise_order_qty_plus_three_percent;
                $total_print_sent_balance = $total_print_sent_qty - $total_cutting;
                $total_print_received_balance = $total_print_received_qty - $total_print_sent_qty;
                $total_input_balance = $total_input_qty - $total_cutting;
                $total_sewing_output_balance = $total_sewing_output_qty - $total_input_qty;
                $total_washing_sent_balance = $total_washing_sent_qty - $total_sewing_output_qty;
                $total_washing_received_balance = $total_washing_received_qty - $total_washing_sent_qty;
                $total_poly_balance = $total_poly_qty - $color_wise_order_qty_plus_three_percent;
                $total_ship_balance = $total_ship_qty - $color_wise_order_qty_plus_three_percent;
                $order_to_ship_percent = $color_wise_order_qty > 0 ? (($total_ship_qty * 100) / $color_wise_order_qty) : 0;
                $cut_to_ship_percent = $total_cutting > 0 ? (($total_ship_qty * 100) / $total_cutting) : 0;
                $total_rejection += $total_cutting_rejection + $total_print_rejection + $total_embr_rejection + $total_sewing_rejection + $total_washing_rejection + $total_finishing_rejection;

                $g_total_cutting_rejection += $total_cutting_rejection;
                $g_total_print_rejection += $total_print_rejection;
                $g_total_embr_rejection += $total_embr_rejection;
                $g_total_sewing_rejection += $total_sewing_rejection;
                $g_total_washing_rejection += $total_washing_rejection;
                $g_total_finishing_rejection += $total_finishing_rejection;
                $g_total_rejection += $total_rejection;

                $g_color_wise_order_qty += $color_wise_order_qty;
                $g_color_wise_order_qty_plus_three_percent += $color_wise_order_qty_plus_three_percent;
                $g_total_cutting += $total_cutting;
                $g_cutting_balance += $cutting_balance;
                $g_total_print_sent_qty += $total_print_sent_qty;
                $g_total_print_sent_balance += $total_print_sent_balance;
                $g_total_print_received_qty += $total_print_received_qty;
                $g_total_print_received_balance += $total_print_received_balance;
                $g_total_input_qty += $total_input_qty;
                $g_total_input_balance += $total_input_balance;
                $g_total_sewing_output_qty += $total_sewing_output_qty;
                $g_total_sewing_output_balance += $total_sewing_output_balance;
                $g_total_washing_sent_qty += $total_washing_sent_qty;
                $g_total_washing_sent_balance += $total_washing_sent_balance;
                $g_total_washing_received_qty += $total_washing_received_qty;
                $g_total_washing_received_balance += $total_washing_received_balance;
                $g_total_poly_qty += $total_poly_qty;
                $g_total_poly_balance += $total_poly_balance;
                $g_total_ship_qty += $total_ship_qty;
                $g_total_ship_balance += $total_ship_balance;

                $g_todays_cutting_production += $todays_cutting_production;
                $g_todays_print_send_qty += $todays_print_send_qty;
                $g_todays_print_received_qty += $todays_print_received_qty;
                $g_todays_sewing_input_qty += $todays_sewing_input_qty;
                $g_todays_sewing_output_qty += $todays_sewing_output_qty;
                $g_todays_washing_sent_qty += $todays_washing_sent_qty;
                $g_todays_washing_received_qty += $todays_washing_received_qty;
                $g_todays_poly_qty += $todays_poly_qty;
                $g_todays_ship_qty += $todays_ship_qty;
            @endphp
            <tr>
                <td>{{ $buyer ?? '' }}</td>
                <td title="{{ $order_style_no ?? '' }}">{{ substr($order_style_no, -10) ?? '' }}</td>
                <td title="{{ $po_no ?? '' }}">{{ substr($po_no, -10) ?? '' }}</td>
                <td title="{{ $color_name ?? '' }}">{{ substr($color_name, -10) ?? '' }}</td>
                <td>{{ $color_wise_order_qty }}</td>
                <td>{{ $color_wise_order_qty_plus_three_percent }}</td>
                <td>{{ $todays_cutting_production }}</td>
                <td>{{ $total_cutting}}</td>
                <td>{{ $cutting_balance}}</td>
                <td>{{ $todays_print_send_qty }}</td>
                <td>{{ $total_print_sent_qty }}</td>
                <td>{{ $total_print_sent_balance }}</td>
                <td>{{ $todays_print_received_qty }}</td>
                <td>{{ $total_print_received_qty }}</td>
                <td>{{ $total_print_received_balance }}</td>
                <td>{{ $todays_sewing_input_qty }}</td>
                <td>{{ $total_input_qty }}</td>
                <td>{{ $total_input_balance }}</td>
                <td>{{ $todays_sewing_output_qty }}</td>
                <td>{{ $total_sewing_output_qty }}</td>
                <td>{{ $total_sewing_output_balance }}</td>
                <td>{{ $todays_washing_sent_qty }}</td>
                <td>{{ $total_washing_sent_qty }}</td>
                <td>{{ $total_washing_sent_balance }}</td>
                <td>{{ $todays_washing_received_qty }}</td>
                <td>{{ $total_washing_received_qty }}</td>
                <td>{{ $total_washing_received_balance }}</td>
                <td>{{ $todays_poly_qty }}</td>
                <td>{{ $total_poly_qty }}</td>
                <td>{{ $total_poly_balance }}</td>
                <td>{{ $total_cutting_rejection }}</td>
                <td>{{ $total_print_rejection }}</td>
                <td>{{ $total_embr_rejection }}</td>
                <td>{{ $total_sewing_rejection }}</td>
                <td>{{ $total_washing_rejection }}</td>
                <td>{{ $total_finishing_rejection }}</td>
                <td>{{ $total_rejection }}</td>
                <td>{{ $todays_ship_qty }}</td>
                <td>{{ $total_ship_qty }}</td>
                <td>{{ $total_ship_balance }}</td>
                <td>{{ number_format($order_to_ship_percent,2) }}%</td>
                <td>{{ number_format($cut_to_ship_percent,2) }}%</td>
            </tr>
        @endforeach
    @endforeach
    <tr>
        <th colspan="4">Total</th>
        <th>{{ $g_color_wise_order_qty }}</th>
        <th>{{ $g_color_wise_order_qty_plus_three_percent }}</th>
        <th>{{ $g_todays_cutting_production }}</th>
        <th>{{ $g_total_cutting}}</th>
        <th>{{ $g_cutting_balance}}</th>
        <th>{{ $g_todays_print_send_qty }}</th>
        <th>{{ $g_total_print_sent_qty }}</th>
        <th>{{ $g_total_print_sent_balance }}</th>
        <th>{{ $g_todays_print_received_qty }}</th>
        <th>{{ $g_total_print_received_qty }}</th>
        <th>{{ $g_total_print_received_balance }}</th>
        <th>{{ $g_todays_sewing_input_qty }}</th>
        <th>{{ $g_total_input_qty }}</th>
        <th>{{ $g_total_input_balance }}</th>
        <th>{{ $g_todays_sewing_output_qty }}</th>
        <th>{{ $g_total_sewing_output_qty }}</th>
        <th>{{ $g_total_sewing_output_balance }}</th>
        <th>{{ $g_todays_washing_sent_qty }}</th>
        <th>{{ $g_total_washing_sent_qty }}</th>
        <th>{{ $g_total_washing_sent_balance }}</th>
        <th>{{ $g_todays_washing_received_qty }}</th>
        <th>{{ $g_total_washing_received_qty }}</th>
        <th>{{ $g_total_washing_received_balance }}</th>
        <th>{{ $g_todays_poly_qty }}</th>
        <th>{{ $g_total_poly_qty }}</th>
        <th>{{ $g_total_poly_balance }}</th>
        <td>{{ $g_total_cutting_rejection }}</td>
        <td>{{ $g_total_print_rejection }}</td>
        <td>{{ $g_total_embr_rejection }}</td>
        <td>{{ $g_total_sewing_rejection }}</td>
        <td>{{ $g_total_washing_rejection }}</td>
        <td>{{ $g_total_finishing_rejection }}</td>
        <td>{{ $g_total_rejection }}</td>
        <th>{{ $g_todays_ship_qty }}</th>
        <th>{{ $g_total_ship_qty }}</th>
        <th>{{ $g_total_ship_balance }}</th>
        <th colspan="2"></th>
    </tr>
    @if($reportData->total() > 15 && $print == 0)
        <tr>
            <td colspan="42" align="center">{{ $reportData->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <th colspan="42">No Data</th>
    </tr>
@endif
</tbody>
