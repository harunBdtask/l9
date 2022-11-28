<table class="reportTable">
    <thead>
    <tr style="background:aliceblue">
        <th>#</th>
        <th>BUYER</th>
        <th>REFERENCE NO</th>
        <th>ORDER/STYLE</th>
        <th>PCD DATE</th>
        <th>PO NO</th>
        <th>EX-FACTORY DATE</th>
        <th>GMTS. ITEM</th>
        <th>MERCHANDISER</th>
        <th>FACTORY</th>
        <th>FOB</th>
        <th>QTY</th>
        <th>ITEM UOM</th>
        <th>TOTAL FOB</th>
        <th>PCD REMARKS</th>
        <th>IE REMARKS</th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalQty = 0;
        $totalFob = 0;
    @endphp
    @foreach($reportData as $date => $monthWiseData)
        @foreach($monthWiseData as $data)
            @php
                $monthWiseTotal = collect($monthWiseData)->sum('quantity');
                $monthWiseTotalFob = collect($monthWiseData)->sum('total_fob');
                $totalQty += $monthWiseTotal;
                $totalFob += $monthWiseTotalFob;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="text-left">{{ $data['buyer'] }}</td>
                <td class="text-left">{{ $data['order_ref_no'] }}</td>
                <td class="text-left">{{ $data['order_no'] }}</td>
                <td class="text-left">{{ $data['pcd_date'] ? date('d-m-Y', strtotime($data['pcd_date'])) : '' }}</td>
                <td class="text-left">{{ $data['po_no'] }}</td>
                <td class="text-left">{{ $data['ex_factory_date'] }}</td>
                <td>{{ $data['garments_item'] }}</td>
                <td>{{ $data['merchandiser'] }}</td>
                <td>{{ $data['factory'] }}</td>
                <td class="text-right">{{ number_format($data['unit_price'], 2) }}</td>
                <td class="text-right">{{ number_format($data['quantity'], 2) }}</td>
                <td class="text-left">{{ $data['uom'] }}</td>
                <td class="text-right">{{ number_format($data['total_fob'], 2) }}</td>
                <td class="text-left">{{ $data['pcd_remarks'] }}</td>
                <td class="text-left">{{ $data['ie_remarks'] }}</td>
            </tr>
            @if($loop->last)
                <tr style="background: #e9e9e9">
                    <td colspan="11" class="text-right"><b>SUB-TOTAL</b></td>
                    <td class="text-right"><b>{{ number_format($monthWiseTotal, 2) }}</b></td>
                    <td></td>
                    <td class="text-right"><b>{{ number_format($monthWiseTotalFob, 2) }}</b></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
        @endforeach
    @endforeach
    <tr>
        <td colspan="16">&nbsp;</td>
    </tr>
    <tr style="background: #e9e9e9">
        <td colspan="11" class="text-right"><b>TOTAL</b></td>
        <td class="text-right"><b>{{ number_format($totalQty, 2) }}</b></td>
        <td></td>
        <td class="text-right"><b>{{ number_format($totalFob, 2) }}</b></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
