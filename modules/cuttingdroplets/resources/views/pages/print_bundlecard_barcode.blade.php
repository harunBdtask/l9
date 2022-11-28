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
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/material-design-icons/material-design-icons.css') }}"
    type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css" />

  @php
    $max_height = getBundleCardStickerStyles()['max_height'];
    $max_width = getBundleCardStickerStyles()['max_width'];
    $width = getBundleCardStickerStyles()['width'];
    $height = getBundleCardStickerStyles()['height'];
    $font_size = getBundleCardStickerStyles()['font_size'];
    $barcode_height = getBundleCardStickerStyles()['barcode_height'];
    $barcode_width = getBundleCardStickerStyles()['barcode_width'];
  @endphp

  <style type="text/css">
    :root {
      --col-sm: 10%;
      --col-1: 22.5%;
      --col-2: 45%;
      --col-4: 90%;
      --col-33: 33.3333334%;
      --col-66: 66%;
      --col-25: 25%;
      --col-half: 50%;
      --col-full: 100%;
      --body-font-size: {{ $font_size }}px;
    }

    .wrapper {
      margin-top: 0.90rem;
      margin-left: 0.45rem;
      margin-right: 0.25rem;
      margin-bottom: 0.25rem;
      width: {{ $max_width }}rem;
      height: auto;
      max-height: {{ $max_height }}rem;
      border-top: 1px solid #000;
      border-left: 1px solid #000;
      border-right: 1px solid #000;
      font-size: var(--body-font-size) !important;
      font-weight: 900;
    }

    .wrapper>.row {
      padding: 0px !important;
      margin: 0px !important;
      border-bottom: 1px solid #000;
    }

    .column-small {
      width: var(--col-sm);
    }

    .column-1 {
      width: var(--col-1);
    }

    .column-2 {
      width: var(--col-2);
    }

    .column-4 {
      width: var(--col-4);
    }

    .column-33 {
      width: var(--col-33);
    }

    .column-25 {
      width: var(--col-25);
    }

    .column-66 {
      width: var(--col-66);
    }

    .column-half {
      width: var(--col-half);
    }

    .column-full {
      width: var(--col-full);
    }

    .height-minimal {
      height: 3px;
    }

    .br-1 {
      border-right: 1px solid #000;
    }

    .flex {
      display: flex;
    }

    .flex-row {
      flex-direction: row;
    }

    .justify-content-center {
      justify-content: center;
    }

    .align-items-center {
      align-items: center;
    }

    .p-1 {
      padding: 1px;
    }

    .p-2 {
      padding: 2px;
    }

    .pt-3 {
      padding-top: 3px;
    }

    .p-tb-2 {
      padding-top: 1px !important;
      padding-bottom: 1px !important;
    }

    .small {
      font-size: 7pt !important;
      font-weight: 900;
    }

    .newpage {
      page-break-after: always;
    }
    
    /* Print styling */

    @media print {
      html,
      body {
        height: 99%;
      }

      body {
        margin: 0;
        padding: 0 !important;
        min-width: 768px;
        font-size: {{ $font_size }}px;
      }

      a[href]:after {
        content: none;
      }
      <?php
      if($width && $height) {
      ?>
      @page {
        size: {{$width}}mm {{$height}}mm;
      }
      <?php } ?>

      @page :left {
        margin-left: 0px;
      }

      @page :right {
        margin-left: 0px;
      }

      .padding {
        padding: 0px;
        margin: 0px;
      }

      .text-right {
        text-align: right;
      }
      .text-left {
        text-align: left!important;
      }

      .newpage {
        page-break-after: always;
      }

      .noprint,
      div.alert,
      header,
      .group-media,
      .btn,
      .footer,
      form,
      #comments,
      .nav,
      ul.links.list-inline,
      ul.action-links {
        display: none !important;
      }
    }

  </style>


</head>

<body>
  @php
    error_reporting(0);
  @endphp
  <div class="app" id="app">
    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
      <div class="padding">
        <section>
          <?php
            $totalPage = ceil(count($bundleCards) / 1);
          ?>
          @for($page = 1; $page <= $totalPage; $page++) 
          <?php
              $bundleCardFirstCol = $bundleCards->where('bundle_no', $page)->first();
              $scanable_rp_barcode = $bundleCardFirstCol['id'] ? '1' . str_pad($bundleCardFirstCol['id'], 8, '0', STR_PAD_LEFT) : '';
              $scanable_op_barcode = $bundleCardFirstCol['id'] ? str_pad($bundleCardFirstCol['id'], 9, '0', STR_PAD_LEFT) : '';
              $colorName = $bundleCardFirstCol['color']['name'] ?? null;
              $colorNameArray = $colorName ? explode(" ", $colorName) : [];
              if ($colorNameArray && is_array($colorNameArray) && count($colorNameArray)) {
                $colorName = $colorNameArray[0];
                if (count($colorNameArray) > 1) {
                  $colorName .= ' '.$colorNameArray[count($colorNameArray)-1];
                }
              } 
            ?> 
          <div class="wrapper">
            <div class="row flex flex-row">
              <div class="column-full text-center flex justify-content-center align-items-center">
                <span
                  class="pt-3"><?php echo DNS1D::getBarcodeSVG(($scanable_rp_barcode ?? '1234'), "C128A", $barcode_width, $barcode_height, '', true); ?></span>
              </div>
            </div>
            <div class="row flex flex-row">
              <div class="column-66 text-center br-1 flex justify-content-center align-items-center">
                <span
                  class="p-tb-2">{{ substr($bundleCardGenerationDetail['buyer']['name'], 0, 12) ?? '' }} - {{ $bundleCardGenerationDetail['order']['style_name'] }}</span>
              </div>
              <div class="column-33 text-center br-1 flex justify-content-center align-items-center">
                <span class="p-tb-2">B/N:
                  &nbsp;{{ $bundleCardFirstCol[getbundleCardSerial()] ?? $bundleCardFirstCol['size_wise_bundle_no'] ?? $bundleCardFirstCol['bundle_no'] }}</span>
              </div>
              <div class="column-25 text-center flex justify-content-center align-items-center">
                <span
                  class="p-tb-2">{{ 'F:'.(substr($bundleCardGenerationDetail['cutting_floor']['floor_no'], 0, 5) ?? substr($bundleCardGenerationDetail['cuttingFloor']['floor_no'], 0, 5) ?? '') }}</span>
              </div>
            </div>
            <div class="row flex flex-row">
              <div class="column-66 text-center br-1 flex justify-content-center align-items-center">
                <span class="p-tb-2">PO:
                  {{ substr($bundleCardFirstCol['purchase_order']['po_no'], 0, 15) ?? substr($bundleCardFirstCol['purchaseOrder']['po_no'], 0, 15) ?? ''  }}</span>
              </div>
              <div class="column-33 text-center br-1 flex justify-content-center align-items-center">
                <span class="p-tb-2">{{ ($bundleCardFirstCol['size']['name'] ?? '').($bundleCardFirstCol['suffix'] ? '('.$bundleCardFirstCol['suffix'].')' : '') }}</span>
              </div>
              <div class="column-25 text-center flex justify-content-center align-items-center">
                <span
                  class="p-tb-2">{{ 'T:'.(substr($bundleCardGenerationDetail['cutting_table']['table_no'], 0, 5) ?? substr($bundleCardGenerationDetail['cuttingTable']['table_no'], 0, 5) ?? '') }}</span>
              </div>
            </div>
            <div class="row flex flex-row">
              <div class="column-66 text-center br-1 flex justify-content-center align-items-center">
                <span class="p-tb-2">Col:
                  {{ $colorName ?? '' }}</span>
              </div>
              <div class="column-33 text-center br-1 flex justify-content-center align-items-center">
                <span
                  class="p-tb-2">{{ $bundleCardGenerationDetail['part'] ? substr($bundleCardGenerationDetail['part']['name'], 0, 15) : '' }}</span>
              </div>
              <div class="column-25 text-center flex justify-content-center align-items-center">
                <span class="p-tb-2">Qty: {{ $bundleCardFirstCol['quantity'] }}</span>
              </div>
            </div>
            <div class="row flex flex-row">
              <div class="column-33 text-center br-1 flex justify-content-center align-items-center">
                <span class="p-tb-2">Lot:
                  {{ substr($bundleCardFirstCol['lot']['lot_no'], 0 , 8) ?? '' }}</span>
              </div>
              <div class="column-66 text-center br-1 flex justify-content-center align-items-center">
                <span class="p-tb-2">SL/N | {{ $bundleCardFirstCol['serial'] }}</span>
              </div>
              <div class="column-33 text-center flex justify-content-center align-items-center">
                <span class="p-tb-2">Cutt. No: {{ $bundleCardFirstCol['cutting_no'] }}</span>
              </div>
            </div>
            <div class="row flex flex-row">
              <div class="column-full text-center flex justify-content-center align-items-center">
                <span
                  class="pt-3"><?php echo DNS1D::getBarcodeSVG(($scanable_op_barcode ?? '1234'), "C128A", $barcode_width, $barcode_height, '', true); ?></span>
              </div>
            </div>
            {{-- <div class="row flex flex-row">
              <div class="column-full text-center flex justify-content-center align-items-center">
                <span class="small p-tb-2">PROTRACKER &copy; Skylark Soft Limited</span>
              </div>
            </div> --}}
            <div class="newpage"></div>
          </div>
        @endfor
      </section>
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