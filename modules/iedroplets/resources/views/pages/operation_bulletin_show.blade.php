@extends('iedroplets::layout')
@section('title', 'Operation Bulletin')
@section('styles')
  <style>
    @media print {
      .noprint,
      .highcharts-legend-item,
      .highcharts-axis-title {
        display: none !important;
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

      .box-header {
        padding: 0px !important;
        margin: 0px !important;
      }

      .box-header h2 {
        color: #000;
        font-size: 7px;
      }

      .div-position-1 {
        padding: 0 10px;
        width: 100%;
      }

      .div-position-2 {
        width: 100%;
        float: left !important;
        padding: 5px 5px 0px !important;
        margin-top: -15px !important;
      }

      .div-position-3 {
        clear: both;
        margin-bottom: 0px !important;
      }

      /* table style */
      .table-position-1 {
        width: 250px;
        float: left;
      }

      .float-left {
        float: left !important;
      }

      .float-right {
        float: right !important;
      }

      .position-center {
        margin-left: -250px !important;
        position: relative;
        left: 50%;
        -webkit-transform: translateX(-50%);
        -moz-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        -o-transform: translateX(-50%);
        transform: translateX(-50%);
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
        color: #000;
        font-style: normal !important;
        padding: 0px !important;
        font-size: 5px !important;
        text-align: center;
      }

      .reportTable th,
      .reportTable td {
        color: #000;
        padding: 0px !important;
        font-size: 6px !important;
        font-style: normal !important;
        border: 1px solid #000 !important;
      }

      .attach-image {
        height: 100px;
        width: 180px;
        position: relative;
        left: 35px;
      }

      /*chart*/
      .line-chart {
        float: left !important;
        height: 260px !important;
        width: 100%;
      }

      .line-chart > div {
        float: left !important;
      }

      .line-chart > div > svg {
        float: left !important;
        margin: 0 !important;
        padding: 0 !important;
        height: 280px;
        width: auto;
      }

      .highcharts-title {
        font-size: 10px !important;
      }

      .highcharts-exporting-group {
        display: none;
      }

      .highcharts-axis-labels text {
        font-size: 6px !important;
      }

      .signature {
        color: #000;
        font-size: 7px;
        font-style: normal;
        padding-top: 10px !important;
      }

      .padding {
        padding: 15px 10px 0px !important;
      }

      .box-header:first-of-type h2 {
        padding-left: 10px;
      }
    }

    #container {
      margin-top: -22px !important;
      background: transparent !important;
    }
  </style>
@endsection
@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Operation Bulletin <span class="pull-right noprint">
                    <a href="{{ url('/operation-bulletin-download/'.request()->get('id')) }}">
                        <i style="color: #DC0A0B" class="fa fa-file-pdf-o"></i>
                    </a>&nbsp;|&nbsp;
                    <a class="pull-right" onclick="window.print()">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </a></span>
        </h2>
      </div>

      <div class="row div-position-1">
        <div class="col-md-4 table-position-1 float-left">
          <table class="reportTable">
            <tbody>
            <tr>
              <th>Prepared Date</th>
              <th> {{ $operation_bulletin->prepared_date ?? '' }}</th>
            </tr>
            <tr>
              <th>Input date</th>
              <th> {{ $operation_bulletin->input_date ?? 'N/A' }}</th>
            </tr>
            <tr>
              <th>Floor</th>
              <th> {{ $operation_bulletin->floor->floor_no ?? 'N/A' }}</th>
            </tr>
            <tr>
              <th>Line</th>
              <th> {{ $operation_bulletin->line->line_no ?? 'N/A' }}</th>
            </tr>
            <tr>
              <th>Buyer</th>
              <th> {{ $operation_bulletin->buyer->name ?? 'N/A' }}</th>
            </tr>
            <tr>
              <th>Order</th>
              <th> {{ $operation_bulletin->order->style_name ?? 'N/A' }}</th>
            </tr>
            <tr>
              <th>Item</th>
              @php
                $items = null;
                $itemDetails = $operation_bulletin->order->item_details ?? null;
                if ($itemDetails && is_array($itemDetails) && array_key_exists('details', $itemDetails)) {
                  $items = collect($itemDetails['details'])->pluck('item_name')->implode(', ');
                }
              @endphp
              <th> {{ $items ?? 'N/A' }}</th>
            </tr>
            <tr>
              <th>GSM</th>
              <th> {{ $operation_bulletin->order->gsm ?? 'N/A' }}</th>
            </tr>
            </tbody>
          </table>
        </div>
        <div class="col-md-4 table-position-1 position-center">
          <img class="attach-image" alt="No Image" height="220" width="380"
               src="@if(isset($operation_bulletin->sketch)){{ asset('/storage/sketch_images/'.$operation_bulletin->sketch) }}@else{{ asset('/flatkit/assets/images/no_image.png') }}@endif">
        </div>
        @if($operation_bulletin)
          @php
            $maxTime = $operation_bulletin->operationBulletinDetails
              ->sortByDesc('time')->first()->time ?? 0;
            $newMaxTime = $operation_bulletin->operationBulletinDetails
              ->sortByDesc('new_time')->first()->new_time ?? 0;
            $shortestCycleTime = $operation_bulletin->operationBulletinDetails
              ->sortByDesc('new_time')->first()->new_time ?? 0;
            $operationBulletinDetails = $operation_bulletin->operationBulletinDetails ?? [];
            $totalNewWorkstation = $operationBulletinDetails->sum('new_work_station');
            $targetHour = ($newMaxTime > 0) ? (int) (3600/$newMaxTime) : 0;
            $efficiency = number_format(($operationBulletinDetails->sum('time') / ($newMaxTime * $operationBulletinDetails->sum('new_work_station'))) * 100, 2);
          @endphp
        @endif
        <div class="col-md-4 table-position-1 float-right">
          <table class="reportTable" width="33%">
            <tbody>
            <tr>
              <th>Fab. Type</th>
              <th> {{-- $operation_bulletin->order->details->first()->fabric_type ?? 'N/A' --}}</th>
            </tr>
            <tr>
              <th>Order Qty</th>
              <th> {{ $operation_bulletin->order->pq_qty_sum ?? '' }}</th>
            </tr>
            <tr>
              <th>Proposed Target</th>
              <th> {{ $operation_bulletin->proposed_target ?? '' }}</th>
            </tr>
            <tr>
              <th>Total SAM</th>
              <th>{{ number_format($operationBulletinDetails->sum('time') / 60, 2) }}</th>
            </tr>
            <tr>
              <th>No. of Workstation</th>
              <th>{{ $totalNewWorkstation ?? 0 }}</th>
            </tr>
            <tr>
              <th>Line Efficiency</th>
              <th>{{ $efficiency }}%</th>
            </tr>
            <tr>
              <th>Shortest Cycle Time</th>
              <th>{{ $shortestCycleTime ?? 0 }}</th>
            </tr>
            <tr>
              <th>100% Target/Hr</th>
              <th>{{ $targetHour }}</th>
            </tr>
            </tbody>
          </table>
        </div>
      </div>

      <div class="box-header div-position-2">
        <h2>Operation Bulletin Details:</h2>

        <table class="reportTable">
          <thead>
          <tr>
            <th style="width: 20px; white-space: normal;">Sl.</th>
            <th style="width: 160px;">Task</th>
            <th style="width: 60px; white-space: normal;">Machine Type</th>
            <th style="width: 80px;">Operator Skill</th>
            <th style="width: 20px; white-space: normal;">Work Station</th>
            <th style="width: 20px; white-space: normal;">Time(s)</th>
            <th style="width: 20px; white-space: normal;">Idle Time</th>
            <th style="width: 20px; white-space: normal;">New Work Station</th>
            <th style="width: 20px; white-space: normal;">New Time(s)</th>
            <th style="width: 20px; white-space: normal;">New Idle Time</th>
            <th style="width: 200px; white-space: normal;">Remarks</th>
            <th style="width: 50px; white-space: normal;">Tgt./Hr</th>
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
                <td>{{ $loop->iteration }}</td>
                <td style="text-align: left;">{{ $bulletindetail->task->name ?? '' }}</td>
                <td style="text-align: left;">{{ $bulletindetail->machineType->name ?? '' }}</td>
                <td style="text-align: left;">{{ $bulletindetail->operatorSkill->name ?? '' }}</td>
                <td style="text-align: right;">{{ $bulletindetail->work_station ?? '' }}</td>
                <td style="text-align: right;">{{ $bulletindetail->time }}</td>
                <td style="text-align: right;">{{ $bulletindetail->idle_time  }}</td>
                <td style="text-align: right;">{{ $bulletindetail->new_work_station  }}</td>
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
                <td style="text-align: right;">{{ $bulletindetail->new_time }}</td>
                <td style="text-align: right;">{{ $bulletindetail->new_idle_time }}</td>
                <td style="text-align: left;">{{ $bulletindetail->remarks ?? '' }}</td>
                <td style="text-align: right;">{{ round(3600 / $bulletindetail->new_time) }}</td>
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
              <td colspan="4">Total</td>
              <td style="text-align: right;">{{ $operationBulletinDetails->sum('work_station') }}</td>
              <td style="text-align: right;">{{ $operationBulletinDetails->sum('time') }}</td>
              <td style="text-align: right;">{{ $operationBulletinDetails->sum('idle_time') }}</td>
              <td style="text-align: right;">{{ $operationBulletinDetails->sum('new_work_station') }}</td>
              <td style="text-align: right;">{{ $operationBulletinDetails->sum('new_time') }}</td>
              <td style="text-align: right;">{{ $operationBulletinDetails->sum('new_idle_time') }}</td>
              <td style="text-align: right;">{{ '' }}</td>
              <td style="text-align: right; padding-right: 2px">{{ $totalTarget }}</td>
            </tr>
          @else
            <tr>
              <td colspan="8" align="center">No Operation Bulletins
              <td>
            </tr>
          @endif
          </tbody>
        </table>
      </div>
      <div id="container" class="line-chart"></div>

      <div class="row div-position-3">
        <div class="col-sm-1"></div>
        <div class="col-sm-3">
          <table style="" class="reportTable">
            <thead>
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
            </thead>
          </table>
        </div>

        <div class="col-sm-1"></div>

        <div class="col-sm-3">
          <table style="" class="reportTable">
            <thead>
            <tr>
              <td colspan="2"><b>Operator Skill Summary</b></td>
            </tr>
            @php $totalSkill = 0; @endphp
            @foreach($operationBulletinDetails->groupby('operator_skill_id') as $opSkill)
              @php $totalSkill += count($opSkill); @endphp
              <tr>
                <td>{{ $opSkill->first()->operatorSkill->name ?? '' }}</td>
                <td>{{ count($opSkill) }}</td>
              </tr>
            @endforeach
            <tr style="font-weight: bold">
              <td>Total</td>
              <td>{{ $totalSkill ?? 0}}</td>
            </tr>
            </thead>
          </table>
        </div>

        <div class="col-sm-1"></div>

        <div class="col-sm-2">
          <table style="" class="reportTable">
            <thead>
            <tr>
              <td colspan="2"><b>M/C Summary</b></td>
            </tr>
            @php $totalMachineSummary = 0; @endphp
            @foreach($operationBulletinDetails->groupby('machine_type_id') as $machineType)
              @php $totalMachineSummary += count($machineType); @endphp
              <tr>
                <td>{{ $machineType->first()->machineType->name ?? '' }}</td>
                <td>{{ count($machineType) }}</td>
              </tr>
            @endforeach
            <tr style="font-weight: bold">
              <td>Total</td>
              <td>{{ $totalMachineSummary ?? 0}}</td>
            </tr>
            </thead>
          </table>
        </div>
      </div>

      <div class="row signature" style="font-weight: bold;padding-top: 60px;">
        <div class="col-sm-1"></div>
        <div class="col-sm-2">IE Executive</div>
        <div class="col-sm-2">Mechanic</div>
        <div class="col-sm-2">APM/PM</div>
        <div class="col-sm-2">IE Manager</div>
        <div class="col-sm-2">GM(Production)</div>
        <div class="col-sm-1"></div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
  <script src="{{ asset('/modules/skeleton/lib/highchart/highcharts.js') }}"></script>
  <script src="{{ asset('/modules/skeleton/lib/highchart/modules/series-label.js') }}"></script>
  <script src="{{ asset('/modules/skeleton/lib/highchart/modules/exporting.js') }}"></script>
  <script src="{{ asset('/modules/skeleton/lib/highchart/modules/export-data.js') }}"></script>

  @php
    echo '<script type="text/javascript">
      var taskData = ' . json_encode($chartTaskData) . '
      var chartTimeData = ' . json_encode($chartTimeData) . '
      var chartTargetData = ' . json_encode($chartTargetData) . '</script>';
  @endphp

  <script type="text/javascript">
    Highcharts.setOptions({ // Apply to all charts
      chart: {
        events: {
          beforePrint: function () {
            this.oldhasUserSize = this.hasUserSize;
            this.resetParams = [this.chartWidth, this.chartHeight, false];
            this.setSize(500, 100, false);
          },
          afterPrint: function () {
            this.setSize.apply(this, this.resetParams);
            this.hasUserSize = this.oldhasUserSize;
          }
        }
      }
    });
    Highcharts.chart({
      chart: {
        renderTo: 'container',
        backgroundColor: 'transparent',
        reflow: true
      },
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
        },
        labels: {
          style: {
            "color": '#000',
            "font-weight": "bold",
            "font-size": "10px"
          },
        }
      },

      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
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
      exporting: {
        enabled: true
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
            maxWidth: 600
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
    var printUpdate = function () {
      $('#container').highcharts().reflow();
    };

    if (window.matchMedia) {
      var mediaQueryList = window.matchMedia('print');
      mediaQueryList.addListener(function (mql) {
        printUpdate();
      });
    }
  </script>
  <style type="text/css">
    .highcharts-credits {
      display: none;
    }
  </style>
@endsection
