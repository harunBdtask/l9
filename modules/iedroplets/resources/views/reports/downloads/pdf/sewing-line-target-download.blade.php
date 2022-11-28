<!DOCTYPE html>

<html>

<head>

    <title>IE Report</title>

    @include('reports.downloads.includes.pdf-styles')
    <style>
        .reportTable thead,
        .reportTable tbody,
        .reportTable th {
            padding: 0;
            margin: 0;
            font-size: 5px;
            text-align: center;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid #e7e7e7;
            font-size: 5px;
        }
        @page { margin: 25px 15px; }
        header { position: fixed; top: -25px; left: 0px; right: 0px; text-align: center; height: 10px; }
        footer { position: fixed; bottom: -25px; font-size: 5px; left: 0px; right: 0px; text-align: center; height: 10px; }
        header h4{margin:0px;font-size: 7px;}
        header h2{margin-bottom:0px;font-size: 10px;}
    </style>

</head>

<body>
@include('reports.downloads.includes.pdf-header')
<main>

    <h4 align="center" style="font-size: 9px;">Line Wise Target/Manpower/Input Plan Update
        || {{ date("D\ - F d- Y",strtotime($target_date)) }}</h4>

    <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
        <thead>
        <tr>
            <th>Unit</th>
            <th>Line</th>
            <th>Buyer</th>
            <th>Order</th>
            <th>PO</th>
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
    <table width="100%">
        <tbody>
        <tr>
            <td colspan="3" align="center" style="font-size: 7px;">Today's Production Summary</td>
        </tr>
        <tr>
            <td valign="top">
                <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;width: 80%;">
                    <thead>
                    <tr>
                        <th colspan="2">Notes</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th width="50%">Total Plan Line</th>
                        <th>{{ $notes['plan_line'] }}</th>
                    </tr>
                    <tr>
                        <th>Total Running Line</th>
                        <th>{{ $notes['running_line'] }}</th>
                    </tr>
                    <tr>
                        <th>Total Close Line</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Total New Input</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Total Style Close</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Total Fabric</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Total Cutting</th>
                        <th></th>
                    </tr>
                    <tr>
                        <th>Total Print/Embr.</th>
                        <th></th>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td valign="top">
                <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;width: 80%;">
                    <thead>
                    <tr>
                        <th>Buyer</th>
                        <th>Line Run</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($buyer_line_reports) && count($buyer_line_reports) > 0)
                        @php
                            $total_line = 0;
                        @endphp
                        @foreach($buyer_line_reports->groupBy('buyer_id') as $groupByBuyer)
                            @php
                                $buyer_name = $groupByBuyer->first()->buyers->name ?? 'N/A';
                                $line = $groupByBuyer->groupBy('line_id')->count() ?? 0;
                                $total_line += $groupByBuyer->groupBy('line_id')->count() ?? 0;
                            @endphp
                            <tr>
                                <td>{{ $buyer_name }}</td>
                                <td>{{ $line }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <td>{{ $total_line }}</td>
                        </tr>
                    @else
                        <tr>
                            <th colspan="2">No Data</th>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </td>
            <td valign="top">
                <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;width: 80%;">
                    <thead>
                    <tr>
                        <th colspan="2">Today's Working Hour Sewing</th>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <th>Line</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($todays_working_hour_sewing)
                        @php
                            $total_line_hour_today = 0;
                        @endphp
                        @foreach($todays_working_hour_sewing as $hour => $lineCount)
                            @php
                                $total_line_hour_today += $lineCount;
                            @endphp
                            <tr>
                                <td>{{ $hour }}</td>
                                <td>{{ $lineCount }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th>{{ $total_line_hour_today }}</th>
                        </tr>
                    @else
                        <tr>
                            <th colspan="2">No Data</th>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

    <table width="100%">
        <tbody>
        <tr>
            <td colspan="2" align="center" style="font-size: 7px;">Previous Day's Production Summary</td>
        </tr>
        <tr>
            <td valign="top">
                <table class="reportTable" style="border: 1px solid black; border-collapse: collapse; width: 80%;">
                    <thead>
                    <tr>
                        <th colspan="4">Previous Day's Production Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Previous Day</td>
                        <td>Target</td>
                        <td>Achieved</td>
                        <td>Achieved(%)</td>
                    </tr>
                    <tr>
                        <td>Sewing</td>
                        <td>{{ $previous_days_production['sewing_target'] }}</td>
                        <td>{{ $previous_days_production['sewing_achieved'] }}</td>
                        <td>{{ $previous_days_production['sewing_target'] > 0 ? number_format(($previous_days_production['sewing_achieved'] * 100)/$previous_days_production['sewing_target'],2) : 0 }} %</td>
                    </tr>
                    <tr>
                        <td>QC Pass</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Finishing</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
            </td>
            <td valign="top">
                <table class="reportTable" style="border: 1px solid black; border-collapse: collapse; width: 80%;">
                    <thead>
                    <tr>
                        <th colspan="2">Working Hour Sewing</th>
                    </tr>
                    <tr>
                        <th>Time</th>
                        <th>Line</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($yesterdays_working_hour_sewing)
                        @php
                            $total_line_hour_yesterday = 0;
                        @endphp
                        @foreach($yesterdays_working_hour_sewing as $hour => $lineCount)
                            @php
                                $total_line_hour_yesterday += $lineCount;
                            @endphp
                            <tr>
                                <td>{{ $hour }}</td>
                                <td>{{ $lineCount }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <th>Total</th>
                            <th>{{ $total_line_hour_yesterday }}</th>
                        </tr>
                    @else
                        <tr>
                            <th colspan="2">No Data</th>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </td>
        </tr>
        </tbody>
    </table>

</main>
@include('reports.downloads.includes.pdf-footer')
</body>
</html>