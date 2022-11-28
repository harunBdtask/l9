<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions For Garments</title>
    <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- for ios 7 style, multi-resolution icon of 152x152 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
    <meta name="apple-mobile-web-app-title" content="Flatkit">
    <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('refresh')
    <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
    <!-- style -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>
    <style type="text/css">
        .box {
            background-color: #D9AFD9;
            background-image: linear-gradient(0deg, #cedab4 0%, #c2e7eb 100%);
        }

        .table-inside {
            border: none;
            width: 100%;
        }

        .table-inside th {
            border-top: none;
        }

        .table-inside th:first-child {
            border-left: none;
        }

        .table-inside th:last-child {
            border-right: none;
        }

        .table-inside td {
            border: none;
            border-right: 1px solid #e7e7e7;
        }

        .table-inside td:last-child {
            border-right: none;
        }

        .table-inside tr {
            border-bottom: 1px solid #e7e7e7;
        }

        .table-inside tr:last-child {
            border-bottom: none;
        }

        .flex {
            display: flex;
        }

        .justify-content-center {
            justify-content: center;
        }

        .align-items-center {
            align-items: center;
        }

        .float-right {
            float: right;
        }

        .app-header,
        .navbar {
            height: 48px;;
        }

        .padding {
            padding: 0.5rem 1rem;
        }

        .parentTableFixed table > thead > tr > th {
            background-color: #97d9e1 !important;
        }

        .parentTableFixed::-webkit-scrollbar {
            width: 0.5em;
        }

        .parentTableFixed::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        .parentTableFixed::-webkit-scrollbar-thumb {
            background-color: rgb(131, 199, 233);
            outline: 1px solid rgb(52, 164, 176);
        }

        #contain {
            height: 57vh;
            overflow-y: scroll;
        }

        #contain::-webkit-scrollbar {
            width: 0.5em;
        }

        #contain::-webkit-scrollbar-track {
            box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        }

        #contain::-webkit-scrollbar-thumb {
            background-color: rgb(131, 199, 233);
            outline: 1px solid rgb(52, 164, 176);
        }

        #table_scroll {
            width: 100%;
            margin-top: 0px;
            margin-bottom: 0px;
        }
    </style>
    <script type="text/javascript">
        var baseUrl = window.location.protocol + "//" + window.location.host + "/";

        function todayDate() {
            var d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }

        var params = {};
        window.location.search.slice(1).split('&').forEach(elm => {
            if (elm === '') return;
            let spl = elm.split('=');
            const d = decodeURIComponent;
            params[d(spl[0])] = (spl.length >= 2 ? d(spl[1]) : true);
        });

        var today_date = params['date'] || todayDate();
        var next_factory_id = params['factory_id'];
        <?php
        $factory_ids = json_encode($factory_ids);
        echo "var factory_ids = " . $factory_ids . ";\n";
        ?>
        var factory_id_param = params['factory_id'] || null;
        var factory_id = params['factory_id'] || factory_ids[0];
        factory_ids.forEach((item, key) => {
            if (item == factory_id) {
                next_factory_id = typeof factory_ids[key + 1] === 'undefined' ? factory_ids[0] : factory_ids[key + 1];
            }
        });
        var reloadTime = factory_id_param ? 90000 : 70000;
        // refresh page every 60 sec
        setTimeout(function () {
            window.location.reload(1);
        }, reloadTime);
        // setTimeout(function() {
        //   window.location.href = baseUrl + `sewing-input-dashboard?date=${today_date}&factory_id=${next_factory_id}`
        // }, 30000);
    </script>
</head>

<body>
<div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                <div class="navbar text-center flex justify-content-center align-items-center">
                    <h4>Sewing Input Dashboard</h4>
                </div>
            </div>
        </div>
        <div class="app-footer">
            <div>
              @include('skeleton::partials.footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
            <div class="padding">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-divider m-a-0"></div>
                            <div class="box-body">
                                @if(!empty($reports) && !request('factory_id'))
                                    @php
                                        $total_input_line = 0;
                                        $total_output_line = 0;
                                        $total_wip = 0;
                                        $total_day_input_target = 0;
                                        $total_day_target = 0;
                                        $total_input_due = 0;
                                    @endphp
                                    @foreach($reports->groupBy('factory_id') as $report_factory)
                                        <div class="parentTableFixed"
                                             style="min-height: 87vh!important; margin-bottom: 2rem;">
                                            <table class="reportTable fixTable">
                                                <thead>
                                                <tr>
                                                    <th colspan="8">Line Wise Input Status</th>
                                                    <th>{{ date('d/m/Y', strtotime($date))}}</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="8"
                                                        style="font-size: 18px;">{{ $report_factory->first()['factory_name'] }}</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                                <tr>
                                                    <th>Unit</th>
                                                    <th>Line</th>
                                                    <th>Style</th>
                                                    <th>Day Input Target</th>
                                                    <th>Day Input Quantity</th>
                                                    <th>Input Due</th>
                                                    <th>Day Sewing Target</th>
                                                    <th>Day Output Quantity</th>
                                                    <th>WIP</th>
                                                </tr>
                                                </thead>
                                                <tbody class="color-wise-report">
                                                @foreach($report_factory->groupBy('floor_id') as $report_by_floor)
                                                    @php
                                                        $sub_floor = $report_by_floor->first()['floor_no'];
                                                        $f_total_input_line = 0;
                                                        $f_total_output_line = 0;
                                                        $f_total_wip = 0;
                                                        $f_total_day_input_target = 0;
                                                        $f_total_day_target = 0;
                                                        $f_total_input_due = 0;
                                                    @endphp
                                                    @foreach($report_by_floor as $report_line_wise)
                                                        @php
                                                            $floor_no = $report_line_wise['floor_no'];
                                                            $line_no = $report_line_wise['line_no'];
                                                            $style_name = $report_line_wise['style_name'];
                                                            $input_target = $report_line_wise['input_target'];
                                                            $target = $report_line_wise['sewing_target'];
                                                            $input_line = $report_line_wise['today_input_qty'];
                                                            $output_line = $report_line_wise['today_output_qty'];
                                                            $wip = $report_line_wise['wip'];
                                                            $input_due = $input_target > $input_line ? $input_line - $input_target : 0;

                                                            $total_input_due += $input_due ?? 0;
                                                            $total_day_input_target += $input_target ?? 0;
                                                            $total_day_target += $target ?? 0;
                                                            $total_input_line += $input_line ?? 0;
                                                            $total_output_line += $output_line ?? 0;
                                                            $total_wip += isset($wip) && !empty($wip) ? $wip : 0;

                                                            $f_total_input_due += $input_due ?? 0;
                                                            $f_total_day_input_target += $input_target ?? 0;
                                                            $f_total_day_target += $target ?? 0;
                                                            $f_total_input_line += $input_line ?? 0;
                                                            $f_total_output_line += $output_line ?? 0;
                                                            $f_total_wip += isset($wip) && !empty($wip) ? $wip : 0;

                                                            $css_class = ($input_line > 0 && $input_target > 0 && $input_line >= $input_target) ?
                                                            'green-200'
                                                            : '';
                                                        @endphp
                                                        <tr class="{{ $css_class }}">
                                                            <td>{{ $floor_no ?? '' }}</td>
                                                            <td>{{ $line_no ?? '' }}</td>
                                                            <td>{{ $style_name ?? '' }}</td>
                                                            <th>{{ $input_target ?? '' }}</th>
                                                            <th>{{ $input_line }}</th>
                                                            <th>{{ $input_due }}</th>
                                                            <th>{{ $target ?? '' }}</th>
                                                            <th>{{ $output_line }}</th>
                                                            <th>{{ $wip }}</th>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="yellow-100">
                                                        <th colspan="3">Sub Total = {{ $sub_floor }}</th>
                                                        <th>{{ $f_total_day_input_target }}</th>
                                                        <th>{{ $f_total_input_line }}</th>
                                                        <th>{{ $f_total_input_due }}</th>
                                                        <th>{{ $f_total_day_target }}</th>
                                                        <th>{{ $f_total_output_line }}</th>
                                                        <th>{{ $f_total_wip }}</th>
                                                    </tr>
                                                @endforeach
                                                <tr class="green-200">
                                                    <th colspan="3">Factory Total</th>
                                                    <th>{{ $total_day_input_target }}</th>
                                                    <th>{{ $total_input_line }}</th>
                                                    <th>{{ $total_input_due }}</th>
                                                    <th>{{ $total_day_target }}</th>
                                                    <th>{{ $total_output_line }}</th>
                                                    <th>{{$total_wip}}</th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                @else
                                    @php
                                        $total_input_line = 0;
                                        $total_output_line = 0;
                                        $total_wip = 0;
                                        $total_day_input_target = 0;
                                        $total_day_target = 0;
                                        $total_input_due = 0;
                                    @endphp
                                    @foreach($reports->groupBy('factory_id') as $report_factory)
                                        <table class="reportTable" id="table_fixed">
                                            <thead>
                                            <tr>
                                                <th colspan="8">Line Wise Input Status</th>
                                                <th>{{ date('d/m/Y', strtotime($date))}}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="8"
                                                    style="font-size: 18px;">{{ $report_factory->first()['factory_name'] }}</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                            <tr>
                                                <th style="width:13.5%;">Unit</th>
                                                <th style="width:11%;">Line</th>
                                                <th style="width:10.5%;">Style</th>
                                                <th style="width:10.9%;">Day Input Target</th>
                                                <th style="width:10.8%;">Day Input Quantity</th>
                                                <th style="width:10.5%;">Input Due</th>
                                                <th style="width:11%;">Day Sewing Target</th>
                                                <th style="width:10.7%;">Day Output Quantity</th>
                                                <th style="width:11.5%;">WIP</th>
                                            </tr>
                                            </thead>
                                        </table>
                                        <div class="" id="contain">
                                            <table class="reportTable" id="table_scroll">
                                                <tbody>
                                                @foreach($report_factory->groupBy('floor_id') as $report_by_floor)
                                                    @php
                                                        $sub_floor = $report_by_floor->first()['floor_no'];
                                                        $f_total_input_line = 0;
                                                        $f_total_output_line = 0;
                                                        $f_total_wip = 0;
                                                        $f_total_day_input_target = 0;
                                                        $f_total_day_target = 0;
                                                        $f_total_input_due = 0;
                                                    @endphp
                                                    @foreach($report_by_floor as $report_line_wise)
                                                        @php
                                                            $floor_no = $report_line_wise['floor_no'];
                                                            $line_no = $report_line_wise['line_no'];
                                                            $style_name = $report_line_wise['style_name'];
                                                            $input_target = $report_line_wise['input_target'];
                                                            $target = $report_line_wise['sewing_target'];
                                                            $input_line = $report_line_wise['today_input_qty'];
                                                            $output_line = $report_line_wise['today_output_qty'];
                                                            $wip = $report_line_wise['wip'];
                                                            $input_due = $input_target > $input_line ? $input_line - $input_target : 0;

                                                            $total_input_due += $input_due ?? 0;
                                                            $total_day_input_target += $input_target ?? 0;
                                                            $total_day_target += $target ?? 0;
                                                            $total_input_line += $input_line ?? 0;
                                                            $total_output_line += $output_line ?? 0;
                                                            $total_wip += isset($wip) && !empty($wip) ? $wip : 0;

                                                            $f_total_input_due += $input_due ?? 0;
                                                            $f_total_day_input_target += $input_target ?? 0;
                                                            $f_total_day_target += $target ?? 0;
                                                            $f_total_input_line += $input_line ?? 0;
                                                            $f_total_output_line += $output_line ?? 0;
                                                            $f_total_wip += isset($wip) && !empty($wip) ? $wip : 0;

                                                            $css_class = ($input_line > 0 && $input_target > 0 && $input_line >= $input_target) ?
                                                            'green-600'
                                                            : '';
                                                        @endphp
                                                        <tr class="{{ $css_class }}">
                                                            <td style="width:13.5%;">{{ $floor_no ?? '' }}</td>
                                                            <td style="width:11%;">{{ $line_no ?? '' }}</td>
                                                            <td style="width:10.5%;"
                                                                title="{{ $style_name }}">{{ substr($style_name, 0, 14) ?? '' }}</td>
                                                            <th style="width:10.9%;">{{ $input_target ?? '' }}</th>
                                                            <th style="width:10.8%;">{{ $input_line }}</th>
                                                            <th style="width:10.5%;">{{ $input_due }}</th>
                                                            <th style="width:11%;">{{ $target ?? '' }}</th>
                                                            <th style="width:10.7%;">{{ $output_line }}</th>
                                                            <th style="width:11.5%;">{{ $wip }}</th>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="yellow-100">
                                                        <th colspan="3">Sub Total = {{ $sub_floor }}</th>
                                                        <th>{{ $f_total_day_input_target }}</th>
                                                        <th>{{ $f_total_input_line }}</th>
                                                        <th>{{ $f_total_input_due }}</th>
                                                        <th>{{ $f_total_day_target }}</th>
                                                        <th>{{ $f_total_output_line }}</th>
                                                        <th>{{ $f_total_wip }}</th>
                                                    </tr>
                                                @endforeach
                                                <tr class="green-200">
                                                    <th colspan="3">Factory Total</th>
                                                    <th>{{ $total_day_input_target }}</th>
                                                    <th>{{ $total_input_line }}</th>
                                                    <th>{{ $total_input_due }}</th>
                                                    <th>{{ $total_day_target }}</th>
                                                    <th>{{ $total_output_line }}</th>
                                                    <th>{{$total_wip}}</th>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ############ PAGE END-->
        </div>
    </div>
</div>
<script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/assets/table_head_fixer/tableHeadFixer.js') }}"></script>
<script>
    $(function () {
        $(".fixTable").tableHeadFixer();
        if (factory_id_param != null) {
            pageScroll();
            $("#contain").mouseover(function () {
                clearTimeout(my_time);
            }).mouseout(function () {
                pageScroll();
            });
        }
    });

    var my_time;

    function pageScroll() {
        var objDiv = document.getElementById("contain");
        objDiv.scrollTop = objDiv.scrollTop + 1;
        if ((objDiv.scrollTop + 100) == objDiv.scrollHeight) {
            objDiv.scrollTop = 0;
        }
        my_time = setTimeout('pageScroll()', 25);
    }
</script>
</body>

</html>
