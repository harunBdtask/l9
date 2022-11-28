<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>goRMG | Price Quotation Costing</title>
    <meta name="description" content="RMG, ERP, Production Tracking"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
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

    <!-- libs -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/datepicker/datepicker3.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/select2/select2.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/morris-charts/morris.css')}}">
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/toaster/toaster.css')}}">
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('css/bootstrap-grid.min.css') }}">
    <style>
        .reportEntryTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }

        .reportEntryTable thead,
        .reportEntryTable tbody,
        .reportEntryTable th {
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }

        .reportEntryTable th,
        .reportEntryTable td {
            border: 1px solid transparent;
        }

        .reportTable th,
        .reportTable td {
            border: 1px solid #000208 !important;
        }

        input {
            font-size: 12px !important;
        }

        #vue-loader {
            /*position: fixed;*/
            /*top: 0;*/
            /*left: 0;*/
            /*right: 0;*/
            /*bottom: 0;*/
            /*height: 100vh;*/
            /*background: rgba(226, 226, 226, 0.75) no-repeat center center;*/
            /*width: 100%;*/
            /*z-index: 999;*/
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100vh;
            background: #e3dadabf url('/SLS_LOADER.GIF') no-repeat center;
            z-index: 999;
        }

        #loader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            width: 100%;
            height: 100vh;
            background: #e3dadabf url('/SLS_LOADER.GIF') no-repeat center;
            z-index: 999;
        }

        .spin-loader {
            position: relative;
            top: 46%;
            left: 0;
        }

        .form-buttons {
            position: fixed;
            z-index: 2000;
            top: 10px;
            right: 10px;
        }

        .text-align-right {
            text-align: right !important;
        }

        .text-align-left {
            text-align: left !important;
        }

        .custom-input-field {
            color: #000208;
            text-align: center !important;
        }

        .custom-color {
            background-color: #5bc0de;
            color: #fbfdff;
        }

        .tooltip-inner {
            background-color: #ddd;
            color: black;
        }

        .tooltip.bs-tooltip-right .arrow:before {
            border-right-color: #ddd !important;
        }

        .tooltip.bs-tooltip-left .arrow:before {
            border-right-color: #ddd !important;
        }

        .tooltip.bs-tooltip-bottom .arrow:before {
            border-right-color: #ddd !important;
        }

        .tooltip.bs-tooltip-top .arrow:before {
            border-right-color: #ddd !important;
        }

        .tooltip-info {
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body>
<div class="app" id="app">
    <div id="loader"></div>

    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('merchandising::partials/header')
            </div>
        </div>
        <div class="app-footer">
            <div>
                @include('skeleton::partials/footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <div class="padding">
                <div class="box">
                    <div id="root">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var loader;

    function loadNow(opacity) {
        if (opacity <= 0) {
            displayContent();
        } else {
            loader.style.opacity = opacity;
            window.setTimeout(function () {
                loadNow(opacity - 0.05);
            }, 5);
        }
    }

    function displayContent() {
        loader.style.display = 'none';
        document.getElementById('content').style.display = 'block';
    }

    document.addEventListener("DOMContentLoaded", function () {
        loader = document.getElementById('loader');
        loadNow(5);
    });
</script>
<script src="{{ asset('js/price_quotation_vue.js') }}"></script>
</body>

</html>
