<thead>
<tr>
    <th>Style/Order No</th>
    <th>PO</th>
    <th>Order Qty</th>
    <th>Cut. Production</th>
    <th>Today's Output</th>
    <th>Total Output</th>
    <th>Today's Sent</th>
    <th>Total Sent</th>
    <th>Today's Received</th>
    <th>Total Received</th>
    <th>Total Rejection</th>
</tr>
</thead>
<tbody>
@if(!empty($reportdata))
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
    @foreach($reportdata->groupBy('purchase_order_id') as $reportByOrder)
        @php
            $buyer_name = $reportByOrder->first()->buyer->name ?? '';
            $order_style_no = $reportByOrder->first()->order->order_style_no ?? 'Style';
            $po_no = $reportByOrder->first()->purchaseOrder->po_no ?? '';
            $order_qty = $reportByOrder->first()->purchaseOrder->po_quantity ?? 0;

            $total_cutting_of_order = 0;
            $total_cutting_rejection_of_order = 0;
            $total_sent_of_order = 0;
            $total_received_of_order = 0;
            $total_print_rejection_of_order = 0;
            $todays_input_of_order = 0;
            $total_input_of_order = 0;
            $todays_sewing_output_of_order = 0;
            $total_sewing_output_of_order = 0;
            $total_sewing_rejection_of_order = 0;
            $todays_wash_sent_of_order = 0;
            $total_wash_sent_of_order = 0;
            $todays_washing_received_of_order = 0;
            $total_washing_received_of_order = 0;
            $total_washing_rejection_of_order = 0;
            $total_rejection_qty = 0;
            foreach($reportByOrder as $report) {
               $total_cutting_of_order += $report->total_cutting - $report->total_cutting_rejection ?? 0;
               $total_cutting_rejection_of_order += $report->total_cutting_rejection ?? 0;
               $total_sent_of_order += $report->total_sent ?? 0;
               $total_received_of_order += $report->total_received ?? 0;
               $total_print_rejection_of_order += $report->total_print_rejection ?? 0;
               $todays_input_of_order += $report->todays_input ?? 0;
               $total_input_of_order += $report->total_input ?? 0;
               $todays_sewing_output_of_order += $report->todays_sewing_output ?? 0;
               $total_sewing_output_of_order += $report->total_sewing_output ?? 0;
               $total_sewing_rejection_of_order += $report->total_sewing_rejection ?? 0;
               $todays_wash_sent_of_order += $report->todays_washing_sent ?? 0;
               $total_wash_sent_of_order += $report->total_washing_sent ?? 0;
               $todays_washing_received_of_order += $report->todays_washing_received ?? 0;
               $total_washing_received_of_order += $report->total_washing_received ?? 0;
               $total_washing_rejection_of_order += $report->total_washing_rejection ?? 0;
            }
            $total_order_qty += $order_qty;
            $total_rejection_qty = $total_cutting_rejection_of_order + $total_print_rejection_of_order + $total_sewing_rejection_of_order + $total_washing_rejection_of_order;
            $grand_total_cutting_qty += $total_cutting_of_order;
            $grand_todays_input_qty += $todays_input_of_order;
            $grand_total_input_qty += $total_input_of_order;
            $grand_todays_sewing_output_qty += $todays_sewing_output_of_order;
            $grand_total_sewing_output_qty += $total_sewing_output_of_order;
            $grand_todays_washing_sent_qty += $todays_wash_sent_of_order;
            $grand_total_washing_sent_qty += $total_wash_sent_of_order;
            $grand_todays_washing_received_qty += $todays_washing_received_of_order;
            $grand_total_washing_received_qty += $total_washing_received_of_order;
            $grand_total_rejection_qty += $total_rejection_qty;

        @endphp
        <tr>
            <td>{{$order_style_no}}</td>
            <td>{{$po_no}}</td>
            <td>{{$order_qty}}</td>
            <td>{{$total_cutting_of_order}}</td>
            <td>{{$todays_sewing_output_of_order}}</td>
            <td>{{$total_sewing_output_of_order}}</td>
            <td>{{$todays_wash_sent_of_order}}</td>
            <td>{{$total_wash_sent_of_order}}</td>
            <td>{{$todays_washing_received_of_order}}</td>
            <td>{{$total_washing_received_of_order}}</td>
            <td>{{$total_rejection_qty}}</td>
        </tr>
    @endforeach
    <tr>
        <th colspan="2">Total</th>
        <th>{{ $total_order_qty }}</th>
        <th>{{ $grand_total_cutting_qty }}</th>
        <th>{{ $grand_todays_sewing_output_qty }}</th>
        <th>{{ $grand_total_sewing_output_qty }}</th>
        <th>{{ $grand_todays_washing_sent_qty }}</th>
        <th>{{ $grand_total_washing_sent_qty }}</th>
        <th>{{ $grand_todays_washing_received_qty }}</th>
        <th>{{ $grand_total_washing_received_qty }}</th>
        <th>{{ $grand_total_rejection_qty }}</th>
    </tr>
    @if($reportdata->total() > 18 && $print == 0)
        <tr>
            <td colspan="11" align="center">{{ $reportdata->appends(request()->except('page'))->links() }}</td>
        </tr>
    @endif
@else
    <tr>
        <td colspan="14" align="center">No Data</td>
    </tr>
@endif
</tbody>