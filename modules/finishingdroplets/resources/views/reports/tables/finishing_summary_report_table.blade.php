<thead>
@if(request()->has('type') || request()->route('type'))
    <tr>
        <th colspan="23">Finishing Summary Report</th>
    </tr>
@endif
<tr>
    <th>Buyer</th>
    <th>Style</th>
    <th>PO</th>
    <th>Color</th>
    <th>PO Qty</th>
    <th>Rcv From Sewing </th>
    <th>Bal From PO Qty</th>
    <th>Pre Iron</th>
    <th>Today Iron</th>
    <th>Total Iron</th>
    <th>Iron Bal</th>
    <th>Pre Poly</th>
    <th>Today Poly</th>
    <th>Total Poly</th>
    <th>Poly Bal</th>
    <th>Pre Packing</th>
    <th>Today Packing</th>
    <th>Total Packing</th>
    <th>Today Ship</th>
    <th>Total Ship</th>
    <th>Total Rej</th>
    <th>Rmrks</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
    @php
        $previous_date = Carbon\Carbon::parse($current_date)->subDay(1)->toDateString();
        $g_total_color_wise_po_qty = 0;
        $g_total_sewing_qty = 0;
        $g_total_sewing_balance = 0;
        $g_total_previous_iron_qty = 0;
        $g_total_today_iron_qty = 0;
        $g_total_total_iron_qty = 0;
        $g_total_iron_balance = 0;
        $g_total_previous_poly_qty = 0;
        $g_total_today_poly_qty = 0;
        $g_total_total_poly_qty = 0;
        $g_total_poly_balance = 0;
        $g_total_previous_packing_qty = 0;
        $g_total_today_packing_qty = 0;
        $g_total_total_packing_qty = 0;
        $g_total_today_ship_qty = 0;
        $g_total_total_ship_qty = 0;
        $g_total_total_rejection_qty = 0;
    @endphp
    @foreach($reports as $report)
        @php
            $purchase_order_id = $report->purchase_order_id;
            $color_id = $report->color_id;
            $color_wise_po_qty = \SkylarkSoft\GoRMG\Merchandising\Models\PurchaseOrderDetail::getColorWisePoQuantity($purchase_order_id, $color_id);
            $sewing_qty = $report->sewing_output_qty;
            $sewing_balance = $color_wise_po_qty - $sewing_qty;
            $previous_iron_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::ironQtyDatePurchaseOrderColorWise($previous_date, $purchase_order_id, $color_id);
            $today_iron_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::ironQtyDatePurchaseOrderColorWise($current_date, $purchase_order_id, $color_id);
            $total_iron_qty = $report->iron_qty;
            $iron_balance = $sewing_qty - $total_iron_qty;
            $previous_poly_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::polyQtyDatePurchaseOrderColorWise($previous_date, $purchase_order_id, $color_id);
            $today_poly_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::polyQtyDatePurchaseOrderColorWise($current_date, $purchase_order_id, $color_id);
            $total_poly_qty = $report->poly_qty;
            $poly_balance = $total_iron_qty - $total_poly_qty;
            $previous_packing_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::packingQtyDatePurchaseOrderColorWise($previous_date, $purchase_order_id, $color_id);
            $today_packing_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::packingQtyDatePurchaseOrderColorWise($current_date, $purchase_order_id, $color_id);
            $total_packing_qty = $report->packing_qty;
            $today_ship_qty = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateAndColorWiseProduction::shipQtyDatePurchaseOrderColorWise($current_date, $purchase_order_id, $color_id);
            $total_ship_qty = $report->ship_qty;
            $total_rejection_qty = $report->total_rejection_qty;

            $g_total_color_wise_po_qty += $color_wise_po_qty;
            $g_total_sewing_qty += $sewing_qty;
            $g_total_sewing_balance += $sewing_balance;
            $g_total_previous_iron_qty += $previous_iron_qty;
            $g_total_today_iron_qty += $today_iron_qty;
            $g_total_total_iron_qty += $total_iron_qty;
            $g_total_iron_balance += $iron_balance;
            $g_total_previous_poly_qty += $previous_poly_qty;
            $g_total_today_poly_qty += $today_poly_qty;
            $g_total_total_poly_qty += $total_poly_qty;
            $g_total_poly_balance += $poly_balance;
            $g_total_previous_packing_qty += $previous_packing_qty;
            $g_total_today_packing_qty += $today_packing_qty;
            $g_total_total_packing_qty += $total_packing_qty;
            $g_total_today_ship_qty += $today_ship_qty;
            $g_total_total_ship_qty += $total_ship_qty;
            $g_total_total_rejection_qty += $total_rejection_qty;
        @endphp
        <tr>
            <td>{{ $report->buyer->name }}</td>
            <td title="{{ $$report->order->style_name ?? 'Style' }}">{{ $report->order->style_name }}</td>
            <td title="{{ $report->purchaseOrder->po_no ?? 'Purchase Order' }}">{{ $report->purchaseOrder->po_no }}</td>
            <td title="{{ $report->color->name ?? 'Color' }}">{{ $report->color->name }}</td>
            <td>{{ $color_wise_po_qty }}</td>
            <td>{{ $sewing_qty }}</td>
            <td>{{ $sewing_balance }}</td>
            <td>{{ $previous_iron_qty }}</td>
            <td>{{ $today_iron_qty }}</td>
            <td>{{ $total_iron_qty }}</td>
            <td>{{ $iron_balance }}</td>
            <td>{{ $previous_poly_qty }}</td>
            <td>{{ $today_poly_qty }}</td>
            <td>{{ $total_poly_qty }}</td>
            <td>{{ $poly_balance }}</td>
            <td>{{ $previous_packing_qty }}</td>
            <td>{{ $today_packing_qty }}</td>
            <td>{{ $total_packing_qty }}</td>
            <td>{{ $today_ship_qty }}</td>
            <td>{{ $total_ship_qty }}</td>
            <td>{{ $total_rejection_qty }}</td>
            <td>&nbsp;</td>
        </tr>
    @endforeach
    <tr>
        <th colspan="4">Total</th>
        <th>{{ $g_total_color_wise_po_qty }}</th>
        <th>{{ $g_total_sewing_qty }}</th>
        <th>{{ $g_total_sewing_balance }}</th>
        <th>{{ $g_total_previous_iron_qty }}</th>
        <th>{{ $g_total_today_iron_qty }}</th>
        <th>{{ $g_total_total_iron_qty }}</th>
        <th>{{ $g_total_iron_balance }}</th>
        <th>{{ $g_total_previous_poly_qty }}</th>
        <th>{{ $g_total_today_poly_qty }}</th>
        <th>{{ $g_total_total_poly_qty }}</th>
        <th>{{ $g_total_poly_balance }}</th>
        <th>{{ $g_total_previous_packing_qty }}</th>
        <th>{{ $g_total_today_packing_qty }}</th>
        <th>{{ $g_total_total_packing_qty }}</th>
        <th>{{ $g_total_today_ship_qty }}</th>
        <th>{{ $g_total_total_ship_qty }}</th>
        <th>{{ $g_total_total_rejection_qty }}</th>
        <th>&nbsp;</th>
    </tr>
@else
    <tr>
        <th colspan="22">No Data</th>
    </tr>
@endif
</tbody>
