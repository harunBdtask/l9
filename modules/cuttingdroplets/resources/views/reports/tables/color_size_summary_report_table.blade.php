<table class="reportTable " id="fixTable">
    <thead>
    <tr style="background-color: mintcream !important;">
        <td rowspan="2"><strong>Buyer</strong></td>
        <td rowspan="2"><strong>Style</strong></td>
        <td rowspan="2"><strong>Ref. No.</strong></td>
        <td rowspan="2"><strong>PO</strong></td>
        <td rowspan="2"><strong>Cutt. Off</strong></td>
        <td rowspan="2"><strong>ShipDate</strong></td>
        <td rowspan="2"><strong>Week Status</strong></td>
        <td rowspan="2"><strong>Color</strong></td>
        <td rowspan="2"></td>
        <td style="text-align: center" colspan="{{collect($sizes)->count()}}"><strong>Sizes</strong></td>
        <td rowspan="2"><strong>Total</strong></td>
    </tr>
    <tr style="background-color: mintcream !important;">
        @foreach($sizes as $size)
            <td><strong>{{$size}}</strong></td>
        @endforeach
    </tr>
    </thead>
    <tbody>
    @php
        $grandTotal=[];
    @endphp
    @if($reports)
        @foreach($reports as $poReports)
            @foreach($poReports as $colorReports)
                @foreach($colorReports as $report)
                    
                    <tr>
                        <td>{{ $report['buyer'] }}</td>
                        <td>{{ $report['style_no'] }}</td>
                        <td>{{ $report['ref_no'] }}</td>
                        <td>{{ $report['po_no'] }}</td>
                        <td>{{ $report['cutt_off'] }}</td>
                        <td>{{ $report['ship_date'] }}</td>
                        <td>{{ $report['week_no'] }}</td>
                        <td>{{ $report['color'] }}</td>
                        <td>{{ $report['qty'] }}</td>

                        @foreach($sizes as $size)
                            @php
                                $grandTotal[$report['qty_key']][$size] =
                                    (int)($grandTotal[$report['qty_key']][$size] ?? 0) + (int)($report[$size] ?? 0);
                            @endphp
                            <td class="text-right">{{$report['qty_key']=='reject_percent' ? ($report[$size] ?? 0).'%' : $report[$size] ?? 0}}</td>
                        @endforeach

                        <td class="text-right">
                            <strong>{{ $report['qty_key']=='reject_percent' ? '' : $report['row_total'] }}</strong>
                        </td>
                    </tr>
                @endforeach

                <tr>
                    <td colspan="{{collect($sizes)->count()+9}}" style="height: 15px"></td>
                </tr>
            @endforeach
        @endforeach


        <tr class="bg-cyan">
            <td colspan="9"><strong>TOTAL ORDER QTY</strong></td>
            @foreach($sizes as $size)
                <td class="text-right"><strong>{{$grandTotal['order_qty'][$size]}}</strong></td>
            @endforeach
            <th></th>
        </tr>
        <tr class="bg-cyan">
            <td colspan="9"><strong>TOTAL CUTTING QTY</strong></td>
            @foreach($sizes as $size)
                <td class="text-right"><strong>{{$grandTotal['total_cutting'][$size]}}</strong></td>
            @endforeach
            <th></th>
        </tr>
        <tr class="bg-cyan">
            <td colspan="9"><strong>TOTAL INPUT QTY</strong></td>
            @foreach($sizes as $size)
                <td class="text-right"><strong>{{$grandTotal['total_input'][$size]}}</strong></td>
            @endforeach
            <th></th>
        </tr>
        <tr class="bg-cyan">
            <td colspan="9"><strong>TOTAL OUTPUT QTY</strong></td>
            @foreach($sizes as $size)
                <td class="text-right"><strong>{{$grandTotal['total_sewing_output'][$size]}}</strong></td>
            @endforeach
            <th></th>
        </tr>
    @endif
    </tbody>
</table>
