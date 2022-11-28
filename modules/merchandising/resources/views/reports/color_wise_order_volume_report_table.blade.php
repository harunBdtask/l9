<table class="reportTable">
    <thead>
    <tr>
        <th><strong>SL</strong></th>
        <th><strong>Responsible</strong></th>
        <th><strong>Buyer</strong></th>
        <th><strong>Unique ID</strong></th>
        <th><strong>Style Name</strong></th>
        <th><strong>PO NO</strong></th>
        <th><strong>Booking Status</strong></th>
        <th><strong>Group</strong></th>
        <th><strong>ITEM</strong></th>
        <th><strong>SMV</strong></th>
        <th><strong>Fabric Type/GSM</strong></th>
        <th><strong>Fabric Comp.</strong></th>
        <th><strong>Color</strong></th>
        <th><strong>ORD.Qty(pcs)</strong></th>
        <th><strong>Unit Price($)</strong></th>
        <th><strong>Total Price($)</strong></th>
        <th><strong>Order Rcv Date</strong></th>
        <th><strong>Order LEAD Time</strong></th>
        <th><strong>PI BUNCH &amp; BUDGET DATE(Planned)</strong></th>
        <th><strong>PI BUNCH &amp; BUDGET DATE(Actual)</strong></th>
        <th><strong>Deviation (Days)</strong></th>
        <th><strong>Prod. LEAD Time</strong></th>
        <th><strong>Expected BOM Handover Date(Planned)</strong></th>
        <th><strong>Expected BOM Handover Date(Actual)</strong></th>
        <th><strong>Deviation (Days)</strong></th>
        <th><strong>Fri Date(Planned)</strong></th>
        <th><strong>Fri Date(Actual)</strong></th>
        <th><strong>Deviation (Days)</strong></th>
        <th><strong>RFS Date(Planned)</strong></th>
        <th><strong>RFS Date(Actual)</strong></th>
        <th><strong>Deviation (Days)</strong></th>
        <th><strong>Shipment Date(Planned)</strong></th>
        <th><strong>Shipment Date(Actual)</strong></th>
        <th><strong>Deviation (Days)</strong></th>
        <th><strong>Revised Shipment Date(Planned)</strong></th>
        <th><strong>Revised Shipment Date(Actual)</strong></th>
        <th><strong>Deviation (Days)</strong></th>
        <th><strong>Actual Shipped Qty</strong></th>
        <th><strong>Short/Excess Shipment</strong></th>
        <th><strong>Actual Shipped Value</strong></th>
        <th><strong>Short/Excess Shipment Value</strong></th>
        <th><strong>Print</strong></th>
        <th><strong>EMB</strong></th>
        <th><strong>Remarks</strong></th>
    </tr>
    </thead>
    <tbody>
    @php
        $totalPoQty = 0;
        $totalPoFob = 0;
        $totalPrice = 0;
    @endphp
    @foreach ($reportData['reports'] as $key => $groupWise)

        @foreach ($groupWise as $group => $data)
            @foreach ($data as $report)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="text-left">{{ $report['dealing_merchant'] }}</td>
                    <td class="text-left">{{ $report['buyer'] }}</td>
                    <td class="text-left">{{ $report['unique_id'] }}</td>
                    <td class="text-left">{{ $report['style'] }}</td>
                    <td class="text-left">{{ $report['po'] }}</td>
                    <td class="text-left">{{ $report['order_status'] }}</td>
                    <td class="text-left">
                        @if ($report['group'] == 1)
                            <span>Basic</span>
                        @elseif($report['group'] == 2)
                            <span>Short/Fashion</span>
                        @elseif($report['group'] == 3)
                            <span>Jogger/Fashion</span>
                        @endif
                    </td>
                    <td class="text-left">{{ collect($report['item']['details'])->pluck('item_name')->implode(', ') }}</td>
                    <td class="text-left">{{ $report['smv'] }}</td>
                    <td class="text-left">{{ $report['fab_type'] }}</td>
                    <td class="text-left">{{ $report['fabric_composition'] }}</td>
                    <td class="text-left">{{ $report['color'] }}</td>
                    <td class="text-left">{{ $report['po_qty'] }}</td>
                    <td class="text-left">${{ number_format($report['po_fob'], 2) }}</td>
                    <td class="text-left">${{ number_format($report['po_qty'] * $report['po_fob'], 2) }}</td>
                    <td class="text-left">{{ $report['order_rcv_date'] }}</td>
                    <td class="text-left">{{ $report['lead_time'] }}</td>
                    <td class="text-left">{{ $report['pi_bunch_budget_date'] }}</td>
                    <td class="text-left">{{ $report['pi_bunch_budget_actual_date'] }}</td>
                    <td class="text-left">{{ $report['pi_bunch_budget_date_deviation_days'] }}</td>
                    <td class="text-left">{{ $report['production_lead_time'] }}</td>
                    <td class="text-left">{{ $report['bom_handover_date'] }}</td>
                    <td class="text-left">{{ $report['bom_handover_actual_date'] }}</td>
                    <td class="text-left">{{ $report['order_handover_date_deviation_days'] }}</td>
                    <td class="text-left">{{ $report['fri_date'] }}</td>
                    <td class="text-left">{{ $report['fri_actual_date'] }}</td>
                    <td class="text-left">{{ $report['fri_date_deviation_days'] }}</td>
                    <td class="text-left">{{ $report['rfs_date'] }}</td>
                    <td class="text-left">{{ $report['rfs_actual_date'] }}</td>
                    <td class="text-left">{{ $report['rfs_date_deviation_days'] }}</td>
                    <td class="text-left">{{ $report['shipment_date'] }}</td>
                    <td class="text-left">{{ $report['shipment_actual_date'] }}</td>
                    <td class="text-left">{{ $report['shipment_date_deviation_days'] }}</td>
                    <td class="text-left">{{ $report['revised_shipment_date'] }}</td>
                    <td class="text-left">{{ $report['revised_shipment_actual_date'] }}</td>
                    <td class="text-left">{{ $report['revised_shipment_date_deviation_days'] }}</td>
                    <td class="text-left">{{ $report['actual_shipment_qty'] }}</td>
                    <td class="text-left">{{ $report['short_access_shipment_quantity'] }}</td>
                    <td class="text-left">{{ number_format($report['po_fob'] * $report['actual_shipment_qty'], 2) }}</td>
                    <td class="text-left">{{ number_format($report['po_fob'] * $report['short_access_shipment_quantity'], 2) }}</td>
                    <td class="text-left">
                        @if ($report['print_status'] == 1)
                            <span>Yes</span>
                        @else
                            <span>No</span>
                        @endif
                    </td>
                    <td class="text-left">
                        @if ($report['embroidery_status'] == 1)
                            <span>Yes</span>
                        @else
                            <span>No</span>
                    @endif
                    <td class="text-left">{{ $report['remarks'] }}</td>
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
                <td colspan="30" style="background-color: gainsboro">
                    <strong>Sub Total</strong>
                </td>
                <td class="text-left" style="background-color: gainsboro">
                    <strong>{{ collect($data)->sum('po_qty') }}</strong>
                </td>
                <td class="text-left" style="background-color: gainsboro">
                    <strong>${{ collect($data)->sum('po_fob') }}</strong>
                </td>
                <td class="text-left" style="background-color: gainsboro">
                    <strong>
                        ${{ collect($data)->map(function($item){
                        return $item['po_qty'] * $item['po_fob'];
                        })->sum() }}
                    </strong>
                </td>
                <td style="background-color: gainsboro" colspan="11"></td>
            </tr>
        @endforeach
        <tr>
            @php
                $totalPoQty += collect($groupWise)->collapse()->sum('po_qty');
                $totalPoFob += collect($groupWise)->collapse()->sum('po_fob');
                $totalPrice += collect($groupWise)->collapse()->map(function($item){
                    return $item['po_qty'] * $item['po_fob'];
                })->sum();
            @endphp
            <td colspan="30" style="background-color: wheat">
                <strong>
                    @if ($key == 1)
                        <span>Basic</span>
                    @elseif($key == 2)
                        <span>Short/Fashion</span>
                    @elseif($key == 3)
                        <span>Jogger/Fashion</span>
                    @endif
                    Sub Total</strong>
            </td>
            <td class="text-left" style="background-color: wheat">
                <strong>{{ number_format(collect($groupWise)->collapse()->sum('po_qty'), 2) }}</strong>
            </td>
            <td class="text-left" style="background-color: wheat">
                <strong>${{ number_format(collect($groupWise)->collapse()->sum('po_fob'), 2) }}</strong>
            </td>
            <td class="text-left" style="background-color: wheat">
                <strong>
                    ${{ number_format(
                        collect($groupWise)->collapse()->map(function($item){
                            return $item['po_qty'] * $item['po_fob'];
                        })->sum()
                    , 2) }}
                </strong>
            </td>
            <td style="background-color: wheat" colspan="11"></td>
        </tr>
    @endforeach

    <tr style="background-color: #bbbbbb">
        <td colspan="30"><strong>Total</strong></td>
        <td class="text-left"><strong>{{ number_format($totalPoQty, 2) }}</strong></td>
        <td class="text-left"><strong>${{ number_format($totalPoFob, 2) }}</strong></td>
        <td class="text-left"><strong>${{ number_format($totalPrice, 2) }}</strong></td>
        <td class="text-left" colspan="11"></td>
    </tr>

    </tbody>
</table>
