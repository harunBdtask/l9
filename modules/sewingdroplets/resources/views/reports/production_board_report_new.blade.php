<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta http-equiv="refresh" content="60"/>
  <title>PROTRACKER | Automated Garments Production Tracking System</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">  

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/pro(64x64).ico') }}">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/pro(64x64).ico') }}">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

  <script src="{{ asset('libs/jquery/jquery/dist/jquery.js') }}"></script>
  <script src="{{ asset('libs/jquery/tether/dist/js/tether.min.js') }}"></script>
  <script src="{{ asset('libs/jquery/bootstrap/dist/js/bootstrap.js') }}"></script>
  {{--<link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css" />--}}
<!--   <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
 -->

  <style type="text/css">
    .container-full {
      overflow: hidden;
      margin-top: 25px; 
      padding-left: 20px !important;   
      padding-right: 20px !important;
      width: 100%;
    }
    .reportTable {
      margin-bottom: 1rem;
      width: 100%;
      max-width: 100%;
    }
    .reportTable thead,
    .reportTable tbody,
    .reportTable th, 
    .reportTable td {
      padding:0 !important; 
      margin:0 !important;     
      font-size: 11px;
      text-align: center;
    }
    .reportTable th,
    .reportTable td {
      border: 1px solid #000000;
    }
    .rotate {
      -moz-transform: rotate(-90.0deg);  /* FF3.5+ */
      -o-transform: rotate(-90.0deg);  /* Opera 10.5 */
      -webkit-transform: rotate(-90.0deg);  /* Saf3.1+, Chrome */
      filter:  progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083);  /* IE6,IE7 */
      -ms-filter: "progid:DXImageTransform.Microsoft.BasicImage(rotation=0.083)"; /* IE8 */
    }

   /* .weekly-inespection-font {
      font-size: 11px !important
    }*/
  </style>

</head>

<body>
  <div class="container-full" id="productionDashboard">
    <div class="row">
      <div class="col-sm-9">
        <table class="reportTable">
          <thead style="background-color:#75ade1;">
            <tr>
              <th colspan="10">General Information</th>
              <th colspan="11">Hours</th>
              <th colspan="3">Others</th>
            </tr>
            <tr>
              <th width="1%">Floor</th>
              <th width="5%">Line<br/>No.</th>
              <th>Buyer</th>
              <th>Booking</th>
              <th>Order/Style</th>          
              <th>PO</th>
              <th>Color</th>
              <th>Input<br/>Date</th>
              <th>Output<br/>Finish Date</th>
              <th>Ins.<br/>Date</th>
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
              <th width="10%">Reasons behind<br/>less production</th>
              <th>Next<br/>Schedule</th>                  
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
                    <td class="rotate" style="white-space: nowrap;" rowspan="{{ count($line_wise_sewing_outputs) }}">
                      {{ $sewing_output['floor'] }}
                    </td>
                  @endif

                  <td>{{ $sewing_output['line'] }}</td>
                  <td title="{{ $sewing_output['buyer'] }}">{{ substr($sewing_output['buyer'], -8) }}</td>
                  <td title="{{ $sewing_output['booking_no'] }}">{{ substr($sewing_output['booking_no'], -8) }}</td>
                  <td title="{{ $sewing_output['order'] }}">{{ substr($sewing_output['order'], -8) }}</td>                 
                  <td title="{{ $sewing_output['po'] }}">{{ substr($sewing_output['po'], -8) }}</td>
                  <td title="{{ $sewing_output['color'] }}">{{ substr($sewing_output['color'], -8) }}</td>
                  <td>{{ $sewing_output['input_date'] ? date('j-M-y', strtotime($sewing_output['input_date'])) : '' }}</td>
                  <td>{{ $sewing_output['output_finish_date'] ? date('j-M-y', strtotime($sewing_output['output_finish_date'])) : '' }}</td>
                  <td>{{ $sewing_output['inspection_date'] ? date('j-M-y', strtotime($sewing_output['inspection_date'])) : '' }}</td>
                  <td>{{ ($sewing_output['hour_8'] > 0) ? $sewing_output['hour_8'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_9'] > 0) ? $sewing_output['hour_9'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_10'] > 0) ? $sewing_output['hour_10'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_11'] > 0) ? $sewing_output['hour_11'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_12'] > 0) ? $sewing_output['hour_12'] : '' }}</td>
                  <td></td>
                  <td>{{ ($sewing_output['hour_14'] > 0) ? $sewing_output['hour_14'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_15'] > 0) ? $sewing_output['hour_15'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_16'] > 0) ? $sewing_output['hour_16'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_17'] > 0) ? $sewing_output['hour_17'] : '' }}</td>
                  <td>{{ ($sewing_output['hour_18'] > 0) ? $sewing_output['hour_18'] : '' }}</td>
                  <td style="font-weight: bold">{{ ($sewing_output['total_output'] > 0) ? $sewing_output['total_output'] : '' }}</td>
                  <td title="{{ $sewing_output['remarks'] }}">
                    {{ substr(strtolower($sewing_output['remarks']) ?? '', 0, 20) }}
                  </td>
                  <td title="{{ $sewing_output['next_schedule'] }}">{{ substr(strtolower($sewing_output['next_schedule']) ?? '', -10) }}</td>
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
              <tr style="font-weight:bold; background-color:#75ade1;">
                <td colspan="10">{{ $floor_total[$floor_id]['floor_no'] . ' Total' }}</td>                   
                <td>{{ ($floor_total[$floor_id]['hour_8'] > 0) ? $floor_total[$floor_id]['hour_8'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_9'] > 0) ? $floor_total[$floor_id]['hour_9'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_10'] > 0) ? $floor_total[$floor_id]['hour_10'] : ''  }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_11'] > 0) ? $floor_total[$floor_id]['hour_11'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_12'] > 0) ? $floor_total[$floor_id]['hour_12'] : '' }}</td>
                <td></td>
                <td>{{ ($floor_total[$floor_id]['hour_14'] > 0) ? $floor_total[$floor_id]['hour_14'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_15'] > 0) ? $floor_total[$floor_id]['hour_15'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_16'] > 0) ? $floor_total[$floor_id]['hour_16'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_17'] > 0) ? $floor_total[$floor_id]['hour_17'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['hour_18'] > 0) ? $floor_total[$floor_id]['hour_18'] : '' }}</td>
                <td>{{ ($floor_total[$floor_id]['total_output'] > 0) ? $floor_total[$floor_id]['total_output'] : '' }}</td>
                <td></td>
                <td></td>
              </tr>
            @endforeach                                      
              <tr style="font-weight:bold; background: #b8b894; height: 28px">
                <td colspan="10">Grand Total</td>                        
                <td>{{ ($grand_8 > 0) ? $grand_8 : '' }}</td>
                <td>{{ ($grand_9 > 0) ? $grand_9 : '' }} </td>
                <td>{{ ($grand_10 > 0) ? $grand_10 : '' }}</td>
                <td>{{ ($grand_11 > 0) ? $grand_11 : '' }}</td>
                <td>{{ ($grand_12 > 0) ? $grand_12 : '' }}</td>
                <td>{{ '' }}</td>
                <td>{{ ($grand_14 > 0) ? $grand_14 : '' }}</td>
                <td>{{ ($grand_15 > 0) ? $grand_15 : '' }}</td>
                <td>{{ ($grand_16 > 0) ? $grand_16 : '' }}</td>
                <td>{{ ($grand_17 > 0) ? $grand_17 : '' }}</td>
                <td>{{ ($grand_18 > 0) ? $grand_18 : '' }}</td>
                <td>{{ ($grand_total > 0) ? $grand_total : '' }}</td>                        
                <td>{{ '' }}</td>
                <td>{{ '' }}</td>
              </tr>
            </tbody>
        </table>
      </div>
      <div class="col-sm-3">
          <table class="reportTable" style="padding:0 !important; margin: 0 !important">
            <thead style="background-color:#75ade1;">
              <tr>
                <th colspan="6">Weekly Inspection Status</th>
              </tr>
              <tr>
                <th>Buyer</th>
                <th>Style</th>
                <th>Buyer Ref</th>                     
                <th>Ins.Qty</th>
                <th>Ins.Date</th>
                <th>Remarks</th>
              </tr>
            </thead>
            <tbody>
              @forelse($weeklyInspectionData as $inspectionData)
                <tr>
                  <td class="weekly-inespection-font" title="{{ $inspectionData->style->buyer->name ?? 'N/A'}}">{{ substr($inspectionData->style->buyer->name, -7) }}</td>
                  <td class="weekly-inespection-font" title="{{ $inspectionData->style->name }}">{{ substr($inspectionData->style->name ?? '', -9) }}</td>
                  <td class="weekly-inespection-font" title="{{ $inspectionData->style->buyer_reference }}">{{ substr($inspectionData->style->buyer_reference ?? '', -9) }}</td>                  
                  <td class="weekly-inespection-font">{{ $inspectionData->inspection_quantity }}</td>
                  <td class="weekly-inespection-font">{{ $inspectionData->inspection_date ? date('j-M-y', strtotime($inspectionData->inspection_date)) : '' }}</td> 
                  <td title="{{ $inspectionData->remarks ?? '' }}" class="weekly-inespection-font">{{ substr($inspectionData->remarks ?? '', -15) }}</td>     
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
</body>
</html>