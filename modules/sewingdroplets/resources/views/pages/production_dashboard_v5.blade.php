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

    .dark-gradient {
      background-image: linear-gradient(45deg, #0d0d0d, #37383b);
      background-repeat: repeat-x;
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
    #buyer,
    #style,
    #po,
    #item,
    #wh {
      color: rgb(238, 255, 47);
    }

    #line-no {
      color: #ff6a00;
    }

    .text-5x {
      font-size: 5rem;
    }
    .text-4-5x {
      font-size: 4.5rem;
    }
    .text-mod {
      font-size: 16px;
    }
  </style>

  @php
  $lineWiseHourShowData = getLineWiseHourShowData();
  $viewableHours = collect($lineWiseHourShowData)->filter(function($item) {
    return $item > 0;
  });
  $totalHours = $viewableHours->count();
  echo '<script type="text/javascript">
    var data = ' . json_encode($data) . ';
    var lineWiseHourShowData = '. json_encode($lineWiseHourShowData) .'
    var viewableHours = '. json_encode($viewableHours->toArray()) .'
  </script>';
  @endphp

</head>

<body>
  <div class="padding">
    <div class="container-full">
      <div class="row">
        <div class="col-md-3">
          <h3 class="text-left">
            <span id="floor-name"></span> ||
            <span id="line-no"></span>
          </h3>
        </div>
        <div class="col-md-5">
          <h3 class="text-center">LINE WISE PRODUCTION DASHBOARD</h3>
        </div>
        <div class="col-md-4">
          <h3 class="text-right">
            <span id="date">{{ date('d M, Y') }}</span> <span id="clock"></span>
          </h3>
        </div>
      </div>

      <div class="row dashboard-tiles">
        <div class="col-md-3">
          <div class="tile box cursor-pointer p-a dark-gradient" data-toggle="tooltip"
            data-placement="top" title="" data-original-title="Target">
            <div class="clear">
              <div class="text-white text-mod">
                <div><b>Buyer:</b> <span id="buyer"></span></div>
                <div><b>Style:</b> <span id="style"></span></div>
                <div><b>PO:</b> <span id="po"></span></div>
                <div><b>Item:</b> <span id="item"></span></div>
                <div><b>WH:</b> <span id="wh"></span></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="tile box cursor-pointer p-a ibiza-sunset-gradient tile-icon" data-toggle="tooltip"
            data-placement="top" title="" data-original-title="Target">
            <div class="pull-right m-r">
              <em class="fa fa-bolt text-4-5x text-white m-y-sm"></em>
            </div>
            <div class="clear">
              <div class="text-white text-md">Day Target</div>
              <h4 class="m-a-0 text-5x _600"><a id="floor-total-target">0</a></h4>
            </div>
          </div>
        </div>
        <div class="col-md-3">
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
        <div class="col-md-3">
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
        efficiencies = [],
        floorLines = [],
        floorLineCount = 0,
        initialFloor,
        initialLine,
        showHourObject = {
          'hour_0': '12AM - 1AM',
          'hour_1': '1AM - 2AM',
          'hour_2': '2AM - 3AM',
          'hour_3': '3AM - 4AM',
          'hour_4': '4AM - 5AM',
          'hour_5': '5AM - 6AM',
          'hour_6': '6AM - 7AM',
          'hour_7': '7AM - 8AM',
          'hour_8': '8AM - 9AM',
          'hour_9': '9AM - 10AM',
          'hour_10': '10AM - 11AM',
          'hour_11': '11AM - 12PM',
          'hour_12': '12PM - 1PM',
          'hour_13': '1PM - 2PM',
          'hour_14': '2PM - 3PM',
          'hour_15': '3PM - 4PM',
          'hour_16': '4PM - 5PM',
          'hour_17': '5PM - 6PM',
          'hour_18': '6PM - 7PM',
          'hour_19': '7PM - 8PM',
          'hour_20': '8PM - 9PM',
          'hour_21': '9PM - 10PM',
          'hour_22': '10PM - 11PM',
          'hour_23': '11PM - 12AM',
        };

    $.each( data['lineTarget'], function(floorKey, val) {
        floors.push(floorKey);
        floorLines[floorKey] = [];
        $.each(val, function(lineKey, lineVal) {
          if (lineKey != 'total_row') {
            floorLines[floorKey].push(lineKey)
            floorLineCount++
          }
        })
        
    });
    
    let timeOut = parseInt(floorLineCount) * 20000;

    setTimeout(function () {
        window.location.reload(1);
    }, timeOut);

    if (Object.keys(floorLines).length) {
      initialFloor = Object.keys(floorLines)[0];
      initialLine = floorLines[initialFloor][0];
      hourlyProductionTable(data['floorLinesHourlyData'][initialFloor][initialLine]);
      
      let fKey = 0;
      let lKey = 1;
      setInterval(function() {
        let floor = floors[fKey];
        let lineCount = floorLines[floor].length;
        let line = floorLines[floor][lKey++];
        hourlyProductionTable(data['floorLinesHourlyData'][floor][line]);
        if (lKey == lineCount - 1) {
          fKey++;
          lKey = 0;
        }
        if(fKey >= floors.length) fKey = 0;
      }, 20000);
    }

    function hourlyProductionTable(floorData) {
      console.log(floorData);
      let tempHours = [];
      let hours = [];
      let showHours = [];
      let date = new Date(); 
      let hh = date.getHours();
      let current_hour = `hour_${hh}`;
      let floor_total_target = 0

      let h_sl = 0;
      for(const hour of Object.keys(viewableHours)) {
        hours.push(hour);
        showHours.push(showHourObject[hour]);
        floor_total_target += hour != 'hour_13' ? parseInt(floorData.hourly_target) : 0;
        let hourExists = hours.find((h, k) => {
          return h == current_hour
        });
        if (hours.length == 5 && hourExists != undefined) {
          break;
        }
        h_sl++;
        if (h_sl > 4) {
          hours.shift();
          showHours.shift();
          h_sl = 4;
        }
      }
      let buyer = floorData.buyer || ""
      let style = floorData.order || ""
      let po = floorData.po || ""
      let item = floorData.item || ""
      let floor_no = (floorData.floor || "")
      let line_no = (floorData.line || "")
      let wh = (floorData.wh || "")
      let day_target = floorData.day_target ? parseInt(floorData.day_target) : 0
      let floor_total_production = floorData.total_output ? parseInt(floorData.total_output) : 0
      let floor_balance = floor_total_target > 0 && floor_total_production > 0 && floor_total_target > floor_total_production ? parseInt(floor_total_target - floor_total_production) : 0
      $('#buyer').html(buyer.substring(0, 22));
      $('#style').html(style.substring(0, 22));
      $('#po').html(po.substring(0, 22));
      $('#item').html(item.substring(0, 22));
      $('#wh').html(wh || "");
      $('#floor-name').html(floor_no || "");
      $('#line-no').html(line_no || "");
      $('#floor-total-target').html(day_target || 0);
      $('#floor-total-production').html(floor_total_production || 0);
      $('#floor-total-balance').html(floor_balance || 0);
      
      outputs = [];
      targets = [];
      for(let h = 0; h < hours.length; h++) {
        let output = 0
        for (const data in floorData) {
          if (data == hours[h]) {
            if (hours[h] == 'hour_12') {
              output = parseInt(floorData[data] > 0 ? floorData[data] : 0) + parseInt(floorData['hour_13'] > 0 ? floorData['hour_13'] : 0)
            } else if (hours[h] == 'hour_13') {
              output = 0
            } else {
              output = floorData[data] > 0 ? floorData[data] : 0;
            }
          }
        }
        outputs.push(parseInt(output));
        let target = hours[h] != 'hour_13' ? floorData.hourly_target : 0
        targets.push(parseInt(target))
      }
      
      setTimeout(function() {
        updateChartData(showHours, outputs, targets);
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

      function updateChartData(hours, outputs, targets) {
        chart.updateOptions({
          series: [{
            name: 'Target',
            data: targets
          },{
            name: 'Output',
            data: outputs
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
              borderRadius: 0,
              dataLabels: {
                position: 'top', // top, center, bottom
              },
            }
          },
          dataLabels: {
            enabled: true,
            formatter: function (val) {
              return val;
            },
            offsetY: -22,
            style: {
              fontSize: '18px',
              colors: ["#f1f1e8"]
            }
          },
          // colors: [function({ value, seriesIndex, w }) { return getTrafficLight(value) }],
          xaxis: {
            categories: hours,
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
                return val;
              }
            }
          },
          stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
          },
          legend: {
            show: false
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