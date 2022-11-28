@php
    $tableHeadColorClass = 'tableHeadColor';
    if (isset($type) || request()->has('type') || request()->route('type')) {
      $tableHeadColorClass = '';
    }
@endphp
@if (isset($type) && $type == 'xls')
    <h5 align="center">
        @if(isset($from_date) && isset($to_date))
            Reported Date From {{ date("jS F, Y", strtotime($from_date)).' To '. date("jS F, Y", strtotime($to_date)) }}
        @endif
    </h5>
@endif
<table class="reportTable" id="fixTable">
    <thead>
    <tr>
        <th colspan="8">PO Wise Cutting Production Summary</th>
    </tr>
    <tr>
        <th>SL</th>
        <th>Table</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>Item</th>
        <th>PO</th>
        <th>PO Quantity</th>
        <th>Cutting Production</th>
    </tr>
    </thead>
    <tbody class="color-wise-report">
    @if($reports && $reports->count())
        @php
            $torder_quantity = 0;
            $tcutting_quantity = 0;
            $sl = 0;
        @endphp
        @foreach($reports->sortBy('cutting_table_id')->groupBy('order_id') as $reportByOrder)
            @php
                $style_name = $reportByOrder->first()->order->style_name;
                $item = $reportByOrder->first()->garmentsItem->name;
                $booking_wise_total_production = 0;
            @endphp
            @foreach($reportByOrder->sortBy('cutting_table_id') as $report)
                @php
                    $sl++;
                    $cutting_production = 0;
                    $cutting_table = $report->cuttingTable->table_no;
                    $buyer = $report->buyer->name;
                    $po_no = $report->purchaseOrder->po_no;
                    $po_qty = $report->purchaseOrder->po_quantity;
                    $cutting_production = $report->total_cutting_qty;

                    $booking_wise_total_production += $cutting_production;
                    $tcutting_quantity += $cutting_production;
                @endphp
                <tr>
                    <td>{{ $sl }}
                    <td>{{ $cutting_table }}</td>
                    <td>{{ $buyer }}</td>
                    <td>{{ $style_name }}</td>
                    <td>{{ $item }}</td>
                    <td>{{ $po_no }}</td>
                    <td>{{ $po_qty }}</td>
                    <td>{{ $cutting_production }}</td>
                </tr>
            @endforeach
            <tr>
                <th colspan="7">Total = {{ $style_name }}</th>
                <th>{{ $booking_wise_total_production }}</th>
            </tr>
        @endforeach
        <tr style="font-weight:bold;">
            <td colspan="7">Grand Total</td>
            <td>{{ $tcutting_quantity }}</td>
        </tr>
    @else
        <tr class="tr-height">
            <td colspan="8" class="text-danger text-center">Not found</td>
        </tr>
    @endif
    </tbody>
</table>
