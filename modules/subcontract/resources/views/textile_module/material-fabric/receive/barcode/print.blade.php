<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>SubContract | goRMG</title>
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
        :root {
            --w-10: 10%;
            --w-22-5: 22.5%;
            --w-45: 45%;
            --w-90: 90%;
            --w-33: 33.3333334%;
            --w-66: 66%;
            --w-25: 25%;
            --w-50: 50%;
            --w-100: 100%;
            --body-font-size: {{ $barcode_font_size }}px;
            --barcode-container-m-top: {{ $barcode_container_m_top }}px;
            --barcode-container-m-left: {{ $barcode_container_m_left }}px;
            --barcode-container-m-right: {{ $barcode_container_m_right }}px;
            --barcode-container-m-bottom: {{ $barcode_container_m_bottom }}px;
            --barcode-container-p-top: {{ $barcode_container_p_top }}px;
            --barcode-container-p-left: {{ $barcode_container_p_left }}px;
            --barcode-container-p-right: {{ $barcode_container_p_right }}px;
            --barcode-container-p-bottom: {{ $barcode_container_p_bottom }}px;
        }

        .w-10 {
            width: var(--w-10);
        }

        .w-22-5 {
            width: var(--w-22-5);
        }

        .w-45 {
            width: var(--w-45);
        }

        .w-90 {
            width: var(--w-90);
        }

        .w-33 {
            width: var(--w-33);
        }

        .w-66 {
            width: var(--w-66);
        }

        .w-25 {
            width: var(--w-25);
        }

        .w-50 {
            width: var(--w-50);
        }

        .w-100 {
            width: var(--w-100);
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

        .justify-content-between {
            justify-content: space-between;
        }

        .align-items-center {
            align-items: center;
        }

        .barcode-container p {
            margin-bottom: 0.15rem !important;
        }

        .font-bigger {
            font-size: 28px;
        }

        .font-600 {
            font-weight: 600;
        }

        .p-l-2 {
            padding-left: 2px;
        }

        .m-t-1 {
            margin-top: 1px;
        }

        @media print {

            html,
            body {
                height: 99%;
            }

            body {
                margin: 0;
                padding: 0 !important;
                min-width: 768px;
                font-size: var(--body-font-size) !important;
            }

            a[href]:after {
                content: none;
            }

            .noprint {
                display: none;
            }

            .app,
            .app-content,
            .app-header,
            .app-body,
            .padding,
            .box,
            .box-body {
                margin: 0 !important;
                padding: 0 !important;
            }

            .barcode-container {
                border: 1px dashed #000 !important;
                margin-top: var(--barcode-container-m-top);
                margin-left: var(--barcode-container-m-left);
                margin-right: var(--barcode-container-m-right);
                margin-bottom: var(--barcode-container-m-b);
                padding-top: var(--barcode-container-p-top);
                padding-left: var(--barcode-container-p-left);
                padding-right: var(--barcode-container-p-right);
                padding-bottom: var(--barcode-container-p-b);
            }

            .page-break-after {
                page-break-after: always;
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
            <div class="box">
                <div class="box-body">
                    @if($detail && count($detail->barcodes))
                        @foreach (collect($detail->barcodes) as $barcodeData)
                            @php
                                $barcodeDetail = $barcodeData->subDyeingOrderDetail;
                            @endphp
                            <div class="row page-break-after">
                                <div class="col-md-12 b-dashed barcode-container">
                                    <div class="text-center">
                                        <p><span class="font-weight-bold font-bigger">{{ sessionFactoryName() }}</span>
                                        </p>
                                    </div>
                                    <div class="flex flex-row align-items-center">
                                        <p class="w-100">
                                            <span class="font-weight-bold">ITEM:</span>
                                            <span class="font-600">{{ $barcodeDetail->fabric_description ?? '' }}</span>
                                        </p>
                                    </div>

                                    <div class="flex flex-row align-items-center">
                                        <p class="w-50"><span class="font-weight-bold">BUYER:</span>
                                            <span class="font-600">{{ $barcodeDetail->supplier->name ?? '' }}</span>
                                        </p>
                                    </div>

                                    <div class="flex flex-row align-items-center">
                                        <p class="w-50">
                                            <span class="font-weight-bold">REF NO:</span>
                                            <span class="font-600">{{ $receive->challan_no ?? '' }}</span>
                                        </p>
                                    </div>

                                    <div class="flex flex-row align-items-center">
                                        <p>
                                            <span class="font-weight-bold">Total Qty: </span>
                                            <span class="font-600">
                                                {{ $barcodeData->barcode_qty ?? '' }}
                                                {{ $barcodeDetail->unitOfMeasurement->unit_of_measurement }}
                                            </span>
                                        </p>
                                    </div>

                                    <div class="flex flex-row align-items-center">
                                        <p class="w-100">
                                            <span>
                                                <?php echo DNS1D::getBarcodeSVG((str_pad($barcodeData->id, 10, 0, STR_PAD_LEFT) ?? '1234'), "C128A", $barcode_width, $barcode_height, '', true); ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3>No Data Found</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('libs/jquery/jquery/dist/jquery.js') }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('libs/jquery/tether/dist/js/tether.min.js') }}"></script>
<script src="{{ asset('libs/jquery/bootstrap/dist/js/bootstrap.js') }}"></script>
</body>

</html>
