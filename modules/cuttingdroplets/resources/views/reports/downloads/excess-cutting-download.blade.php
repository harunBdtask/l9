<!DOCTYPE html>

<html>
<head>
    <title>Production Report</title>
    @include('reports.downloads.includes.pdf-styles')
</head>

<body>
  <div class="header-section">
    @include('reports.downloads.includes.pdf-header')
</div>
<main>
    <h4 align="center">PO Wise Excess Cutting Production Report</h4>
    <table class="reportTable" style="border: 1px solid black;border-collapse: collapse;">
        <thead>
        <tr>
            <th>SL</th>
            <th>Buyer</th>
            <th>Style</th>
            <th>PO</th>
            <th>PO Qty</th>
            <th>Today's Cutting</th>
            <th>Total Cutting</th>
            <th>Extra Qty</th>
            <th>Extra Cutting(%)</th>
        </tr>
        </thead>
        <tbody>
        @if(!$reports->getCollection()->isEmpty())
            @php
                $total_order_qty = 0;
                $todays_cutting_qty = 0;
                $total_cutting_qty = 0;
                $total_extra_qty = 0;
            @endphp
            @foreach($reports->getCollection()->groupBy('purchase_order_id') as $reportByPurchaseOrder)
                @php
                    $buyer_name = $reportByPurchaseOrder->first()->buyer->name;
                    $style_name = $reportByPurchaseOrder->first()->order->style_name ?? '';
                    $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                    $po_quantity = $reportByPurchaseOrder->first()->purchaseOrder->po_quantity;
                    $total_order_qty += $po_quantity;
                    $todays_cutting = $reportByPurchaseOrder->sum('todays_cutting') - $reportByPurchaseOrder->sum('todays_cutting_rejection');
                    $todays_cutting_qty += $todays_cutting;
                    $total_cutting = $reportByPurchaseOrder->sum('total_cutting') - $reportByPurchaseOrder->sum('total_cutting_rejection');
                    $total_cutting_qty += $total_cutting;
                    $extra_qty = $reportByPurchaseOrder->sum('total_cutting') - $reportByPurchaseOrder->sum('total_cutting_rejection') - $reportByPurchaseOrder->first()->purchaseOrder->po_quantity ?? 0;
                    $extra_cutting_percent = ($reportByPurchaseOrder->first()->purchaseOrder->po_quantity > 0) ? ((( $extra_qty) * 100) / $reportByPurchaseOrder->first()->purchaseOrder->po_quantity) : 0;
                    $total_extra_qty += $extra_qty ?? 0;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $buyer_name }}</td>
                    <td>{{ $style_name }}</td>
                    <td>{{ $po_no }}</td>
                    <td>{{ $po_quantity }}</td>
                    <td>{{ $todays_cutting }}</td>
                    <td>{{ $total_cutting }}</td>
                    <td>{{ $extra_qty }}</td>
                    <td>{{ number_format($extra_cutting_percent,2) }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="4">Total</th>
                <th>{{$total_order_qty}}</th>
                <th>{{$todays_cutting_qty}}</th>
                <th>{{$total_cutting_qty}}</th>
                <th>{{$total_extra_qty}}</th>
                <th></th>
            </tr>
        @else
            <tr class="tr-height">
                <td colspan="9" class="text-center text-danger">No Data</td>
            </tr>
        @endif
        </tbody>
        <tfoot>
        @if(!$print && $reports->total() > 15)
            <tr>
                <td colspan="9" align="center">{{ $reports->appends(request()->except('page'))->links() }}</td>
            </tr>
        @endif
        </tfoot>
    </table>
</main>
</body>
</html>
          