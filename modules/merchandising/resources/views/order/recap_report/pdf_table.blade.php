<style>
    table thead {
        display: table-row-group;
    }

    table, tr, td, th, tbody, thead, tfoot {
        page-break-inside: avoid !important;
    }
</style>
@php
    $chunkPurchaseOrders = array_chunk($orderData->pluck('purchase_orders')->collapse()->collapse()->toArray(), 13);
@endphp
@foreach($chunkPurchaseOrders as $chunkPurchaseOrder)
    <table class="reportTable" style="width: 100%;">
        <thead>
        <tr>
            <th>Season</th>
            <th>Factory</th>
            <th>Buyer</th>
            <th>Customer</th>
            <th>Brand</th>
            <th>Style</th>
            <th>VPO</th>
            <th>Description</th>
            <th>Order Qty <br> In Pcs</th>
            <th>FTY FOB <br> (US$/PC)</th>
            <th>PO <br> (US$/PC)</th>
            <th>PO Delivery Date</th>
            <th>FTY Delivery Date</th>
            <th>FTY FOB Value(US$)</th>
            <th>PO FOB Value(US$)</th>
            <th>REMARKS</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orderData as $key => $item)
            @foreach(collect($item['purchase_orders'])->flatten(1) as $purchaseOrder)
                <tr>
                    @if($loop->first)
                        <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $purchaseOrder['season_name'] }}</td>
                        <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $purchaseOrder['factory_name'] }}</td>
                        <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $purchaseOrder['buyer_name'] }}</td>
                    @endif
                    <td>{{ $purchaseOrder['customer'] }}</td>
                    @if($loop->first)
                        <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}"></td>
                        <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $purchaseOrder['style_name'] }}</td>
                    @endif
                    <td>{{ $purchaseOrder['po_no'] }}</td>
                    @if($loop->first)
                        <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $purchaseOrder['description'] }}</td>
                    @endif
                    <td>{{ $purchaseOrder['order_qty'] }}</td>
                    <td style="text-align: right;">{{ '$'.number_format($purchaseOrder['fty_fob'],2) }}</td>
                    <td style="text-align: right;">{{ '$'.number_format($purchaseOrder['po'],2) }}</td>
                    <td>{{ $purchaseOrder['po_delivery_date'] ? \Carbon\Carbon::make($purchaseOrder['po_delivery_date'])->toFormattedDateString() : '' }}</td>
                    <td>{{ $purchaseOrder['fty_delivery_date'] ? \Carbon\Carbon::make($purchaseOrder['fty_delivery_date'])->toFormattedDateString() : '' }}</td>
                    <td style="text-align: right;">{{ '$'.number_format($purchaseOrder['fty_fob_value'],2) }}</td>
                    <td style="text-align: right;">{{ '$'.number_format($purchaseOrder['po_fob_value'],2) }}</td>
                    <td>{{ $purchaseOrder['remarks'] }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <td colspan="8">Total</td>
            <td>{{collect($orderData)->flatten(3)->sum('order_qty')}}</td>
            <td style="text-align: right;">{{'$'.number_format(collect($orderData)->flatten(3)->sum('fty_fob'),2)}}</td>
            <td style="text-align: right;">{{'$'.number_format(collect($orderData)->flatten(3)->sum('po'),2)}}</td>
            <td></td>
            <td></td>
            <td style="text-align: right;">{{'$'.number_format(collect($orderData)->flatten(3)->sum('fty_fob_value'),2)}}</td>
            <td style="text-align: right;">{{'$'.number_format(collect($orderData)->flatten(3)->sum('po_fob_value'),2)}}</td>
            <td></td>
        </tr>
        </tbody>
    </table>
    <br>
    <br>
    <br>
@endforeach
