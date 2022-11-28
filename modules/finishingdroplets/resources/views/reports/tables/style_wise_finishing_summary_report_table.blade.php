<thead>
@if(request()->has('type') || request()->route('type'))
    <tr>
        <th colspan="21">Finishing Summary Report</th>
    </tr>
@endif
<tr>
    <th>Buyer</th>
    <th>Style/Order.</th>
    <th>Order Qty</th>
    <th>Received From Sewing</th>
    <th>Balance From Order Qty</th>
    <th>Previous Iron</th>
    <th>Today Iron</th>
    <th>Total Iron</th>
    <th>Iron Balance</th>
    <th>Previous Poly</th>
    <th>Today Poly</th>
    <th>Total Poly</th>
    <th>Poly Balance</th>
    <th>Previous Packing</th>
    <th>Today Packing</th>
    <th>Total Packing</th>
    <th>Packing Balance</th>
    <th>Total Rejection</th>
    <th>Remarks</th>
</tr>
</thead>
<tbody>
@if($reports && $reports->count())
    @php
        $total_order_qty = 0;
    @endphp
    @foreach($reports->groupBy('order_id') as $report)
        @php
            $previous_day = now()->subDay()->toDateString();
            $today = now()->toDateString();
            $orderWiseUniqueRow = $report->first();
            $order_qty = $orderWiseUniqueRow->order->po_pcs_qty ?? 0;
            $total_order_qty += $order_qty;
        @endphp
        <tr>
            <td>{{ $orderWiseUniqueRow->buyer->name }}</td>
            <td title="{{ $orderWiseUniqueRow->order->style_name ?? 'Style/Order' }}">{{ substr($orderWiseUniqueRow->order->style_name ?? 'Style/Order', 0, 12) }}</td>
            <td>{{ $order_qty }}</td>
            <td>{{ $report->sum('sewing_output_qty') }}</td>
            <td>{{ $order_qty - $report->sum('sewing_output_qty') }}</td>
            <td>{{ $report->where('production_date', $previous_day)->sum('iron_qty') }}</td>
            <td>{{ $report->where('production_date', $today)->sum('iron_qty') }}</td>
            <td>{{ $report->sum('iron_qty') }}</td>
            <td>{{ $report->sum('sewing_output_qty') - $report->sum('iron_qty') }}</td>

            <td>{{ $report->where('production_date', $previous_day)->sum('poly_qty') }}</td>
            <td>{{ $report->where('production_date', $today)->sum('poly_qty') }}</td>
            <td>{{ $report->sum('poly_qty') }}</td>
            <td>{{ $report->sum('iron_qty') - $report->sum('poly_qty') }}</td>

            <td>{{ $report->where('production_date', $previous_day)->sum('packing_qty') }}</td>
            <td>{{ $report->where('production_date', $today)->sum('packing_qty') }}</td>
            <td>{{ $report->sum('packing_qty') }}</td>
            <td>{{ $report->sum('poly_qty') - $report->sum('packing_qty') }}</td>
            <td>{{ $report->sum('poly_rejection') + $report->sum('iron_rejection_qty') + $report->sum('packing_rejection_qty') }} </td>
            <td>&nbsp;</td>
        </tr>
    @endforeach
    <tr class="font-weight-bold">
        <th colspan="2">Total</th>
        <td>{{ $total_order_qty ?? '' }}</td>
        <td>{{ $reports->sum('sewing_output_qty') }}</td>
        <td>{{ $total_order_qty - $reports->sum('sewing_output_qty') }}</td>
        <td>{{ $reports->where('production_date', $previous_day)->sum('iron_qty') }}</td>
        <td>{{ $reports->where('production_date', $today)->sum('iron_qty') }}</td>
        <td>{{ $reports->sum('iron_qty') }}</td>
        <td>{{ $reports->sum('sewing_output_qty') - $reports->sum('iron_qty') }}</td>

        <td>{{ $reports->where('production_date', $previous_day)->sum('poly_qty') }}</td>
        <td>{{ $reports->where('production_date', $today)->sum('poly_qty') }}</td>
        <td>{{ $reports->sum('poly_qty') }}</td>
        <td>{{ $reports->sum('iron_qty') - $reports->sum('poly_qty') }}</td>

        <td>{{ $reports->where('production_date', $previous_day)->sum('packing_qty') }}</td>
        <td>{{ $reports->where('production_date', $today)->sum('packing_qty') }}</td>
        <td>{{ $reports->sum('packing_qty') }}</td>
        <td>{{ $reports->sum('poly_qty') - $reports->sum('packing_qty') }}</td>
        <td>{{ $reports->sum('poly_rejection') + $reports->sum('iron_rejection_qty') + $reports->sum('packing_rejection_qty') }} </td>
        <th>&nbsp;</th>
    </tr>
@else
    <tr>
        <th colspan="21">No Data</th>
    </tr>
@endif
</tbody>
