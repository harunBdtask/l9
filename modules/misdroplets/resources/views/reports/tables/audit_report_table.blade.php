    <thead>
    <tr align="center">
        <th>Buyer</th>
        <th>
          <div style="width: 100px;">Style/Order</div>
        </th>
        <th>Order Qty</th>
        <th>Ship Date</th>
        <th>Cut Qty</th>
        <th>Ex/Srt Cut Qty</th>
        <th>Print<br/> Sent</th>
        <th>Print<br/> Rcv</th>
        <th>Print<br/> Bal</th>
        <th>Emb<br/> Sent</th>
        <th>Emb<br/> Rcv</th>
        <th>Emb<br/> Bal</th>
        <th>Input Qty</th>
        <th>Out Qty</th>
        <th>Cut<br/> Rej</th>
        <th>Print<br/> Rej</th>
        <th>Emb<br/> Rej</th>
        <th>Swng<br/> Rej</th>
        <th>Fin<br/> Rej</th>
        <th>Total<br/> Rej</th>
        <th>Fin<br/> Qty</th>
        <th>Ship<br/> Qty</th>
        <th>Shrt<br/> Qty</th>
        <th>Cut to Ship <br> Ratio</th>
        <th>Rmrks</th>
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
                $excess_short_cut_qty = $report->cutting_qty_sum - ($order_qty ?? 0);
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

                $finishing_rejection_qty = $report->finishing_rejection_qty_sum ?? 0;
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
                <td title="{{ $buyer }}">{{ $buyer }}</td>
                <td title="{{ $style }}">{{ $style }}</td>
                <td>{{ $order_qty }}</td>
                <td>{{ $shipment_date ? date('d/m/Y', strtotime($shipment_date)) : '' }}</td>
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
            <th colspan="25" align="center">No Data</th>
        </tr>
    @endif
    </tbody>

