<table>
    <thead>
    <tr>
        <td colspan="20"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">{{ factoryName() }}</td>
    </tr>
    <tr>
        <td colspan="20"
            style="text-align: center; font-weight: bold; font-size: 20px; height: 35px">Buyer Name : {{ $reportData->buyer }}</td>
    </tr>
    <tr>
        <td colspan="20"
            style="text-align: center;height: 35px">
            <b>Color Wise Order Volume Report</b>
        </td>
    </tr>
    </thead>
</table>

<table class="reportTable">

    <thead>
    <tr>
        {{-- <th><b>SL</b></th> --}}
        <th><b>Merchant</b></th>
        <th><b>Group</b></th>
        <th><b>Buyer</b></th>
        <th><b>Color</b></th>
        <th><b>Style/Order No</b></th>
        <th><b>PO NO</b></th>
        <th><b>ITEM</b></th>
        <th><b>Fabric Type/GSM</b></th>
        <th><b>Fabric Composition</b></th>
        <th><b>Color</b></th>
        <th><b>ORD.Qty(pcs)</b></th>
        <th><b>Unit Price</b></th>
        <th><b>Total Price</b></th>
        <th><b>Order Rcv Date</b></th>
        <th><b>Fri Date</b></th>
        <th><b>X-Fty Date</b></th>
        <th><b>H/ OAT CTG</b></th>
        <th><b>LEAD Time</b></th>
        <th><b>Print</b></th>
        <th><b>EMB</b></th>
        <th><b>Remarks</b></th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalPoQty = 0;
        $totalPoFob = 0;
        $totalPrice = 0;
    @endphp
    @foreach ($reportData['reports'] as $key => $data)
   
        @foreach ($data as $report)
            <tr>
                {{-- <td class="text-center">{{ $loop->iteration }}</td> --}}
                <td class="text-center">{{ $report['dealing_merchant'] }}</td>
                <td class="text-center">{{ $report['group'] }}</td>
                <td class="text-center">{{ $report['buyer'] }}</td>
                <td class="text-center">{{ $report['color'] }}</td>
                <td class="text-center">{{ $report['style'] }}</td>
                <td class="text-center">{{ $report['po'] }}</td>
                <td class="text-center">{{ collect($report['item']['details'])->pluck('item_name')->implode(', ') }}</td>
                <td class="text-center">{{ $report['fab_type'] }}</td>
                <td class="text-center">{{ $report['fabric_composition'] }}</td>
                <td class="text-center">{{ $report['color'] }}</td>
                <td class="text-center">{{ $report['po_qty'] }}</td>
                <td class="text-center">{{ $report['po_fob'] }}</td>
                <td class="text-center">{{ $report['po_qty'] * $report['po_fob'] }}</td>
                <td class="text-center">{{ $report['order_rcv_date'] }}</td>
                <td class="text-center">{{ $report['country_ship_date'] }}</td>
                <td class="text-center">{{ $report['ex_factory_date'] }}</td>
                <td class="text-center"></td>
                <td class="text-center">{{ $report['lead_time'] }}</td>
                <td class="text-center">{{ $report['print_status'] }}</td>
                <td class="text-center">{{ $report['embroidery_status'] }}</td>
                <td class="text-center">{{ $report['remarks'] }}</td>
            </tr>

        @endforeach
        <tr>
            @php
                $totalPoQty += collect($data)->sum('po_qty');
                $totalPoFob += collect($data)->sum('po_fob');
                $totalPrice += collect($data)->map(function($item){
                    return $item['po_qty'] * $item['po_fob'];
                })->sum();
            @endphp
            <td colspan="10" style="background-color: gainsboro"><b> Sub Total</b></td>
            <td style="background-color: gainsboro">{{ collect($data)->sum('po_qty') }}</td>
            <td style="background-color: gainsboro">{{ collect($data)->sum('po_fob') }}</td>
            <td style="background-color: gainsboro">{{ collect($data)->map(function($item){
                return $item['po_qty'] * $item['po_fob'];
            })->sum() }}</td>
            <td style="background-color: gainsboro" colspan="8"></td>
        </tr>
    @endforeach

    <tr>
        <td colspan="10" >Total</td>
        <td>{{ $totalPoQty }}</td>
        <td>{{ $totalPoFob }}</td>
        <td>{{ $totalPrice }}</td>
        <td colspan="8"></td>
    </tr>

    </tbody>
</table>
