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
    type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}"
    type="text/css" />

  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css" />

  <script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/tether/tether.min.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/bootstrap/bootstrap.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css" />

  <style>
    .container-full {
      overflow: hidden;
      margin: 15px 0;
      width: 100%;
    }

    .reportTable thead,
    .reportTable tbody,
    .reportTable th {
      padding: 1px;
    }

    body {
      color: #f1f1e8;
      font-size: 0.875rem;
      background-color: rgba(0, 0, 0, 0.87);
      -webkit-font-smoothing: antialiased;
    }

    .padding {
      padding: 0.1rem 1rem;
    }

    .reportTable thead>tr>th {
      padding: 2px;
      font-size: 11px;
      text-align: center;
    }

    .reportTable tbody>tr>td {
      padding: 2px;
      font-size: 13px;
      text-align: left;
    }

    .tile {
      border-radius: 1rem;
    }

    .box,
    .box-color {
      background-color: #fff;
      position: relative;
      margin-bottom: .2rem;
    }

    .dashboard-tiles .tile-icon {
      position: relative;
      box-shadow: 0 5px 5px 0 rgb(0 0 0 / 9%), 0 8px 9px 0 rgb(0 0 0 / 9%);
      overflow: hidden;
    }

    .dashboard-tiles .tile-icon::before {
      position: absolute;
      right: -25px;
      bottom: -61px;
      content: "";
      font: normal normal normal 180px/1 FontAwesome;
      color: #fff;
      opacity: .2;
    }

    .dashboard-tiles .tile-icon::after {
      position: absolute;
      top: -26px;
      right: -32px;
      content: "";
      font: normal normal normal 150px/1 FontAwesome;
      color: #fff;
      opacity: .2;
    }

    .ibiza-sunset-gradient {
      background-image: linear-gradient(45deg, #ee0979, #ff6a00);
      background-repeat: repeat-x;
    }

    .pomegranate-gradient {
      background-image: linear-gradient(45deg, #9b3cb7, #ff396f);
      background-repeat: repeat-x;
    }

    .green-tea-gradient {
      background-image: linear-gradient(45deg, #004b91, #78cc37);
      background-repeat: repeat-x;
    }

    #date,
    #clock,
    #floor-name {
      color: greenyellow;
    }

    .text-5x {
      font-size: 5rem;
    }
    .text-4-5x {
      font-size: 4.5rem;
    }
  </style>

  @php
  $lineWiseHourShowData = getLineWiseHourShowData();
  $totalHours = collect($lineWiseHourShowData)->filter(function($item) {
    return $item > 0;
  })->count();
  echo '<script type="text/javascript">
    var data = ' . json_encode($data) . ';
    var lineWiseHourShowData = '. json_encode($lineWiseHourShowData) .'
  </script>';
  @endphp

  <script>
    let totalFloors = Object.keys(data['floorLinesHourlyData']).length
    let timeOut = parseInt(totalFloors) * 30000;

    setTimeout(function () {
        window.location.reload(1);
    }, timeOut);
  </script>
</head>

<body>
  <div class="padding">
    <div class="container-full">
      <div class="row">
        <div class="col-md-4">
          <h3 class="text-left">
            Floor: <span id="floor-name"></span>
          </h3>
        </div>
        <div class="col-md-4">
          <h3 class="text-center">PRODUCTION DASHBOARD</h3>
        </div>
        <div class="col-md-4">
          <h3 class="text-right">
            <span id="date">{{ date('d M, Y') }}</span> <span id="clock"></span>
          </h3>
        </div>
      </div>

      <div class="row dashboard-tiles">
        <div class="col-md-4">
          <div class="tile box cursor-pointer p-a ibiza-sunset-gradient tile-icon" data-toggle="tooltip"
            data-placement="top" title="" data-original-title="Target">
            <div class="pull-right m-r">
              <em class="fa fa-bolt text-4-5x text-white m-y-sm"></em>
            </div>
            <div class="clear">
              <div class="text-white text-md">Target Till Hour</div>
              <h4 class="m-a-0 text-5x _600"><a id="floor-total-target">0</a></h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="tile box cursor-pointer p-a green-tea-gradient tile-icon" data-toggle="tooltip"
            data-placement="top" title="" data-original-title="Production">
            <div class="pull-right m-r">
              <em class="fa fa-level-up text-4-5x text-white m-y-sm"></em>
            </div>
            <div class="clear">
              <div class="text-white text-md">Total Production</div>
              <h4 class="m-a-0 text-5x _600"><a id="floor-total-production">0</a></h4>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="tile box cursor-pointer p-a pomegranate-gradient tile-icon" data-toggle="tooltip"
            data-placement="top" title="" data-original-title="Balance">
            <div class="pull-right m-r">
              <em class="fa fa-sort text-4-5x text-white m-y-sm"></em>
            </div>
            <div class="clear">
              <div class="text-white text-md">Balance</div>
              <h4 class="m-a-0 text-5x _600"><a id="floor-total-balance">0</a></h4>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container-full" id="productionDashboard">
      <div class="row">
        <div class="col-md-12">
          <table class="reportTable text-center">
            <thead>
              <tr>
                <th colspan="{{ 15 + $totalHours}}">Line Wise Hourly Production</th>
              </tr>
              <tr>
                <th>Line</th>
                <th>Buyer</th>
                <th>Order/Style</th>
                <th>Item</th>
                <th>PO</th>
                <th>Hr.<br />Target</th>
                <th>Day<br />Target</th>
                @if($lineWiseHourShowData['hour_0'])
                <th>12-1<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_1'])
                <th>1-2<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_2'])
                <th>2-3<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_3'])
                <th>3-4<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_4'])
                <th>4-5<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_5'])
                <th>5-6<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_6'])
                <th>6-7<br />AM</th>
                @endif
                @if($lineWiseHourShowData['hour_7'])
                <th>7-8<br />AM</th>
                @endif
                <th>8-9<br />AM</th>
                <th>9-10<br />AM</th>
                <th>10-11<br />AM</th>
                <th>11-12<br />PM</th>
                <th>12-1<br />PM</th>
                <th>BR</th>
                <th>2-3<br />PM</th>
                <th>3-4<br />PM</th>
                <th>4-5<br />PM</th>
                @if($lineWiseHourShowData['hour_17'])
                <th>5-6<br />PM</th>
                @endif
                @if($lineWiseHourShowData['hour_18'])
                <th>6-7<br />PM</th>
                @endif
                @if($lineWiseHourShowData['hour_19'])
                <th>7-8<br />PM</th>
                @endif
                @if($lineWiseHourShowData['hour_20'])
                <th>8-9<br />PM</th>
                @endif
                @if($lineWiseHourShowData['hour_21'])
                <th>9-10<br />PM</th>
                @endif
                @if($lineWiseHourShowData['hour_22'])
                <th>10-11<br />PM</th>
                @endif
                @if($lineWiseHourShowData['hour_23'])
                <th>11-12<br />PM</th>
                @endif
                <th>Total</th>
                <th>Hr.<br />Avg</th>
                <th>Line<br />Eff.</th>
                <th style="width:15%">Comments</th>
              </tr>
            </thead>
            <tbody style="font-size: 12px" id="lineWiseProduction">
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="container-full">
      <div class="row">
        <div class="col-md-12 text-center">
          <span class="text-muted" style="position: fixed; bottom: 10px; right: 36%;">&copy; Copyright - <img
              src="{{ asset('modules/skeleton/flatkit/assets/images/protrackerNew.png') }}" alt="PROTRACKER"
              style="height: 20px; width: auto;" /> Product of Skylark Soft Limited</span>
        </div>
      </div>
    </div>
  </div>

  <script>
    var height = $(window).height(),
        width = $('#lineTarget').width(),
        floors = [],
        lines  = [],
        target = [],
        output = [],
        efficiency = [];

    $.each( data['lineTarget'], function(key, val) {
        floors.push(key);
    });

    var floor_total = 0, floor_target = 0, floor_efficiency = 0;
    $.each( data['lineTarget'][floors[0]], function(key, val) {
        if (key != 'total_row') {
            floor_total = data['output'][floors[0]]['output'];
            floor_target = Math.round(data['output'][floors[0]]['target']);
            floor_efficiency =  parseFloat(parseFloat(Math.round(data['floorEfficiency'][floors[0]] * 100) / 100).toFixed(2));

            line_efficiency = parseFloat(parseFloat(Math.round(val.efficiency * 100) / 100).toFixed(2));
            line_target = Math.round(val.target);

            lines.push(key);
            target.push(line_target);
            output.push(val.output);
            efficiency.push(line_efficiency);
        }
    });
    hourlyProductionTable(data['floorLinesHourlyData'][floors[0]]);

    if (floors.length > 1) {
      var i = 1;
      setInterval(function() {
          var floor = floors[i++];

          lines  = [], target = [], output = []; efficiency = [];

          var floor_total = 0, floor_target = 0, floor_efficiency = 0;
          $.each( data['lineTarget'][floor], function(key, val) {
              if (key != 'total_row') {
                  floor_total = data['output'][floor]['output'];
                  floor_target = Math.round(data['output'][floor]['target']);
                  floor_efficiency = parseFloat(Math.round(data['floorEfficiency'][floor] * 100) / 100).toFixed(2);

                  line_efficiency = parseFloat(parseFloat(Math.round(val.efficiency * 100) / 100).toFixed(2));
                  line_target = Math.round(val.target);

                  lines.push(key);
                  target.push(line_target);
                  output.push(val.output);
                  efficiency.push(line_efficiency);
              }
          });

          hourlyProductionTable(data['floorLinesHourlyData'][floor]);

          if(i >= floors.length) i = 0;
      }, 30000);
    }
    function hourlyProductionTable(floorData) {
      var tr = '';
      let floor_no = floorData.total_row && floorData.total_row.floor_no ? floorData.total_row.floor_no : ""
      let floor_total_target = floorData.total_row && floorData.total_row.hour_passed_target ? parseInt(floorData.total_row.hour_passed_target) : 0
      let floor_total_production = floorData.total_row && floorData.total_row.total_output ? parseInt(floorData.total_row.total_output) : 0
      let floor_balance = floor_total_target > 0 && floor_total_production > 0 && floor_total_target > floor_total_production ? parseInt(floor_total_target - floor_total_production) : 0
      $('#floor-name').html(floor_no || "");
      $('#floor-total-target').html(floor_total_target || 0);
      $('#floor-total-production').html(floor_total_production || 0);
      $('#floor-total-balance').html(floor_balance || 0);

      $.each(floorData, function(key, report) {

        var styleLineEff = "color:#FFFFFF;background-color:red;";
        if (report.line_efficiency < 50) {
            styleLineEff = "color:#FFFFFF;background-color:red;";
        }

        if (report.line_efficiency >= 50 && report.line_efficiency <= 75) {
            styleLineEff = "color:#000000;background-color:yellow;";
        }

        if (report.line_efficiency > 75) {
            styleLineEff = "color:#ffffff;background-color:green;";
        }

        var buyer = report.buyer ? report.buyer : '';
        var order = report.order ? report.order : '';
        var item = report.item ? report.item : '';
        var po = report.po ? report.po.substring(0, 9) : '';
        var hourly_target = report.hourly_target ;
        var day_target = report.day_target ;
        var hourly_avg_production = report.hourly_avg_production;
        var efficiency = report.line_efficiency;

        var colspan = '';
        var colspanColumn = '';
        var conditionalColumnsFirstSection = '';
        var conditionalColumnsLastSection = '';
        var totalRowColor = '';
        var line_or_total;

        if (typeof report.line == 'undefined') {
            line_or_total = 'Total';
            colspan = 5;
            totalRowColor = 'background-color:#75ade1; font-weight:bold';

            hourly_target = report.total_hourly_target;
            day_target = report.total_day_target;
            hourly_avg_production = report.total_hourly_avg_production;
            efficiency = report.floor_efficiency;
        } else {
            line_or_total = report.line;
            var colspanColumn = '<td title="' + report.buyer + '" style="font-size: 11px;">' + buyer + '</td>' +
                '<td title="' + report.order + '" style="font-size: 11px;">' + order + '</td>'+
                '<td title="' + report.item + '" style="font-size: 11px;">' + item + '</td>'+
                '<td title="' + report.po + '" style="font-size: 11px;">' + po + '</td>';
        }

        if (efficiency < 50) {
            styleLineEff = "color:#FFFFFF;background-color:red;";
        }

        if (efficiency >= 50 && efficiency <= 75) {
            styleLineEff = "color:#000000;background-color:yellow;";
        }

        if (efficiency > 75) {
            styleLineEff = "color:#ffffff;background-color:green;";
        }

        conditionalColumnsFirstSection += (lineWiseHourShowData.hour_0 == 1 ? '<td>' + (report.hour_0 >= 0 ? report.hour_0 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_1 == 1 ? '<td>' + (report.hour_1 >= 0 ? report.hour_1 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_2 == 1 ? '<td>' + (report.hour_2 >= 0 ? report.hour_2 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_3 == 1 ? '<td>' + (report.hour_3 >= 0 ? report.hour_3 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_4 == 1 ? '<td>' + (report.hour_4 >= 0 ? report.hour_4 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_5 == 1 ? '<td>' + (report.hour_5 >= 0 ? report.hour_5 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_6 == 1 ? '<td>' + (report.hour_6 >= 0 ? report.hour_6 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_7 == 1 ? '<td>' + (report.hour_7 >= 0 ? report.hour_7 : 0) + '</td>' : '');
            
        conditionalColumnsLastSection += (lineWiseHourShowData.hour_17 == 1 ? '<td>' + (report.hour_17 >= 0 ? report.hour_17 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_18 == 1 ? '<td>' + (report.hour_18 >= 0 ? report.hour_18 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_19 == 1 ? '<td>' + (report.hour_19 >= 0 ? report.hour_19 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_20 == 1 ? '<td>' + (report.hour_20 >= 0 ? report.hour_20 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_21 == 1 ? '<td>' + (report.hour_21 >= 0 ? report.hour_21 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_22 == 1 ? '<td>' + (report.hour_22 >= 0 ? report.hour_22 : 0) + '</td>' : '') +
            (lineWiseHourShowData.hour_23 == 1 ? '<td>' + (report.hour_23 >= 0 ? report.hour_23 : 0) + '</td>' : '');

        tr  += [
            '<tr style="'+ totalRowColor +'">',
            '<td style="'+ (colspan && colspan > 1 ? 'background-color:#75ade1;' : styleLineEff) +'" colspan="'+ colspan +'">'+ line_or_total + '</td>'
            +colspanColumn+ '',
            '<td>' + hourly_target + '</td>',
            '<td>' + day_target + '</td>'
            + conditionalColumnsFirstSection + '',
            '<td>' + (report.hour_8 >= 0 ? report.hour_8 : 0) + '</td>',
            '<td>' + (report.hour_9 >= 0 ? report.hour_9 : 0) + '</td>',
            '<td>' + (report.hour_10 >= 0 ? report.hour_10 : 0) + '</td>',
            '<td>' + (report.hour_11 >= 0 ? report.hour_11 : 0) + '</td>',
            '<td>' + (report.hour_12 >= 0 ? report.hour_12 : 0) + '</td>',
            '<td></td>',
            '<td>' + (report.hour_14 >= 0 ? report.hour_14 : 0) + '</td>',
            '<td>' + (report.hour_15 >= 0 ? report.hour_15 : 0) + '</td>',
            '<td>' + (report.hour_16 >= 0 ? report.hour_16 : 0) + '</td>'
            + conditionalColumnsLastSection + '',
            '<td style="background-color:#75ade1;">' + report.total_output + '</td>',
            '<td style="background-color:#75ade1;">' + hourly_avg_production + '</td>',
            '<td style="'+styleLineEff+'">' + parseFloat(efficiency).toFixed(2) + '%</td>',
            // '<td style="' + styleProdEff + '">' + parseFloat(report.production_efficiency).toFixed(2) + '%</td>',
            '<td>' + (report.remarks ? report.remarks : '') + '</td>',
            '</tr>'
        ].join('');
      });

      $('#lineWiseProduction').html(tr);
    }

    // Animated live clock
    function currentTime() {
      let date = new Date(); 
      let hh = date.getHours();
      let mm = date.getMinutes();
      let ss = date.getSeconds();
      let session = "AM";

      if(hh == 0){
          hh = 12;
      }
      if(hh > 12){
          hh = hh - 12;
          session = "PM";
      }

      hh = (hh < 10) ? "0" + hh : hh;
      mm = (mm < 10) ? "0" + mm : mm;
      ss = (ss < 10) ? "0" + ss : ss;
        
      let time = hh + ":" + mm + ":" + ss + " " + session;

      document.getElementById("clock").innerText = time; 
      let t = setTimeout(function(){ currentTime() }, 1000);
    }
    currentTime();

  </script>

</body>

</html>