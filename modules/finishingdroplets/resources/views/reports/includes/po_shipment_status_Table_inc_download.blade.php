<thead>
<tr>
    <th colspan="8">
        Buyer: {{ $buyer ?? '' }}  &nbsp;&nbsp;&nbsp;
        Style/Order No: {{ $order_style_no ?? '' }}  &nbsp;&nbsp;&nbsp;
    </th>
</tr>
<tr>
    <th>Sl</th>
    <th>PO</th>
    <th>Order Qty</th>
    <th>Shipped Qty</th>
    <th>Shipment Date</th>
    <th>Shipment Status</th>
    <th>Excess/Short Qty</th>
    <th>Shipment(%)</th>
</tr>
</thead>
@if($order_report)
    <tbody class="po-shipment-report-table">
    @if(!$order_report->getCollection()->isEmpty())
        @php
            $total_order_qty = 0;
            $total_shipped_qty = 0;
        @endphp
        @foreach($order_report->getCollection() as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{$order->po_no ?? ''}}</td>
                <td>{{$order->po_quantity ?? ''}}</td>
                <td>
                    @php
                        $shipment_qty = 0;
                        $shipment_status = 'Pending';
                         if($order->shipments){
                             foreach($order->shipments as $shipment) {
                                $shipment_qty += $shipment->ship_quantity ?? 0;
                                if($shipment->status == 1){
                                    $shipment_status = 'Shipped Out';
                                }
                             }
                         }
                        $excess_short_cut = $order->po_quantity ? $shipment_qty - $order->po_quantity : $shipment_qty;
                        $excess_short_cut_percent = $order->po_quantity != 0 ? number_format(($excess_short_cut/$order->po_quantity), 4) : '';
                        $total_order_qty += $order->po_quantity ?? 0;
                        $total_shipped_qty += $shipment_qty;
                    @endphp
                    {{$shipment_qty}}
                </td>
                <td>{{date('d M, Y',strtotime($order->shipment_date))}}</td>
                <td>{{ $shipment_status }}</td>
                <td>{{$excess_short_cut}}</td>
                <td>{{$excess_short_cut_percent}}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2" align="center">Total</th>
            <th>{{$total_order_qty}}</th>
            <th>{{$total_shipped_qty}}</th>
            <th colspan="4">&nbsp;</th>
        </tr>
    @else
        <tr>
            <td colspan="8" align="center">No Data
            <td>
        </tr>
    @endif
    </tbody>
@else
    <tbody class="po-shipment-report-table">
    <tr>
        <td colspan="8" align="center">No Data</td>
    </tr>
    </tbody>
@endif