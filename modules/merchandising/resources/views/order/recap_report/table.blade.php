{{--@if(count($pos) == 1)--}}
@foreach($orderData as $key => $item)
    <table class="reportTable" style="width: 100%">
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
        {{--        @php(dump(count(collect($item['purchase_orders'])->flatten(1))))--}}
        @foreach(collect($item['purchase_orders'])->flatten(1) as $purchaseOrder)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $item['season_name'] }}</td>
                    <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $item['factory_name'] }}</td>
                    <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $item['buyer_name'] }}</td>
                @endif
                <td>{{ $purchaseOrder['customer'] }}</td>
                @if($loop->first)
                    <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}"></td>
                    <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $item['style_name'] }}</td>
                @endif
                <td>{{ $purchaseOrder['po_no'] }}</td>
                @if($loop->first)
                    <td rowspan="{{ count(collect($item['purchase_orders'])->flatten(1)) }}">{{ $item['description'] }}</td>
                @endif
                <td>{{ $purchaseOrder['order_qty'] }}</td>
                <td>{{ $purchaseOrder['fty_fob'] }}</td>
                <td>{{ $purchaseOrder['po'] }}</td>
                <td>{{ $purchaseOrder['fty_delivery_date'] }}</td>
                <td>{{ $purchaseOrder['po_delivery_date'] }}</td>
                <td>{{ $purchaseOrder['fty_fob_value'] }}</td>
                <td>{{ $purchaseOrder['po_fob_value'] }}</td>
                <td>{{ $purchaseOrder['remarks'] }}</td>

            </tr>
        @endforeach
        </thead>
        <tbody>
        </tbody>
    </table>
    <br>
@endforeach

{{--@endif--}}


