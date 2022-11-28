<div class="row m-t">
    <div class="col-sm-12">
        <table class="reportTable">
            <thead>
            <tr style="background-color: #d7f6d3">
                <th colspan="{{5+count($floors)}}">Daily Input Unit Wise - {{\Carbon\Carbon::now()->format('F Y')}}</th>
            </tr>
            <tr style="background-color: #d7f6d3" align="center">
                <th>Date</th>
                <th>Buyer</th>
                @foreach($floors as $floor)
                    <th>{{$floor}}</th>
                @endforeach
                <th>Total Input Qty</th>
                <th>WIP ready for Input</th>
                <th>Remarks</th>
            </tr>
            </thead>
            <tbody>
            @if($reports && count($reports))
                @php
                    $total_input_qty = 0;
                    $total_wip = 0;
                    $floors_input_qty = [];
                @endphp
                @foreach($reports->groupBy('production_date') as $report)
                    @php
                        $current_date_total_input_qty = 0;
                    @endphp
                    <tr>
                        <td> {{ $report->first()->production_date }}</td>
                        <td>All</td>
                        @foreach($floors as $floor_key => $floor_name)
                            @php
                                $current_input_qty = $report->where('floor_id', $floor_key)->sum('production_qty');
                                $current_date_total_input_qty += $current_input_qty;
                                $floors_input_qty[$floor_key] = ($floors_input_qty[$floor_key] ?? 0) + $current_input_qty;
                            @endphp
                            <td>{{ (int)$current_input_qty }}</td>
                        @endforeach
                        @php
                            $total_input_qty += $current_date_total_input_qty;
                            $wip=\SkylarkSoft\GoRMG\ManualProduction\Models\Reports\ManualDailyProductionReport::getInputReadyWipDataToDate($report->first()->production_date);
                            $total_wip += $wip;
                        @endphp
                        <td>{{ (int)$current_date_total_input_qty }}</td>
                        <td>{{ $wip }}</td>
                        <td></td>
                    </tr>
                @endforeach
                <tr style="background-color: #fcffc6">
                    <th colspan="2">Total</th>
                    @foreach($floors as $floor_key=>$floor_name)
                        <th>{{$floors_input_qty[$floor_key]}}</th>
                    @endforeach
                    <th>{{ (int)$total_input_qty }}</th>
                    <th>{{ $total_wip }}</th>
                    <th></th>
                </tr>
            @else
                <tr>
                    <th colspan="14">No Data</th>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
