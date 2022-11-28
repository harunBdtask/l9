@if(isset($type))
    @include('merchandising::reports.includes.order_volume_report_chart')
@endif
<div class="col-md-8 col-md-offset-2">
    <table class="reportTable">
        <thead>
        <tr style="background-color:aliceblue; font-size: 16px;">
            <td><strong>SL</strong></td>
            <td><strong>Buyer</strong></td>
            <td style="text-align: right"><strong>Total OQ</strong></td>
            <td style="text-align: right"><strong>Total Value</strong></td>
        </tr>
        </thead>
        <tbody>
        @foreach($reportData as $data)
            <tr style="font-size: 13px;">
                <td><strong>{{ str_pad($loop->iteration, 2, 0, STR_PAD_LEFT) }}</strong></td>
                <td class="text-left"><strong>{{ $data->buyer->name ?? 'N/A' }}</strong></td>
                <td style="text-align: right"><strong>{{ $data['total_qty'] }}</strong></td>
                <td style="text-align: right"><strong>${{ number_format($data['total_value'], 2) }}</strong></td>
            </tr>
        @endforeach
        <tr style="background-color: gainsboro; font-size: 15px;">
            <td style="text-align: right" colspan="2"><strong>Total</strong></td>
            <td style="text-align: right"><strong>{{ collect($reportData)->sum('total_qty') }}</strong></td>
            <td style="text-align: right"><strong>${{ number_format(collect($reportData)->sum('total_value'), 2) }}</strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>
