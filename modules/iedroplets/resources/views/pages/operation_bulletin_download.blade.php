<!DOCTYPE html>

<html>
<head>
    <title>Operation Bulletin</title>
{{--    @include('reports.downloads.includes.pdf-styles')--}}
  <style>
    /*header { position: fixed; top: -100px; left: 0px; right: 0px; text-align: center; height: 30px; }*/
    header * {
      font-size: 10px;
      margin: 2px 0;
      text-align: center;
    }
    h2 {
      font-size: 12px;
    }
    h4, .highcharts-title {
      font-size: 10px !important;
    }
    table{
      border-spacing: 0px;
      border-collapse: collapse;
    }
    .highcharts-credits,
    .highcharts-exporting-group,
    .highcharts-legend-item,
    .highcharts-axis-title {
      display: none;
    }
    .app-header ~ .app-body {
      padding-top: 0rem;
      margin: 0px !important;
    }

    .app-content {
      margin-left: 0rem !important;
    }

    .app-header {
      margin: 0px !important;
    }
    .reportTable {
      /*margin-bottom: 1rem;*/
      width: 100%;
      height: 30px;
      max-width: 100%;
    }
    .reportTable thead,
    .reportTable tbody,
    .reportTable th {
      font-style: normal !important;
      padding: 0px !important;
      font-size: 6px !important;
      text-align: center;
    }
    .reportTable th,
    .reportTable td {
      padding: 0px !important;
      font-size: 6px !important;
      font-style: normal !important;
      border: 1px solid #e7e7e7;
    }
    .footer {
      width: 100%;
      position: fixed;
      bottom: 0cm;
      left: 0cm;
      right: 0cm;
      height: 2cm;
    }
    .footer td {
      font-size: 10px;
    }
    footer { position: fixed; bottom: -100px; font-size: 7px; left: 0px; right: 0px; text-align: center; height: 20px; }
  </style>
  <script src="{{ asset('js/charts/highcharts.js') }}"></script>
  <script src="{{ asset('js/charts/modules/series-label.js') }}"></script>
  <script src="{{ asset('js/charts/modules/exporting.js') }}"></script>
  <script src="{{ asset('js/charts/modules/export-data.js') }}"></script>

</head>
<body>
@include('reports.downloads.includes.pdf-header')
<main>  
<div class="padding"> 
  <div class="box">
    <div class="box-header">
      <h2 align="center">Operation Bulletin</h2>
    </div>

      <table width="100%" >
        <tr>
          <td width="33%">
            <table class="reportTable">
              <tbody>
                <tr><th>Prepared Date</th><th> {{ $operation_bulletin->prepared_date ?? '' }}</th></tr>
                <tr><th>Input date</th><th> {{ $operation_bulletin->input_date ?? 'N/A' }}</th></tr>
                <tr><th>Floor</th><th> {{ $operation_bulletin->floor->floor_no ?? 'N/A' }}</th></tr>
                <tr><th>Line</th><th> {{ $operation_bulletin->line->line_no ?? 'N/A' }}</th></tr>
                <tr><th>Buyer</th><th> {{ $operation_bulletin->buyer->name ?? 'N/A' }}</th></tr>
                <tr><th>Style</th><th> {{ $operation_bulletin->style->name ?? 'N/A' }}</th></tr>
                <tr><th>Order</th><th> {{ $operation_bulletin->order->order_no ?? 'N/A' }}</th></tr>
                <tr><th>Item</th><th> {{ $operation_bulletin->style->item_name ?? 'N/A' }}</th></tr>
                <tr><th>Gsm</th><th> {{ $operation_bulletin->order->gsm ?? 'N/A' }}</th></tr>
              </tbody>
            </table>
          </td>
          <td width="33%">
            <img alt="No Image" height="100" width="380"
          src="@if(isset($operation_bulletin->sketch)){{ asset('/storage/sketch_images/'.$operation_bulletin->sketch) }}@else{{ asset('/flatkit/assets/images/no_image.png') }}@endif">
          </td>
          @if($operation_bulletin)
            @php 
              $maxTime = $operation_bulletin->operationBulletinDetails
                ->sortByDesc('time')->first()->time ?? 0;
              $newMaxTime = $operation_bulletin->operationBulletinDetails
                ->sortByDesc('new_time')->first()->time ?? 0;
              $operationBulletinDetails = $operation_bulletin->operationBulletinDetails ?? [];
              $totalNewWorkstation = $operationBulletinDetails->sum('new_work_station');
              $targetHour = ($newMaxTime > 0) ? (int) (3600/$newMaxTime) : 0;
              $efficiency = number_format(($operationBulletinDetails->sum('time') / ($newMaxTime * $operationBulletinDetails->sum('new_work_station'))) * 100, 2);
            @endphp
          @endif
          <td width="33%">
            <table class="reportTable" width="33%">
              <thead>
                <tr><th>Fab. Type</th><th> {{ $operation_bulletin->order->fab_type ?? 'N/A' }}</th></tr>   
                <tr><th>Order Qty</th><th> {{ $operation_bulletin->order->total_quantity ?? '' }}</th></tr>
                <tr><th>Proposed Target</th><th> {{ $operation_bulletin->proposed_target ?? '' }}</th></tr>
                <tr><th>Total SAM</th><th>{{ number_format($operationBulletinDetails->sum('time') / 60, 2) }}</th></tr>
                <tr><th>No. of Workstation</th><th>{{ $totalNewWorkstation ?? 0 }}</th></tr>
                <tr><th>Line Efficiency</th><th>{{ $efficiency }}%</th></tr>
                <tr><th>Shortest Cycle Time</th><th>{{ $newMaxTime ?? 0 }}</th></tr>
                <tr><th>100% Target/Hr</th><th>{{ $targetHour }}</th></tr>
              </thead>
            </table>
          </td>
        </tr>
      </table>
    

    <div class="box-header">
      <h2>Operation Bulletin Details:</h2>
    </div> 
    <table class="reportTable">
      <thead>
        <tr>
          <th>Task</th>
          <th>Machine Type</th>
          <th>Operator Skill</th>
          <th>Work Station</th>
          <th>Time(s)</th>
          <th>Idle Time</th>
          <th>New Work Station</th>
          <th>New Time(s)</th>
          <th>New Idle Time</th>
          <th>Remarks</th>
          <th>Tgt./Hr</th>
        </tr>
      </thead>
      <tbody>
        @if($operation_bulletin)
          @php
            $chartTaskData = [];
            $chartTimeData = [];
            $chartTargetData = [];
            $specialMachines = [];
            $specialOperations = [];
            $guideFolderNo = 0;
            $totalTarget = 0;
          @endphp       
          @foreach($operationBulletinDetails as $bulletindetail)
            <tr>
              <td>{{ $bulletindetail->task->name ?? '' }}</td>
              <td>{{ $bulletindetail->machineType->name ?? '' }}</td>
              <td>{{ $bulletindetail->operatorSkill->name ?? '' }}</td>
              <td>{{ $bulletindetail->work_station ?? '' }}</td>
              <td>{{ $bulletindetail->time }}</td>
              <td>{{ $bulletindetail->idle_time  }}</td>
              <td>{{ $bulletindetail->new_work_station  }}</td>
              @php
                if ($bulletindetail->guide_or_folder_id) {
                  ++$guideFolderNo;
                }

                if (($bulletindetail->special_machine == 1) && 
                !in_array($bulletindetail->machineType->name, $specialMachines)) {
                  $specialMachines[] = $bulletindetail->machineType->name; 
                }

                if (($bulletindetail->special_task == 1) && 
                !in_array($bulletindetail->task->name, $specialOperations)) {
                  $specialOperations[] = $bulletindetail->task->name; 
                }
              @endphp
              <td>{{ $bulletindetail->new_time }}</td>
              <td>{{ $bulletindetail->new_idle_time }}</td>
              <td>{{ $bulletindetail->remarks ?? '' }}</td>
              <td>{{ round(3600 / $bulletindetail->new_time) }}</td>            
            </tr>
            @php
              $chartTaskData[] = $bulletindetail->task->name ?? '';
              $chartTimeData[] = $bulletindetail->time ?? 0;
              $target = round(3600 / $bulletindetail->new_time);
              $chartTargetData[] = $target;
              $totalTarget += $target;
            @endphp
          @endforeach
            <tr style="font-weight: bold;">
              <td colspan="3">Total</td>
              <td>{{ $operationBulletinDetails->sum('work_station') }}</td>
              <td>{{ $operationBulletinDetails->sum('time') }}</td>
              <td>{{ $operationBulletinDetails->sum('idle_time') }}</td>
              <td>{{ $operationBulletinDetails->sum('new_work_station') }}</td>
              <td>{{ $operationBulletinDetails->sum('new_time') }}</td>
              <td>{{ $operationBulletinDetails->sum('new_idle_time') }}</td>
              <td>{{ '' }}</td>
              <td>{{ $totalTarget }}</td>
            </tr>           
        @else
          <tr>
            <td colspan="11" align="center">No Operation Bulletins<td>
          </tr>
        @endif
      </tbody>
    </table>
    
    
    <div id="container"></div>

    <table width="100%">
      <tbody>
        <tr>
          <td width="30%" valign="top">
            <table width="100%" class="reportTable">            
              <tr>
                <td colspan="2"><b>Style's Status</b></td>
              </tr>
              <tr>
                <td>No. of workstation</td>
                <td>{{ $operationBulletinDetails->sum('new_work_station') ?? 0 }}</td>
              </tr>
              <tr>
                <td>Line Efficiency</td>
                <td>{{ $efficiency ?? '' }}</td>
              </tr>
              <tr>
                <td>Pattern Status</td>
                <td>{{ $operation_bulletin->pattern_status ?? 'N/A' }}</td>
              </tr>
              <tr>
                <td>Special M/C</td>
                <td>{{ $specialMachines ? implode(", ", $specialMachines) : 'N/A' }}</td>
              </tr>
              <tr>
                <td>Special Operation</td>
                <td>{{ $specialOperations ? implode(", ", $specialOperations) : 'N/A' }}</td>
              </tr> 
              <tr>
                <td>No .of Guide/Folder</td>
                <td>{{ $guideFolderNo }}</td>
              </tr>             
            </table> 
          </td>
          <td valign="top">
            <table width="30%" class="reportTable">
              <tr>
                <td colspan="2"><b>Operator Skill Summary</b></td>
              </tr>
              @php $totalSkill = 0; @endphp
              @foreach($operationBulletinDetails->groupby('operator_skill_id') as $opSkill)
                @php $totalSkill += count($opSkill); @endphp
                <tr>
                  <td>{{ $opSkill->first()->operatorSkill->name ?? '' }}</td><td>{{ count($opSkill) }}</td>
                </tr>
              @endforeach
              <tr style="font-weight: bold"><td>Total</td><td>{{ $totalSkill ?? 0}}</td></tr>         
            </table>
          </td>
          <td valign="top">
            <table width="30%" class="reportTable">          
              <tr>
                <td colspan="2"><b>M/C Summary</b></td>
              </tr>
              @php $totalMachineSummary = 0; @endphp
              @foreach($operationBulletinDetails->groupby('machine_type_id') as $machineType)
                @php $totalMachineSummary += count($machineType); @endphp
                <tr>
                  <td>{{ $machineType->first()->machineType->name ?? '' }}</td><td>{{ count($machineType) }}</td>
                </tr>
              @endforeach
              <tr style="font-weight: bold"><td>Total</td><td>{{ $totalMachineSummary ?? 0}}</td></tr>    
            </table>
          </td>
        </tr>
      </tbody>
    </table>
    <br/>    
    <br/>
  </div>

  <table class="footer">
    <tr>
      <td width="20%">IE Executive</td>
      <td width="20%">Mechanic</td>
      <td width="20%">APM/PM</td>
      <td width="20%">IE Manager</td>
      <td width="20%">GM(Production)</td>
    </tr>
  </table>

</div>
</main>
@php
  echo '<script type="text/javascript">
    var taskData = ' . json_encode($chartTaskData) . '
    var chartTimeData = ' . json_encode($chartTimeData) . '
    var chartTargetData = ' . json_encode($chartTargetData) . '</script>';
@endphp

<script type="text/javascript">
    Highcharts.chart('container', {
        title: {
            text: '<b>Operation Bulletin Graph:</b>'
        },
        /*
         subtitle: {
             text: 'Source: thesolarfoundation.com'
         },*/
        xAxis: {
            categories: taskData,
            crosshair: true,
            title: {
                text: '<b>Tasks</b>'
            }
        },

        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle'
        },

        "export": {
            "enabled": true,
            "menu": []
        },

        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            },
            line: {
                dataLabels: {
                    enabled: true
                },
                enableMouseTracking: true
            }
        },

        series: [{
            name: 'Target',
            data: chartTargetData

        }, {
            name: 'Time',
            data: chartTimeData
        }],

        responsive: {
            rules: [{
                condition: {
                    maxWidth: 500
                },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }
    });
</script>


@include('reports.downloads.includes.pdf-footer')
</body>
</html>