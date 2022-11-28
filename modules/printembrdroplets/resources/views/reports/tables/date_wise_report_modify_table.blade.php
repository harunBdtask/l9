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
            <th colspan="7">{{ factoryName() }}</th>
        </tr>
        <tr>
            <th colspan="7">{{ factoryAddress() }}</th>
        </tr>
    @endif
    <tr>
        <th colspan="7">Section-1: PO Wise</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>Print Send Quantity</th>
        <th>Print Received Quantity</th>
        <th>Embroidery Send Quantity</th>
        <th>Embroidery Received Quantity</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $t_print_send_qty = 0;
            $t_print_received_qty = 0;
            $t_embr_send_qty = 0;
            $t_embr_received_qty = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @php
                $print_send_qty = 0;
                $print_received_qty = 0;
                $embroidery_send_qty = 0;
                $embroidery_received_qty = 0;
                $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
                $style = $reportByPurchaseOrder->first()->order->style_name ?? '';
                $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
            @endphp
            @foreach($reportByPurchaseOrder as $report)
                @php
                    $print_send_qty += $report->print_sent_qty_sum;
                    $print_received_qty += $report->print_received_qty_sum;
                    $embroidery_send_qty += $report->embroidery_sent_qty_sum;
                    $embroidery_received_qty += $report->embroidery_received_qty_sum;
                    $t_print_send_qty += $report->print_sent_qty_sum;
                    $t_print_received_qty += $report->print_received_qty_sum;
                    $t_embr_send_qty += $report->embroidery_sent_qty_sum;
                    $t_embr_received_qty += $report->embroidery_received_qty_sum;
                @endphp
            @endforeach
            <tr>
                <td>{{ $buyer_name }}</td>
                <td>{{ $style }}</td>
                <td>{{ $po_no }}</td>
                <td>{{ $print_send_qty }}</td>
                <td>{{ $print_received_qty }}</td>
                <td>{{ $embroidery_send_qty }}</td>
                <td>{{ $embroidery_received_qty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="3">Total</th>
            <th>{{ $t_print_send_qty }}</th>
            <th>{{ $t_print_received_qty }}</th>
            <th>{{ $t_embr_send_qty }}</th>
            <th>{{ $t_embr_received_qty }}</th>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="7" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>

<table class="reportTable" style="border-collapse: collapse;" id="fixTable2">
    <thead>
    <tr class="tr-height">
        <th colspan="9">Section-2: Color Wise</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>Colour Name</th>
        <th>Size</th>
        <th>Print Send Quantity</th>
        <th>Print Received Quantity</th>
        <th>Embroidery Send Quantity</th>
        <th>Embroidery Received Quantity</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $t_print_send_qty_color = 0;
            $t_print_received_qty_color = 0;
            $t_embroidery_send_qty_color = 0;
            $t_embroidery_received_qty_color = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
                @foreach($reportByColor->groupBy('size_id') as $reportBySize)
                    @php
                        $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
                        $style = $reportByPurchaseOrder->first()->order->style_name ?? '';
                        $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                        $color = $reportBySize->first()->color->name ?? '';
                        $size = $reportBySize->first()->size->name ?? '';

                        $print_send_qty = $reportBySize->sum('print_sent_qty_sum');
                        $print_received_qty = $reportBySize->sum('print_received_qty_sum');
                        $embroidery_send_qty = $reportBySize->sum('embroidery_sent_qty_sum');
                        $embroidery_received_qty = $reportBySize->sum('embroidery_received_qty_sum');

                        $t_print_send_qty_color += $reportBySize->sum('print_sent_qty_sum');
                        $t_print_received_qty_color += $reportBySize->sum('print_received_qty_sum');
                        $t_embroidery_send_qty_color += $reportBySize->sum('embroidery_sent_qty_sum');
                        $t_embroidery_received_qty_color += $reportBySize->sum('embroidery_received_qty_sum');
                    @endphp
                    <tr>
                        <td>{{ $buyer_name }}</td>
                        <td>{{ $style }}</td>
                        <td>{{ $po_no }}</td>
                        <td>{{ $color }}</td>
                        <td>{{ $size }}</td>
                        <td>{{ $print_send_qty }}</td>
                        <td>{{ $print_received_qty }}</td>
                        <td>{{ $embroidery_send_qty }}</td>
                        <td>{{ $embroidery_received_qty }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        <tr>
            <th colspan="5">Total</th>
            <th>{{ $t_print_send_qty_color }}</th>
            <th>{{ $t_print_received_qty_color }}</th>
            <th>{{ $t_embroidery_send_qty_color }}</th>
            <th>{{ $t_embroidery_received_qty_color }}</th>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="9" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>

<table class="reportTable" style="border-collapse: collapse;" id="fixTable3">
    <thead>
    <tr>
        <th colspan="6">Section-3: Factory Wise</th>
    </tr>
    <tr>
        <th>Factory Name</th>
        <th>Factory Address</th>
        <th>Print Send Quantity</th>
        <th>Print Received Quantity</th>
        <th>Embroidery Send Quantity</th>
        <th>Embroidery Received Quantity</th>
    </tr>
    </thead>
    <tbody>
    @if($reports && $reports->count())
        @php
            $t_print_send_qty_factory = 0;
            $t_print_received_qty_factory = 0;
            $t_embroidery_send_qty_factory = 0;
            $t_embroidery_received_qty_factory = 0;
        @endphp
        @foreach($reports->groupBy('factory_id') as $factoryGroup)
            @php
                $print_send_qty = 0;
                $print_received_qty = 0;
                $embroidery_send_qty = 0;
                $embroidery_received_qty = 0;
                $factory_name = $factoryGroup->first()->factory->factory_name ?? '';
                $factory_address = $factoryGroup->first()->factory->factory_address ?? '';
            @endphp
            @foreach($factoryGroup as $factory)
                @php
                    $print_send_qty += $factory->print_sent_qty_sum ?? 0;
                    $print_received_qty += $factory->print_received_qty_sum ?? 0;
                    $embroidery_send_qty += $factory->embroidery_sent_qty_sum ?? 0;
                    $embroidery_received_qty += $factory->embroidery_received_qty_sum ?? 0;

                    $t_print_send_qty_factory += $factory->print_sent_qty_sum ?? 0;
                    $t_print_received_qty_factory += $factory->print_received_qty_sum ?? 0;
                    $t_embroidery_send_qty_factory += $factory->embroidery_sent_qty_sum ?? 0;
                    $t_embroidery_received_qty_factory += $factory->embroidery_received_qty_sum ?? 0;
                @endphp
            @endforeach
            <tr>
                <td>{{ $factory_name }}</td>
                <td>{{ $factory_address }}</td>
                <td>{{ $print_send_qty }}</td>
                <td>{{ $print_received_qty }}</td>
                <td>{{ $embroidery_send_qty }}</td>
                <td>{{ $embroidery_received_qty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Total</th>
            <th>{{ $t_print_send_qty_factory }}</th>
            <th>{{ $t_print_received_qty_factory }}</th>
            <th>{{ $t_embroidery_send_qty_factory }}</th>
            <th>{{ $t_embroidery_received_qty_factory }}</th>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="6" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
