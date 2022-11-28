<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Service Under Maintenance | goRMG</title>
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
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/daterangepicker.css') }}" type="text/css"/>
</head>
<body>
<div class="amber w-full" style="min-height: 750px;">
    <div class="text-center pos-rlt p-y-md">
        <h1 class="text-shadow m-a-0 text-white text-4x">
            <span class="text-2x font-bold block m-t-lg">Under Maintenance</span>
        </h1>
        <div class="row">
            <div class="col-sm-5"></div>
        <div style="height: 200px; width: 200px; margin-left: 60px;" class="col-sm-4">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.24 202.24"><defs><style>.cls-1{fill:#fff;}</style></defs><title>Asset 3</title><g id="Layer_2" data-name="Layer 2"><g id="Capa_1" data-name="Capa 1"><path class="cls-1" d="M101.12,0A101.12,101.12,0,1,0,202.24,101.12,101.12,101.12,0,0,0,101.12,0ZM159,148.76H43.28a11.57,11.57,0,0,1-10-17.34L91.09,31.16a11.57,11.57,0,0,1,20.06,0L169,131.43a11.57,11.57,0,0,1-10,17.34Z"/><path class="cls-1" d="M101.12,36.93h0L43.27,137.21H159L101.13,36.94Zm0,88.7a7.71,7.71,0,1,1,7.71-7.71A7.71,7.71,0,0,1,101.12,125.63Zm7.71-50.13a7.56,7.56,0,0,1-.11,1.3l-3.8,22.49a3.86,3.86,0,0,1-7.61,0l-3.8-22.49a8,8,0,0,1-.11-1.3,7.71,7.71,0,1,1,15.43,0Z"/></g></g></svg>
        </div>
        </div>
        <p class="h5 m-y-lg text-u-c font-bold text-black">Sorry for the inconvenience. We&rsquo;re performing some maintenance at the moment. <br>
            If you need to you can always contact for updates, otherwise we&rsquo;ll be back up shortly!</p>
        <p class="h5 m-y-lg text-u-c font-bold text-black">&mdash; The Skylark Soft Limited Team</p>
        <a href="/" class="md-btn amber-700 md-raised p-x-md">
            <span class="text-white">Go to the home page</span>
        </a>
    </div>
</div>
<!-- jQuery -->
<script src="{{ asset('modules/skeleton/lib/jquery/jquery.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/jquery/jquery-ui.min.js') }}"></script>

<script src="{{ asset('modules/skeleton/lib/parsley/parsley.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/lodash/lodash.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/select2/select2.min.js')  }}"></script>

<!-- Bootstrap -->
<script src="{{ asset('modules/skeleton/lib/tether/tether.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/bootstrap/bootstrap.js') }}"></script>
<!-- core -->
<script src="{{ asset('modules/skeleton/lib/underscore/underscore-min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/jquery/jquery.storageapi.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/pace/pace.min.js') }}"></script>

<script src="{{ asset('modules/skeleton/flatkit/scripts/config.lazyload.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/palette.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-load.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-include.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-device.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-jp.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-load.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-form.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-nav.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-scroll-to.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/ui-toggle-class.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/screenfull.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/flatkit/scripts/app.js') }}"></script>

<script src="{{ asset('modules/skeleton/lib/axios/axios.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/toaster/toaster.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/confirmation.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/common.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/common.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/moment.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/daterangepicker.js') }}"></script>
<script src="{{ asset('/js/tableHeadFixer.js') }}"></script>
</body>
</html>
