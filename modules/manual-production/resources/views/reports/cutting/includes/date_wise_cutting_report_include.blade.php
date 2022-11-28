{{--Order Wise Cutting Production Summary--}}
<table class="reportTable tableHeadColor" cellpadding="2">
    <thead>
    <tr align="center" style="background-color: #c8f6c2;">
        <th colspan="6"><b>Order Wise Cutting Production Summary</b></th>
    </tr>
    <tr align="center" style="background-color: #c8f6c2;">
        <th>SL</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>Order Quantity</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if(!empty($reports))
        @php
            $torder_quantity = 0;
            $tcutting_quantity = 0;
            $order_wise_sl = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @php
                $cutting_production = 0;

                $buyer_name = $reportByPurchaseOrder->first()->buyer->name;
                $style_name = $reportByPurchaseOrder->first()->order->style_name;
                $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no;
                $po_qty = $reportByPurchaseOrder->first()->purchaseOrder->po_quantity;
                $cutting_production = $reportByPurchaseOrder->sum('production_qty');

                $torder_quantity += $po_qty;
                $tcutting_quantity += $cutting_production;
            @endphp
            <tr>
                <td>{{ ++$order_wise_sl }}</td>
                <td>{{ $buyer_name }}</td>
                <td>{{ $style_name }}</td>
                <td>{{ $po_no }}</td>
                <td>{{ $po_qty }}</td>
                <td>{{ $cutting_production }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="4">Total</td>
            <td>{{ $torder_quantity }}</td>
            <td>{{ $tcutting_quantity }}</td>
        </tr>
    @else
        <tr>
            <td colspan="6" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
<p>&nbsp;</p>
<!-- color wise -->
<table class="reportTable tableHeadColor" cellpadding="2">
    <thead>
    <tr align="center" style="background-color: #c8f6c2;">
        <th colspan="6"><b>Color Wise Cutting Production Summary</b></th>
    </tr>
    <tr align="center" style="background-color: #c8f6c2;">
        <th>SL</th>
        <th>Buyer</th>
        <th>Order/Style</th>
        <th>PO</th>
        <th>Color</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="">
    @if(!empty($reports))
        @php
            $tcutting_quantity_color = 0;
            $color_sl = 0;
        @endphp
        @foreach($reports->groupBy('purchase_order_id') as $reportByPurchaseOrder)
            @php
                $buyer_name = $reportByPurchaseOrder->first()->buyer->name;
                $style_name = $reportByPurchaseOrder->first()->order->style_name;
                $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no;
            @endphp
            @foreach($reportByPurchaseOrder->groupBy('color_id') as $key => $reportByColor)
                @php
                    $cutting_color_wise_production = $reportByColor->sum('production_qty');
                    $tcutting_quantity_color += $cutting_color_wise_production;
                    $color = $reportByColor->first()->color->name;
                @endphp
                <tr>
                    <td>{{ ++$color_sl }}</td>
                    <td>{{ $buyer_name }}</td>
                    <td>{{ $style_name }}</td>
                    <td>{{ $po_no }}</td>
                    <td>{{ $color }}</td>
                    <td>{{ $cutting_color_wise_production }}</td>
                </tr>
            @endforeach
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="5">Total</td>
            <td>{{ $tcutting_quantity_color }}</td>
        </tr>
    @else
        <tr>
            <td colspan="6" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
<p>&nbsp;</p>
<!--table wise target summary-->
<table class="reportTable tableHeadColor" cellpadding="2">
    <thead>
    <tr align="center" style="background-color: #c8f6c2;">
        <th colspan="3"><b>Cutting Target Wise Cutting Production Summary</b></th>
    </tr>
    <tr align="center" style="background-color: #c8f6c2;">
        <th>Floor</th>
        <th>Table</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody>
    @if(!empty($reports) && !$subcontract_factory_id)
        @php
            $total_cutting = 0;
        @endphp
        @foreach($reports->groupBy('cutting_table_id') as $reportByTable)
            @php
                $cutting_floor = $reportByTable->first()->cuttingFloor->floor_no;
                $cutting_table = $reportByTable->first()->cuttingTable->table_no;
                $cutting_qty = $reportByTable->sum('production_qty');
                $total_cutting += $cutting_qty;
            @endphp
            <tr>
                <td>{{ $cutting_floor }}</td>
                <td>{{ $cutting_table }}</td>
                <td>{{ $cutting_qty }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="2">Total</td>
            <td>{{ $total_cutting }}</td>
        </tr>
    @elseif(!empty($reports) && $subcontract_factory_id)
        @php
            $total_cutting = 0;
        @endphp
        @foreach($reports->groupBy('sub_cutting_table_id') as $reportByTable)
            @php
                $cutting_floor = $reportByTable->first()->subCuttingFloor->floor_name;
                $cutting_table = $reportByTable->first()->subCuttingTable->table_name;
                $cutting_qty = $reportByTable->sum('production_qty');
                $total_cutting += $cutting_qty;
            @endphp
            <tr>
                <td>{{ $cutting_floor }}</td>
                <td>{{ $cutting_table }}</td>
                <td>{{ $cutting_qty }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="2">Total</td>
            <td>{{ $total_cutting }}</td>
        </tr>
    @else
        <tr>
            <td colspan="3" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
