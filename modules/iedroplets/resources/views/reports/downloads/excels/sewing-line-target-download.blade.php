<table>
    <thead>
    <tr>
        <th colspan="17">{{ factoryName() }}</th>

    </tr>
    <tr>
        <th colspan="17"> {{  factoryAddress()  }}</th>
    </tr>
    <tr>
        <th>Unit</th>
        <th>Line</th>
        <th>Buyer</th>
        <th>Style</th>
        <th>Order</th>
        <th>Input Date</th>
        <th>Ttl Attend. Opt</th>
        <th>Operator</th>
        <th>Helper</th>
        <th>Top Plan Target</th>
        <th>Hourly Plan Target</th>
        <th>Working Hr</th>
        <th>Day-Target<br>(Sewing)</th>
        <th>Day-Target<br>(QC Pass)</th>
        <th>Day-Target<br>(Finishing)</th>
        <th>Remarks(Sewing)</th>
        <th>Remarks(Finishing)</th>
    </tr>
    </thead>
    <tbody>
    @if($report)
        @php
            $g_total_operator = 0;
            $g_total_helper = 0;
            $g_total_top_plan_target = 0;
            $g_total_hourly_plan_target = 0;
            $g_total_working_hour = 0;
            $g_total_day_target_sewing = 0;
        @endphp
        @foreach($report->groupBy('floor_id') as $floorGroup)
            @php
                $total_operator = 0;
                $total_helper = 0;
                $total_top_plan_target = 0;
                $total_hourly_plan_target = 0;
                $total_working_hour = 0;
                $total_day_target_sewing = 0;
            @endphp
            @foreach($floorGroup as $reportData)
                @if($reportData->sewingLineTarget && count($reportData->sewingLineTarget) > 0)
                    @foreach($reportData->sewingLineTarget as $sewing_line_target)
                        @php
                            $input_date = null;
                            if($sewing_line_target->purchase_order_id) {
                              $input_date = \SkylarkSoft\GoRMG\Inputdroplets\Models\CuttingInventoryChallan::where('purchase_order_id', $sewing_line_target->purchase_order_id)->orderBy('input_date')->first()->input_date ?? null;
                            }

                            $total_operator += $sewing_line_target->operator ?? 0;
                            $total_helper += $sewing_line_target->helper ?? 0;
                            $total_top_plan_target += $sewing_line_target->orders->plan_target ?? 0;
                            $total_hourly_plan_target += $sewing_line_target->target ?? 0;
                            $total_working_hour += $sewing_line_target->wh ?? 0;
                            $total_day_target_sewing += $sewing_line_target->wh * $sewing_line_target->target ?? 0;

                            $g_total_operator += $sewing_line_target->operator ?? 0;
                            $g_total_helper += $sewing_line_target->helper ?? 0;
                            $g_total_top_plan_target += $sewing_line_target->orders->plan_target ?? 0;
                            $g_total_hourly_plan_target += $sewing_line_target->target ?? 0;
                            $g_total_working_hour += $sewing_line_target->wh ?? 0;
                            $g_total_day_target_sewing += $sewing_line_target->wh * $sewing_line_target->target ?? 0;
                        @endphp
                        <tr>
                            <td>{{ $reportData->floor->floor_no ?? '' }}</td>
                            <td>{{ $reportData->line_no ?? '' }}</td>
                            <td>{{ $sewing_line_target->buyer->name ?? '' }}</td>
                            <td>{{ $sewing_line_target->order->order_style_no ?? '' }}</td>
                            <td>{{ $sewing_line_target->purchaseOrder->po_no ?? '' }}</td>
                            <td>{{ $input_date ? date('d M, Y', strtotime($input_date)) : '' }}</td>
                            <td>{{ '' }}</td>
                            <td>{{ $sewing_line_target->operator ?? '' }}</td>
                            <td>{{ $sewing_line_target->helper ?? '' }}</td>
                            <td>{{ $sewing_line_target->orders->plan_target ?? '' }}</td>
                            <td>{{ $sewing_line_target->target ?? '' }}</td>
                            <td>{{ $sewing_line_target->wh ?? '' }}</td>
                            <td>{{ $sewing_line_target->wh * $sewing_line_target->target ?? '' }}</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>{{ $sewing_line_target->remarks ?? '' }}</td>
                            <td>&nbsp;</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>{{ $reportData->floor->floor_no ?? '' }}</td>
                        <td>{{ $reportData->line_no ?? '' }}</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                    </tr>
                @endif
            @endforeach
            <tr>
                <th colspan="7">Total</th>
                <th>{{ $total_operator }}</th>
                <th>{{ $total_helper }}</th>
                <th>{{ $total_top_plan_target }}</th>
                <th>{{ $total_hourly_plan_target }}</th>
                <th>{{ $total_working_hour }}</th>
                <th>{{ $total_day_target_sewing }}</th>
                <th colspan="4"></th>
            </tr>
        @endforeach
        <tr>
            <th colspan="7">Grand Total</th>
            <th>{{ $g_total_operator }}</th>
            <th>{{ $g_total_helper }}</th>
            <th>{{ $g_total_top_plan_target }}</th>
            <th>{{ $g_total_hourly_plan_target }}</th>
            <th>{{ $g_total_working_hour }}</th>
            <th>{{ $g_total_day_target_sewing }}</th>
            <th colspan="4"></th>
        </tr>
    @else
        <tr>
            <th colspan="17">No Data</th>
        </tr>
    @endif
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th colspan="8">Today's Production Summary</th>
        </tr>
        <tr>
            <th colspan="2" rowspan="2">Notes</th>
            <th rowspan="2"></th>
            <th rowspan="2">Buyer</th>
            <th rowspan="2">Line Run</th>
            <th rowspan="2"></th>
            <th colspan="2">Today's Working Hour Sewing</th>
        </tr>
        <tr>
            <th>Time</th>
            <th>Line</th>
        </tr>
    </thead>
    <tbody>
    @foreach($todays_summary as $summary)
        <tr>
            <td>{{ $summary['notes_key'] }}</td>
            <td>{{ $summary['notes_value'] }}</td>
            <td></td>
            <td>{{ $summary['buyer_line_run_key'] }}</td>
            <td>{{ $summary['buyer_line_run_value'] }}</td>
            <td></td>
            <td>{{ $summary['todays_sewing_working_hour_key'] }}</td>
            <td>{{ $summary['todays_sewing_working_hour_value'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<table>
    <thead>
    <tr>
        <th colspan="8">Previous Day's Production Summary</th>
    </tr>

    <tr>
        <th colspan="4">Previous Day's Production Status</th>
        <th></th>
        <th></th>
        <th colspan="2">Working Hour Sewing</th>
    </tr>
    <tr>
        <th>Previous Day</th>
        <th>Target</th>
        <th>Achieved</th>
        <th>Achieved(%)</th>
        <th></th>
        <th></th>
        <th>Time</th>
        <th>Line</th>
    </tr>
    </thead>
    <tbody>
        @foreach($previous_summaries as $previous_summary)
            <tr>
                <td>{{ $previous_summary['previous_production_status_key']}}</td>
                <td>{{ $previous_summary['previous_production_target'] }}</td>
                <td>{{ $previous_summary['previous_production_achieved'] }}</td>
                <td>{{ $previous_summary['previous_production_achieved_percent'] }}</td>
                <td></td>
                <td></td>
                <td>{{ $previous_summary['time_key'] }}</td>
                <td>{{ $previous_summary['time_value'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>