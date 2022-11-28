<div class="body-section table-responsive" style="margin-top: 0;">
    <table class="reportTable">
        <thead>
        <tr style="background-color: aliceblue;">
            <th>Booking Type</th>
            <th>Style</th>
            <th>Body Parts</th>
            <th>Fabrication</th>
            <th>GSM</th>
            <th>Fab. Dia</th>
            <th>Color Type</th>
            <th>Gmts Color</th>
            <th>Fab. Color</th>
            <th>Act. Fab. Qty</th>
            <th>P. Loss %</th>
            <th>T. Fab. Qty</th>
            <th>Prog. Qty</th>
            <th>Prod. Qty</th>
            <th>Del. Qty</th>
            <th>Balance Qty</th>
        </tr>
        </thead>

        <tbody>
            @foreach($data as $value)
                <tr>
                    <td style="text-transform: capitalize">{{ $value['booking_type'] }}</td>
                    <td>{{ $value['style_names'] }}</td>
                    <td>{{ $value['body_parts'] }}</td>
                    <td>{{ $value['fabric_descriptions'] }}</td>
                    <td>{{ $value['fab_gsms'] }}</td>
                    <td>{{ $value['fab_dias'] }}</td>
                    <td>{{ $value['color_types'] }}</td>
                    <td>{{ $value['gmt_colors'] }}</td>
                    <td>{{ $value['item_colors'] }}</td>
                    <td>{{ number_format($value['act_fab_qty'], 2) }}</td>
                    <td>{{ number_format($value['process_loss'], 2) }}</td>
                    <td>{{ number_format($value['total_fab_qty'], 2) }}</td>
                    <td>{{ number_format($value['program_qty'], 2) }}</td>
                    <td>{{ number_format($value['production_qty'], 2) }}</td>
                    <td>{{ number_format($value['delivery_qty'], 2) }}</td>
                    <td>{{ number_format($value['balance_qty'], 2) }}</td>
                </tr>
            @endforeach

            <tr>
                <td colspan="8"><b>Total:</b> </td>
                <td><b>{{ number_format(collect($data)->sum('act_fab_qty'), 2) }}</b></td>
                <td></td>
                <td><b>{{ number_format(collect($data)->sum('total_fab_qty'), 2) }}</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tbody>
    </table>
</div>
