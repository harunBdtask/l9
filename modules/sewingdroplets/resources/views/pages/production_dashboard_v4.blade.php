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
  <script src="{{ asset('flatkit/assets/apexchart/chart.min.js') }}"></script>

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
    <div class="container-full" id="productionDashboardChart"></div>
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
    var chartData;
    var height = $(window).height(),
        width = $('#lineTarget').width(),
        floors = [],
        lines  = [],
        target = [],
        output = [],
        efficiencies = [];

    $.each( data['lineTarget'], function(key, val) {
        floors.push(key);
    });

    hourlyProductionTable(data['floorLinesHourlyData'][floors[0]]);

    if (floors.length > 1) {
      var i = 1;
      setInterval(function() {
          var floor = floors[i++];
          
          hourlyProductionTable(data['floorLinesHourlyData'][floor]);

          if(i >= floors.length) i = 0;
      }, 30000);
    }
    function hourlyProductionTable(floorData) {
      let floor_no = floorData.total_row && floorData.total_row.floor_no ? floorData.total_row.floor_no : ""
      let floor_total_target = floorData.total_row && floorData.total_row.hour_passed_target ? parseInt(floorData.total_row.hour_passed_target) : 0
      let floor_total_production = floorData.total_row && floorData.total_row.total_output ? parseInt(floorData.total_row.total_output) : 0
      let floor_balance = floor_total_target > 0 && floor_total_production > 0 && floor_total_target > floor_total_production ? parseInt(floor_total_target - floor_total_production) : 0
      $('#floor-name').html(floor_no || "");
      $('#floor-total-target').html(floor_total_target || 0);
      $('#floor-total-production').html(floor_total_production || 0);
      $('#floor-total-balance').html(floor_balance || 0);
      lines = [];
      efficiencies = [];
      for(const key of Object.keys(floorData)) {
        if(key == 'total_row') {
          continue
        }
        
        let line = floorData[key].line
        lines.push(line);
        let efficiency = floorData[key].line_efficiency
        efficiencies.push(parseFloat(efficiency).toFixed(2))
      }
      
      setTimeout(function() {
        updateChartData(lines, efficiencies);
      }, 3000)

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

    var options = {
      chart: {
        height: 435,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      dataLabels: {
          enabled: false
      },
      series: [],
      title: {
        text: 'Line Wise Hourly Production Summary',
        floating: true,
        offsetY: 415,
        align: 'center',
        style: {
          color: '#f1f1e8'
        }
      },
      noData: {
        text: 'Loading...'
      }
    };

      var chart = new ApexCharts(document.querySelector("#productionDashboardChart"), options);
      chart.render();

      function updateChartData(lines, efficiencies) {
        chart.updateOptions({
          series: [{
            name: 'Efficiency',
            data: efficiencies
          }],
          chart: {
            height: 435,
            type: 'bar',
            toolbar: {
              show: false
            }
          },
          tooltip: {
            enabled: false,
          },
          plotOptions: {
            bar: {
              borderRadius: 10,
              dataLabels: {
                position: 'top', // top, center, bottom
              },
            }
          },
          dataLabels: {
            enabled: true,
            formatter: function (val) {
              return val + "%";
            },
            offsetY: -22,
            style: {
              fontSize: '16px',
              colors: ["#f1f1e8"]
            }
          },
          colors: [function({ value, seriesIndex, w }) { return getTrafficLight(value) }],
          xaxis: {
            categories: lines,
            position: 'top',
            axisBorder: {
              show: false
            },
            axisTicks: {
              show: false
            },
            crosshairs: {
              fill: {
                type: 'gradient',
                gradient: {
                  colorFrom: '#D8E3F0',
                  colorTo: '#BED1E6',
                  stops: [0, 100],
                  opacityFrom: 0.4,
                  opacityTo: 0.5,
                }
              }
            },
            tooltip: {
              enabled: false,
            },
            labels: {
              style: {
                colors: '#f1f1e8',
                fontSize: '30px',
                fontWeight: 800,
              }
            }
          },
          yaxis: {
            axisBorder: {
              show: false
            },
            axisTicks: {
              show: false,
            },
            labels: {
              show: false,
              formatter: function (val) {
                return val + "%";
              }
            }
          },
          title: {
            text: 'Line Wise Hourly Production Summary',
            floating: true,
            offsetY: 415,
            align: 'center',
            style: {
              color: '#f1f1e8'
            }
          }
        })
      }

      function getTrafficLight(value) {
        let efficiency = parseFloat(value).toFixed(2);
        let color = '#D7263D';
        if (efficiency < 50) {
          return '#D7263D';
        } 
        if (efficiency >= 50 && efficiency <= 75) {
          return'#F9CE1D';
        } 
        if (efficiency > 75) {
          return '#4CAF50';
        }

        return color;
      }
  </script>

</body>

</html>