<div class="col-md-12">
    <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
           id="fixTable">
        <thead>
        <tr style="background-color: #d7f6d3" align="center">
            <th rowspan="2">Buyer</th>
            <th rowspan="2">Style No</th>
            <th rowspan="2">Unit</th>
            <th rowspan="2">Color</th>
            <th rowspan="2">1st Input Date</th>
            <th rowspan="2">Last Input Date</th>
            <th rowspan="2">DESC</th>
            <th colspan="{{count($sizes)}}">Size</th>
            <th rowspan="2">Total</th>
        </tr>
        <tr style="background-color: #d7f6d3">
            @if($sizes && is_array($sizes) && count($sizes))
                @foreach($sizes as $size)
                    <th>{{$size}}</th>
                @endforeach
            @else
                <th>&nbsp;</th>
            @endif
        </tr>
        </thead>
        <tbody>
        @if($reports && count($reports))
            @php
                $total_input_qty = 0;
            @endphp
            @foreach($reports->groupBy('order_id') as $reportByOrder)
                @php
                    $buyer = $reportByOrder->first()->buyer->name;
                    $style = $reportByOrder->first()->order->style_name;
                    $order_rowspan = 0;
                    $reportByOrder->groupBy('floor_id')->each(function($floorData, $floor_id) use(&$order_rowspan) {
                      $order_rowspan += $floorData->groupBy('color_id')->count() * 3;
                    });
                    $floor_count = 0;
                @endphp
                <tr>
                    <td rowspan="{{ $order_rowspan }}">{{ $buyer }}</td>
                    <td rowspan="{{ $order_rowspan }}">{{ $style }}</td>
                @foreach($reportByOrder->groupBy('floor_id') as $reportByFloor)
                    @if($floor_count > 0)
                        <tr>
                            @endif
                            @php
                                $floor_count++;
                                $floor_no = $reportByFloor->first()->floor->floor_no;
                                $floor_rowspan = $reportByFloor->groupBy('color_id')->count() * 3;
                                $color_count = 0;
                            @endphp
                            <td rowspan="{{ $floor_rowspan }}">{{ $floor_no }}</td>
                        @foreach($reportByFloor->groupBy('color_id') as $report)
                            @if($color_count > 0)
                                <tr>
                                    @endif
                                    @php
                                        $report = $report->first();
                                        $total_input_qty += $report['production_qty'];
                                        $color_count++;
                                        $first_input_date = $report->getOrderColorFloorWiseFirstInputDate($report->order_id, $report->color_id, $report->floor_id);
                                        $last_input_date = $report->getOrderColorFloorWiseLastInputDate($report->order_id, $report->color_id, $report->floor_id);
                                        $types = ['Input', 'Output', 'Balance'];
                                    @endphp
                                    <td rowspan="3">{{ Arr::get($report,'color.name',null) }}</td>
                                    <td rowspan="3">{{ $first_input_date }}</td>
                                    <td rowspan="3">{{ $last_input_date }}</td>
                                    @foreach($types as $type_key => $type)
                                        @switch($type)
                                            @case('Input')
                                            <td><b>Input</b></td>
                                            @php
                                                $currentInput = [];
                                                $currentOutput = [];
                                            @endphp
                                            @if($sizes && is_array($sizes) && count($sizes))
                                                @foreach($sizes as $size_id=>$size)
                                                    @php
                                                        $currentInput[$size_id] = $reports->where('floor_id', $report->floor_id)->where('color_id', $report->color_id)
                                                                                                ->where('size_id', $size_id)->first()->total_sewing_input_sum ?? 0;
                                                    @endphp
                                                    <td>{{$currentInput[$size_id]}}</td>
                                                @endforeach
                                            @else
                                                @php
                                                    $currentInput[] = $reports->where('floor_id', $report->floor_id)->where('color_id', $report->color_id)->sum('total_sewing_input_sum') ?? 0;
                                                @endphp
                                                <td>&nbsp;</td>
                                            @endif
                                            <td>{{array_sum($currentInput)}}</td>
                                </tr>
                                @break
                                @case('Output')
                                <tr>
                                    <td><b>Output</b></td>
                                    @if($sizes && is_array($sizes) && count($sizes))
                                        @foreach($sizes as $size_id=>$size)
                                            @php
                                                $currentOutput[$size_id]=$reports->where('floor_id', $report->floor_id)->where('color_id', $report->color_id)
                                                                                        ->where('size_id', $size_id)->first()->total_sewing_output_sum ?? 0;
                                            @endphp
                                            <td>{{$currentOutput[$size_id]}}</td>
                                        @endforeach
                                    @else
                                        @php
                                            $currentOutput[]=$reports->where('floor_id', $report->floor_id)->where('color_id', $report->color_id)->sum('total_sewing_output_sum') ?? 0;
                                        @endphp
                                        <td>&nbsp;</td>
                                    @endif
                                    <td>{{array_sum($currentOutput)}}</td>
                                </tr>
                                @break
                                @case('Balance')
                                <tr>
                                    <td><b>Balance</b></td>
                                    @if($sizes && is_array($sizes) && count($sizes))
                                        @foreach($sizes as $size_id=>$size)
                                            <td>
                                                {{ $currentInput[$size_id] - $currentOutput[$size_id] }}
                                            </td>
                                        @endforeach
                                    @else
                                        <td>&nbsp;</td>
                                    @endif
                                    <td>{{array_sum($currentInput) - array_sum($currentOutput)}}</td>
                                </tr>
                                @break
                                @endswitch
                                @endforeach
                                @endforeach
                                @endforeach
                                @endforeach
                            @else
                                <tr>
                                    <th colspan="14">No Data</th>
                                </tr>
                            @endif
        </tbody>
    </table>
</div>
<br>
<div class="col-md-12">
    <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;">
        <thead>
        <tr>
            <th colspan="5">Report Summary</th>
        </tr>
        <tr>
            <th>Unit</th>
            <th>Color</th>
            <th>Input Qty</th>
            <th>Output Qty</th>
            <th>Balance Qty</th>
        </tr>
        </thead>
        <tbody>
        @if($reports && count($reports))
            @php
                $colorWiseTotalInput=0;
                $totalInput=0;
                $colorWiseTotalOutput=0;
                $totalOutput=0;
                $colorWiseTotalBalance=0;
                $totalBalance=0;
            @endphp
            @foreach($reports->groupBy('floor_id') as $reportByFloor)
                @foreach($reportByFloor->groupBy('color_id') as $report)
                    @php
                        $reportFirst = $report->first();
                    $colorWiseTotalInput += $report->sum('total_sewing_input_sum');
                    $colorWiseTotalOutput += $report->sum('total_sewing_output_sum');
                    $colorWiseTotalBalance += ($report->sum('total_sewing_input_sum')-$report->sum('total_sewing_output_sum'));
                    $totalInput+=$colorWiseTotalInput;
                    $totalOutput+=$colorWiseTotalOutput;
                    $totalBalance+=$colorWiseTotalBalance;
                    @endphp
                    <tr>
                        @if($loop->first)
                            <td rowspan="{{count($reportByFloor->groupBy('color_id'))+1}}">{{$reportFirst->floor->floor_no}}</td>
                        @endif
                        <td>{{$reportFirst->color->name}}</td>
                        <td>{{$report->sum('total_sewing_input_sum')}}</td>
                        <td>{{$report->sum('total_sewing_output_sum')}}</td>
                        <td>{{$report->sum('total_sewing_input_sum')-$report->sum('total_sewing_output_sum')}}</td>
                    </tr>
                @endforeach
                <tr style="background-color: #bbdefb; font-weight: bold">
                    <td>All Color Total</td>
                    <td>{{$colorWiseTotalInput}}</td>
                    <td>{{$colorWiseTotalOutput}}</td>
                    <td>{{$colorWiseTotalBalance}}</td>
                </tr>
            @endforeach
            <tr style="background-color: #ffecb3; font-weight: bold">
                <td colspan="2">Total</td>
                <td>{{$totalInput}}</td>
                <td>{{$totalOutput}}</td>
                <td>{{$totalBalance}}</td>
            </tr>
        @else
            <tr>
                <th colspan="14">No Data</th>
            </tr>
        @endif
        </tbody>
    </table>
</div>
