<thead>
    @if($print == 0 && ($buyer || $order_info || $from_date || $to_date))
        <tr>
            <th colspan="6">{{ $buyer ? 'Buyer: '.$buyer->name : '' }}</th>
            <th colspan="6">{{ $order_info ? 'Style: '.$order_info->style_name : '' }}</th>
            <th colspan="6">{{ $from_date ? 'From Date: '. $from_date : '' }}</th>
            <th colspan="6">{{ $to_date ? 'To Date: '. $to_date : '' }}</th>
        </tr>
    @endif
    <tr style="background-color: #ebffe4;" align="center">
        <th>Buyer</th>
        <th>Style/Order</th>
        <th>Order Qty</th>
        <th>Shipment Date</th>
        <th>Cutting<br/> Qty</th>
        <th>Excess/Short<br/> Cutting <br/>Qty</th>
        <th>Print<br/> Sent</th>
        <th>Print<br/> Received</th>
        <th>Print<br/> Balance</th>
        <th>Embroidery<br/> Sent</th>
        <th>Embroidery<br/> Received</th>
        <th>Embroidery<br/> Balance</th>
        <th>Sewing<br/> Input</th>
        <th>Sewing<br/> Output</th>
        <th>Cutting<br/> Rejection</th>
        <th>Print<br/> Rejection</th>
        <th>Embroidery<br/> Rejection</th>
        <th>Sewing<br/> Rejection</th>
        <th>Finishing<br/> Rejection</th>
        <th>Total<br/> Rejection</th>
        <th>Finishing<br/> Qty</th>
        <th>Shipment<br/> Qty</th>
        <th>Short<br/> Qty</th>
        <th>Cut to Ship <br> Ratio</th>
        <th>Remarks</th>
    </tr>
</thead>
<tbody>
@if($reports && $reports->count())
    @php
        $date_from = $from_date == '' ? date('Y-m-d', strtotime('2019-01-01')) : $from_date;
        $date_to = $to_date == '' ? date('Y-m-d') : $to_date;

        $t_order_qty = 0;
        $t_cut_qty = 0;
        $t_excess_short_cut_qty = 0;
        $t_print_sent_qty = 0;
        $t_print_receive_qty = 0;
        $t_print_balance_qty = 0;
        $t_embroidery_sent_qty = 0;
        $t_embroidery_receive_qty = 0;
        $t_embroidery_balance_qty = 0;
        $t_sewing_input_qty = 0;
        $t_sewing_output_qty = 0;
        $t_cut_rejection_qty = 0;
        $t_print_rejection_qty = 0;
        $t_embroidery_rejection_qty = 0;
        $t_sewing_rejection_qty = 0;
        $t_finishing_rejection_qty = 0;
        $t_total_rejection_qty = 0;
        $t_finishing_qty = 0;
        $t_shipment_qty = 0;
        $t_shipment_short_qty = 0;
    @endphp
    @foreach($reports as $report)
        @php
            $buyer = $report->buyer->name ?? '';
            $style = $report->order->style_name;
            $order_qty = $report->order->pq_qty_sum;
            $shipment_date = $report->order->purchaseOrders()->orderBy('ex_factory_date', 'asc')->first()->ex_factory_date ?? '';
            $cut_qty = $report->cutting_qty_sum ?? 0;
            $excess_short_cut_qty = $report->cutting_qty_sum - $report->order->total_quantity ?? 0;
            $print_sent_qty = $report->print_sent_qty_sum ?? 0;
            $print_receive_qty = $report->print_received_qty_sum ?? 0;
            $print_balance_qty = $report->print_sent_qty_sum - $report->print_received_qty_sum ?? 0;
            $embroidery_sent_qty = $report->embroidary_sent_qty_sum ?? 0;
            $embroidery_receive_qty = $report->embroidary_received_qty_sum ?? 0;
            $embroidery_balance_qty = $report->embroidary_sent_qty_sum - $report->embroidary_received_qty_sum ?? 0;
            $sewing_input_qty = $report->input_qty_sum ?? 0;
            $sewing_output_qty = $report->sewing_output_qty_sum ?? 0;

            $cut_rejection_qty = $report->cutting_rejection_qty_sum ?? 0;
            $print_rejection_qty = $report->print_rejection_qty_sum ?? 0;
            $embroidery_rejection_qty = $report->embroidary_rejection_qty_sum ?? 0;
            $sewing_rejection_qty = $report->sewing_rejection_qty_sum ?? 0;

            $iron_production = 0;
            $poly_production = $report->poly_qty_sum ?? 0;
            $shipment_production = \SkylarkSoft\GoRMG\Iedroplets\Models\Shipment::totalShipmentOfOrder($report->order_id);

            $finishing_rejection_qty = $iron_production + $poly_production;
            $total_rejection_qty = $cut_rejection_qty + $print_rejection_qty + $embroidery_rejection_qty + $sewing_rejection_qty + $finishing_rejection_qty;

            $finishing_qty = $poly_production;

            $shipment_qty = $shipment_production ?? 0;
            $shipment_short_qty = $order_qty - $shipment_qty;
            $cut_to_ship_ratio = $cut_qty > 0 ? round(($shipment_qty / $cut_qty) * 100) : 0;

            $t_order_qty += $order_qty;
            $t_cut_qty += $cut_qty;
            $t_excess_short_cut_qty += $excess_short_cut_qty;
            $t_print_sent_qty += $print_sent_qty;
            $t_print_receive_qty += $print_receive_qty;
            $t_print_balance_qty += $print_balance_qty;
            $t_embroidery_sent_qty += $embroidery_sent_qty;
            $t_embroidery_receive_qty += $embroidery_receive_qty;
            $t_embroidery_balance_qty += $embroidery_balance_qty;
            $t_sewing_input_qty += $sewing_input_qty;
            $t_sewing_output_qty += $sewing_output_qty;
            $t_cut_rejection_qty += $cut_rejection_qty;
            $t_print_rejection_qty += $print_rejection_qty;
            $t_embroidery_rejection_qty += $embroidery_rejection_qty;
            $t_sewing_rejection_qty += $sewing_rejection_qty;
            $t_finishing_rejection_qty += $finishing_rejection_qty;
            $t_total_rejection_qty += $total_rejection_qty;
            $t_finishing_qty += $finishing_qty;
            $t_shipment_qty += $shipment_qty;
            $t_shipment_short_qty += $shipment_short_qty;
        @endphp
        <tr>
            <td>{{ $buyer }}</td>
            <td>{{ $style }}</td>
            <td>{{ $order_qty }}</td>
            <td>{{ date('d/m/Y', strtotime($shipment_date)) }}</td>
            <td>{{ $cut_qty }}</td>
            <td>{{ $excess_short_cut_qty }}</td>
            <td>{{ $print_sent_qty }}</td>
            <td>{{ $print_receive_qty }}</td>
            <td>{{ $print_balance_qty }}</td>
            <td>{{ $embroidery_sent_qty }}</td>
            <td>{{ $embroidery_receive_qty }}</td>
            <td>{{ $embroidery_balance_qty }}</td>
            <td>{{ $sewing_input_qty }}</td>
            <td>{{ $sewing_output_qty }}</td>
            <td>{{ $cut_rejection_qty }}</td>
            <td>{{ $print_rejection_qty }}</td>
            <td>{{ $embroidery_rejection_qty }}</td>
            <td>{{ $sewing_rejection_qty }}</td>
            <td>{{ $finishing_rejection_qty }}</td>
            <td>{{ $total_rejection_qty }}</td>
            <td>{{ $finishing_qty }}</td>
            <td>{{ $shipment_qty }}</td>
            <td>{{ $shipment_short_qty }}</td>
            <td>{{ $cut_to_ship_ratio }} %</td>
            <td>&nbsp;</td>
        </tr>
    @endforeach
    <tr style="background-color: #ffee88;" align="center" class="tr-height">
        <th colspan="2">Total</th>
        <th>{{ $t_order_qty }}</th>
        <th>&nbsp;</th>
        <th>{{ $t_cut_qty }}</th>
        <th>{{ $t_excess_short_cut_qty }}</th>
        <th>{{ $t_print_sent_qty }}</th>
        <th>{{ $t_print_receive_qty }}</th>
        <th>{{ $t_print_balance_qty }}</th>
        <th>{{ $t_embroidery_sent_qty }}</th>
        <th>{{ $t_embroidery_receive_qty }}</th>
        <th>{{ $t_embroidery_balance_qty }}</th>
        <th>{{ $t_sewing_input_qty }}</th>
        <th>{{ $t_sewing_output_qty }}</th>
        <th>{{ $t_cut_rejection_qty }}</th>
        <th>{{ $t_print_rejection_qty }}</th>
        <th>{{ $t_embroidery_rejection_qty }}</th>
        <th>{{ $t_sewing_rejection_qty }}</th>
        <th>{{ $t_finishing_rejection_qty }}</th>
        <th>{{ $t_total_rejection_qty }}</th>
        <th>{{ $t_finishing_qty }}</th>
        <th>{{ $t_shipment_qty }}</th>
        <th>{{ $t_shipment_short_qty }}</th>
        <th>&nbsp;</th>
        <th>&nbsp;</th>
    </tr>
@else
    <tr class="tr-height">
        <th colspan="26" align="center">No Data</th>
    </tr>
@endif
</tbody>
@if($reports && $reports->count() && $print)
    <tfoot>
    <tr>
        <td colspan="26" align="center">{{ $reports->appends($search_data)->links() }}</td>
    </tr>
    </tfoot>
@endif
