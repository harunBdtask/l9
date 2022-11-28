<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="refresh" content="90">
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions For Garments</title>
    <meta name="description" content="Input Dashboard Report"/>
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


    <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
    <!-- style -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet"
          href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
          type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>
    <style>
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
                                @if(!empty($report))
                                    <div class="parentTableFixed"
                                         style="min-height: 87vh!important; margin-bottom: 2rem;">
                                        <table class="reportTable fixTable">
                                            <thead>
                                            <tr>
                                                <th colspan="21">Line Wise Input Status</th>
                                                <th>{{ date('d/m/Y')}}</th>
                                            </tr>
                                            <tr>
                                                <th colspan="21"
                                                    style="font-size: 18px;">{{ factoryName() }}</th>
                                                <th></th>
                                            </tr>
                                            <tr>
                                                <th>Floor</th>
                                                <th>Line</th>
                                                <th>Merchandiser</th>
                                                <th>Buyer</th>
                                                <th>Item</th>
                                                <th>Item Group</th>
                                                <th>Style</th>
                                                <th>Style Qty</th>
                                                <th>Country</th>
                                                <th>PO</th>
                                                <th>PO Qty</th>
                                                <th>Color</th>
                                                <th>Color Qty</th>
                                                <th>Challan No</th>
                                                <th>Challan Qty</th>
                                                <th>Day Input Target</th>
                                                <th>Day Input Qty</th>
                                                <th>Input Due</th>
                                                <th>Day Sewing Target</th>
                                                <th>Day Output Qty</th>
                                                <th>Carry Forward</th>
                                                <th>WIP</th>
                                            </tr>
                                            </thead>
                                            <tbody class="color-wise-report">
                                              @if($report && count($report))
                                                @foreach(collect($report)->groupBy('floor_id') as $floorKey => $reportByFloor)
                                                    @php
                                                        $floorFirst = true;
                                                        $subDayInputTarget[$floorKey] = 0;
                                                        $subDayInputQty[$floorKey] = 0;
                                                        $subInputDue[$floorKey] = 0;
                                                        $subDaySewingTarget[$floorKey] = 0;
                                                        $subDayOutputQty[$floorKey] = 0;
                                                        $subCarryForward[$floorKey] = 0;
                                                        $subWip[$floorKey] = 0;
                                                        $oldLineId = '';
                                                        $newLineId = '';
                                                        $floor = $reportByFloor->first()['floor'];
                                                    @endphp
                                                    @foreach(collect($reportByFloor) as $report)
                                                        @php
                                                          $newLineId = $report['line_id'];
                                                          $lineRowspan = collect($report['challanDetails'])->count();
                                                        @endphp
                                                        @if($lineRowspan < 1)
                                                          @continue
                                                        @endif
                                                        @foreach(collect($report['challanDetails']) as $reportData)
                                                          <tr>
                                                            @if($oldLineId != $newLineId)
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['floor'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['line'] }}</td>
                                                            @endif
                                                              <td>{{ $reportData['merchandiser'] }}</td>
                                                              <td>{{ $reportData['buyer'] }}</td>
                                                              <td>{{ $reportData['garments_item'] }}</td>
                                                              <td>{{ $reportData['item_group'] }}</td>
                                                              <td>{{ $reportData['style'] }}</td>
                                                              <td>{{ $reportData['style_qty'] }}</td>
                                                              <td>{{ $reportData['country'] }}</td>
                                                              <td>{{ $reportData['po_no'] }}</td>
                                                              <td>{{ $reportData['po_quantity'] }}</td>
                                                              <td>{{ $reportData['color'] }}</td>
                                                              <td>{{ (int)$reportData['color_qty'] }}</td>
                                                              <td>
                                                                @if($reportData['challan_no'])
                                                                <a href="{{ url('/view-challan/'.$reportData['challan_id']) }}" target="_blank">
                                                                  {{ $reportData['challan_no'] }}{!! $reportData['challan_time'] ? '<br>('.$reportData['challan_time'].')' : '' !!}
                                                                </a>
                                                                @endif
                                                              </td>
                                                              <td>{{ $reportData['challan_qty'] }}</td>
                                                            @if($oldLineId != $newLineId)
                                                              @php
                                                                  $subDayInputTarget[$floorKey] += $report['day_input_target'];
                                                                  $subDayInputQty[$floorKey] += $report['day_input_qty'];
                                                                  $subInputDue[$floorKey] += $report['input_due'];
                                                                  $subDaySewingTarget[$floorKey] += $report['day_sewing_target'];
                                                                  $subDayOutputQty[$floorKey] += $report['day_output_qty'];
                                                                  $subCarryForward[$floorKey] += $report['carry_forward'];
                                                                  $subWip[$floorKey] += $report['wip'];
                                                              @endphp
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['day_input_target'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['day_input_qty'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['input_due'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['day_sewing_target'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['day_output_qty'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['carry_forward'] }}</td>
                                                              <td rowspan="{{ $lineRowspan }}">{{ $report['wip'] }}</td>
                                                            @endif
                                                          </tr>
                                                          @php
                                                            $oldLineId = $newLineId;
                                                          @endphp
                                                        @endforeach
                                                        @php
                                                            $floorFirst = false;
                                                        @endphp

                                                    @endforeach
                                                    <tr class="yellow-100">
                                                        <th colspan="14">Sub Total
                                                            = {{ $floor }} </th>
                                                        <th>{{ $subDayInputQty[$floorKey] }}</th>
                                                        <th>{{ $subDayInputTarget[$floorKey] }}</th>
                                                        <th>{{ $subDayInputQty[$floorKey] }}</th>
                                                        <th>{{ $subInputDue[$floorKey] }}</th>
                                                        <th>{{ $subDaySewingTarget[$floorKey] }}</th>
                                                        <th>{{ $subDayOutputQty[$floorKey] }}</th>
                                                        <th>{{ $subCarryForward[$floorKey] }}</th>
                                                        <th>{{ $subWip[$floorKey] }}</th>
                                                    </tr>
                                                @endforeach
                                                <tr class="green-200">
                                                    <th colspan="14">Grand Total</th>
                                                    <th>{{ array_sum($subDayInputQty) }}</th>
                                                    <th>{{ array_sum($subDayInputTarget) }}</th>
                                                    <th>{{ array_sum($subDayInputQty) }}</th>
                                                    <th>{{ array_sum($subInputDue) }}</th>
                                                    <th>{{ array_sum($subDaySewingTarget) }}</th>
                                                    <th>{{ array_sum($subDayOutputQty) }}</th>
                                                    <th>{{ array_sum($subCarryForward) }}</th>
                                                    <th>{{ array_sum($subWip) }}</th>
                                                </tr>
                                              @endif
                                            </tbody>
                                        </table>
                                    </div>
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
    });

    let my_time;

    function pageScroll() {
        let objDiv = document.getElementById("contain");
        objDiv.scrollTop = objDiv.scrollTop + 1;
        if ((objDiv.scrollTop + 100) == objDiv.scrollHeight) {
            objDiv.scrollTop = 0;
        }
        my_time = setTimeout('pageScroll()', 25);
    }
</script>
</body>

</html>
