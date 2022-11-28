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
            <th colspan="8">{{ factoryName() }}</th>
        </tr>
        <tr>
            <th colspan="8">{{ factoryAddress() }}</th>
        </tr>
    @endif
    <tr>
        <th colspan="11">Section-1: PO Wise</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Booking No</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Print Rcv Qty</th>
        <th>Embr Rcv Qty</th>
        <th>Production Qty</th>
        <th>QC/ Del Qty</th>
        <th>T.Production Blnc</th>
        <th>T.Del Blnc</th>
        <th>Bundle Qty</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $t_print_received_qty = 0;
            $t_embr_received_qty = 0;
            $t_production_qty = 0;
            $t_qc_qty = 0;
            $t_total_production_qty = 0;
            $t_total_qc_qty = 0;
            $t_bundle_card_qty = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @php
                $print_received_qty = 0;
                $embroidery_received_qty = 0;
                $production_qty = 0;
                $qc_qty = 0;
                $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
                $booking_no = $reportByPurchaseOrder->first()->order->booking_no ?? '';
                $order_style_no = $reportByPurchaseOrder->first()->order->order_style_no ?? '';
                $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                $bundleTotalQty = $reportByPurchaseOrder->sum('bundle_card.quantity');
            $t_bundle_card_qty +=$bundleTotalQty;
            @endphp
            @foreach($reportByPurchaseOrder as $report)
                @php
                    $print_received_qty += $report->print_received_qty_sum;
                    $embroidery_received_qty += $report->embroidery_received_qty_sum;
                    $production_qty += $report->production_qty_sum;
                    $qc_qty += $report->qc_qty_sum;
                    $t_print_received_qty += $report->print_received_qty_sum;
                    $t_embr_received_qty += $report->embroidery_received_qty_sum;
                    $t_production_qty += $report->production_qty_sum;
                    $t_qc_qty += $report->qc_qty_sum;
                    $total_production = $print_received_qty + $embroidery_received_qty - $production_qty;
                    $total_del_blnc = $production_qty - $qc_qty;

                @endphp
            @endforeach
            @php
                $t_total_production_qty +=$total_production;
                $t_total_qc_qty += $total_del_blnc;
            @endphp
            <tr>
                <td>{{ $buyer_name }}</td>
                <td>{{ $booking_no }}</td>
                <td>{{ $order_style_no }}</td>
                <td>{{ $po_no }}</td>
                <td>{{ $print_received_qty }}</td>
                <td>{{ $embroidery_received_qty }}</td>
                <td>{{ $production_qty }}</td>
                <td>{{ $qc_qty }}</td>
                <td>{{ $total_production }}</td>
                <td>{{ $total_del_blnc }}</td>
                <td>{{ $bundleTotalQty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{ $t_print_received_qty }}</th>
            <th>{{ $t_embr_received_qty }}</th>
            <th>{{ $t_production_qty }}</th>
            <th>{{ $t_qc_qty }}</th>
            <th>{{ $t_total_production_qty }}</th>
            <th>{{ $t_total_qc_qty }}</th>
            <th>{{ $t_bundle_card_qty }}</th>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="11" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>

<table class="reportTable" style="border-collapse: collapse;" id="fixTable2">
    <thead>
    <tr class="tr-height">
        <th colspan="13">Section-2: Color And Size Wise</th>
    </tr>
    <tr>
        <th>Buyer</th>
        <th>Booking No</th>
        <th>Style/Order No</th>
        <th>PO</th>
        <th>Colour Name</th>
        <th>Size</th>
        <th>Print Rcv Qty</th>
        <th>Embr Rcv Qty</th>
        <th>Production Qty</th>
        <th>QC/Del Qty</th>
        <th>T.Production Blnc</th>
        <th>T.Qc Blnc</th>
        <th>Bundle Qty</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $t_print_received_qty_color = 0;
            $t_embroidery_received_qty_color = 0;
            $t_production_qty_color = 0;
            $t_qc_qty_color = 0;
            $t_total_production_qty_color = 0;
            $t_total_qc_qty_color = 0;
        $t_bundle_card_qty = 0;

        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
                @foreach($reportByColor->groupBy('size_id') as $reportBySize)
                    @php
                        $buyer_name = $reportByPurchaseOrder->first()->buyer->name ?? '';
                        $booking_no = $reportByPurchaseOrder->first()->order->booking_no ?? '';
                        $order_style_no = $reportByPurchaseOrder->first()->order->order_style_no ?? '';
                        $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                        $color = $reportBySize->first()->color->name ?? '';
                        $size = $reportBySize->first()->size->name ?? '';
                        $print_received_qty = $reportBySize->sum('print_received_qty_sum');
                        $embroidery_received_qty = $reportBySize->sum('embroidery_received_qty_sum');
                        $production_qty = $reportBySize->sum('production_qty_sum');
                        $qc_qty = $reportBySize->sum('qc_qty_sum');
                        $t_print_received_qty_color += $reportBySize->sum('print_received_qty_sum');
                        $t_embroidery_received_qty_color += $reportBySize->sum('embroidery_received_qty_sum');
                        $t_production_qty_color += $reportBySize->sum('production_qty_sum');
                        $t_qc_qty_color += $reportBySize->sum('qc_qty_sum');
                        $total_production_qty = $reportBySize->sum('print_received_qty_sum') + $reportBySize->sum('embroidery_received_qty_sum') - $production_qty;
                        $total_qc_qty = $production_qty - $qc_qty;

                        $t_total_production_qty_color +=$total_production_qty;
                         $t_total_qc_qty_color += $total_qc_qty;
                    $bundleTotalQty = $reportBySize->sum('bundle_card.quantity');
                    $t_bundle_card_qty += $bundleTotalQty;

                    @endphp
                    <tr>
                        <td>{{ $buyer_name }}</td>
                        <td>{{ $booking_no }}</td>
                        <td>{{ $order_style_no }}</td>
                        <td>{{ $po_no }}</td>
                        <td>{{ $color }}</td>
                        <td>{{ $size }}</td>
                        <td>{{ $print_received_qty }}</td>
                        <td>{{ $embroidery_received_qty }}</td>
                        <td>{{ $production_qty }}</td>
                        <td>{{ $qc_qty }}</td>
                        <td>{{ $total_production_qty }}</td>
                        <td>{{ $total_qc_qty }}</td>
                        <td>{{ $bundleTotalQty }}</td>
                    </tr>
                @endforeach
            @endforeach
        @endforeach
        @php
                @endphp

        <tr>
            <th colspan="6">Total</th>
            <th>{{ $t_print_received_qty_color }}</th>
            <th>{{ $t_embroidery_received_qty_color }}</th>
            <th>{{ $t_production_qty_color }}</th>
            <th>{{ $t_qc_qty_color }}</th>
            <th>{{ $t_total_production_qty_color }}</th>
            <th>{{ $t_total_qc_qty_color }}</th>
            <th>{{ $t_bundle_card_qty }}</th>

        </tr>
    @else
        <tr class="tr-height">
            <td colspan="13" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
<table class="reportTable" style="border-collapse: collapse;" id="fixTable3">
    <thead>
    <tr>
        <th colspan="9">Section-3: Factory Wise</th>
    </tr>
    <tr>
        <th>Factory Name</th>
        <th>Factory Address</th>
        <th>Print Rcv Qty</th>
        <th>Embr Rcv Qty</th>
        <th>Production Qty</th>
        <th>QC/ Del Qty</th>
        <th>T.Production Blnc</th>
        <th>T.Del Blnc</th>
        <th>Bundle Qty</th>

    </tr>
    </thead>
    <tbody>
    @if($reports && $reports->count())
        @php
            $t_print_received_qty_factory = 0;
            $t_embroidery_received_qty_factory = 0;
            $t_production_qty_factory = 0;
            $t_qc_qty_factory = 0;
            $t_total_production_balance_factory = 0;
            $t_total_del_balance_factory = 0;
            $t_bundle_card_qty=0;
        @endphp
        @foreach($reports->groupBy('factory_id') as $factoryGroup)
            @php
                $print_received_qty = 0;
                $embroidery_received_qty = 0;
                $production_qty = 0;
                $qc_qty = 0;
                $factory_name = $factoryGroup->first()->factory->factory_name ?? '';
                $factory_address = $factoryGroup->first()->factory->factory_address ?? '';
            $bundleTotalQty = $factoryGroup->sum('bundle_card.quantity');
            $t_bundle_card_qty += $bundleTotalQty;
            @endphp
            @foreach($factoryGroup as $factory)
                @php
                    $print_received_qty += $factory->print_received_qty_sum ?? 0;
                    $embroidery_received_qty += $factory->embroidery_received_qty_sum ?? 0;
                    $production_qty += $factory->production_qty_sum ?? 0;
                    $qc_qty += $factory->qc_qty_sum ?? 0;
                    $t_print_received_qty_factory += $factory->print_received_qty_sum ?? 0;
                    $t_embroidery_received_qty_factory += $factory->embroidery_received_qty_sum ?? 0;
                    $t_production_qty_factory += $factory->production_qty_sum ?? 0;
                    $t_qc_qty_factory += $factory->qc_qty_sum ?? 0;
                    $total_production = $print_received_qty + $embroidery_received_qty - $production_qty;
                    $total_del = $production_qty - $qc_qty;




                @endphp
            @endforeach
            @php
                $t_total_production_balance_factory +=$total_production;
                $t_total_del_balance_factory +=$total_del;
            @endphp
            <tr>
                <td>{{ $factory_name }}</td>
                <td>{{ $factory_address }}</td>
                <td>{{ $print_received_qty }}</td>
                <td>{{ $embroidery_received_qty }}</td>
                <td>{{ $production_qty }}</td>
                <td>{{ $qc_qty }}</td>
                <td>{{ $total_production }}</td>
                <td>{{ $total_del }}</td>
                <td>{{ $bundleTotalQty }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="2">Total</th>
            <th>{{ $t_print_received_qty_factory }}</th>
            <th>{{ $t_embroidery_received_qty_factory }}</th>
            <th>{{ $t_production_qty_factory }}</th>
            <th>{{ $t_qc_qty_factory }}</th>
            <th>{{ $t_total_production_balance_factory }}</th>
            <th>{{ $t_total_del_balance_factory }}</th>
            <th>{{ $t_bundle_card_qty }}</th>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="9" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
