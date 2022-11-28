@if($reportData && count($reportData))
  @foreach($reportData as $data)
  <tr>
    <td>{{ $data['buyer'] }}</td>
    <td>{{ $data['merchant_name'] }}</td>
    <td>{{ $data['item'] }}</td>
    <td>{{ $data['style_name'] }}</td>
    <td>{{ $data['ref_no'] }}</td>
    <td>{{ $data['fabric_type'] }}</td>
    <td>{{ $data['color'] }}</td>
    <td style="text-align: right">{{ round($data['order_qty']) }}</td>
    <td style="text-align: right">{{ round($data['today_cutting']) }}</td>
    <td style="text-align: right">{{ round($data['total_cutting']) }}</td>
    <td style="text-align: right">{{ round($data['total_cutting_rejection']) }}</td>
    <td style="text-align: right">{{ round($data['ok_cutting_qty']) }}</td>
    <td style="text-align: right">{{ round($data['cutting_balance']) }}</td>
    <td style="text-align: right">{{ round($data['today_print_send']) }}</td>
    <td style="text-align: right">{{ round($data['total_print_send']) }}</td>
    <td style="text-align: right">{{ round($data['print_send_balance']) }}</td>
    <td style="text-align: right">{{ round($data['today_embr_send']) }}</td>
    <td style="text-align: right">{{ round($data['total_embr_send']) }}</td>
    <td style="text-align: right">{{ round($data['embr_send_balance']) }}</td>
    <td style="text-align: right">{{ round($data['today_print_received']) }}</td>
    <td style="text-align: right">{{ round($data['total_print_received']) }}</td>
    <td style="text-align: right">{{ round($data['print_received_balance']) }}</td>
    <td style="text-align: right">{{ round($data['today_embr_received']) }}</td>
    <td style="text-align: right">{{ round($data['total_embr_received']) }}</td>
    <td style="text-align: right">{{ round($data['embr_received_balance']) }}</td>
    <td style="text-align: right">{{ round($data['today_input']) }}</td>
    <td style="text-align: right">{{ round($data['total_input']) }}</td>
    <td style="text-align: right">{{ round($data['input_balance']) }}</td>
    <td></td>
  </tr>
  @endforeach
  <tr style="background-color: gainsboro">
    <td colspan="7" class="text-right"><b>Total</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('order_qty')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('today_cutting')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_cutting')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_cutting_rejection')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('ok_cutting_qty')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('cutting_balance')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('today_print_send')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_print_send')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('print_send_balance')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('today_embr_send')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_embr_send')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('embr_send_balance')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('today_print_received')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_print_received')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('print_received_balance')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('today_embr_received')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_embr_received')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('embr_received_balance')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('today_input')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('total_input')) }}</b></td>
    <td class="text-right"><b>{{ round(collect($reportData)->sum('input_balance')) }}</b></td>
    <td></td>
  </tr>
@else
  <tr>
    <th colspan="27">No Data Found</th>
  </tr>
@endif