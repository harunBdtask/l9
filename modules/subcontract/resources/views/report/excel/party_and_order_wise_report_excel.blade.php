<table>
    <thead>
    <tr>
        <td colspan="18"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="18"
            style="text-align: center;height: 35px">
            <b>Party And Order Wise Report</b>
        </td>
    </tr>
    </thead>
</table>
<table class="reportTable">
    <thead>
    <tr>
        <th><b>SL</b></th>
        <th><b>Party Name</b></th>
        <th><b>Sales Order No</b></th>
        <th><b>Order Receive Date</b></th>
        <th><b>Fab Description</b></th>
        <th><b>Fab Color</b></th>
        <th><b>Order Qty</b></th>
        <th><b>Order Value (BDT)</b></th>
        <th><b>Grey Rcv Qty</b></th>
        <th><b>Grey Rcv Balance</b></th>
        <th><b>T Grey Issue to Dye</b></th>
        <th><b>Batch Grey Balance</b></th>
        <th><b>T Batch Qty</b></th>
        <th><b>T Dye Production Qty</b></th>
        <th><b>T Dye Finish Qty</b></th>
        <th><b>T Delivery QTY</b></th>
        <th><b>T Bill Value</b></th>
    </tr>
    </thead>
    <tbody>
        
        @foreach ($orderDetails as $key=>$order)

        @foreach ($order->subTextileOrderDetails as $details)
            <tr>
                @if ($loop->first)
                @php
                    $rowSpan = $order->subTextileOrderDetails->count();
                @endphp
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $key+1 }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->supplier->name }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->order_no }}</td>
                <td class="text-center" rowspan="{{ $rowSpan }}">{{ $order->receive_date }}</td>
                @endif

                <td class="text-center">{{ $details->fabric_description }}</td>
                <td class="text-center">{{ $details->color->name }}</td>
                <td class="text-center">{{ $details->order_qty }}</td>
                <td class="text-center">{{ $details->total_value }}</td>
                <td class="text-center">{{ $details->subGreyStoreReceiveDetail->receive_qty }}</td>
                <td class="text-center">{{ $details->order_qty - $details->subGreyStoreReceiveDetail->receive_qty }}</td>
                <td class="text-center">{{ $details->subGreyStoreIssueDetail->issue_qty }}</td>
                <td class="text-center">{{ $details->subGreyStoreReceiveDetail->receive_qty - $details->subGreyStoreIssueDetail->issue_qty }}</td>
                <td class="text-center">{{ $details->subDyeingBatchDetail->issue_qty }}</td>
                <td class="text-center">{{ $details->subDyeingProductionDetails->dyeing_production_qty }}</td>
                <td class="text-center">{{ $details->subDyeingFinishingProductionDetail->total_finish_qty }}</td>
                <td class="text-center">{{ $details->subDyeingGoodsDeliveryDetail->delivery_qty }}</td>
                <td class="text-center"></td>
            </tr>
            @endforeach
            
            @endforeach
    </tbody>
</table>