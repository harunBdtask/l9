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
  <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/pro(64x64).ico') }}">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
    type="text/css" />

  <script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/tether/tether.min.js') }}"></script>
  <script src="{{ asset('modules/skeleton/lib/bootstrap/bootstrap.js') }}"></script>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>

  <style type="text/css">
    .container-full {
      overflow: hidden;
      margin: 15px 15px;
      width: 100%;
    }

    .reportTable thead,
    .reportTable tbody,
    .reportTable th {
      padding: 1px;
    }

    .graph-title {
      font-size: 22px;
      font-weight: bold;
      color: #125C5C;
    }

    .flex-alignment-center {
      display: flex;
      justify-content: space-around;
      align-items: center;
    }

    .logo {
      height: 2rem;
      margin-bottom: 0.4rem;
    }

    .teal-color {
      color: #02908f!important;
    }

    .green-200{
      background-color: #6ae492!important;
      color: #070333!important;
    }

    .text-center {
      text-align: center;
    }
  </style>

  <script type="text/javascript">
    // refresh page every 30 sec
		setTimeout(function(){
			window.location.reload(1);
		}, 9000);
  </script>
</head>

<body>
  <div class="container-full" id="cuttingProductionDashboard">
    <div class="row">
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-11 offset-1">
            <!-- color wise -->
            <h5 class="text-center">COLOR WISE CUTTING PRODUCTION SUMMARY</h5>
            <table class="reportTable">
              <thead>
                <tr>
                    <th colspan="8">Color Wise Cutting Production Summary</th>
                </tr>
                <tr>
                    <th>SL</th>
                    <th>Table</th>
                    <th>Buyer</th>
                    <th>Style</th>
                    <th>PO</th>
                    <th>Color</th>
                    <th>No. of Bundle</th>
                    <th>Cutting Production</th>
                </tr>
                </thead>
                <tbody class="">
                @if($reports && $reports->count())
                    @php
                        $total_bundle_count = 0;
                        $sl = 0;
                    @endphp
                    @foreach($reports->sortBy('cutting_table_id')->groupBy('cutting_table_id') as $reportByCuttingTable)
                        @php
                            $cutting_table = $reportByCuttingTable->first()->cuttingTable->table_no ?? '';
                            $cutting_table_id = $reportByCuttingTable->first()->cutting_table_id;
                            $table_total_bundle_count = 0;
                            $table_total_cut_production = 0;
                        @endphp
                        @foreach($reportByCuttingTable->groupBy('purchase_order_id') as $reportByPurchaseOrder)
                            @php
                                $buyer = $reportByPurchaseOrder->first()->buyer->name ?? '';
                                $style_name = $reportByPurchaseOrder->first()->order->style_name ?? '';
                                $po_no = $reportByPurchaseOrder->first()->purchaseOrder->po_no ?? '';
                                $purchase_order_id = $reportByPurchaseOrder->first()->purchase_order_id;
                            @endphp
                            @foreach($reportByPurchaseOrder->groupBy('color_id') as $reportByColor)
                                @php
                                    $sl++;
                                    $color = $reportByColor->first()->color->name ?? '';
                                    $color_id = $reportByColor->first()->color_id;
                                    $cutting_production = $reportByColor->sum('total_cutting_qty');
                                    $bundle_count = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard::dateColorWiseBundleCount($date, $cutting_table_id, $purchase_order_id, $color_id);
                                    $total_bundle_count += $bundle_count;
                                    $table_total_bundle_count += $bundle_count;
                                    $table_total_cut_production += $cutting_production;
                                @endphp
                                <tr>
                                    <td>{{ $sl }}</td>
                                    <td style="text-align: left; padding-left: 5px">{{ $cutting_table }}</td>
                                    <td style="text-align: left; padding-left: 5px">{{ $buyer }}</td>
                                    <td style="text-align: left; padding-left: 5px">{{ $style_name }}</td>
                                    <td style="text-align: left; padding-left: 5px">{{ $po_no }}</td>
                                    <td style="text-align: left; padding-left: 5px">{{ $color }}</td>
                                    <td>{{ $bundle_count }}</td>
                                    <td>{{ $cutting_production }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        <tr style="font-weight:bold;">
                            <td colspan="6">Total = {{ $cutting_table }}</td>
                            <td>{{ $table_total_bundle_count }}</td>
                            <td>{{ $table_total_cut_production }}</td>
                        </tr>
                    @endforeach
                    <tr class="green-200" style="font-weight:bold;">
                        <td colspan="6">Total</td>
                        <td>{{ $total_bundle_count }}</td>
                        <td>{{ $reports->sum('total_cutting_qty') }}</td>
                    </tr>
                @else
                    <tr class="tr-height">
                        <td colspan="8" class="text-danger text-center">Not found
                        <td>
                    </tr>
                @endif
                </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-11">
            <h5 class="text-center ">TARGET WISE CUTTING PRODUCTION SUMMARY</h5>
            <!--table wise target summary-->
            <table class="reportTable">
              <thead>
                <tr>
                    <th colspan="5"><b>Cutting Target Wise Cutting Production Summary</b></th>
                </tr>
                </thead>
                <thead>
                <tr>
                    <th>Floor</th>
                    <th>Table</th>
                    <th>Target/Day</th>
                    <th>Cutting Production</th>
                    <th>Achievement</th>
                </tr>
                </thead>
                <tbody>
                @if($reports && $reports->count())
                    @php
                        $ttoday_target = 0;
                        $total_cutting = 0;
                    @endphp
                    @foreach($reports->sortBy('cutting_table_id')->groupBy('cutting_floor_id') as $reportByCuttingFloor)
                        @php
                            $floor_today_target = 0;
                            $floor_total_cutting = 0;
                            $cutting_floor = $reportByCuttingFloor->first()->cuttingFloor->floor_no ?? '';
                        @endphp
                        @foreach($reportByCuttingFloor->sortBy('cutting_table_id')->groupBy('cutting_table_id') as $reportByCuttingTable)
                            @php
                                $cutting_table_id = $reportByCuttingTable->first()->cutting_table_id;
            
                                $cutting_table = $reportByCuttingTable->first()->cuttingTable->table_no ?? '';
                                $cutting_target_per_day = \SkylarkSoft\GoRMG\Cuttingdroplets\Models\DateTableWiseCutProductionReport::cuttingTarget($cutting_table_id, $date);
                                $cutting_production = $reportByCuttingTable->sum('total_cutting_qty');
                                $cutting_percentage = ($cutting_target_per_day > 0) ? (($cutting_production * 100) / $cutting_target_per_day) : 0;
            
                                $ttoday_target += $cutting_target_per_day;
                                $total_cutting += $cutting_production;
            
                                $floor_today_target += $cutting_target_per_day;
                                $floor_total_cutting += $cutting_production;
                            @endphp
                            <tr>
                                <td>{{ $cutting_floor }}</td>
                                <td>{{ $cutting_table }}</td>
                                <td>{{ $cutting_target_per_day }}</td>
                                <td>{{ $cutting_production }}</td>
                                <td>{{ round($cutting_percentage,2) }} %</td>
                            </tr>
                        @endforeach
                        <tr style="font-weight:bold;">
                            <td colspan="2">Total = {{ $cutting_floor }}</td>
                            <td>{{ $floor_today_target }}</td>
                            <td>{{ $floor_total_cutting }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    <tr class="green-200" style="font-weight:bold;">
                        <td colspan="2">Total</td>
                        <td>{{ $ttoday_target }}</td>
                        <td>{{ $total_cutting }}</td>
                        <td></td>
                    </tr>
                @else
                    <tr class="tr-height">
                        <td colspan="5" class="text-danger text-center">Not found</td>
                    </tr>
                @endif
                </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var imageUrl = "{{ asset('modules/skeleton/flatkit/assets/images/protrackerNew.png') }}"; 
  </script>

</body>

</html>