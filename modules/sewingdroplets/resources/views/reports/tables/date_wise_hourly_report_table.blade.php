@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
  $lineWiseHourShowData = getLineWiseHourShowData();
  $totalHours = collect($lineWiseHourShowData)->filter(function($item) {
    return $item > 0;
  })->count();
@endphp
<table id="fixTable" class="reportTable" style="border-collapse: collapse;">
  <thead>
  @isset($type)
    <tr>
      <th colspan="{{ 15 + $totalHours}}" style="font-size: 14px !important">{{ sessionFactoryName() }}</th>
    </tr>
    <tr>
      <th colspan="{{ 15 + $totalHours}}" style="font-size: 14px !important">
        <b>
          Line Wise Hourly Sewing Production Report
          | Reported Date: {{ date("jS F, Y", strtotime($date)) }}
        </b>
      </th>
    </tr>
  @endisset
  <tr style="background: #98FB98;">
    <th {{isset($type) || request()->has('type') || request()->route('type') ? "" : 'width="5%"'}}>Floor</th>
    <th style="width:59px !important">Line</th>
    <th>Buyer</th>
    <th>Order</th>
    <th>Item</th>
    <th>PO</th>
    <th>Color</th>
    <th>SMV</th>
    <th>MP</th>
    <th>Hourly<br>Target</th>
    <th>Day<br>Target</th>
    @if($lineWiseHourShowData['hour_0'])
    <th>12-1<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_1'])
    <th>1-2<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_2'])
    <th>2-3<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_3'])
    <th>3-4<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_4'])
    <th>4-5<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_5'])
    <th>5-6<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_6'])
    <th>6-7<br/>AM</th>
    @endif
    @if($lineWiseHourShowData['hour_7'])
    <th>7-8<br/>AM</th>
    @endif
    <th>8-9<br/>AM</th>
    <th>9-10<br/>AM</th>
    <th {{ isset($type) || request()->has('type') || request()->route('type')  ? "" : 'width="4%"'}}>10-11<br/>AM</th>
    <th {{ isset($type) || request()->has('type') || request()->route('type')  ? "" : 'width="4%"'}}>11-12<br/>AM</th>
    <th>12-1<br/>PM</th>
    <th>BR</th>
    <th>2-3<br/>PM</th>
    <th>3-4<br/>PM</th>
    <th>4-5<br/>PM</th>
    @if($lineWiseHourShowData['hour_17'])
    <th>5-6<br/>PM</th>
    @endif
    @if($lineWiseHourShowData['hour_18'])
    <th>6-7<br/>PM</th>
    @endif
    @if($lineWiseHourShowData['hour_19'])
    <th>7-8<br/>PM</th>
    @endif
    @if($lineWiseHourShowData['hour_20'])
    <th>8-9<br/>PM</th>
    @endif
    @if($lineWiseHourShowData['hour_21'])
    <th>9-10<br/>PM</th>
    @endif
    @if($lineWiseHourShowData['hour_22'])
    <th>10-11<br/>PM</th>
    @endif
    @if($lineWiseHourShowData['hour_23'])
    <th>11-12<br/>PM</th>
    @endif
    <th>Total</th>
    <th>Hourly<br/>Avg</th>
    <th>Line<br/>Eff.</th>
    {{-- <th>Prod.<br/>Eff.</th>  --}}
    <th {{ isset($type) || request()->has('type') || request()->route('type')  ? "" : 'width="6%"'}}>Remarks</th>
  </tr>
  </thead>
  <tbody>
  @php
    $grand_0 = 0;
    $grand_1 = 0;
    $grand_2 = 0;
    $grand_3 = 0;
    $grand_4 = 0;
    $grand_5 = 0;
    $grand_6 = 0;
    $grand_7 = 0;
    $grand_8 = 0;
    $grand_9 = 0;
    $grand_10 = 0;
    $grand_11 = 0;
    $grand_12 = 0;
    $grand_14 = 0;
    $grand_15 = 0;
    $grand_16 = 0;
    $grand_17 = 0;
    $grand_18 = 0;
    $grand_19 = 0;
    $grand_20 = 0;
    $grand_21 = 0;
    $grand_22 = 0;
    $grand_23 = 0;
    $grand_total = 0;
    $grand_avg = 0;
    $grand_production_minutes = 0;
    $grand_used_minutes = 0;
    $grand_avg_production = 0;
    $grand_avg_plan_target = 0;
    $grand_hourly_target = 0;
    $grand_day_target = 0;
  @endphp
  @foreach($sewing_outputs as $floor_id => $line_wise_sewing_outputs)
    @foreach($line_wise_sewing_outputs as $sewing_output)
      @php
        if(!isset($sewing_output['line'])) {
          continue;
        }
        /*$productionEffBackground = 'red';
        if ($sewing_output['production_efficiency'] > 89) {
          $productionEffBackground = 'yellow';
        }
        if ($sewing_output['production_efficiency'] > 99) {
          $productionEffBackground = '#00ff4c';
        }*/
        $hour_0 = $sewing_output['hour_0'] > 0 ? $sewing_output['hour_0'] : 0;
        $hour_1 = $sewing_output['hour_1'] > 0 ? $sewing_output['hour_1'] : 0;
        $hour_2 = $sewing_output['hour_2'] > 0 ? $sewing_output['hour_2'] : 0;
        $hour_3 = $sewing_output['hour_3'] > 0 ? $sewing_output['hour_3'] : 0;
        $hour_4 = $sewing_output['hour_4'] > 0 ? $sewing_output['hour_4'] : 0;
        $hour_5 = $sewing_output['hour_5'] > 0 ? $sewing_output['hour_5'] : 0;
        $hour_6 = $sewing_output['hour_6'] > 0 ? $sewing_output['hour_6'] : 0;
        $hour_7 = $sewing_output['hour_7'] > 0 ? $sewing_output['hour_7'] : 0;
        $hour_8 = $sewing_output['hour_8'] > 0 ? $sewing_output['hour_8'] : 0;
        $hour_9 = $sewing_output['hour_9'] > 0 ? $sewing_output['hour_9'] : 0;
        $hour_10 = $sewing_output['hour_10'] > 0 ? $sewing_output['hour_10'] : 0;
        $hour_11 = $sewing_output['hour_11'] > 0 ? $sewing_output['hour_11'] : 0;
        $hour_12 = $sewing_output['hour_12'] > 0 ? $sewing_output['hour_12'] : 0;
        $hour_14 = $sewing_output['hour_14'] > 0 ? $sewing_output['hour_14'] : 0;
        $hour_15 = $sewing_output['hour_15'] > 0 ? $sewing_output['hour_15'] : 0;
        $hour_16 = $sewing_output['hour_16'] > 0 ? $sewing_output['hour_16'] : 0;
        $hour_17 = $sewing_output['hour_17'] > 0 ? $sewing_output['hour_17'] : 0;
        $hour_18 = $sewing_output['hour_18'] > 0 ? $sewing_output['hour_18'] : 0;
        $hour_19 = $sewing_output['hour_19'] > 0 ? $sewing_output['hour_19'] : 0;
        $hour_20 = $sewing_output['hour_20'] > 0 ? $sewing_output['hour_20'] : 0;
        $hour_21 = $sewing_output['hour_21'] > 0 ? $sewing_output['hour_21'] : 0;
        $hour_22 = $sewing_output['hour_22'] > 0 ? $sewing_output['hour_22'] : 0;
        $hour_23 = $sewing_output['hour_23'] > 0 ? $sewing_output['hour_23'] : 0;
      @endphp
      <tr>
        @if($loop->first)
          <td rowspan="{{ count($line_wise_sewing_outputs) }}" style="background-color:#75ade1;">
            {{ $sewing_output['floor'] }}
          </td>
        @endif

        <td>{{ $sewing_output['line'] }}</td>
        <td title="{{ $sewing_output['buyer'] }}">{{ substr($sewing_output['buyer'], -9) }}</td>
        <td title="{{ $sewing_output['order'] }}">{{ substr($sewing_output['order'], -9) }}</td>
        <td title="{{ $sewing_output['item'] }}">{{ $sewing_output['item'] }}</td>
        <td title="{{ $sewing_output['po'] }}">{{ substr($sewing_output['po'], -9) }}</td>
        <td title="{{ $sewing_output['color'] }}">{{ substr($sewing_output['color'], -9) }}</td>
        <td>{{ number_format($sewing_output['smv'], 2) }}</td>
        <td>{{ $sewing_output['mp'] }}</td>
        <td style="background-color:#75ade1;">{{ $sewing_output['hourly_target'] }}</td>
        <td style="background-color:#75ade1;">{{ $sewing_output['day_target'] }}</td>
        @if($lineWiseHourShowData['hour_0'])
        <td>{{ $hour_0 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_1'])
        <td>{{ $hour_1 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_2'])
        <td>{{ $hour_2 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_3'])
        <td>{{ $hour_3 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_4'])
        <td>{{ $hour_4 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_5'])
        <td>{{ $hour_5 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_6'])
        <td>{{ $hour_6 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_7'])
        <td>{{ $hour_7 }}</td>
        @endif
        <td>{{ $hour_8 }}</td>
        <td>{{ $hour_9 }}</td>
        <td>{{ $hour_10 }}</td>
        <td>{{ $hour_11 }}</td>
        <td>{{ $hour_12 }}</td>
        <td></td>
        <td>{{ $hour_14 }}</td>
        <td>{{ $hour_15 }}</td>
        <td>{{ $hour_16 }}</td>
        @if($lineWiseHourShowData['hour_17'])
        <td>{{ $hour_17 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_18'])
        <td>{{ $hour_18 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_19'])
        <td>{{ $hour_19 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_20'])
        <td>{{ $hour_20 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_21'])
        <td>{{ $hour_21 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_22'])
        <td>{{ $hour_22 }}</td>
        @endif
        @if($lineWiseHourShowData['hour_23'])
        <td>{{ $hour_23 }}</td>
        @endif
        <td style="background-color:#75ade1;">{{ $sewing_output['total_output'] }}</td>
        <td style="background-color:#75ade1;">{{ $sewing_output['hourly_avg_production'] }}</td>
            <td @if( $sewing_output['line_efficiency'] < 50 ) style="background-color:red;color:white;"
                @elseif( $sewing_output['line_efficiency']>=  50 && $sewing_output['line_efficiency'] <= 75) style="background-color:yellow;"
                @else style="background-color:green;color:white;" @endif >{{ number_format($sewing_output['line_efficiency'], 2) }}%
            </td>
        {{-- <td style="background-color:{{ $productionEffBackground }}">
          {{ number_format($sewing_output['production_efficiency'], 2) }}
        </td> --}}
        <td title="{{ $sewing_output['remarks'] }}">
          {{ substr(strtolower($sewing_output['remarks']), 0, 11) }}
        </td>
      </tr>
    @endforeach
    @php
      $grand_hourly_target += $floor_total[$floor_id]['total_hourly_target'];
      $grand_day_target += $floor_total[$floor_id]['total_day_target'];
      $grand_0 += $floor_total[$floor_id]['hour_0'];
      $grand_1 += $floor_total[$floor_id]['hour_1'];
      $grand_2 += $floor_total[$floor_id]['hour_2'];
      $grand_3 += $floor_total[$floor_id]['hour_3'];
      $grand_4 += $floor_total[$floor_id]['hour_4'];
      $grand_5 += $floor_total[$floor_id]['hour_5'];
      $grand_6 += $floor_total[$floor_id]['hour_6'];
      $grand_7 += $floor_total[$floor_id]['hour_7'];
      $grand_8 += $floor_total[$floor_id]['hour_8'];
      $grand_9 += $floor_total[$floor_id]['hour_9'];
      $grand_10 += $floor_total[$floor_id]['hour_10'];
      $grand_11 += $floor_total[$floor_id]['hour_11'];
      $grand_12 += $floor_total[$floor_id]['hour_12'];
      $grand_14 += $floor_total[$floor_id]['hour_14'];
      $grand_15 += $floor_total[$floor_id]['hour_15'];
      $grand_16 += $floor_total[$floor_id]['hour_16'];
      $grand_17 += $floor_total[$floor_id]['hour_17'];
      $grand_18 += $floor_total[$floor_id]['hour_18'];
      $grand_19 += $floor_total[$floor_id]['hour_19'];
      $grand_20 += $floor_total[$floor_id]['hour_20'];
      $grand_21 += $floor_total[$floor_id]['hour_21'];
      $grand_22 += $floor_total[$floor_id]['hour_22'];
      $grand_23 += $floor_total[$floor_id]['hour_23'];
      $grand_total += $floor_total[$floor_id]['total_output'];
      $grand_avg += $floor_total[$floor_id]['total_hourly_avg_production'];
      $grand_production_minutes += $floor_total[$floor_id]['total_production_minutes'];
      $grand_used_minutes += $floor_total[$floor_id]['total_used_minutes'];
      $grand_avg_production += $floor_total[$floor_id]['total_hourly_avg_production'];
      //$grand_avg_plan_target += $floor_total[$floor_id]['total_hourly_avg_plan_target'];
    @endphp
    <tr style="font-weight:bold;">
      <td colspan="8">{{ $floor_total[$floor_id]['floor_no'] . ' Total' }}</td>
      <td>{{ $floor_total[$floor_id]['total_hourly_target'] }}</td>
      <td>{{ $floor_total[$floor_id]['total_day_target'] }}</td>
      @if($lineWiseHourShowData['hour_0'])
      <td>{{ $floor_total[$floor_id]['hour_0'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_1'])
      <td>{{ $floor_total[$floor_id]['hour_1'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_2'])
      <td>{{ $floor_total[$floor_id]['hour_2'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_3'])
      <td>{{ $floor_total[$floor_id]['hour_3'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_4'])
      <td>{{ $floor_total[$floor_id]['hour_4'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_5'])
      <td>{{ $floor_total[$floor_id]['hour_5'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_6'])
      <td>{{ $floor_total[$floor_id]['hour_6'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_7'])
      <td>{{ $floor_total[$floor_id]['hour_7'] }}</td>
      @endif
      <td>{{ $floor_total[$floor_id]['hour_8'] }}</td>
      <td>{{ $floor_total[$floor_id]['hour_9'] }}</td>
      <td>{{ $floor_total[$floor_id]['hour_10'] }}</td>
      <td>{{ $floor_total[$floor_id]['hour_11'] }}</td>
      <td>{{ $floor_total[$floor_id]['hour_12'] }}</td>
      <td></td>
      <td>{{ $floor_total[$floor_id]['hour_14'] }}</td>
      <td>{{ $floor_total[$floor_id]['hour_15'] }}</td>
      <td>{{ $floor_total[$floor_id]['hour_16'] }}</td>
      @if($lineWiseHourShowData['hour_17'])
      <td>{{ $floor_total[$floor_id]['hour_17'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_18'])
      <td>{{ $floor_total[$floor_id]['hour_18'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_19'])
      <td>{{ $floor_total[$floor_id]['hour_19'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_20'])
      <td>{{ $floor_total[$floor_id]['hour_20'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_21'])
      <td>{{ $floor_total[$floor_id]['hour_21'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_22'])
      <td>{{ $floor_total[$floor_id]['hour_22'] }}</td>
      @endif
      @if($lineWiseHourShowData['hour_23'])
      <td>{{ $floor_total[$floor_id]['hour_23'] }}</td>
      @endif
      <td>{{ $floor_total[$floor_id]['total_output'] }}</td>
      <td>
        {{ round($floor_total[$floor_id]['total_hourly_avg_production']) }}
      </td>
      <td>
        {{ number_format($floor_total[$floor_id]['floor_efficiency'], 2) }}
      </td>
      {{-- <td>
        {{ number_format($floor_total[$floor_id]['floor_production_efficiency'], 2) }}
      </td> --}}
      <td></td>
    </tr>
  @endforeach
  @php
    $grandLineEff = ($grand_used_minutes > 0) ? number_format($grand_production_minutes * 100 / $grand_used_minutes, 2) : 0;
    /*$prodEff = ($grand_avg_plan_target > 0) ? number_format($grand_avg_production * 100 / $grand_avg_plan_target, 2) : 0;*/
  @endphp
  <tr style="font-weight:bold; background: #b8b894; height: 28px">
    <td colspan="9">Grand Total</td>
    <td>{{ $grand_hourly_target }}</td>
    <td>{{ $grand_day_target }}</td>
    @if($lineWiseHourShowData['hour_0'])
    <td>{{ $grand_0 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_1'])
    <td>{{ $grand_1 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_2'])
    <td>{{ $grand_2 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_3'])
    <td>{{ $grand_3 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_4'])
    <td>{{ $grand_4 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_5'])
    <td>{{ $grand_5 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_6'])
    <td>{{ $grand_6 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_7'])
    <td>{{ $grand_7 }}</td>
    @endif
    <td>{{ $grand_8 }}</td>
    <td>{{ $grand_9 }} </td>
    <td>{{ $grand_10 }}</td>
    <td>{{ $grand_11 }}</td>
    <td>{{ $grand_12 }}</td>
    <td>{{ '' }}</td>
    <td>{{ $grand_14 }}</td>
    <td>{{ $grand_15 }}</td>
    <td>{{ $grand_16 }}</td>
    @if($lineWiseHourShowData['hour_17'])
    <td>{{ $grand_17 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_18'])
    <td>{{ $grand_18 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_19'])
    <td>{{ $grand_19 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_20'])
    <td>{{ $grand_20 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_21'])
    <td>{{ $grand_21 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_22'])
    <td>{{ $grand_22 }}</td>
    @endif
    @if($lineWiseHourShowData['hour_23'])
    <td>{{ $grand_23 }}</td>
    @endif
    <td>{{ $grand_total }}</td>
    <td>{{ $grand_avg }}</td>
    <td>{{ $grandLineEff }}</td>
    {{-- <td>{{ $prodEff }}</td> --}}
    <td>{{ '' }}</td>
  </tr>
  </tbody>
</table>
