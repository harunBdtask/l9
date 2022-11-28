<thead>
<tr>
    <th>Buyer</th>
    <th>Style</th>
    <th>PO</th>
    <th>Shipment Date</th>
    <th>Days to Go Shipment</th>
    <th>Color</th>
    <th>Color Wise PO Qty</th>
    <th>Cutting Production</th>
    <th>Cutting/ Printing WIP</th>
    <th>Current Cutting Inventory</th>
    <th>Todays Input</th>
    <th>Total Sewing Input</th>
</tr>
</thead>
<tbody>
@if($order_wise_report)
    @php
        $torder_qty = 0;
        $grand_total_cutting_qty = 0;
        $grand_total_todays_input = 0;
        $grand_total_input = 0;
        $twip = 0;
        $tpewip = 0;
        $tcurrnt_inventory = 0;
    @endphp
    @foreach($order_wise_report->groupBy('purchase_order_id') as $reportByPurchaseOrder)
        @php
            if (!$reportByPurchaseOrder->first()->purchaseOrder || !$reportByPurchaseOrder->first()->order || !$reportByPurchaseOrder->first()->buyer) {
                continue;
            }
            $wip = 0;
            $pewip = 0;
            $current_inventory = 0;
            $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
            $style_name = $reportByPurchaseOrder->first()->order->style_name ?? 'Style';
            $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
            $purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id;
            $shipment_date = $reportByPurchaseOrder->first()->purchaseOrder->ex_factory_date ?? null;
            $dateInMs = strtotime($shipment_date) - strtotime(now()->toDateString());
            $days_to_shipment = round($dateInMs / 86400);
            $color_row_span = $reportByPurchaseOrder->groupBy('color_id')->count() + 1;

            $color_wise_total_order_qty = 0;
            $color_wise_total_cutting_qty = 0;
            $color_wise_total_cut_print_wip_qty = 0;
            $color_wise_total_cut_inventory_qty = 0;
            $color_wise_total_todays_input_qty = 0;
            $color_wise_total_input_qty = 0;
        @endphp
        <tr>
            <td rowspan="{{ $color_row_span }}">{{ $buyer_name ?? '' }}</td>
            <td rowspan="{{ $color_row_span }}">{{ $style_name ?? 'Style' }}</td>
            <td rowspan="{{ $color_row_span }}">{{ $po_no }}</td>
            <td rowspan="{{ $color_row_span }}">{{ $shipment_date ? date('d/m/Y', strtotime($shipment_date)) : "" }}</td>
            <td rowspan="{{ $color_row_span }}">{{ $days_to_shipment }} D(s)</td>
        @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
            @php
                $color_id = $reportByColor->first()->color_id;
                $color = $reportByColor->first()->color->name;
                $color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);
                $torder_qty += $color_wise_po_qty;

                $total_cutting_of_order = $reportByColor->sum('total_cutting') ?? 0;
                $total_sent_of_order = $reportByColor->sum('total_sent') ?? 0;
                $total_received_of_order = $reportByColor->sum('total_received') ?? 0;
                $todays_input_of_order = $reportByColor->sum('todays_input') ?? 0;
                $total_input_of_order = $reportByColor->sum('total_input') ?? 0;

                $grand_total_cutting_qty += $total_cutting_of_order;
                $grand_total_todays_input += $todays_input_of_order;
                $grand_total_input += $total_input_of_order;

                $wip = $total_cutting_of_order - $total_sent_of_order;
                $twip +=  $wip;
                $pewip += $total_sent_of_order - $total_received_of_order;
                $tpewip += $pewip;

                $current_inventory = $total_cutting_of_order - $total_sent_of_order;
                $tcurrnt_inventory += $current_inventory;

                $color_wise_total_order_qty += $color_wise_po_qty;
                $color_wise_total_cutting_qty += $total_cutting_of_order;
                $color_wise_total_cut_print_wip_qty += $wip;
                $color_wise_total_cut_inventory_qty += $current_inventory;
                $color_wise_total_todays_input_qty += $todays_input_of_order;
                $color_wise_total_input_qty += $total_input_of_order;
            @endphp
            @if(!$loop->first)
                <tr>
            @endif
                <td>{{ $color }}</td>
                <td>{{ $color_wise_po_qty?? 0 }}</td>
                <td>{{ $total_cutting_of_order }}</td>
                <td>{{ $wip }}</td>
                <td>{{ $current_inventory }}</td>
                <td>{{ $todays_input_of_order }}</td>
                <td>{{ $total_input_of_order }}</td>
            </tr>
        @endforeach
            <tr>
                <th>Total</th>
                <th>{{ $color_wise_total_order_qty }}</th>
                <th>{{ $color_wise_total_cutting_qty }}</th>
                <th>{{ $color_wise_total_cut_print_wip_qty }}</th>
                <th>{{ $color_wise_total_cut_inventory_qty }}</th>
                <th>{{ $color_wise_total_todays_input_qty }}</th>
                <th>{{ $color_wise_total_input_qty }}</th>
            </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td colspan="6">{{ 'Total' }}</td>
        <td>{{ $torder_qty }}</td>
        <td>{{ $grand_total_cutting_qty }}</td>
        <td>{{ $twip }}</td>
        <td>{{ $tcurrnt_inventory }}</td>
        <td>{{ $grand_total_todays_input }}</td>
        <td>{{ $grand_total_input }}</td>
    </tr>
    @if($order_wise_report->total() > PAGINATION && $print == 0)
        <tr>
            <td colspan="12" align="center">{{ $order_wise_report->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="12" class="text-danger text-center">Not found
        </td>
    </tr>
@endif
</tbody>