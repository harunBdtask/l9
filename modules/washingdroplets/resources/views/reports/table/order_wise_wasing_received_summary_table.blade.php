<thead>
<tr>
    <th>Buyer</th>
    <th>Style/Order No</th>
    <th>PO</th>
    <th>Color</th>
    <th>Color Wise PO Qty</th>
    <th>Cut. Production</th>
    <th>Today's Input</th>
    <th>Total Input</th>
    <th>Today's Output</th>
    <th>Total Output</th>
    <th>Today's W.Sent</th>
    <th>Total W.Sent</th>
    <th>Today's W.Received</th>
    <th>Total W.Received</th>
    <th>Total Rejection</th>
</tr>
</thead>
<tbody>
@if(isset($order_wise_report))
    @php
        $total_order_qty = 0;
        $grand_total_cutting_qty = 0;
        $grand_todays_input_qty = 0;
        $grand_total_input_qty = 0;
        $grand_todays_sewing_output_qty = 0;
        $grand_total_sewing_output_qty = 0;
        $grand_todays_washing_sent_qty = 0;
        $grand_total_washing_sent_qty = 0;
        $grand_todays_washing_received_qty = 0;
        $grand_total_washing_received_qty = 0;
        $grand_total_rejection_qty = 0;
    @endphp
    @foreach($order_wise_report->groupBy('purchase_order_id') as $reportByPurchaseOrder)
        @php
            $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
            $order_style_no = $reportByPurchaseOrder->first()->order->order_style_no ?? '';
            $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
            $purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id ?? '';
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
            $po_todays_wash_sent_of_order = 0;
            $po_total_wash_sent_of_order = 0;
            $po_todays_washing_received_of_order = 0;
            $po_total_washing_received_of_order = 0;
            $po_total_washing_rejection_of_order = 0;
            $po_total_rejection_qty = 0;
        @endphp
        <tr>
            <td rowspan="{{ $color_row_span }}">{{ $buyer_name ?? '' }}</td>
            <td rowspan="{{ $color_row_span }}">{{ $order_style_no ?? '' }}</td>
            <td rowspan="{{ $color_row_span }}">{{ $po_no ?? '' }}</td>
        @foreach($reportByPurchaseOrder as $report)
            @php
                $color_id = $report->color_id;
                $color = $report->color->name ?? '';

                $color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);

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
                $todays_wash_sent_of_order = $report->todays_washing_sent ?? 0;
                $total_wash_sent_of_order = $report->total_washing_sent ?? 0;
                $todays_washing_received_of_order = $report->todays_washing_received ?? 0;
                $total_washing_received_of_order = $report->total_washing_received ?? 0;
                $total_washing_rejection_of_order = $report->total_washing_rejection ?? 0;
                
                $po_total_qty += $color_wise_po_qty ?? 0;
                $po_total_cutting_of_order += $report->total_cutting ?? 0;
                $po_total_cutting_rejection_of_order += $report->total_cutting_rejection ?? 0;
                $po_total_sent_of_order += $report->total_sent ?? 0;
                $po_total_received_of_order += $report->total_received ?? 0;
                $po_total_print_rejection_of_order += $report->total_print_rejection ?? 0;
                $po_todays_input_of_order += $report->todays_input ?? 0;
                $po_total_input_of_order += $report->total_input ?? 0;
                $po_todays_sewing_output_of_order += $report->todays_sewing_output ?? 0;
                $po_total_sewing_output_of_order += $report->total_sewing_output ?? 0;
                $po_total_sewing_rejection_of_order += $report->total_sewing_rejection ?? 0;
                $po_todays_wash_sent_of_order += $report->todays_washing_sent ?? 0;
                $po_total_wash_sent_of_order += $report->total_washing_sent ?? 0;
                $po_todays_washing_received_of_order += $report->todays_washing_received ?? 0;
                $po_total_washing_received_of_order += $report->total_washing_received ?? 0;
                $po_total_washing_rejection_of_order += $report->total_washing_rejection ?? 0;

                $total_order_qty += $color_wise_po_qty;
                $total_rejection_qty = $total_cutting_rejection_of_order + $total_print_rejection_of_order + $total_sewing_rejection_of_order + $total_washing_rejection_of_order;
                $po_total_rejection_qty += $total_rejection_qty;
                $grand_total_cutting_qty += $total_cutting_of_order;
                $grand_todays_input_qty += $todays_input_of_order;
                $grand_total_input_qty += $total_input_of_order;
                $grand_todays_sewing_output_qty += $todays_sewing_output_of_order;
                $grand_total_sewing_output_qty += $total_sewing_output_of_order;
                $grand_todays_washing_sent_qty += $todays_wash_sent_of_order;
                $grand_total_washing_sent_qty += $total_wash_sent_of_order;
                $grand_todays_washing_received_qty += $todays_washing_received_of_order;
                $grand_total_washing_received_qty += $total_washing_received_of_order;
                $grand_total_rejection_qty += $total_cutting_rejection_of_order + $total_print_rejection_of_order + $total_sewing_rejection_of_order + $total_washing_rejection_of_order;
            @endphp
            @if(!$loop->first)
                <tr>
            @endif
                <td>{{ $color}}</td>
                <td>{{ $color_wise_po_qty }}</td>
                <td>{{ $total_cutting_of_order }}</td>
                <td>{{ $todays_input_of_order }}</td>
                <td>{{ $total_input_of_order }}</td>
                <td>{{ $todays_sewing_output_of_order }}</td>
                <td>{{ $total_sewing_output_of_order }}</td>
                <td>{{ $todays_wash_sent_of_order }}</td>
                <td>{{ $total_wash_sent_of_order }}</td>
                <td>{{ $todays_washing_received_of_order }}</td>
                <td>{{ $total_washing_received_of_order }}</td>
                <td>{{ $total_rejection_qty }}</td>
            </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <th>{{ $po_total_qty }}</th>
            <th>{{ $po_total_cutting_of_order }}</th>
            <th>{{ $po_todays_input_of_order }}</th>
            <th>{{ $po_total_input_of_order }}</th>
            <th>{{ $po_todays_sewing_output_of_order }}</th>
            <td>{{ $po_total_sewing_output_of_order }}</td>
            <td>{{ $po_todays_wash_sent_of_order }}</td>
            <td>{{ $po_total_wash_sent_of_order }}</td>
            <td>{{ $po_todays_washing_received_of_order }}</td>
            <td>{{ $po_total_washing_received_of_order }}</td>
            <td>{{ $po_total_rejection_qty }}</td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td colspan="4">Total</td>
        <td>{{ $total_order_qty }}</td>
        <td>{{ $grand_total_cutting_qty }}</td>
        <td>{{ $grand_todays_input_qty }}</td>
        <td>{{ $grand_total_input_qty }}</td>
        <td>{{ $grand_todays_sewing_output_qty }}</td>
        <td>{{ $grand_total_sewing_output_qty }}</td>
        <td>{{ $grand_todays_washing_sent_qty }}</td>
        <td>{{ $grand_total_washing_sent_qty }}</td>
        <td>{{ $grand_todays_washing_received_qty }}</td>
        <td>{{ $grand_total_washing_received_qty }}</td>
        <td>{{ $grand_total_rejection_qty }}</td>
    </tr>
    @if($order_wise_report->total() > PAGINATION && $print == 0)
        <tr>
            <td colspan="15" align="center">{{ $order_wise_report->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="15" class="text-danger text-center">Not found
        </td>
    </tr>
@endif
</tbody>