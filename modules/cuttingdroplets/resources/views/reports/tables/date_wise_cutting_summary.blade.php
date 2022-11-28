@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
{{--Order Wise Cutting Production Summary--}}
@if (isset($type) && $type == 'xls')
    <h4 align="center">Report Date: {{ date("jS F, Y", strtotime($date)) }}</h4>
@endif
<table class="reportTable" id="fixTable1">
    <thead>
    <tr>
        <th colspan="7">Order Wise Cutting Production Summary</th>
    </tr>
    <tr>
        <th>SL</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>Item</th>
        <th>PO</th>
        <th>Order Quantity</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $torder_quantity = 0;
            $tcutting_quantity = 0;
            $serial = 0;
        @endphp
        @foreach($reports->groupBy('order_id') as $reportByOrder)
            @php
                $buyer = $reportByOrder->first()->buyer->name ?? '';
                $style_name = $reportByOrder->first()->order->style_name ?? '';
                $style_torder_quantity = 0;
                $style_tcutting_quantity = 0;
            @endphp
            @foreach($reportByOrder->groupBy('garments_item_id') as $reportByItem)
                @php
                    $item = $reportByItem->first()->garmentsItem->name;
                    $item_torder_quantity = 0;
                    $item_tcutting_quantity = 0;
                @endphp
                @foreach($reportByItem->groupBy('purchase_order_id') as $reportByPurchaseOrder)
                    @php
                        $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                        $po_qty = $reportByPurchaseOrder->first()->purchaseOrder->po_quantity ?? 0;
                        $cutting_production = $reportByPurchaseOrder->sum('total_cutting_qty');

                        $item_torder_quantity += $po_qty;
                        $item_tcutting_quantity += $cutting_production;

                        $style_torder_quantity += $po_qty;
                        $style_tcutting_quantity += $cutting_production;

                        $torder_quantity += $po_qty;
                        $tcutting_quantity += $cutting_production;
                    @endphp
                    <tr>
                        <td>{{ ++$serial }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $buyer }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $style_name }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $item }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $po_no }}</td>
                        <td>{{ $po_qty }}</td>
                        <td>{{ $cutting_production }}</td>
                    </tr>
                @endforeach
                <tr style="font-weight:bold;">
                    <td colspan="5">Sub Total = {{ $item }}</td>
                    <td>{{ $item_torder_quantity }}</td>
                    <td>{{ $item_tcutting_quantity }}</td>
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="5">Sub Total = {{ $style_name }}</td>
                <td>{{ $style_torder_quantity }}</td>
                <td>{{ $style_tcutting_quantity }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="5">Total</td>
            <td>{{ $torder_quantity }}</td>
            <td>{{ $tcutting_quantity }}</td>
        </tr>
    @else
        <tr>
            <td colspan="6" class="text-danger text-center">Not found
            </td>
        </tr>
    @endif
    </tbody>
</table>

<!-- color wise -->
<table class="reportTable" id="fixTable2">
    <thead>
    <tr>
        <th colspan="8">Color Wise Cutting Production Summary</th>
    </tr>
    <tr>
        <th>SL</th>
        <th>Table</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>PO</th>
        <th>Color</th>
        <th>No. of Bundle</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="">
    @if($reports && $reports->count())
        @php
            $total_bundle_count = 0;
            $sl = 0;
        @endphp
        @foreach($reports->sortBy('cutting_table_id')->groupBy('cutting_table_id') as $reportByCuttingTable)
            @php
                $cutting_table = $reportByCuttingTable->first()->cuttingTable->table_no ?? '';
                $cutting_table_id = $reportByCuttingTable->first()->cutting_table_id;
                $table_total_bundle_count = 0;
                $table_total_cut_production = 0;
            @endphp
            @foreach($reportByCuttingTable->groupBy('purchase_order_id') as $reportByPurchaseOrder)
                @php
                    $buyer = $reportByPurchaseOrder->first()->buyer->name ?? '';
                    $style_name = $reportByPurchaseOrder->first()->order->style_name ?? '';
                    $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                    $purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id;
                @endphp
                @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
                    @php
                        $sl++;
                        $color = $reportByColor->first()->color->name ?? '';
                        $color_id = $reportByColor->first()->color_id;
                        $cutting_production = $reportByColor->sum('total_cutting_qty');
                        $bundle_count = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard::dateColorWiseBundleCount($date, $cutting_table_id, $purchase_order_id, $color_id);
                        $total_bundle_count += $bundle_count;
                        $table_total_bundle_count += $bundle_count;
                        $table_total_cut_production += $cutting_production;
                    @endphp
                    <tr>
                        <td>{{ $sl }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $cutting_table }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $buyer }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $style_name }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $po_no }}</td>
                        <td style="text-align: left; padding-left: 5px">{{ $color }}</td>
                        <td>{{ $bundle_count }}</td>
                        <td>{{ $cutting_production }}</td>
                    </tr>
                @endforeach
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="6">Total = {{ $cutting_table }}</td>
                <td>{{ $table_total_bundle_count }}</td>
                <td>{{ $table_total_cut_production }}</td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="6">Total</td>
            <td>{{ $total_bundle_count }}</td>
            <td>{{ $reports->sum('total_cutting_qty') }}</td>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="8" class="text-danger text-center">Not found
            <td>
        </tr>
    @endif
    </tbody>
</table>

<!--table wise target summary-->
<table class="reportTable" aria-describedby="example2_info" id="fixTable3">
    <thead>
    <tr>
        <th colspan="5"><b>Cutting Target Wise Cutting Production Summary</b></th>
    </tr>
    </thead>
    <thead>
    <tr>
        <th>Floor</th>
        <th>Table</th>
        <th>Target/Day</th>
        <th>Cutting Production</th>
        <th>Achievement</th>
    </tr>
    </thead>
    <tbody>
    @if($reports && $reports->count())
        @php
            $ttoday_target = 0;
            $total_cutting = 0;
        @endphp
        @foreach($reports->sortBy('cutting_table_id')->groupBy('cutting_floor_id') as $reportByCuttingFloor)
            @php
                $floor_today_target = 0;
                $floor_total_cutting = 0;
                $cutting_floor = $reportByCuttingFloor->first()->cuttingFloor->floor_no ?? '';
            @endphp
            @foreach($reportByCuttingFloor->sortBy('cutting_table_id')->groupBy('cutting_table_id') as $reportByCuttingTable)
                @php
                    $cutting_table_id = $reportByCuttingTable->first()->cutting_table_id;

                    $cutting_table = $reportByCuttingTable->first()->cuttingTable->table_no ?? '';
                    $cutting_target_per_day = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport::cuttingTarget($cutting_table_id, $date);
                    $cutting_production = $reportByCuttingTable->sum('total_cutting_qty');
                    $cutting_percentage = ($cutting_target_per_day > 0) ? (($cutting_production * 100) / $cutting_target_per_day) : 0;

                    $ttoday_target += $cutting_target_per_day;
                    $total_cutting += $cutting_production;

                    $floor_today_target += $cutting_target_per_day;
                    $floor_total_cutting += $cutting_production;
                @endphp
                <tr>
                    <td>{{ $cutting_floor }}</td>
                    <td>{{ $cutting_table }}</td>
                    <td>{{ $cutting_target_per_day }}</td>
                    <td>{{ $cutting_production }}</td>
                    <td>{{ round($cutting_percentage,2) }} %</td>
                </tr>
            @endforeach
            <tr style="font-weight:bold;">
                <td colspan="2">Total = {{ $cutting_floor }}</td>
                <td>{{ $floor_today_target }}</td>
                <td>{{ $floor_total_cutting }}</td>
                <td></td>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="2">Total</td>
            <td>{{ $ttoday_target }}</td>
            <td>{{ $total_cutting }}</td>
            <td></td>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="5" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
