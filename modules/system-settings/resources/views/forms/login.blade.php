<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions for Garments</title>
    <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- for ios 7 style, multi-resolution icon of 152x152 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/gormg_fav_ico.ico') }}">
    <meta name="apple-mobile-web-app-title" content="Flatkit">
    <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.ico') }}">

    <!-- style -->
    <link rel="stylesheet" href="{{ asset('flatkit/assets/animate.css/animate.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/material-design-icons/material-design-icons.css') }}" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/styles/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/styles/font.css') }}" type="text/css"/>
</head>
<body>
<div class="app" id="app">

    <!-- ############ LAYOUT START-->
    <div class="center-block w-xxl w-auto-xs p-y-md" style="width: 450px;">
        <div style="margin-top: 60px;">
            <div class="navbar">
                <div class="pull-center">
                    <a class="navbar-brand">
                        <img src="{{asset('flatkit/assets/images/gormg_new.png')}}" alt="" class="" style="max-height: 93px;padding-bottom: 30px;vertical-align: -4px;">
                    </a>
                    {{--<div ui-include="'../views/blocks/navbar.brand.html'"></div>--}}
                </div>
            </div>
            <br>
            <div class="p-a-md box-color r box-shadow-z1 text-color m-a">
                <div class="m-b text-sm">
                    Sign in with your Account
                </div>
                @if(Session::has('error'))
                    <div class="text-danger text-center">{{ Session::get('error') }}</div>
                @endif
                <form name="form" action="{{ url('/post-login') }}" method="post">
                    {!! csrf_field() !!}
                    <div class="md-form-group float-label">
                        <input type="email" class="md-input" name="email" ng-model="user.email">
                        <label>Email</label>
                        @if($errors->has('email'))
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        @endif
                    </div>
                    <div class="md-form-group float-label">
                        <input type="password" class="md-input" name="password" ng-model="user.password">
                        <label>Password</label>
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="m-b-md">
                        <label class="md-check">
                            <input type="checkbox"><i class="primary"></i> Keep me signed in
                        </label>
                    </div>
                    <button type="submit" class="btn btn-block p-x-md" style="background-color: #0ea6e2;color: #ffffff;">Sign in</button>
                </form>
            </div>
        </div>
        <!-- <div class="p-v-lg text-center">
          <div class="m-b"><a ui-sref="access.forgot-password" href="#/access/forgot-password" class="text-primary _600">Forgot password?</a></div>
        </div> -->
    </div>

    <!-- ############ LAYOUT END-->

</div>

<!-- jQuery -->
<script src="{{ asset('libs/jquery/jquery/dist/jquery.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('libs/jquery/tether/dist/js/tether.min.js') }}"></script>
<script src="{{ asset('libs/jquery/bootstrap/dist/js/bootstrap.js') }}"></script>
<!-- core -->
<script src="{{ asset('libs/jquery/underscore/underscore-min.js') }}"></script>
<script src="{{ asset('libs/jquery/jQuery-Storage-API/jquery.storageapi.min.js') }}"></script>
<script src="{{ asset('libs/jquery/PACE/pace.min.js') }}"></script>

<script src="{{ asset('flatkit/scripts/config.lazyload.js') }}"></script>

<script src="{{ asset('flatkit/scripts/palette.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-load.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-jp.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-include.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-device.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-form.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-nav.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-screenfull.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-scroll-to.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ui-toggle-class.js') }}"></script>

<script src="{{ asset('flatkit/scripts/app.js') }}"></script>

<!-- ajax -->
<script src="{{ asset('libs/jquery/jquery-pjax/jquery.pjax.js') }}"></script>
<script src="{{ asset('flatkit/scripts/ajax.js') }}"></script>
<!-- endbuild -->

<script src="{{ asset('js/confirmation.js') }}"></script>

@yield('scripts')


</body>
</html>