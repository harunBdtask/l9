<table class="reportTable" style="border-collapse: collapse;">
    <thead>
    @if(request('type') === 'xls')
        <tr>
            <th colspan="6">{{ factoryName() }}</th>
        </tr>
        <tr>
            <th colspan="6">{{ factoryAddress() }}</th>
        </tr>
    @endif
    <tr>
        <th colspan="5">Section-1: PO Wise</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Print Send Quantity</th>
        <th>Embroidery Send Quantity</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $t_print_send_qty = 0;
            $t_embr_send_qty = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @php
                $print_send_qty = 0;
                $embroidery_send_qty = 0;
                $buyer_name = $reportByPurchaseOrder->first()->buyerWithoutGlobalScopes->name ?? '';
                $order_style_no = $reportByPurchaseOrder->first()->orderWithoutGlobalScopes->order_style_no ?? '';
                $po_no = $reportByPurchaseOrder->first()->purchaseOrderWithoutGlobalScopes->po_no ?? '';
            @endphp
            @foreach($reportByPurchaseOrder as $report)
                @php
                    $print_send_qty += $report->print_sent_qty_sum;
                    $embroidery_send_qty += $report->embroidary_sent_qty_sum;
                    $t_print_send_qty += $report->print_sent_qty_sum;
                    $t_embr_send_qty += $report->embroidary_sent_qty_sum;
                @endphp
            @endforeach
            <tr>
                <td>{{ $buyer_name }}</td>
                <td>{{ $order_style_no }}</td>
                <td>{{ $po_no }}</td>
                <td>{{ $print_send_qty }}</td>
                <td>{{ $embroidery_send_qty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th>{{ $t_print_send_qty }}</th>
            <th>{{ $t_embr_send_qty }}</th>
        </tr>
    @else
        <tr>
            <td colspan="5" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

<table class="reportTable" style="border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="6">Section-2: Color Wise</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Colour Name</th>
        <th>Print Send Quantity</th>
        <th>Embroidery Send Quantity</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $t_print_send_qty_color = 0;
            $t_embroidery_send_qty_color = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
                @php
                    $buyer_name = $reportByPurchaseOrder->first()->buyerWithoutGlobalScopes->name ?? '';
                    $order_style_no = $reportByPurchaseOrder->first()->orderWithoutGlobalScopes->order_style_no ?? '';
                    $po_no = $reportByPurchaseOrder->first()->purchaseOrderWithoutGlobalScopes->po_no ?? '';
                    $color = $reportByColor->first()->colorWithoutGlobalScopes->name ?? '';
                    $print_send_qty = 0;
                    $embroidery_send_qty = 0;
                @endphp
                @foreach($reportByColor as $report)
                    @php
                        $print_send_qty += $report->print_sent_qty_sum;
                        $embroidery_send_qty += $report->embroidary_sent_qty_sum;

                        $t_print_send_qty_color += $report->print_sent_qty_sum;
                        $t_embroidery_send_qty_color += $report->embroidary_sent_qty_sum;
                    @endphp
                @endforeach
                <tr>
                    <td>{{ $buyer_name }}</td>
                    <td>{{ $order_style_no }}</td>
                    <td>{{ $po_no }}</td>
                    <td>{{ $color }}</td>
                    <td>{{ $print_send_qty }}</td>
                    <td>{{ $embroidery_send_qty }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{ $t_print_send_qty_color }}</th>
            <th>{{ $t_embroidery_send_qty_color }}</th>
        </tr>
    @else
        <tr>
            <td colspan="6" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

<table class="reportTable" style="border-collapse: collapse;">
    <thead>
    <tr>
        <th colspan="4">Section-3: Factory Wise</th>
    </tr>
    <tr>
        <th>Factory Name</th>
        <th>Factory Address</th>
        <th>Print Send Quantity</th>
        <th>Embroidery Send Quantity</th>
    </tr>
    </thead>
    <tbody>
    @if($reports && $reports->count())
        @php
            $t_print_send_qty_factory = 0;
            $t_embroidery_send_qty_factory = 0;
        @endphp
        @foreach($reports->groupBy('factory_id') as $factoryGroup)
            @php
                $print_send_qty = 0;
                $embroidery_send_qty = 0;
                $factory_name = $factoryGroup->first()->factoryWithoutGlobalScopes->factory_name ?? '';
                $factory_address = $factoryGroup->first()->factoryWithoutGlobalScopes->factory_address ?? '';
            @endphp
            @foreach($factoryGroup as $factory)
                @php
                    $print_send_qty += $factory->print_sent_qty_sum ?? 0;
                    $embroidery_send_qty += $factory->embroidary_sent_qty_sum ?? 0;
                    $t_print_send_qty_factory += $factory->print_sent_qty_sum ?? 0;
                    $t_embroidery_send_qty_factory += $factory->embroidary_sent_qty_sum ?? 0;
                @endphp
            @endforeach
            <tr>
                <td>{{ $factory_name }}</td>
                <td>{{ $factory_address }}</td>
                <td>{{ $print_send_qty }}</td>
                <td>{{ $embroidery_send_qty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Total</th>
            <th>{{ $t_print_send_qty_factory }}</th>
            <th>{{ $t_embroidery_send_qty_factory }}</th>
        </tr>
    @else
        <tr>
            <td colspan="4" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>
