@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
<table class="reportTable" style="border-collapse: collapse;" id="fixTable1">
    <thead>
    @if(request('type') === 'xls')
        <tr>
            <th colspan="6" style="font-size: 14px; font-weight: bold">Report Date: {{ $from_date ?? '' }}
                to {{ $to_date ?? '' }}</th>
        </tr>
    @endif
    <tr>
        <th colspan="9" style="font-size: 14px; font-weight: bold">Section-1 : Order Wise Iron, Poly &amp; Packing
            Report
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order</th>
        <th>PO</th>
        <th>Iron</th>
        <th>Iron Rejection Qty</th>
        <th>Poly</th>
        <th>Poly Rejection Qty</th>
        <th>Packing</th>
        <th>Packing Rejection Qty</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports)
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @php
                $singlePO = $reportByPurchaseOrder->first();
            @endphp
            @if ($reportByPurchaseOrder->sum('iron_qty')
                    || $reportByPurchaseOrder->sum('iron_rejection_qty')
                    || $reportByPurchaseOrder->sum('poly_qty')
                    || $reportByPurchaseOrder->sum('poly_rejection')
                    || $reportByPurchaseOrder->sum('packing_qty')
                    || $reportByPurchaseOrder->sum('packing_rejection_qty'))
                <tr>
                    <td>{{ $singlePO->buyer->name ?? 'N/A' }}</td>
                    <td>{{ $singlePO->order->style_name ?? 'N/A' }}</td>
                    <td>{{ $singlePO->purchaseOrder->po_no ?? 'N/A' }}</td>
                    <td>{{ $reportByPurchaseOrder->sum('iron_qty') }}</td>
                    <td>{{ $reportByPurchaseOrder->sum('iron_rejection_qty') }}</td>
                    <td>{{ $reportByPurchaseOrder->sum('poly_qty') }}</td>
                    <td>{{ $reportByPurchaseOrder->sum('poly_rejection') }}</td>
                    <td>{{ $reportByPurchaseOrder->sum('packing_qty') }}</td>
                    <td>{{ $reportByPurchaseOrder->sum('packing_rejection_qty') }}</td>
                </tr>
            @endif
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th>{{ $reports->sum('iron_qty') }}</th>
            <th>{{ $reports->sum('iron_rejection_qty') }}</th>
            <th>{{ $reports->sum('poly_qty') }}</th>
            <th>{{ $reports->sum('poly_rejection') }}</th>
            <th>{{ $reports->sum('packing_qty') }}</th>
            <th>{{ $reports->sum('packing_rejection_qty') }}</th>
        </tr>
    @else
        <tr>
            <td colspan="9" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

<table class="reportTable" style="border-collapse: collapse;" id="fixTable2">
    <thead>
    <tr>
        <th colspan="10" style="font-size: 14px; font-weight: bold">Section-2: Color Wise Iron, Poly &amp; Packing
            Report
        </th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order</th>
        <th>PO</th>
        <th>Color</th>
        <th>Iron</th>
        <th>Iron Rejection Qty</th>
        <th>Poly</th>
        <th>Poly Rejection Qty</th>
        <th>Packing</th>
        <th>Packing Rejection Qty</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports)
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
                @php
                    $singleColor = $reportByColor->first();
                @endphp
                @if ($reportByColor->sum('iron_qty')
                    || $reportByColor->sum('iron_rejection_qty')
                    || $reportByColor->sum('poly_qty')
                    || $reportByColor->sum('poly_rejection')
                    || $reportByColor->sum('packing_qty')
                    || $reportByColor->sum('packing_rejection_qty'))
                    <tr>
                        <td>{{ $singleColor->buyer->name ?? 'N/A' }}</td>
                        <td>{{ $singleColor->order->booking_no ?? 'N/A' }}</td>
                        <td>{{ $singleColor->purchaseOrder->po_no ?? 'N/A' }}</td>
                        <td>{{ $singleColor->color->name ?? 'N/A' }}</td>
                        <td>{{ $reportByColor->sum('iron_qty') }}</td>
                        <td>{{ $reportByColor->sum('iron_rejection_qty') }}</td>
                        <td>{{ $reportByColor->sum('poly_qty') }}</td>
                        <td>{{ $reportByColor->sum('poly_rejection') }}</td>
                        <td>{{ $reportByColor->sum('packing_qty') }}</td>
                        <td>{{ $reportByColor->sum('packing_rejection_qty') }}</td>
                    </tr>
                @endif
            @endforeach
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{ $reports->sum('iron_qty') }}</th>
            <th>{{ $reports->sum('iron_rejection_qty') }}</th>
            <th>{{ $reports->sum('poly_qty') }}</th>
            <th>{{ $reports->sum('poly_rejection') }}</th>
            <th>{{ $reports->sum('packing_qty') }}</th>
            <th>{{ $reports->sum('packing_rejection_qty') }}</th>
        </tr>
    @else
        <tr>
            <td colspan="9" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

{{-- <table class="reportTable" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th colspan="4" style="font-size: 14px; font-weight: bold">Section-3: Factory Wise</th>
        </tr>
        <tr>
            <th>Factory Name</th>
            <th>Poly</th>
            <th>Cartoon</th>
            <th>Short/Reject</th>
        </tr>
    </thead>
    <tbody>
    @if($reports)
        @foreach($reports->groupBy('factory_id') as $reportByFactory)
            @php
                $singleFactory = $reportByFactory->first();
            @endphp
            <tr>
                <td>{{ $singleFactory->factory->factory_name ?? 'N/A' }}</td>
                <td>{{ $reportByFactory->sum('poly_qty') }}</td>
                <td>{{ $reportByFactory->sum('total_cartoon') }}</td>
                <td>{{ $reportByFactory->sum('poly_rejection') }}</td>
            </tr>
        @endforeach
        <tr>
            <th>Total</th>
            <th>{{ $reports->sum('poly_qty') }}</th>
            <th>{{ $reports->sum('total_cartoon') }}</th>
            <th>{{ $reports->sum('poly_rejection') }}</th>
        </tr>
    @else
        <tr>
            <td colspan="4" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table> --}}
