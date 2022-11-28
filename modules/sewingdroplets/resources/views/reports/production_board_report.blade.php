@extends('sewingdroplets::layout')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          {{--
          <div class="box-header text-center">
            <h2>Production Board || {{ date("jS F, Y") }}  <span class="pull-right"><a href="{{url('date-wise-hourly-sewing-output-report-download/pdf/'.$date)}}"><i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i></a> | <a href="{{url('date-wise-hourly-sewing-output-report-download/xls/'.$date)}}"><i style="color: #0F733B" class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div> --}}
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            {{--
              <form action="{{ url('/date-wise-hourly-sewing-output') }}" method="post">
                @csrf
                <div class="form-group">
                  <div class="row m-b">
                    <div class="col-sm-2">
                      <input type="date" name="date" class="form-control form-control-sm" required="required"  value="{{ $date ?? date('Y-m-d') }}">
                      @if($errors->has('date'))
                        <span class="text-danger">{{ $errors->first('date') }}</span>
                      @endif
                    </div>
                    <div class="col-sm-2">
                      <button type="submit" class="btn btn-sm white form-control form-control-sm">Search</button>
                    </div>
                  </div>
                </div>
              </form>
            --}}
            <div class="row">
              <div class="col-sm-9">
                <table class="reportTable">
                  <thead>
                    <tr>
                      <th colspan="10">Weekly Inspection Status</th>
                      <th colspan="11">Hours</th>
                      <th colspan="3">Others</th>
                    </tr>
                    <tr>
                      <th>Floor</th>
                      <th width="5%">Line<br/>No.</th>
                      <th>Buyer</th>
                      <th>Order/Style</th>
                      <th>PO</th>
                      <th>Color</th>
                      <th>Input<br/>Date</th>
                      <th>Output<br/>Finish Date</th>
                      <th>Inspection<br/>Date</th>
                      <th>8-9</th>
                      <th>9-10</th>
                      <th>10-11</th>
                      <th>11-12</th>
                      <th>12-1</th>
                      <th>BR</th>
                      <th>2-3</th>
                      <th>3-4</th>
                      <th>4-5</th>
                      <th>5-6</th>
                      <th>6-7</th>
                      <th>Total</th>
                      <th>Reasons behind<br/>less production</th>
                      <th style="background: #49DB1E">Next Schedule</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
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
                      $grand_total = 0;
                    @endphp
                    @foreach($sewing_outputs as $floor_id => $line_wise_sewing_outputs)
                      @foreach($line_wise_sewing_outputs as $sewing_output)
                        <tr>
                          @if($loop->first)
                            <td rowspan="{{ count($line_wise_sewing_outputs) }}" style="background-color:#75ade1;">
                              {{ $sewing_output['floor'] }}
                            </td>
                          @endif

                          <td>{{ $sewing_output['line'] }}</td>
                          <td title="{{ $sewing_output['buyer'] }}">{{ substr($sewing_output['buyer'], -10) }}</td>
                          <td title="{{ $sewing_output['our_reference'] }}">{{ substr($sewing_output['our_reference'], -10) }}</td>
                          <td title="{{ $sewing_output['order'] }}">{{ substr($sewing_output['order'], -10) }}</td>
                          <td title="{{ $sewing_output['color'] }}">{{ substr($sewing_output['color'], -10) }}</td>
                          <td>{{ $sewing_output['input_date'] }}</td>
                          <td>{{ $sewing_output['output_finish_date'] }}</td>
                          <td>{{ $sewing_output['inspection_date'] }}</td>
                          <td>{{ $sewing_output['hour_8'] }}</td>
                          <td>{{ $sewing_output['hour_9'] }}</td>
                          <td>{{ $sewing_output['hour_10'] }}</td>
                          <td>{{ $sewing_output['hour_11'] }}</td>
                          <td>{{ $sewing_output['hour_12'] }}</td>
                          <td></td>
                          <td>{{ $sewing_output['hour_14'] }}</td>
                          <td>{{ $sewing_output['hour_15'] }}</td>
                          <td>{{ $sewing_output['hour_16'] }}</td>
                          <td>{{ $sewing_output['hour_17'] }}</td>
                          <td>{{ $sewing_output['hour_18'] }}</td>
                          <td style="background-color:#75ade1;">{{ $sewing_output['total_output'] }}</td>
                          <td title="{{ $sewing_output['remarks'] }}">
                            {{ substr(strtolower($sewing_output['remarks']) ?? '', 0, 14) }}
                          </td>
                          <td style="background: #49DB1E" title="{{ $sewing_output['next_schedule'] }}">{{ substr(strtolower($sewing_output['next_schedule']) ?? '', -10) }}</td>
                        </tr>
                      @endforeach
                      @php
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
                        $grand_total += $floor_total[$floor_id]['total_output'];
                      @endphp
                      <tr style="font-weight:bold;">
                        <td colspan="9">{{ $floor_total[$floor_id]['floor_no'] . ' Total' }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_8'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_9'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_10'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_11'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_12'] }}</td>
                        <td></td>
                        <td>{{ $floor_total[$floor_id]['hour_14'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_15'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_16'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_17'] }}</td>
                        <td>{{ $floor_total[$floor_id]['hour_18'] }}</td>
                        <td>{{ $floor_total[$floor_id]['total_output'] }}</td>
                        <td></td>
                        <td></td>
                      </tr>
                    @endforeach
                      <tr style="font-weight:bold;">
                        <td colspan="9">Grand Total</td>
                        <td>{{ $grand_8 }}</td>
                        <td>{{ $grand_9 }} </td>
                        <td>{{ $grand_10 }}</td>
                        <td>{{ $grand_11 }}</td>
                        <td>{{ $grand_12 }}</td>
                        <td>{{ '' }}</td>
                        <td>{{ $grand_14 }}</td>
                        <td>{{ $grand_15 }}</td>
                        <td>{{ $grand_16 }}</td>
                        <td>{{ $grand_17 }}</td>
                        <td>{{ $grand_18 }}</td>
                        <td>{{ $grand_total }}</td>
                        <td>{{ '' }}</td>
                        <td>{{ '' }}</td>
                      </tr>
                    </tbody>
                </table>
              </div>
              <div class="col-sm-3">
                  <table class="reportTable">
                    <thead>
                      <tr>
                        <th colspan="6">Weekly Inspection Status</th>
                      </tr>
                      <tr>
                        <th>Buyer</th>
                        <th>Order/Style</th>
                        <th>Order Qty.</th>
                        <th>Inspection<br/>Date</th>
                      </tr>
                    </thead>
                    <tbody>
                      @forelse($weeklyInspectionData as $inspectionData)
                        <tr>
                          <td title="{{ $inspectionData->buyer->name ?? 'N/A'}}">{{ substr($inspectionData->buyer->name, -8) }}</td>
                          <td title="{{ $inspectionData->name }}">{{ substr($inspectionData->name, -10) }}</td>
                          @php
                            $styleWiseOrderWiseQty = 0;
                            foreach ($inspectionData->orders as $order) {
                              $styleWiseOrderWiseQty += $order->total_quantity;
                            }
                          @endphp
                          <td>{{ $styleWiseOrderWiseQty }}</td>
                          <td>{{ $inspectionData->inspection_date }}</td>
                        </tr>
                      @empty
                        <tr>
                          <td colspan="6" class="text-danger text-center">Data not found</td>
                        </tr>
                      @endforelse
                    </tbody>
                  <tbody>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style type="text/css">
      @media screen and (-webkit-min-device-pixel-ratio: 0){

      input[type=date].form-control form-control-sm{
        line-height: 1;
      }
      }
  </style>
@endsection
