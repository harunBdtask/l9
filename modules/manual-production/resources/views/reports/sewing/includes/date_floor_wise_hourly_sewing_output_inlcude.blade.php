<div class="row m-t">
    <div class="col-sm-12">
        @if($floor_id)
            <table class="reportTable" style="border: 1px solid black; border-collapse: collapse;"
                   id="fixTable">
                <thead>
                <tr style="background-color: #d1f8c5;">
                    <th>{{$floors[$floor_id]}}</th>
                    <th colspan="{{count($lines)}}"></th>
                    <th>Date: {{ date('d/m/Y', strtotime($date)) }}</th>
                </tr>
                <tr>
                    <th>Buyer</th>
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $buyer = $reports->where('line_id', $line_id)->first()->buyer->name ?? '';
                            @endphp
                            <td>{{ $buyer }}</td>
                        @else
                            <td>&nbsp;</td>
                        @endif
                    @endforeach
                    <th>Total</th>
                </tr>
                <tr>
                    <th>Style</th>
                    @foreach($lines as $line_id=>$line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $style = $reports->where('line_id', $line_id)->first()->order->style_name ?? '';
                            @endphp
                            <td>{{$style}}</td>
                        @else
                            <td>&nbsp;</td>
                        @endif
                    @endforeach
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Item</th>
                    @foreach($lines as $line_id=>$line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $item = $reports->where('line_id', $line_id)->first()->garmentsItem->name ?? '';
                            @endphp
                            <td>{{$item}}</td>
                        @else
                            <td>&nbsp;</td>
                        @endif
                    @endforeach
                    <th></th>
                </tr>
                <tr>
                    <th>Order Qty</th>
                    @php
                        $total_order_qty=0;
                    @endphp
                    @foreach($lines as $line_id=>$line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $order_qty = $reports->where('line_id', $line_id)->first()->order->pq_qty_sum ?? '';
                                $total_order_qty+=$order_qty;
                            @endphp
                            <td>{{(int)$order_qty}}</td>
                        @else
                            <td>&nbsp;</td>
                        @endif
                    @endforeach
                    <th>{{(int)$total_order_qty}}</th>
                </tr>
                <tr style="background-color: #c8d1f8;">
                    <th colspan="{{ count($lines) + 2 }}">INPUT STATUS</th>
                </tr>
                <tr>
                    <th>Today Input</th>
                    @php
                        $total_today_input = 0;
                        $total_line_input=[];
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $today_input_qty = $reports->where('line_id',$line_id)->sum('production_qty');
                                $total_today_input += $today_input_qty;
                                $total_line_input[$line_id] = $today_input_qty;
                            @endphp
                            <td>{{(int)$today_input_qty}}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{(int)$total_today_input}}</th>
                </tr>
                <tr>
                    <th>Previous Input</th>
                    @php
                        $total_prev_input = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $prev_input = collect($floorwise_manual_productions)->where('production_date', $prev_date)
                                    ->where('line_id' , $line_id)->sum('production_qty');
                                $total_prev_input += $prev_input;
                                $total_line_input[$line_id] += $prev_input;
                            @endphp
                            <td>{{ (int)$prev_input }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ (int)$total_prev_input }}</th>
                </tr>
                <tr>
                    <th>Total Input</th>
                    @php
                        $t_total_input = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $total_input = $total_line_input[$line_id];
                                $t_total_input += $total_input;
                            @endphp
                            <td>{{ $total_input }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $t_total_input }}</th>
                </tr>
                <tr style="background-color: #c8d1f8;">
                    <th colspan="{{ count($lines) + 2 }}">OUTPUT STATUS</th>
                </tr>
                <tr>
                    <th>Line No</th>
                    @foreach($lines as $line)
                        <td>{{ $line ?? '' }}</td>
                    @endforeach
                    <th>Total</th>
                </tr>
                <tr>
                    <th>H/Target</th>
                    @foreach($lines as $line)
                        <td>0</td>
                    @endforeach
                    <td>0</td>
                </tr>
                <tr>
                    <th>08:00-09:00</th>
                    @php
                        $total_hour_8 = 0;
                        $output_hours=[];
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_8 = $reports->where('line_id', $line_id)->sum('hour_8');
                                $output_hours[$line_id]['hour_8'] = $hour_8;
                                $total_hour_8 += $hour_8;
                            @endphp
                            <td>{{ $hour_8 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_8 }}</th>
                </tr>
                <tr>
                    <th>09:00-10:00</th>
                    @php
                        $total_hour_9 = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_9 = $reports->where('line_id', $line_id)->sum('hour_9');
                               $output_hours[$line_id]['hour_9'] =$hour_9;
                                $total_hour_9 += $hour_9;
                            @endphp
                            <td>{{ $hour_9 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_9 }}</th>
                </tr>
                <tr>
                    <th>10:00-11:00</th>
                    @php
                        $total_hour_10 = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_10 = $reports->where('line_id', $line_id)->sum('hour_10');
                                $output_hours[$line_id]['hour_10'] = $hour_10;
                                $total_hour_10 += $hour_10;
                            @endphp
                            <td>{{ $hour_10 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_10 }}</th>
                </tr>
                <tr>
                    <th>11:00-12:00</th>
                    @php
                        $total_hour_11 = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_11 = $reports->where('line_id', $line_id)->sum('hour_11');
                                $output_hours[$line_id]['hour_11'] = $hour_11;
                                $total_hour_11 += $hour_11;
                            @endphp
                            <td>{{ $hour_11 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_11 }}</th>
                </tr>
                <tr>
                    <th>12:00-01:00</th>
                    @php
                        $total_hour_12 = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_12 = $reports->where('line_id', $line_id)->sum('hour_12');
                                $output_hours[$line_id]['hour_12'] = $hour_12;
                                $total_hour_12 += $hour_12;
                            @endphp
                            <td>{{ $hour_12 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_12 }}</th>
                </tr>
                <tr style="background-color: #c8d1f8;">
                    <th colspan="{{ count($lines) + 2 }}">LUNCH TIME</th>
                </tr>
                <tr>
                    <th>02:00-03:00</th>
                    @php
                        $total_hour_14 = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_13 = $reports->where('line_id', $line_id)->sum('hour_13');
                                $hour_14 = $reports->where('line_id', $line_id)->sum('hour_14')+$hour_13;
                                $output_hours[$line_id]['hour_14'] = $hour_14;
                                $total_hour_14 += $hour_14;
                            @endphp
                            <td>{{ $hour_14 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_14 }}</th>
                </tr>
                <tr>
                    <th>03:00-04:00</th>
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_15 = $reports->where('line_id', $line_id)->sum('hour_15') ;
                                $output_hours[$line_id]['hour_15'] = $hour_15;
                            @endphp
                            <td>{{ $hour_15 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $reports->sum('hour_15') }}</th>
                </tr>
                <tr>
                    <th>04:00-05:00</th>
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $output_hours[$line_id]['hour_16'] = $reports->where('line_id', $line_id)->sum('hour_16');
                            @endphp
                            <td>{{ $output_hours[$line_id]['hour_16'] }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $reports->sum('hour_16') }}</th>
                </tr>
                <tr>
                    <th>05:00-06:00</th>
                    @php
                        $total_hour_17 = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_17 = $reports->where('line_id', $line_id)->sum('hour_17') ;
                                $output_hours[$line_id]['hour_17'] = $hour_17;
                                $total_hour_17 += $hour_17;
                            @endphp
                            <td>{{ $hour_17 }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_hour_17 }}</th>
                </tr>
                <tr>
                    <th>06:00-07:00</th>
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $output_hours[$line_id]['hour_18'] = $reports->where('line_id', $line_id)->sum('hour_18') ;
                            @endphp
                            <td>{{ $output_hours[$line_id]['hour_18'] }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $reports->sum('hour_18') }}</th>
                </tr>
                <tr style="background-color: #eeeec0;">
                    <th>Today Output</th>
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $hour_output = array_sum($output_hours[$line_id]);
                                $total_line_today_prev_output[$line_id] = $hour_output;
                            @endphp
                            <td>{{ $hour_output }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ collect($output_hours)->flatten()->sum() }}</th>
                </tr>
                <tr style="background-color: #eeeec0;">
                    <th>Previous Day Output</th>
                    @php
                        $total_previous_output = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $prev_output = collect($floorwise_manual_productions)->where('line_id' , $line_id)
                                ->where('production_date', $prev_day)->first()->total_prev_output ?? 0;
                                $total_line_today_prev_output[$line_id] += $prev_output;
                                $total_previous_output += $prev_output;
                            @endphp
                            <td>{{ $prev_output }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th> {{$total_previous_output}}</th>
                </tr>
                <tr style="background-color: #eeeec0;">
                    <th>Total Output</th>
                    @php
                        $total_today_previous_output = 0;
                    @endphp
                    @foreach($lines as $line_id => $line)
                        @if($reports->where('line_id', $line_id)->count())
                            @php
                                $total_today_previous_output += $total_line_today_prev_output[$line_id];
                            @endphp
                            <td>{{ $total_line_today_prev_output[$line_id] }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @endforeach
                    <th>{{ $total_today_previous_output }}</th>
                </tr>
                </tbody>
            </table>
        @else
            <p class="text-center" style="border: 1px solid black">No Data</p>
        @endif
    </div>
</div>
