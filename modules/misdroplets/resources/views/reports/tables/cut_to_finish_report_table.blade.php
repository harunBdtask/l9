<table class="reportTable">
    <thead>
    <tr>
        <th rowspan="2">Buyer</th>
        <th rowspan="2">Style</th>
        <th rowspan="2">Purchase Order</th>
        <th rowspan="2">Color</th>
        <th colspan="5">Cutting</th>
        <th colspan="5">Print</th>
        <th colspan="5">Embroidery</th>
        <th colspan="3">Input</th>
        <th colspan="3">Output</th>
        <th colspan="3">Iron</th>
        <th colspan="3">Poly</th>
        <th colspan="3">Packing</th>
    </tr>
    <tr>
        <th>Color Wise PO Qty</th>
        <th>Today's Cutting</th>
        <th>Total Cutting</th>
        <th>Left/Extra Qty</th>
        <th>Left/Extra %</th>
        <th>Tdy. Print Sent</th>
        <th>TTL Print sent</th>
        <th>Tdy. Print Rcv.</th>
        <th>TTL Print RCV</th>
        <th>Print Balance</th>
        <th>Tdy. Emb. Sent</th>
        <th>TTL EMB sent</th>
        <th>Tdy. Embr. Rcv.</th>
        <th>TTL EMB RCV</th>
        <th>EMB Balalnce</th>
        <th>Tdy. Input</th>
        <th>TTL Input</th>
        <th>Input Balance</th>
        <th>Tdy. Output</th>
        <th>TTL Output</th>
        <th>Output Balance</th>
        <th>Tdy Iron</th>
        <th>TTL Iron</th>
        <th>Iron Balance</th>
        <th>Tdy Poly</th>
        <th>TTL Poly</th>
        <th>Poly Balance</th>
        <th>Tdy Packing</th>
        <th>TTL Packing</th>
        <th>Packing Balance</th>
    </tr>
    </thead>
    <tbody>
    @foreach(collect($reportData)->groupBy('style') as $styleWiseData)
        @foreach($styleWiseData as $data)
            <tr>
                @if($loop->first)
                    <td rowspan="{{ $styleWiseData->count() }}">{{ $data['buyer'] }}</td>
                    <td rowspan="{{ $styleWiseData->count() }}">{{ $data['style'] }}</td>
                @endif
                <td>{{ $data['purchase_order'] }}</td>
                <td>{{ $data['color'] }}</td>
                <td>{{ $data['color_wise_po'] }}</td>
                <td>{{ $data['today_cutting'] }}</td>
                <td>{{ $data['total_cutting'] }}</td>
                <td>{{ $data['left_qty'] }}</td>
                <td>{{ $data['left_percent'] }}</td>
                <td>{{ $data['today_print_sent'] }}</td>
                <td>{{ $data['total_print_sent'] }}</td>
                <td>{{ $data['today_print_receive'] }}</td>
                <td>{{ $data['total_print_receive'] }}</td>
                <td>{{ $data['print_balance'] }}</td>
                <td>{{ $data['today_embr_sent'] }}</td>
                <td>{{ $data['total_embr_sent'] }}</td>
                <td>{{ $data['today_embr_receive'] }}</td>
                <td>{{ $data['total_embr_receive'] }}</td>
                <td>{{ $data['embr_balance'] }}</td>
                <td>{{ $data['today_input'] }}</td>
                <td>{{ $data['total_input'] }}</td>
                <td>{{ $data['input_balance'] }}</td>
                <td>{{ $data['today_output'] }}</td>
                <td>{{ $data['total_output'] }}</td>
                <td>{{ $data['output_balance'] }}</td>
                <td>{{ $data['today_iron'] }}</td>
                <td>{{ $data['total_iron'] }}</td>
                <td>{{ $data['iron_balance'] }}</td>
                <td>{{ $data['today_poly'] }}</td>
                <td>{{ $data['total_poly'] }}</td>
                <td>{{ $data['poly_balance'] }}</td>
                <td>{{ $data['today_packing'] }}</td>
                <td>{{ $data['total_packing'] }}</td>
                <td>{{ $data['packing_balance'] }}</td>
            </tr>
        @endforeach
        <tr style="background: #dcdcdc6e">
            <td colspan="4"><strong>Total</strong></td>
            <td>{{ collect($styleWiseData)->sum('color_wise_po') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_cutting') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_cutting') }}</td>
            <td>{{ collect($styleWiseData)->sum('left_qty') }}</td>
            <td> </td>
            <td>{{ collect($styleWiseData)->sum('today_print_sent') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_print_sent') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_print_receive') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_print_receive') }}</td>
            <td>{{ collect($styleWiseData)->sum('print_balance') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_embr_sent') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_embr_sent') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_embr_receive') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_embr_receive') }}</td>
            <td>{{ collect($styleWiseData)->sum('embr_balance') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_input') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_input') }}</td>
            <td>{{ collect($styleWiseData)->sum('input_balance') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_output') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_output') }}</td>
            <td>{{ collect($styleWiseData)->sum('output_balance') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_iron') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_iron') }}</td>
            <td>{{ collect($styleWiseData)->sum('iron_balance') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_poly') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_poly') }}</td>
            <td>{{ collect($styleWiseData)->sum('poly_balance') }}</td>
            <td>{{ collect($styleWiseData)->sum('today_packing') }}</td>
            <td>{{ collect($styleWiseData)->sum('total_packing') }}</td>
            <td>{{ collect($styleWiseData)->sum('packing_balance') }}</td>
        </tr>
    @endforeach
    <tr style="background: gainsboro">
        <td colspan="4"><strong>G. Total</strong></td>
        <td>{{ collect($reportData)->sum('color_wise_po') }}</td>
        <td>{{ collect($reportData)->sum('today_cutting') }}</td>
        <td>{{ collect($reportData)->sum('total_cutting') }}</td>
        <td>{{ collect($reportData)->sum('left_qty') }}</td>
        <td> </td>
        <td>{{ collect($reportData)->sum('today_print_sent') }}</td>
        <td>{{ collect($reportData)->sum('total_print_sent') }}</td>
        <td>{{ collect($reportData)->sum('today_print_receive') }}</td>
        <td>{{ collect($reportData)->sum('total_print_receive') }}</td>
        <td>{{ collect($reportData)->sum('print_balance') }}</td>
        <td>{{ collect($reportData)->sum('today_embr_sent') }}</td>
        <td>{{ collect($reportData)->sum('total_embr_sent') }}</td>
        <td>{{ collect($reportData)->sum('today_embr_receive') }}</td>
        <td>{{ collect($reportData)->sum('total_embr_receive') }}</td>
        <td>{{ collect($reportData)->sum('embr_balance') }}</td>
        <td>{{ collect($reportData)->sum('today_input') }}</td>
        <td>{{ collect($reportData)->sum('total_input') }}</td>
        <td>{{ collect($reportData)->sum('input_balance') }}</td>
        <td>{{ collect($reportData)->sum('today_output') }}</td>
        <td>{{ collect($reportData)->sum('total_output') }}</td>
        <td>{{ collect($reportData)->sum('output_balance') }}</td>
        <td>{{ collect($reportData)->sum('today_iron') }}</td>
        <td>{{ collect($reportData)->sum('total_iron') }}</td>
        <td>{{ collect($reportData)->sum('iron_balance') }}</td>
        <td>{{ collect($reportData)->sum('today_poly') }}</td>
        <td>{{ collect($reportData)->sum('total_poly') }}</td>
        <td>{{ collect($reportData)->sum('poly_balance') }}</td>
        <td>{{ collect($reportData)->sum('today_packing') }}</td>
        <td>{{ collect($reportData)->sum('total_packing') }}</td>
        <td>{{ collect($reportData)->sum('packing_balance') }}</td>
    </tr>
    </tbody>
</table>
