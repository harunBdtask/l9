<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>ProTracker | Production Tracking Software</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- for ios 7 style, multi-resolution icon of 152x152 -->
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
  <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/logo.png') }}">
  <meta name="apple-mobile-web-app-title" content="Flatkit">
  <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @yield('refresh')

  <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/logo.png') }}">
  <!-- style -->
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}"
    type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}"
    type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}"
    type="text/css" />
  <link rel="stylesheet"
    href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}"
    type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}"
    type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css" />
  @php
    error_reporting(0);
    $bundle_card_sticker_ratio_view_status = getBundleCardStickerRatioViewStatus();
    $garmentsItem = $bundleCardGenerationDetail['garments_item']['name'] ?? $bundleCardGenerationDetail['garmentsItem']['name'] ?? '';
  @endphp

  <style type="text/css">
    .bundle-cards table,
    .bundle-cards th,
    .bundle-cards td {
      border: 1px solid black !important;
    }

    .bundle-card-generation-details table,
    .bundle-card-generation-details th,
    .bundle-card-generation-details td {
      border: 1px solid black !important;
    }

    .bundle-cards td.third {
      display: none;
    }

    #consumptionInfo div[class^="col-"] {
      padding-right: 3px !important;
      padding-left: 3px !important;
    }

    .barcode {
      padding-top: 6px !important;
    }

    .bundle-cards .margin9 {
      margin-bottom: 9px;
    }

    .lg {
      /*font-size: 9px;*/
    }

    .vertical-align {
      margin-top: 0.45rem !important;
      font-size: 8px;
      font-weight: 900;
      font-style: italic;
      text-orientation: sideways;
      -webkit-writing-mode: vertical-rl;
      -ms-writing-mode: tb-rl;
      writing-mode: vertical-rl;
    }

    .minimal-padding {
      padding-left: 0.35rem !important;
      padding-right: 0.35rem !important;
      padding-bottom: 0.20rem !important;
    }

    .minimal-margin {
      margin: 0 !important;
    }

    /*
    @media print {
      td.bg-black {
          background-color: #505151 !important;
          color: white !important;
          -webkit-print-color-adjust: exact;
      }
    }
    */

    .table-borderlss tr,
    .table-borderlss td,
    .table-borderlss th {
      border: 1px solid transparent!important;
      font-size: smaller;
      text-align: left;
    }

    .table-condensed thead > tr > th, .table-condensed tbody > tr > th, .table-condensed tfoot > tr > th, .table-condensed thead > tr > td, .table-condensed tbody > tr > td, .table-condensed tfoot > tr > td {
      padding: 1px;
    }
  </style>

  <link rel="stylesheet" type="text/css" href="{{ asset('css/print.css') }}">
</head>

<body>
  <div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
      <div class="padding">
        <div class="box">
          <div class="box-header">
            <div class="row">
              <div class="col-md-4">
                @if(getBundleCardPrintStyle() == 2)
                <table class="table table-condensed table-borderlss">
                  <thead>
                    <tr>
                      <th>Document's No: GMS/CUTTING/(FR)-02</th>
                    </tr>
                    <tr>
                      <th>Department: Cutting</th>
                    </tr>
                  </thead>
                </table>
                @endif
              </div>
              <div class="col-md-4">
                <h4 class="text-center">{{ $bundleCardGenerationDetail['factory']['factory_name'] ?? '' }}</h4>
                <h4 class="text-center">{{ $bundleCardGenerationDetail['factory']['factory_address'] ?? '' }}</h4>
                <h4 class="text-center">{{ 'Barcode Enabled Bundle Card Generator' }}</h4>
                {{--  <h4 class="text-center">{{ 'Unit: '.($bundleCardGenerationDetail->factory->factory_name ?? '') }}</h4>
                --}}
              </div>
              <div class="col-md-4">
                @if(getBundleCardPrintStyle() == 2)
                <table class="table table-condensed table-borderlss">
                  <thead>
                    <tr>
                      <th colspan="4">Document's Name : Barcode Enabled Bundle Card Generator</th>
                    </tr>
                    <tr>
                      <th colspan="2">Issue/Version No : 1.0</th>
                      <th colspan="2">Issue Date : 1-March-2022</th>
                    </tr>
                    <tr>
                      <th>Revised No :</th>
                      <th>&nbsp;</th>
                      <th>Revised Date :</th>
                      <th>&nbsp;</th>
                    </tr>
                  </thead>
                </table>
                @endif
              </div>
            </div>
          </div>
          <div class="box-header">
            <div class="row">
              <div class="col-sm-4 pull-left">
                <h2>Session No. : {{ str_pad($bundleCardGenerationDetail['sid'], 4, "0", STR_PAD_LEFT) }}</h2>
              </div>
              <div class="col-sm-2 col-sm-offset-6">
                {!! $bundleCardGenerationDetail['created_at'] ? '<h2>Date: '.Date('d/m/Y', strtotime($bundleCardGenerationDetail['created_at'])).'</h2>' : '' !!}
              </div>
            </div>
          </div>
          <div class="box-body b-t bundle-card-generation-details">
            <div class="row" id='consumptionInfo'>
              <div class="col-sm-3">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Roll</th>
                      <th class="text-center">Ply</th>
                      <th class="text-center">Weight</th>
                      <th class="text-center">Dia</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bundleCardGenerationDetail['rolls'] as $roll)
                    <tr>
                      <td align="center">{{ $roll['roll_no'] }}</td>
                      <td align="center">{{ $roll['plys'] }}</td>
                      <td align="center">{{ number_format($roll['weight'], 2) }}</td>
                      <td align="center">{{ number_format($roll['dia'], 2) }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Total Ply</th>
                      <th class="text-center">Total Weight</th>
                      {{-- <th class="text-center">Total Dia</th> --}}
                      <th class="text-center">Avg. Dia</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_ply'] }}</td>
                      <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_weight'] }}</td>
                      {{-- <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_dia'] }}</td> --}}
                      <td align="center">
                        {{ number_format($bundleCardGenerationDetail['roll_summary']['average_dia'], 2) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-3">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Serial</th>
                      <th class="text-center">Size</th>
                      <th class="text-center">Suffix</th>
                      <th class="text-center">Ratio</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bundleCardGenerationDetail['ratios'] as $ratio)
                    <tr>
                      <td align="center">{{ $ratio['serial_no'] }}</td>
                      <td align="center">{{ $ratio['size_name'] }}</td>
                      <td align="center">{{ $ratio['suffix'] }}</td>
                      <td align="center">{{ $ratio['ratio'] }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <th class="text-center">Marker Piece</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center">{{ $bundleCardGenerationDetail['marker_piece'] }}</td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <th class="text-center">Lot No</th>
                    <th class="text-center" colspan="2">Range</th>
                  </thead>
                  <tbody>
                    @foreach($bundleCardGenerationDetail['lot_ranges'] as $lot_range)
                    <tr>
                      <td align="center">{{ $lots->where('id', $lot_range['lot_id'])->first()->lot_no ?? '' }}</td>
                      <td align="center">{{ $lot_range['from'] }}</td>
                      <td align="center">{{ $lot_range['to'] }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>

                  <table class="table table-bordered table-sm">
                      <thead>
                      <tr>
                          <th></th>
                          <th>DIA</th>
                          <th>GSM</th>
                          <th>CONS</th>
                          <th>COMMENTS</th>
                          <th>RESULT</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                          <td>BOOKING</td>
                          <td>{{ $summaryReport['booking']['dia'] }}</td>
                          <td>{{ $summaryReport['booking']['gsm'] }}</td>
                          <td>{{ $summaryReport['booking']['consumption'] }}</td>
                          <td rowspan="3">{{ $summaryReport['comments'] }}</td>
                          <td rowspan="3">{{ $summaryReport['result'] }}</td>
                      </tr>
                      <tr>
                          <td>ACTUAL</td>
                          <td>{{ $summaryReport['actual']['dia'] }}</td>
                          <td>{{ $summaryReport['actual']['gsm'] }}</td>
                          <td>{{ $summaryReport['actual']['consumption'] }}</td>
                      </tr>
                      <tr>
                          <td>DEVIATION</td>
                          <td>{{ $summaryReport['deviation']['dia'] }}</td>
                          <td>{{ $summaryReport['deviation']['gsm'] }}</td>
                          <td>{{ $summaryReport['deviation']['consumption'] }}</td>
                      </tr>
                      </tbody>
                  </table>

              </div>
              <div class="col-sm-3">
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th width="7%">SL</th>
                      <th width="15%">Size</th>
                      <th width="15%">Bundles</th>
                      <th width="15%">Quantity</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bundleCardGenerationDetail['bundle_summary']['bundle'] as $summary)
                    <tr>
                      <td align="center">{{ $summary['serial'] }}</td>
                      <td align="center">{{ $summary['size'] . ($summary['suffix'] ? '('.$summary['suffix'].')' : '') }}
                      </td>
                      <td align="center">{{ $summary['bundles'] }}</td>
                      <td align="center">{{ $summary['quantity'] }}</td>
                    </tr>
                    @endforeach
                    <tr>
                      <td colspan="2" align="center"><strong>Total</strong></td>
                      <td align="center">
                        <strong>{{ $bundleCardGenerationDetail['bundle_summary']['total_bundle'] }}</strong>
                      </td>
                      <td align="center">
                        <strong>{{ $bundleCardGenerationDetail['bundle_summary']['total_quantity'] }}</strong></td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="col-sm-3">
                <table class="table table-bordered table-sm">
                  <tbody>
                    <tr>
                      <td align="left"><strong>Max Qty / Bundle</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['max_quantity'] }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Booking Consumption</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['booking_consumption'] }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Booking Dia</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['booking_dia'] }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Buyer Name</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>{{ localizedFor('Style') }}</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}</td>
                    </tr>
                    @if(isset($bundleCardGenerationDetail['order']['reference_no']))
                    <tr>
                      <td align="left"><strong>Ref. No.</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['order']['reference_no'] ?? '' }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td align="left"><strong>{{ localizedFor('PO') }}</strong></td>
                        <td align="left">{{ $pos ?? '' }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Item</strong></td>
                      <td align="left">{{ $garmentsItem }}</td>
                    </tr>
                    <tr>
                        <td align="left"><strong>Color</strong></td>
                        <td align="left">{{ $colors ?? '' }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Cutting No.</strong></td>
                      <td align="left">
                        @php
                        $cuttingNo = $bundleCardGenerationDetail['cutting_no'];

                        if ($bundleCardGenerationDetail['colors']) {
                        $cuttingNosWithColor = explode('; ', $cuttingNo);

                        $cuttingNo = '';
                        foreach ($cuttingNosWithColor as $cuttingNoWithColor) {
                        $cutting = explode(': ', $cuttingNoWithColor);
//                        $cuttingNo .= \SkylarkSoft\GoRMG\SystemSettings\Models\Color::findOrFail($cutting[0])->name . ':
                        $cuttingNo .= $cutting[1] . '; ';
                        }
                        $cuttingNo = rtrim($cuttingNo, '; ');
                        }
                        @endphp
                        {{ $cuttingNo }}
                      </td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Cutting Table</strong></td>
                      <td align="left">
                        {{ $bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no']  ?? '' }}
                      </td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Part</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['part']['name'] ?? '' }}</td>
                    </tr>
                    <tr>
                      <td align="left"><strong>Type</strong></td>
                      <td align="left">{{ $bundleCardGenerationDetail['type']['name'] ?? '' }}</td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Total No. Of Roll</th>
                      <th class="text-center">Total Cutting Qty</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_roll'] }}</td>
                      <td align="center">{{ $bundleCardGenerationDetail['bundle_summary']['total_quantity'] }}</td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Total Weight</th>
                      <th class="text-center">Total Cutting Should Be</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center">{{ $bundleCardGenerationDetail['roll_summary']['total_weight'] }} Kg</td>
                      <td align="center">
                        {{ number_format($bundleCardGenerationDetail['total_cutting_quantity_should_be'], 2) . ' PCs' }}
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Quantity Save/Loss</th>
                      <th class="text-center">Used Consumption</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center">{{ number_format($bundleCardGenerationDetail['quantity_save_or_loss'], 2) }}
                        PCs</td>
                      <td align="center">{{ number_format($bundleCardGenerationDetail['used_consumption'], 2) }} Kg/Dz
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="table table-bordered table-sm">
                  <thead>
                    <tr>
                      <th class="text-center">Consumption Save Or Loss</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td align="center">
                        {{ number_format($bundleCardGenerationDetail['consumption_save_or_loss'], 2) }}Kgs
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <hr class="noprint">
            <div class="newpage"></div>
            <br>
            <div class="row" style="font-size: 14px">
              @foreach($stickers as $sticker)
              @php
              $sticker = collect($sticker)->sortBy('sorting');
              @endphp
              <div class="col-md-4">
                <div>
                  <p class="text-center" style="margin-bottom: 5px">
                    <strong>Buyer:</strong>
                    {{ $bundleCardGenerationDetail['buyer'] ? $bundleCardGenerationDetail['buyer']['name'] : '' }}
                    <strong>{{ localizedFor('Style') }}:</strong> {{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                    <strong>Color:</strong> {{ $sticker->first()['color'] }},
                    <strong>Cutting Table.</strong>
                    {{ $bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ??'' }}
                    <strong>Cutting No.</strong> {{ $sticker->first()['cutting_no'] }}
                  </p>
                </div>
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th>Size & Suffix</th>
                      <th>Serial Range</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($sticker as $sizeAndSuffix => $stickerDetails)
                    <tr>
                      <td>{{ $sizeAndSuffix }}</td>
                      <td>{{ $stickerDetails['sl_start'] . ' - ' . $stickerDetails['sl_end'] }}</td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
              @endforeach
              @if($bundleCards && $bundleCards->count())
              <div class="col-md-8">
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th>{{ localizedFor('PO') }}/Cntry</th>
                      <th>Size & Suffix</th>
                      <th>Bndl</th>
                      <th>Qty</th>
                      <th>Serial Range</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($bundleCards->groupBy('size_id') as $size_id => $bundleBySize)
                    @foreach(collect($bundleBySize)->groupBy('suffix') as $suffix => $bundleBySizeSuffix)
                    @foreach(collect($bundleBySizeSuffix)->groupBy('purchase_order_id') as $purchase_order_id =>
                    $bundleByPO)
                    @php
                    $bundleByPO = collect($bundleByPO);
                    $po_no = $bundleByPO->first()['purchase_order']['po_no'] ??
                    $bundleByPO->first()['purchaseOrder']['po_no'] ?? '';
                    $sizeAndSuffix = $bundleBySizeSuffix->first()['suffix'] ? $bundleBySize->first()['size']['name'] . '
                    (' . $bundleBySizeSuffix->first()['suffix'] . ')' : $bundleBySize->first()['size']['name'];

                    $sl_start = explode('-', str_replace('/','-', $bundleBySizeSuffix->first()['serial']))[0];
                    $sl_end = array_last(explode('-', str_replace('/','-', $bundleBySizeSuffix->last()['serial'])));

                    $bundles = $bundleByPO->count();
                    $bundle_serials = $bundles > 1 ? $bundleByPO->first()['serial'].'<br>'.$bundleByPO->last()['serial']
                    : $bundleByPO->first()['serial'];

                    $qty = $bundleByPO->sum('quantity');
                    @endphp
                    <tr>
                      <td>{{ $po_no }}</td>
                      <td>{{ $sizeAndSuffix }}</td>
                      <td>{{ $bundles }}</td>
                      <td>{{ $qty }}</td>
                      <td>{!! $bundle_serials !!}</td>
                      {{--<td>{{ $sl_start . ' - ' . $sl_end }}</td>--}}
                    </tr>
                    @endforeach
                    @endforeach
                    @endforeach
                  </tbody>
                </table>
              </div>
              @endif
            </div>
            <hr class="noprint">
            <div class="newpage"></div>
            <br>
            <div class="bundle-cards">
              <?php
              $totalPage = ceil(count($bundleCards) / 14);
              ?>

              @for($page = 1; $page <= $totalPage; $page++) @for($i=0; $i < 7; $i++) <?php
                      $bundleCardFirstCol = $bundleCards->where('bundle_no', (($totalPage * $i) + $page))->first();
                      $bundleCardSecondCol = $bundleCards->where('bundle_no', (($totalPage * (7 + $i)) + $page))->first();
                      ?> <div class="row margin9">
                @if($bundleCardFirstCol && count($bundleCardFirstCol))
                @php
                $scanable_rp_barcode = $bundleCardFirstCol['id'] ? '1' . str_pad($bundleCardFirstCol['id'], 8, '0', STR_PAD_LEFT) : '';
                $scanable_op_barcode = $bundleCardFirstCol['id'] ? str_pad($bundleCardFirstCol['id'], 9, '0', STR_PAD_LEFT) : '';
                $bundeCardSizeId = $bundleCardFirstCol['size_id'] ?? null;
                $bundeCardSizeSuffix = $bundleCardFirstCol['suffix'] ?? null;
                $ratioQuery = collect($bundleCardGenerationDetail['ratios'])->where('size_id', $bundeCardSizeId)->where('suffix', $bundeCardSizeSuffix)->first();
                $bundleRatio = (count($ratioQuery) && $bundle_card_sticker_ratio_view_status) ? $ratioQuery['ratio'] : null;
                @endphp
                <div class="col-sm-6 minimal-padding">
                  <div class="row minimal-padding">
                    <div class="col-sm-6 minimal-padding">
                      <table class="table table-sm table-bordered minimal-margin bundle-card-table"
                        style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                        <tbody>
                          <tr>
                            <td colspan="2" align="center"
                              class="double-col {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                              style="{{ $bundleCardFirstCol ? ' !important; padding-top: 0px !important; padding-bottom: 0px !important;' : 'display: none;' }}">
                              <span class="lg">{{ substr($bundleCardGenerationDetail['buyer']['name'], 0, 12) ?? '' }}{{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                                -{{ $bundleCardGenerationDetail['order']['style_name'] }}</span></td>
                            <td class="third text-left">
                              &nbsp;{{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '') }}
                            </td>
                          </tr>
                          <tr>
                            <td class="first">{{ localizedFor('PO') }}</td>
                            <td class="second"><span class="lg"
                                style="">{{ $bundleCardFirstCol['purchase_order']['po_no'] ?? $bundleCardFirstCol['purchaseOrder']['po_no'] ?? '' }}</span>
                            </td>
                            <td class="third text-left"></td>
                          </tr>
                          <tr>
                            <td class="first">Color</td>
                            <td class="second"><span class="lg"
                                style="">{{ $bundleCardFirstCol['color']['name'] ?? '' }}</span></td>
                            <td class="third text-left"></td>
                          </tr>
                          <tr>
                            <td class="first">Lot</td>
                            <td class="second"><span class="lg"
                                style="">{{ $lots->where('id', $bundleCardFirstCol['lot_id'])->first()->lot_no ?? '' }}{{ isset($bundleCardFirstCol['country_id']) ? (count($bundleCardFirstCol['country']) ? ' || '.substr($bundleCardFirstCol['country']['code'], 0, 6) : '') : "" }}</span>
                            </td>
                            <td class="third text-left"></td>
                          </tr>
                          <tr>
                            <td colspan="2" align="center" class="double-col">
                              RP: {{ $scanable_rp_barcode ?? '' }} |
                              OP: {{ $scanable_op_barcode ?? '' }}
                            </td>
                            <td class="third text-left"></td>
                          </tr>
                          <tr>
                            <td colspan="2" align="center" class='double-col barcode'>
                              <span><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                            </td>
                            <td class="third text-left">
                              {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 18) : '' }}
                              <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="col-sm-1 minimal-padding" style="width: 1.33333% !important;">
                      <span class="vertical-align"
                        style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }} font-size: 6px!important;">&copy;
                        Skylark Soft Limited</span>
                    </div>
                    <div class="col-md-5 minimal-padding" style="width: 46% !important;">
                      <table class="table table-sm table-bordered minimal-margin bundle-card-table"
                        style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                        <tr>
                          <td class="third text-right">
                            {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}
                          </td>
                          <td class="first {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                            style="{{ $bundleCardFirstCol ? '' : 'display: none;' }}">Bundle
                          </td>
                          <td class="second {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                            style="{{ $bundleCardFirstCol ? '' : 'display: none;' }}">
                            <span class="lg" style="">
                              {{ $bundleCardFirstCol[getbundleCardSerial()] ?? $bundleCardFirstCol['size_wise_bundle_no'] ?? $bundleCardFirstCol['bundle_no'] }}
                              (Roll No. {{ $bundleCardFirstCol['roll_no'] ?? '--' }})
                            </span>
                          </td>
                        </tr>
                        <tr>
                          <td class="third text-right">
                            {{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}
                          </td>
                          <td class="first">Size</td>
                          <td class="second"><span class="lg"
                              style="">{{ ($bundleCardFirstCol['size']['name'] ?? '').($bundleCardFirstCol['suffix'] ? '('.$bundleCardFirstCol['suffix'].')' : '')  }}</span></td>
                        </tr>
                        <tr>
                          <td class="third text-right"></td>
                          <td class="first">Quantity</td>
                          <td class="second"><span class="lg" style="">{{ $bundleCardFirstCol['quantity'] }} {{ $bundleRatio ? '- ('.$bundleRatio.')' : ''}}</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="third text-right"></td>
                          <td class="first">Serial</td>
                          <td class="second {{ $bundleCardFirstCol['sl_overflow'] ? 'bg-black' : '' }}"
                            style="{{ $bundleCardFirstCol ? '' : 'display: none;' }}">
                            <span class="lg"
                              style="">{{  implode(' - ', explode('-',$bundleCardFirstCol['serial'])) }}</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="third text-right"></td>
                          <td class="first">Cutt. No</td>
                          <td class="second">{{ $bundleCardFirstCol['cutting_no'] }} {{ $garmentsItem ? '('.$garmentsItem.')': ''  }}</td>
                        </tr>
                        <tr>
                          <td class="third text-right">
                            {{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}
                          </td>
                          <td colspan="2" align="center" class="double-col barcode">
                            <span><?php echo DNS1D::getBarcodeSVG(($scanable_op_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                @endif
                @if($bundleCardSecondCol && count($bundleCardSecondCol))
                @php
                $scanable_rp_barcode = $bundleCardSecondCol['id'] ? '1' . str_pad($bundleCardSecondCol['id'], 8, '0', STR_PAD_LEFT) : '';
                $scanable_op_barcode = $bundleCardSecondCol['id'] ? str_pad($bundleCardSecondCol['id'], 9, '0', STR_PAD_LEFT) : '';
                $bundeCardSizeId = $bundleCardSecondCol['size_id'] ?? null;
                $bundeCardSizeSuffix = $bundleCardSecondCol['suffix'] ?? null;
                $ratioQuery = collect($bundleCardGenerationDetail['ratios'])->where('size_id', $bundeCardSizeId)->where('suffix', $bundeCardSizeSuffix)->first();
                $bundleRatio = (count($ratioQuery) && $bundle_card_sticker_ratio_view_status) ? $ratioQuery['ratio'] : null;
                @endphp
                <div class="col-sm-6 minimal-padding">
                  <div class="row minimal-padding">
                    <div class="col-sm-6 minimal-padding">
                      <table class="table table-sm table-borderd minimal-margin bundle-card-table"
                        style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                        <tr>
                          <td colspan="2" align="center"
                            class="double-col {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                            style="{{ $bundleCardSecondCol ? ' !important; padding-top: 0px !important; padding-bottom: 0px !important;' : 'display: none;' }}">
                            <span class="lg">{{ substr($bundleCardGenerationDetail['buyer']['name'], 0, 12) ?? '' }}{{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                              -{{ $bundleCardGenerationDetail['order']['style_name'] }}</span></td>
                          <td class="third text-left">
                            {{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '') }}
                          </td>
                        </tr>
                        <tr>
                          <td class="first">{{ localizedFor('PO') }}</td>
                          <td class="second"><span class="lg"
                              style="">{{ $bundleCardSecondCol['purchase_order']['po_no'] ?? $bundleCardSecondCol['purchaseOrder']['po_no'] ?? '' }}</span>
                          </td>
                          <td class="third text-left"></td>
                        </tr>
                        <tr>
                          <td class="first">Color</td>
                          <td class="second"><span class="lg"
                              style="">{{ $bundleCardSecondCol['color']['name'] ?? '' }}</span>
                          </td>
                          <td class="third text-left"></td>
                        </tr>
                        <tr>
                          <td class="first">Lot</td>
                          <td class="second"><span class="lg"
                              style="">{{ $lots->where('id', $bundleCardSecondCol['lot_id'])->first()->lot_no ?? '' }}{{ isset($bundleCardSecondCol['country_id']) ? (count($bundleCardSecondCol['country']) ? ' || '.substr($bundleCardSecondCol['country']['code'], 0, 6) : '') : "" }}</span>
                          </td>
                          <td class="third text-left"></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" class="double-col">
                            RP: {{ $scanable_rp_barcode ?? '' }} |
                            OP: {{ $scanable_op_barcode ?? '' }}
                          </td>
                          <td class="third text-left"></td>
                        </tr>
                        <tr>
                          <td colspan="2" align="center" class='double-col barcode'>
                            <span><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                          </td>
                          <td class="third text-left">
                            {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 18) : '' }}
                            <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                          </td>
                        </tr>
                      </table>
                    </div>
                    <div class="col-sm-1 minimal-padding" style="width: 1.33333% !important;">
                      <span class="vertical-align"
                        style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }} font-size: 6px!important;">&copy;
                        Skylark Soft Limited</span>
                    </div>
                    <div class="col-sm-5 minimal-padding" style="width: 46% !important;">
                      <table class="table table-sm table-borderd minimal-margin bundle-card-table"
                        style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                        <tr>
                          <td class="third text-right">
                            {{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}
                          </td>
                          <td class="first {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                            style="{{ $bundleCardSecondCol ? '' : 'display: none;' }}">
                            Bundle.
                          </td>
                          <td class="second {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                            style="{{ $bundleCardSecondCol ? '' : 'display: none;' }}">
                            <span class="lg"
                              style="">{{ $bundleCardSecondCol[getbundleCardSerial()] ?? $bundleCardSecondCol['size_wise_bundle_no'] ?? $bundleCardSecondCol['bundle_no'] ?? '' }}
                              (Roll No. {{ $bundleCardSecondCol['roll_no'] ?? '--' }})</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="third text-right">
                            {{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                          <td class="first">Size</td>
                          <td class="second"><span class="lg"
                              style="">{{ ($bundleCardSecondCol['size']['name'] ?? '').($bundleCardSecondCol['suffix'] ? '('.$bundleCardSecondCol['suffix'].')' : '')  }}</span></td>
                        </tr>
                        <tr>
                          <td class="third text-right"></td>
                          <td class="first">Quantity</td>
                          <td class="second"><span class="lg"
                              style="">{{ $bundleCardSecondCol['quantity'] ?? '' }} {{ $bundleRatio ? '- ('.$bundleRatio.')' : ''}}</span></td>
                        </tr>
                        <tr>
                          <td class="third text-right"></td>
                          <td class="first">Serial</td>
                          <td
                            class="second {{ (isset($bundleCardSecondCol['sl_overflow']) && $bundleCardSecondCol['sl_overflow']) ? 'bg-black' : '' }}"
                            style="{{ $bundleCardSecondCol ? '' : 'display: none;' }}">
                            <span class="lg"
                              style="">{{ (isset($bundleCardSecondCol['serial']) && $bundleCardSecondCol['serial']) ? implode(' - ', explode('-',$bundleCardSecondCol['serial'])) : '' }}</span>
                          </td>
                        </tr>
                        <tr>
                          <td class="third text-right"></td>
                          <td class="first">Cutt. No</td>
                          <td class="second">{{ $bundleCardSecondCol['cutting_no'] ?? '' }} {{ $garmentsItem ? '('.$garmentsItem.')': ''  }}</td>
                        </tr>
                        <tr>
                          <td class="third text-right">
                            {{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}
                          </td>
                          <td colspan="2" align="center" class="double-col barcode">
                            <span><?php echo DNS1D::getBarcodeSVG(($scanable_op_barcode ?? '1234'), "C128A", 1.2, 19, '', false); ?></span>
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                @endif
            </div>
            @endfor
            @if( $page != $totalPage)
            <div class="newpage"></div>
            <br>
            @endif
            @endfor
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- / -->
  </div>
  <!-- build:js flatkit/scripts/app.html.js -->

  <!-- jQuery -->
  <script src="{{ asset('libs/jquery/jquery/dist/jquery.js') }}"></script>

  <!-- Bootstrap -->
  <script src="{{ asset('libs/jquery/tether/dist/js/tether.min.js') }}"></script>
  <script src="{{ asset('libs/jquery/bootstrap/dist/js/bootstrap.js') }}"></script>
</body>

</html>
