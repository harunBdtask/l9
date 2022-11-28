<table class="reportTable" id="fixTable">
    <thead>
    <tr>
        <td style="font-size: 13px;background-color: #e3e3e3; font-weight: bold; text-align: right"
            colspan="{{ request('page') !='view' ? 24 : 20 }}">IRON G.
            TOTAL
        </td>
        <td colspan="3"
            style="font-size: 13px;background-color: #e3e3e3; font-weight: bold; text-align: right">{{ number_format($total['IRON']['total'],0) }}
            <span style="font-size: 10px">PCS</span>
        </td>
    </tr>
    <tr>
        <td style="font-size: 13px; background-color: #e3e3e3; font-weight: bold; text-align: right"
            colspan="{{ request('page') !='view' ? 24 : 20 }}">POLY G.
            TOTAL
        </td>
        <td colspan="3"
            style="font-size: 13px;background-color: #e3e3e3; font-weight: bold; text-align: right">{{ number_format($total['POLY']['total'], 0) }}
            <span style="font-size: 10px">PCS</span>
        </td>
    </tr>
    <tr>
        <td style="font-size: 13px; background-color: #e3e3e3; font-weight: bold; text-align: right"
            colspan="{{ request('page') !='view' ? 24 : 20 }}">PACKING G.
            TOTAL
        </td>
        <td colspan="3"
            style="font-size: 13px; background-color: #e3e3e3; font-weight: bold; text-align: right">{{ number_format($total['PACKING']['total'], 0) }}
            <span style="font-size: 10px">PCS</span>
        </td>
    </tr>
    <tr>
        <th style="width: 4% !important;font-weight: bold; background-color: aliceblue">FL</th>
        <td style="font-weight: bold; background-color: aliceblue">BUYER</td>
        <td style="font-weight: bold; background-color: aliceblue; text-align: left">STYLE</td>
        <td style="font-weight: bold; background-color: aliceblue; text-align: left">COLOR</td>
        {{--        <td style="font-weight: bold; background-color: aliceblue">ITEM</td>--}}
        <td style="font-weight: bold; background-color: aliceblue">I. GROUP</td>
        <td style="font-weight: bold; background-color: aliceblue">M. P.</td>
        <td style="font-weight: bold; background-color: aliceblue">SMV</td>
        <td style="font-weight: bold; background-color: aliceblue">HR. TARGET</td>
        <td style="font-weight: bold; background-color: aliceblue">PROCESS</td>
        <td style="font-weight: bold; background-color: aliceblue">8-9 AM</td>
        <td style="font-weight: bold; background-color: aliceblue">9-10 AM</td>
        <td style="font-weight: bold; background-color: aliceblue">10-11 AM</td>
        <td style="font-weight: bold; background-color: aliceblue">11-12 PM</td>
        <td style="font-weight: bold; background-color: aliceblue">12-1 PM</td>
        <td style="font-weight: bold; background-color:#e1dcdc">BR</td>
        <td style="font-weight: bold; background-color: aliceblue">2-3 PM</td>
        <td style="font-weight: bold; background-color: aliceblue">3-4 PM</td>
        <td style="font-weight: bold; background-color: aliceblue">4-5 PM</td>
        <td style="font-weight: bold; background-color: aliceblue">5-6 PM</td>
        <td style="font-weight: bold; background-color: aliceblue">6-7 PM</td>
        @if(request('page') !='view')
            <td style="font-weight: bold; background-color: aliceblue">7-8 PM</td>
            <td style="font-weight: bold; background-color: aliceblue">8-9 PM</td>
            <td style="font-weight: bold; background-color: aliceblue">9-10 PM</td>
            <td style="font-weight: bold; background-color: aliceblue">10-11 PM</td>
        @endif
        <td style="font-weight: bold; background-color: aliceblue">TOTAL</td>
        <td style="font-weight: bold; background-color: aliceblue">AVG EFF%</td>
        <td style="font-weight: bold; background-color: aliceblue">REMARKS</td>
    </tr>
    </thead>

    @php
        $total['IRON']['man_power'] = 0;
        $total['IRON']['smv'] = 0;
        $total['IRON']['total_row'] = 0;
        $total['IRON']['hr_target'] = 0;

        $total['POLY']['man_power'] = 0;
        $total['POLY']['smv'] = 0;
        $total['POLY']['total_row'] = 0;
        $total['POLY']['hr_target'] = 0;

        $total['PACKING']['man_power'] = 0;
        $total['PACKING']['smv'] = 0;
        $total['PACKING']['total_row'] = 0;
        $total['PACKING']['hr_target'] = 0;
    @endphp
    @if($reportData)
        <tbody>
        @foreach($reportData as $values)
            @php
                $floorWiseTotal = [];
                $rowCount = [];
            @endphp
            @foreach($values as $floorWiseData)
                @php
                    $currentStyle = ["IRON" => null, "POLY" => null, "PACKING" => null,];
                @endphp
                @foreach(collect($floorWiseData)->sortBy('style') as $targetProduction)
                    @php
                        $rowTotalProduction = $targetProduction['hour_8']
                                       + $targetProduction['hour_9']
                                       + $targetProduction['hour_10']
                                       + $targetProduction['hour_11']
                                       + $targetProduction['hour_12']
                                       + $targetProduction['hour_14']
                                       + $targetProduction['hour_15']
                                       + $targetProduction['hour_16']
                                       + $targetProduction['hour_17']
                                       + $targetProduction['hour_18'];

                       if(request('page') != 'view') {
                           $rowTotalProduction += ($targetProduction['hour_19']
                           + $targetProduction['hour_20']
                           + $targetProduction['hour_21']
                           + $targetProduction['hour_22']);
                       }
                        $totalWorkingHour = 0;
                       for ($i = 0; $i<24; $i++) {
                           if (request('page') == 'view' && $i > 18 && $i < 22){
                               continue;
                           }
                           if ($targetProduction['hour_'.$i] > 0) {
                               $totalWorkingHour+=1;
                           }
                       }
                        $avgEff = $targetProduction['man_power'] > 0 && $totalWorkingHour > 0
                               ? $rowTotalProduction * $targetProduction['smv'] / $targetProduction['man_power'] / $totalWorkingHour : 0;
                      $floorWiseTotal[$targetProduction['process']]['total'] = ($floorWiseTotal[$targetProduction['process']]['total'] ?? 0) + $rowTotalProduction;
                    @endphp
                    @if($targetProduction['style'] != $currentStyle[$targetProduction['process']])
                        @php
                            $rowCount[$targetProduction['process']] = ($rowCount[$targetProduction['process']] ?? 0) + 1;
                            $floorWiseTotal[$targetProduction['process']]['man_power'] = ($floorWiseTotal[$targetProduction['process']]['man_power'] ?? 0) + $targetProduction['man_power'];
                            $floorWiseTotal[$targetProduction['process']]['smv'] = ($floorWiseTotal[$targetProduction['process']]['smv'] ?? 0) + $targetProduction['smv'];
                            $floorWiseTotal[$targetProduction['process']]['hr_target'] = ($floorWiseTotal[$targetProduction['process']]['hr_target'] ?? 0) + $targetProduction['hr_target'];

                            $total[$targetProduction['process']]['man_power'] += $targetProduction['man_power'];
                            $total[$targetProduction['process']]['smv'] += $targetProduction['smv'];
                            $total[$targetProduction['process']]['total_row'] += 1;
                            $total[$targetProduction['process']]['hr_target'] += $targetProduction['hr_target'];
                        @endphp
                    @endif
                    @php
                        $floorWiseTotal[$targetProduction['process']]['hour_8'] = ($floorWiseTotal[$targetProduction['process']]['hour_8'] ?? 0) + $targetProduction['hour_8'];
                        $floorWiseTotal[$targetProduction['process']]['hour_9'] = ($floorWiseTotal[$targetProduction['process']]['hour_9'] ?? 0) + $targetProduction['hour_9'];
                        $floorWiseTotal[$targetProduction['process']]['hour_10'] = ($floorWiseTotal[$targetProduction['process']]['hour_10'] ?? 0) + $targetProduction['hour_10'];
                        $floorWiseTotal[$targetProduction['process']]['hour_11'] = ($floorWiseTotal[$targetProduction['process']]['hour_11'] ?? 0) + $targetProduction['hour_11'];
                        $floorWiseTotal[$targetProduction['process']]['hour_12'] = ($floorWiseTotal[$targetProduction['process']]['hour_12'] ?? 0) + $targetProduction['hour_12'];
                        $floorWiseTotal[$targetProduction['process']]['hour_14'] = ($floorWiseTotal[$targetProduction['process']]['hour_14'] ?? 0) + $targetProduction['hour_14'];
                        $floorWiseTotal[$targetProduction['process']]['hour_15'] = ($floorWiseTotal[$targetProduction['process']]['hour_15'] ?? 0) + $targetProduction['hour_15'];
                        $floorWiseTotal[$targetProduction['process']]['hour_16'] = ($floorWiseTotal[$targetProduction['process']]['hour_16'] ?? 0) + $targetProduction['hour_16'];
                        $floorWiseTotal[$targetProduction['process']]['hour_17'] = ($floorWiseTotal[$targetProduction['process']]['hour_17'] ?? 0) + $targetProduction['hour_17'];
                        $floorWiseTotal[$targetProduction['process']]['hour_18'] = ($floorWiseTotal[$targetProduction['process']]['hour_18'] ?? 0) + $targetProduction['hour_18'];
                        if(request('page') != 'view') {
                            $floorWiseTotal[$targetProduction['process']]['hour_19'] = ($floorWiseTotal[$targetProduction['process']]['hour_19'] ?? 0) + $targetProduction['hour_19'];
                            $floorWiseTotal[$targetProduction['process']]['hour_20'] = ($floorWiseTotal[$targetProduction['process']]['hour_20'] ?? 0) + $targetProduction['hour_20'];
                            $floorWiseTotal[$targetProduction['process']]['hour_21'] = ($floorWiseTotal[$targetProduction['process']]['hour_21'] ?? 0) + $targetProduction['hour_21'];
                            $floorWiseTotal[$targetProduction['process']]['hour_22'] = ($floorWiseTotal[$targetProduction['process']]['hour_22'] ?? 0) + $targetProduction['hour_22'];
                        }
                    @endphp
                    @if($targetProduction['style'] != $currentStyle[$targetProduction['process']])
                        @php
                            $colorWiseRowSpanCount[$targetProduction['process']] = collect($floorWiseData)
                                ->where('process', $targetProduction['process'])
                                ->where('style', $targetProduction['style'])
                                ->count();
                        @endphp
                    @endif
                    <tr>
                        @if($targetProduction['style'] != $currentStyle[$targetProduction['process']])
                            <td rowspan="{{ $colorWiseRowSpanCount[$targetProduction['process']] }}">{{ $targetProduction['floor'] }}</td>
                            <td rowspan="{{ $colorWiseRowSpanCount[$targetProduction['process']] }}">{{ $targetProduction['buyer'] }}</td>
                            <td class="text-left"
                                rowspan="{{ $colorWiseRowSpanCount[$targetProduction['process']] }}">{{ $targetProduction['style'] }}</td>
                        @endif
                        <td class="text-left">{{ $targetProduction['color'] }}</td>
                        @if($targetProduction['style'] != $currentStyle[$targetProduction['process']])

                            <td rowspan="{{ $colorWiseRowSpanCount[$targetProduction['process']] }}">{{ $targetProduction['item_group'] }}</td>

                            <td rowspan="{{ $colorWiseRowSpanCount[$targetProduction['process']] }}"
                                class="text-right">{{ $targetProduction['man_power'] }}</td>
                            <td rowspan="{{ $colorWiseRowSpanCount[$targetProduction['process']] }}"
                                class="text-right">{{ $targetProduction['smv'] }}</td>

                        @endif
                        <td class="text-right">{{ round($targetProduction['hr_target']) }}</td>

                        @if($loop->first)
                            @if($targetProduction['process'] === "IRON")
                                <td style="background-color:#6495ed94"
                                    rowspan="{{count($floorWiseData)}}"><b>{{ $targetProduction['process'] }}</b></td>
                            @elseif($targetProduction['process'] === "POLY")
                                <td style="background-color: khaki"
                                    rowspan="{{count($floorWiseData)}}"><b>{{ $targetProduction['process'] }}</b></td>
                            @else
                                <td style="background-color: powderblue"
                                    rowspan="{{count($floorWiseData)}}"><b>{{ $targetProduction['process'] }}</b></td>
                            @endif
                        @endif
                        <td class="text-right">{{ $targetProduction['hour_8'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_9'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_10'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_11'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_12'] }}</td>
                        @if($loop->first)
                            <td style="background-color:#e1dcdc" rowspan="{{count($floorWiseData)}}"></td>
                        @endif
                        <td class="text-right">{{ $targetProduction['hour_14'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_15'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_16'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_17'] }}</td>
                        <td class="text-right">{{ $targetProduction['hour_18'] }}</td>
                        @if(request('page') !='view')
                            <td class="text-right">{{ $targetProduction['hour_19'] }}</td>
                            <td class="text-right">{{ $targetProduction['hour_20'] }}</td>
                            <td class="text-right">{{ $targetProduction['hour_21'] }}</td>
                            <td class="text-right">{{ $targetProduction['hour_22'] }}</td>
                        @endif
                        <td class="text-right">{{ round($rowTotalProduction) }}</td>
                        <td class="text-right">{{ number_format($avgEff, 2) }}</td>
                        <td></td>
                    </tr>
                    @php
                        $currentStyle[$targetProduction['process']] = $targetProduction['style'];
                    @endphp
                @endforeach
            @endforeach
            <tr>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right" colspan="5">IRON TOTAL
                </td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['man_power'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ number_format(($floorWiseTotal['IRON']['smv']/$rowCount['IRON']), 2) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ round($floorWiseTotal['IRON']['hr_target']) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_8'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_9'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_10'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_11'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_12'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_14'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_15'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_16'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_17'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_18'] }}</td>
                @if(request('page') !='view')
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_19'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_20'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_21'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['IRON']['hour_22'] }}</td>
                @endif
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ round($floorWiseTotal['IRON']['total']) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
            </tr>
            <tr>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right" colspan="5">POLY TOTAL
                </td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['man_power'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ number_format(($floorWiseTotal['POLY']['smv'] / $rowCount['POLY']), 2) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ round($floorWiseTotal['POLY']['hr_target']) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_8'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_9'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_10'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_11'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_12'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_14'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_15'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_16'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_17'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_18'] }}</td>
                @if(request('page') !='view')
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_19'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_20'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_21'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['POLY']['hour_22'] }}</td>
                @endif
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ round($floorWiseTotal['POLY']['total']) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
            </tr>
            <tr>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right" colspan="5">PACKING TOTAL
                </td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['man_power'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ number_format(($floorWiseTotal['PACKING']['smv'] / $rowCount['PACKING']), 2) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ round($floorWiseTotal['PACKING']['hr_target']) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_8'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_9'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_10'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_11'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_12'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_14'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_15'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_16'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_17'] }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_18'] }}</td>
                @if(request('page') !='view')
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_19'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_20'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_21'] }}</td>
                    <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ $floorWiseTotal['PACKING']['hour_22'] }}</td>
                @endif
                <td style="background-color: gainsboro; font-weight: bold; text-align: right">{{ round($floorWiseTotal['PACKING']['total']) }}</td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
                <td style="background-color: gainsboro; font-weight: bold; text-align: right"></td>
            </tr>
        @endforeach
        <tr>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right"
                colspan="5">IRON G.
                TOTAL
            </td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['man_power'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ number_format(($total['IRON']['smv'] / $total['IRON']['total_row']), 2) }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ round($total['IRON']['hr_target']) }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right"></td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_8'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_9'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_10'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_11'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_12'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right"></td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_14'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_15'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_16'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_17'] }}</td>
            <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_18'] }}</td>
            @if(request('page') !='view')
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_19'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_20'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_21'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['IRON']['hour_22'] }}</td>
            @endif
            <td colspan="3"
                style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ number_format($total['IRON']['total']) }}
                <span style="font-size: 10px">PCS</span>
            </td>
        </tr>
        <tr>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right"
                colspan="5">POLY G.
                TOTAL
            </td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['man_power'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ number_format(($total['POLY']['smv'] / $total['POLY']['total_row']), 2) }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ round($total['POLY']['hr_target']) }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right"></td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_8'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_9'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_10'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_11'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_12'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right"></td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_14'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_15'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_16'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_17'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_18'] }}</td>
            @if(request('page') !='view')
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_19'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_20'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_21'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['POLY']['hour_22'] }}</td>
            @endif
            <td colspan="3"
                style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ number_format($total['POLY']['total']) }}
                <span style="font-size: 10px">PCS</span>
            </td>
        </tr>
        <tr>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right"
                colspan="5">PACKING G.
                TOTAL
            </td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['man_power'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ number_format(($total['PACKING']['smv'] / $total['PACKING']['total_row']), 2) }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ round($total['PACKING']['hr_target']) }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right"></td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_8'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_9'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_10'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_11'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_12'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right"></td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_14'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_15'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_16'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_17'] }}</td>
            <td style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_18'] }}</td>
            @if(request('page') !='view')
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_19'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_20'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_21'] }}</td>
                <td style="font-size: 13px;background-color: #3eab82c9; font-weight: bold; text-align: right">{{ $total['PACKING']['hour_22'] }}</td>
            @endif
            <td colspan="3"
                style="font-size: 13px; background-color: #3eab82c9; font-weight: bold; text-align: right">{{ number_format($total['PACKING']['total']) }}
                <span style="font-size: 10px">PCS</span>
            </td>
        </tr>
        </tbody>
    @else
        <tbody>
        <tr>
            <td colspan="27">No Data Available</td>
        </tr>
        </tbody>
    @endif
</table>
