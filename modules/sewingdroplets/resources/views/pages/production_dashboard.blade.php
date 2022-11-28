<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>PROTRACKER | Automated Garments Production Tracking System</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
    type="text/css"/>

  <script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/tether/tether.min.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/bootstrap/bootstrap.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>
  <script src="{{ asset('modules/skeleton/lib/highchart/highcharts.src.js') }}"></script>

  <style type="text/css">
    .container-full {
      overflow: hidden;
      margin: 15px 0px;
      width: 100%;
    }

    .reportTable thead,
    .reportTable tbody,
    .reportTable th {
      padding: 1px;
    }
  </style>

  @php
  echo '<script type="text/javascript">
    var data = ' . json_encode($data) . '
  </script>';
  @endphp

  <script type="text/javascript">
    // refresh page every 30 sec
    setTimeout(function(){
       window.location.reload(1);
    }, 90000);
  </script>
</head>

<body>
  <div class="container-full" id="productionDashboard">
    <div class="row">
      <div class="col-md-5">
        <div id="targetAchievment"></div>
      </div>
      <div class="col-md-2">
        <table width="100%" class="text-center">
          <thead>
            <tr>
              <th colspan="3" style="color: #75ade1;">{{ date('Y-m-d h:i:s a') }}</th>
            </tr>
            <tr>
              <th colspan="3" class="text-center" style="font-size: 0.8rem;">
                {{--{{ groupName() }} <br>--}}{{ sessionFactoryName() }}
              </th>
            </tr>
            <tr>
              <th colspan="3" class="text-center" style="font-size: 0.8rem;">
                Previous Day Production
              </th>
            </tr>
          </thead>
          <tbody style="font-size: 11px">
            @if($floors)
            @foreach($floors as $floor)
            <tr>
              <td>{{ $floor->floor_no }}</td>
              <td>T-{{ $floor->last_day_target ?? 0 }}</td>
              <td @if( $floor->last_day_target > $floor->last_day_ouptut ) style="color:red"
                @endif>P-{{ $floor->last_day_ouptut ?? 0 }}</td>
            </tr>
            @endforeach
            @endif
          </tbody>
        </table>
      </div>
      <div class="col-md-5">
        <div id="productionTarget"></div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-5">
        <div id="lineTarget" style="margin: 0 auto"></div>
      </div>
      <div class="col-md-7">
        <h5 class="text-center">Line wise Hourly Production</h5>
        <table width="90%" class="reportTable text-center" style="margin-left: -10px;">
          <thead>
            <tr style="font-size: 12px">
              <th style="width: 6% !important">Line</th>
              <th>Buyer</th>
              <th>Order</th>
              <th>Item</th>
              <th>PO</th>
              <th>Hourly<br />Target</th>
              <th>Day<br />Target</th>
              <th>8-9<br />AM</th>
              <th>9-10<br />AM</th>
              <th>10-11<br />AM</th>
              <th>11-12<br />PM</th>
              <th>12-1<br />PM</th>
              <th>BR</th>
              <th>2-3<br />PM</th>
              <th>3-4<br />PM</th>
              <th>4-5<br />PM</th>
              <th>5-6<br />PM</th>
              <th>6-7<br />PM</th>
              <th>Total</th>
              <th>Hr.<br />Avg</th>
              <th>Line<br />Eff.</th>
              <th width="15%">Reason behind less production</th>
            </tr>
          </thead>
          <tbody style="font-size: 10px" id="lineWiseProduction">
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <style type="text/css">
    .graph-title {
      font-size: 22px;
      font-weight: bold;
      color: #125C5C;
    }
  </style>
  <script type="text/javascript">
    var imageUrl = "{{ asset('modules/skeleton/flatkit/assets/images/protrackerNew.png') }}"; 
  </script>

  <script type="text/javascript" src="{{ asset('protracker/target-achievment.js') }}"></script>
  <script type="text/javascript" src="{{ asset('protracker/production-target.js') }}"></script>
  <script type="text/javascript" src="{{ asset('protracker/line-target.js') }}"></script>
</body>

</html>