<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>ProTracker | Production Tracking Software</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
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
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>

  <link rel="stylesheet" href="{{ asset('css/custom.css') }}" type="text/css"/>

  <style type="text/css">
    .bundle-cards table, .bundle-cards th, .bundle-cards td {
      border: 1px solid black !important;
    }

    .bundle-card-generation-details table, .bundle-card-generation-details th, .bundle-card-generation-details td {
      border: 1px solid black !important;
    }

    .bundle-cards td.third {
      display: none;
    }

    .barcode {
      padding-top: 6px !important;
    }

    .bundle-cards .margin9 {
      margin-bottom: 9px;
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
    }*/

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
@php
  error_reporting(0);
  $garmentsItem = $bundleCardGenerationDetail['garments_item']['name'] ?? $bundleCardGenerationDetail['garmentsItem']['name'] ?? '';
@endphp
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
              <small class="text-center">No consumption information for manual bundle card generation</small>
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
          <div class="row" style="font-size: 14px">
            @foreach($stickers as $sticker)
              <div class="col-md-4">
                <div>
                  <p class="text-center" style="margin-bottom: 5px">
                    <strong>Buyer:</strong> {{ $bundleCardGenerationDetail['buyer'] ? $bundleCardGenerationDetail['buyer']['name'] : '' }}
                    <strong>{{ localizedFor('Style') }}:</strong> {{ $bundleCardGenerationDetail['order']['style_name'] ?? '' }}
                    @if(isset($bundleCardGenerationDetail['order']['reference_no']))
                    <strong>Ref. No:</strong> {{ $bundleCardGenerationDetail['order']['reference_no'] ?? '' }}
                    @endif
                    <strong>Color:</strong> {{ $sticker && is_array($sticker) ? collect($sticker)->first()['color'] : $sticker->first()['color'] }},
                    <strong>Cutting
                      Table.</strong> {{ $bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '' }},
                    <strong>Lot.</strong> {{ $bundleCards->unique('lot_id')->implode('lot.lot_no',',') }}
                    <strong>Cutting No.</strong> {{ $sticker && is_array($sticker) ? collect($sticker)->first()['cutting_no'] : $sticker->first()['cutting_no'] }},
                    <strong>Item</strong> {{ garmentsItem }}
                  </p>
                </div>
                <div>
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
                  <table class="table table-sm table-bordered">
                    <thead>
                    <tr>
                      <th>Sl</th>
                      <th>Size</th>
                      <th>Bundles</th>
                      <th>Quantity</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                      $sizeSl = 0;
                      $sizeSl = 0;
                      $bundles=0;
                      $quantity=0;
                    @endphp
                    @foreach($sticker as $sizeAndSuffix => $stickerDetails)
                      <tr>
                        <td>{{ ++$sizeSl }}</td>
                        <td>{{ $sizeAndSuffix }}</td>
                        <td>{{ $stickerDetails['bundles'] }}</td>
                        <td>{{ $stickerDetails['quantity'] }}</td>
                      </tr>
                      @php
                        $bundles += $stickerDetails['bundles'];
                        $quantity += $stickerDetails['quantity'];
                      @endphp
                    @endforeach
                    <tr>
                      <td colspan="2">Total</td>
                      <td>{{ $bundles }}</td>
                      <td>{{ $quantity }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            @endforeach
          </div>
          <hr class="noprint">
          <div class="newpage"></div>
          <br>
          <div class="bundle-cards">
              <?php
              $totalPage = ceil(count($bundleCards) / 14);
              $starter = $bundleCards->first()['bundle_no'] ?? 1;
              $starter -= 1;
              ?>

            @for($page = 1; $page <= $totalPage; $page++)
              @for($i = 0; $i < 7; $i++)
                    <?php
                      $bundleCardFirstCol = $bundleCards->where('bundle_no', (($totalPage * $i) + $page + $starter))->first();
                      $bundleCardSecondCol = $bundleCards->where('bundle_no', (($totalPage * (7 + $i)) + $page + $starter))->first();
                    ?>
                <div class="row margin9">
                  @if($bundleCardFirstCol && count($bundleCardFirstCol))
                  @php
                    $scanable_rp_barcode = $bundleCardFirstCol['id'] ? '1' . str_pad($bundleCardFirstCol['id'], 8, '0', STR_PAD_LEFT) : '';
                    $scanable_op_barcode = $bundleCardFirstCol['id'] ? str_pad($bundleCardFirstCol['id'], 9, '0', STR_PAD_LEFT) : '';
                  @endphp
                    <div class="col-sm-6 minimal-padding">
                      <div class="row minimal-padding">
                        <div class="col-sm-6 minimal-padding">
                          <table class="table table-sm table-bordered minimal-margin"
                                 style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                            <tbody>
                            <tr>
                              <td colspan="2" align="center"
                                  class="double-col {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                                  style="{{ $bundleCardFirstCol ? ' padding-top: 0px !important; padding-bottom: 0px !important;' : 'display: none;' }}">{{ substr($bundleCardGenerationDetail['buyer']['name'], 0, 12) ?? '' }}
                                  {{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                                  -{{ $bundleCardGenerationDetail['order']['style_name'] }}</td>
                              <td class="third text-left">
                                &nbsp;{{ 'T:'.($bundleCardGenerationDetail['cutting_table']['table_no'] ?? $bundleCardGenerationDetail['cuttingTable']['table_no'] ?? '') }}</td>
                            </tr>
                            <tr>
                              <td class="first">{{ localizedFor('PO') }}</td>
                              <td class="second">{{ $bundleCardFirstCol['purchase_order']['po_no'] ?? $bundleCardFirstCol['purchaseOrder']['po_no'] ?? '' }}</td>
                              <td class="third text-left"></td>
                            </tr>
                            <tr>
                              <td class="first">Color</td>
                              <td class="second">{{ $bundleCardFirstCol['color']['name'] ?? '' }}</td>
                              <td class="third text-left"></td>
                            </tr>
                            <tr>
                              <td class="first">Lot</td>
                              <td class="second">{{ $bundleCardFirstCol['lot']['lot_no'] ?? '' }} </td>
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
                              <td class="third text-left">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 18) : '' }}
                                <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                              </td>
                            </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="col-sm-1 minimal-padding" style="width: 1.33333% !important;">
                          <span class="vertical-align"
                                style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }} font-size: 6px!important;">&copy; Skylark Soft Limited</span>
                        </div>
                        <div class="col-md-5 minimal-padding" style="width: 46% !important;">
                          <table class="table table-sm table-bordered minimal-margin"
                                 style="{{ $bundleCardFirstCol ? '' : 'visibility: hidden;' }}">
                            <tr>
                              <td class="third text-right">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}</td>
                              <td class="first {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                                  style="{{ $bundleCardFirstCol ? '' : 'display: none;' }}">
                                Bundle No.
                              </td>
                              <td class="second {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                                  style="{{ $bundleCardFirstCol ? '' : 'display: none;' }}">
                                {{ $bundleCardFirstCol['size_wise_bundle_no'] ?? $bundleCardFirstCol['bundle_no'] ?? '' }}
                                (Roll No. {{ $bundleCardFirstCol['roll_no'] ?? '--' }})
                              </td>
                            </tr>
                            <tr>
                              <td class="third text-right">{{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                              <td class="first">Size Name</td>
                              <td class="second">{{ ($bundleCardFirstCol['size']['name'] ?? '').($bundleCardFirstCol['suffix'] ? '('.$bundleCardFirstCol['suffix'].')' : '') }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right"></td>
                              <td class="first">Quantity</td>
                              <td class="second">{{ $bundleCardFirstCol['quantity'] ?? '' }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right"></td>
                              <td class="first">Serial No</td>
                              <td class="second">{{ $bundleCardFirstCol['serial'] ?? '' }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right"></td>
                              <td class="first">Cutt. No</td>
                              <td class="second">{{ $bundleCardFirstCol['cutting_no'] ?? '' }} {{ $garmentsItem ? '('.$garmentsItem.')' : '' }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right">{{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}</td>
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
                  @endphp
                    <div class="col-sm-6 minimal-padding">
                      <div class="row minimal-padding">
                        <div class="col-sm-6 minimal-padding">
                          <table class="table table-sm table-borderd minimal-margin"
                                 style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                            <tr>
                              <td colspan="2" align="center"
                                  class="double-col {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                                  style="{{ $bundleCardSecondCol ? ' padding-top: 0px !important; padding-bottom: 0px !important;' : 'display: none;' }}">{{ $bundleCardGenerationDetail['buyer']['name'] ?? '' }}
                                  {{ isset($bundleCardGenerationDetail['order']['reference_no']) ? '- '.$bundleCardGenerationDetail['order']['reference_no'] : '' }}
                                  -{{ $bundleCardGenerationDetail['order']['style_name'] }}</td>
                              <td class="third text-left">{{ 'T:'.$bundleCardGenerationDetail->cuttingTable['table_no'] ?? '' }}</td>
                            </tr>
                            <tr>
                              <td class="first">{{ localizedFor('PO') }}</td>
                              <td class="second">{{ $bundleCardSecondCol['purchase_order']['po_no'] ?? $bundleCardSecondCol['purchaseOrder']['po_no'] ?? '' }}</td>
                              <td class="third text-left"></td>
                            </tr>
                            <tr>
                              <td class="first">Color</td>
                              <td class="second">{{ $bundleCardSecondCol['color']['name'] ?? '' }}</td>
                              <td class="third text-left"></td>
                            </tr>
                            <tr>
                              <td class="first">Lot</td>
                              <td class="second">{{ $bundleCardSecondCol['lot']['lot_no'] ?? '' }} </td>
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
                              <td class="third text-left">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 18) : '' }}
                                <br>{{ $bundleCardGenerationDetail['type'] ? substr($bundleCardGenerationDetail['type']['name'], 0, 9) : '' }}
                              </td>
                            </tr>
                          </table>
                        </div>
                        <div class="col-sm-1 minimal-padding" style="width: 1.33333% !important;">
                          <span class="vertical-align"
                                style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }} font-size: 6px!important;">&copy; Skylark Soft Limited</span>
                        </div>
                        <div class="col-sm-5 minimal-padding" style="width: 46% !important;">
                          <table class="table table-sm table-borderd minimal-margin"
                                 style="{{ $bundleCardSecondCol ? '' : 'visibility: hidden;' }}">
                            <tr>
                              <td class="third text-right">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 9) : '' }}</td>
                              <td class="first {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                                  style="{{ $bundleCardSecondCol ? '' : 'display: none;' }}">
                                Bundle No.
                              </td>
                              <td class="second {{ $bundleCardGenerationDetail['is_regenerated'] ? '' : 'bg-black' }}"
                                  style="{{ $bundleCardSecondCol ? '' : 'display: none;' }}">
                                {{ $bundleCardSecondCol['size_wise_bundle_no'] ?? $bundleCardSecondCol['bundle_no'] ?? '' }}
                                (Roll No. {{ $bundleCardSecondCol['roll_no'] ?? '--' }})
                              </td>
                            </tr>
                            <tr>
                              <td class="third text-right">{{ $bundleCardGenerationDetail['factory']['factory_short_name'] ?? '' }}</td>
                              <td class="first">Size Name</td>
                              <td class="second">{{ ($bundleCardSecondCol['size']['name'] ?? '').($bundleCardSecondCol['suffix'] ? '('.$bundleCardSecondCol['suffix'].')' : '') }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right"></td>
                              <td class="first">Quantity</td>
                              <td class="second">{{ $bundleCardSecondCol['quantity'] ?? '' }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right"></td>
                              <td class="first">Serial No</td>
                              <td class="second">{{ $bundleCardSecondCol['serial'] ?? '' }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right"></td>
                              <td class="first">Cutt. No</td>
                              <td class="second">{{ $bundleCardSecondCol['cutting_no'] ?? '' }} {{ $garmentsItem ? '('.$garmentsItem.')' : '' }}</td>
                            </tr>
                            <tr>
                              <td class="third text-right">{{ $bundleCardGenerationDetail['created_at'] ? date('Y-m-d', strtotime($bundleCardGenerationDetail['created_at'])) : '' }}</td>
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