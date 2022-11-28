
<div>
    <table class="reportTable">
        <thead>
        <tr class="text-center">
            <th class="text-center" colspan="13"
                style="background-color: aliceblue; height: 20px;">Party And Order Wise Report</th>
        </tr>
        <tr>
            <th><b>SL</b></th>
            <th><b>Buyer Name</b></th>
            <th><b>Sales Order No</b></th>
            <th><b>Order Receive Date</b></th>
            <th><b>Fab Description</b></th>
            <th><b>Fab Color</b></th>
            <th><b>Order Qty</b></th>
            <th><b>Order Value (BDT)</b></th>
            <th><b>T Batch Qty</b></th>
            <th><b>T Dye Production Qty</b></th>
            <th><b>T Dye Finish Qty</b></th>
            <th><b>T Delivery QTY</b></th>
            <th><b>T Bill Value</b></th>
        </tr>
        </thead>
        <tbody>
            
            @foreach ($orderDetails as $key=>$order)

            @foreach ($order->textileOrderDetails as $details)
                <tr>
                    @if ($loop->first)
                    @php
                        $rowSpan = $order->textileOrderDetails->count();
                    @endphp
                    <td class="text-center" rowspan="{{ $rowSpan }}">{{ $key+1 }}</td>
                    <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->buyer->name }}</td>
                    <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->fabric_sales_order_no }}</td>
                    <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->receive_date }}</td>
                    @endif

                    <td class="text-center">{{ $details->fabric_composition_value }}</td>
                    <td class="text-center">{{ $details->color->name }}</td>
                    <td class="text-center">{{ $details->order_qty }}</td>
                    <td class="text-center">{{ $details->total_value }}</td>
                    <td class="text-center">{{ $details->dyeingBatchDetail->order_qty }}</td>
                    <td class="text-center">{{ $details->dyeingProductionDetails->dyeing_production_qty }}</td>
                    <td class="text-center">{{ $details->dyeingFinishingProductionDetail->total_finish_qty }}</td>
                    <td class="text-center">{{ $details->dyeingGoodsDeliveryDetail->delivery_qty }}</td>
                    <td class="text-center"></td>
                </tr>
                @endforeach
                
                @endforeach
        </tbody>
    </table>
</div>
