@php
  $tableHeadColorClass = 'tableHeadColor';
  if (isset($type) || request()->has('type') || request()->route('type')) {
    $tableHeadColorClass = '';
  }
@endphp
@extends('sewingdroplets::layout')
@section('refresh')
  <meta http-equiv="refresh" content="60"/>
@endsection
@section('title', 'Line Wise Hourly Sewing Production')
@section('content')
  <div class="padding">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header text-center">
            <h2>Line Wise Hourly Sewing Production || {{ date("jS F, Y") }} <span class="pull-right"><a
                    href="{{url('line-wise-hourly-sewing-output-report-download/pdf')}}"><i style="color: #DC0A0B"
                                                                                            class="fa fa-file-pdf-o"></i></a> | <a
                    href="{{url('line-wise-hourly-sewing-output-report-download/xls')}}"><i style="color: #0F733B"
                                                                                            class="fa fa-file-excel-o"></i></a></span>
            </h2>
          </div>
          <div class="box-divider m-a-0"></div>
          <div class="box-body">
            <div id="parentTableFixed" class="table-responsive">
              <table class="reportTable" id="fixTable">
                <thead>
                <tr>
                  <th style="width: 4% !important">Floor</th>
                  <th style="width: 4% !important">Line</th>
                  <th>Buyer</th>
                  <th>Order/Style</th>
                  <th>PO</th>
                  <th>Colour</th>
                  <th>Target<br>/hr</th>
                  <th>8-9 AM</th>
                  <th>9-10 AM</th>
                  <th>10-11 AM</th>
                  <th>11-12 PM</th>
                  <th>12-1 PM</th>
                  <th>BR</th>
                  <th>2-3 PM</th>
                  <th>3-4 PM</th>
                  <th>4-5 PM</th>
                  <th>5-6 PM</th>
                  <th>6-7 PM</th>
                  <th>Hourly Avg</th>
                  <th>Total</th>
                  <th>Line Efficiency</th>
                  <th>Current Line Status</th>
                </tr>
                </thead>
                <tbody>
                @if(isset($floors))
                  @php
                    $total_target = 0;
                    $total_am8 = 0;
                    $total_am9 = 0;
                    $total_am10 = 0;
                    $total_am11 = 0;
                    $total_pm12 = 0;
                    $total_pm1 = 0;
                    $total_pm2 = 0;
                    $total_pm3 = 0;
                    $total_pm4 = 0;
                    $total_pm5 = 0;
                    $total_pm6 = 0;
                    $total_avg = 0;
                    $total_sum = 0;
                  @endphp
                  @foreach($floors as $floor)
                    @php
                      $total_target += $floor->line_data->sum('target');
                      $total_am8 += $floor->line_data->sum('am8');
                      $total_am9 += $floor->line_data->sum('am9');
                      $total_am10 += $floor->line_data->sum('am10');
                      $total_am11 += $floor->line_data->sum('am11');
                      $total_pm12 += $floor->line_data->sum('pm12');
                      $total_pm1 += $floor->line_data->sum('pm1');
                      $total_pm2 += $floor->line_data->sum('pm2');
                      $total_pm3 += $floor->line_data->sum('pm3');
                      $total_pm4 += $floor->line_data->sum('pm4');
                      $total_pm5 += $floor->line_data->sum('pm5');
                      $total_pm6 += $floor->line_data->sum('pm6');
                      $total_avg += $floor->line_data->sum('avg');
                      $total_sum += $floor->line_data->sum('total_output');
                    @endphp
                    @foreach($floor->line_data as $report)
                      <tr>
                        <td style="background-color:#75ade1;">{{ $report->floor->floor_no ?? '' }}</td>
                        <td style="background-color:#75ade1;">{{ $report->line_no }}</td>
                        <td>{{ $report->buyer }}</td>
                        <td>{{ $report->order }}</td>
                        <td>{{ $report->po }}</td>
                        <td>{{ $report->color }}</td>
                        <td style="background-color:#75ade1;">{{ $report->target }}</td>
                        <td>{{ $report->am8 }}</td>
                        <td>{{ $report->am9 }}</td>
                        <td>{{ $report->am10 }}</td>
                        <td>{{ $report->am11 }}</td>
                        <td>{{ $report->pm12 }}</td>
                        <td>{{ '' }}</td>
                        <td>{{ $report->pm2 }}</td>
                        <td>{{ $report->pm3 }}</td>
                        <td>{{ $report->pm4 }}</td>
                        <td>{{ $report->pm5 }}</td>
                        <td>{{ $report->pm6 }}</td>
                        <td style="background-color:#75ade1;">{{ $report->avg }}</td>
                        <td style="background-color:#75ade1;">{{ $report->total_output }}</td>
                        <td @if( $report->efficiency < 50 ) style="background-color:red;"
                            @elseif( $report->efficiency>  50 && $report->efficiency < 60) style="background-color:yellow;"
                            @else style="background-color:green;" @endif >{{ $report->efficiency }}%
                        </td>
                        <td style="background-color:#75ade1;">{{ $report->line_staus ? 'inactive' : 'active'  }}</td>
                      </tr>
                    @endforeach
                    <tr style="font-weight:bold;">
                      <td colspan="6">{{ $report->floor->floor_no ?? '' }} = Total</td>
                      <td>{{ $floor->line_data->sum('target') }}</td>
                      <td>{{ $floor->line_data->sum('am8') }}</td>
                      <td>{{ $floor->line_data->sum('am9') }}</td>
                      <td>{{ $floor->line_data->sum('am10') }}</td>
                      <td>{{ $floor->line_data->sum('am11') }}</td>
                      <td>{{ $floor->line_data->sum('pm12') }}</td>
                      <td>{{ '' }}</td>
                      <td>{{ $floor->line_data->sum('pm2') }}</td>
                      <td>{{ $floor->line_data->sum('pm3') }}</td>
                      <td>{{ $floor->line_data->sum('pm4') }}</td>
                      <td>{{ $floor->line_data->sum('pm5') }}</td>
                      <td>{{ $floor->line_data->sum('pm6') }}</td>
                      <td>{{-- $floor->line_data->sum('avg') --}}</td>
                      <td>{{ $floor->line_data->sum('total_output') }}</td>
                      <td>{{ '' }}</td>
                      <td>{{ '' }}</td>
                    </tr>

                  @endforeach
                  <tr style="height:50px;font-size:16px; font-weight:bold;">
                    <td colspan="6">Total</td>
                    <td>{{ $total_target ?? 0 }}</td>
                    <td>{{ $total_am8 }}</td>
                    <td>{{ $total_am9 }}</td>
                    <td>{{ $total_am10 }}</td>
                    <td>{{ $total_am11 }}</td>
                    <td>{{ $total_pm12 }} </td>
                    <td>{{ '' }}</td>
                    <td>{{ $total_pm2 }}</td>
                    <td>{{ $total_pm3 }}</td>
                    <td>{{ $total_pm4 }}</td>
                    <td>{{ $total_pm5 }}</td>
                    <td>{{ $total_pm6 }}</td>
                    <td>{{-- $total_avg --}}</td>
                    <td>{{ $total_sum }}</td>
                    <td>{{ '' }}</td>
                    <td>{{ '' }}</td>
                  </tr>
                @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
