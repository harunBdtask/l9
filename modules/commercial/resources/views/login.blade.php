<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions For Garments</title>
    <meta name="description" content="RMG, ERP, Production Tracking"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('refresh')
    
    <link rel="shortcut icon" sizes="196x196" href="{{ asset('modules/skeleton/flatkit/assets/images/gormg_fav_ico.png') }}">
    
    <!-- style -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/animate.css/animate.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/flatkit/assets/styles/font.css') }}" type="text/css"/>

    <!-- libs -->
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/datepicker/datepicker3.css')}}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/select2/select2.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/morris-charts/morris.css')}}">
    <link rel="stylesheet" href="{{ asset('modules/skeleton/lib/toaster/toaster.css')}}">
    <link rel="stylesheet" href="{{ asset('modules/skeleton/css/custom.css') }}" type="text/css"/>

    @stack('style')
    @yield('styles')
    
    @yield('head-script')
</head>
<body>
    <div class="app" id="app">
        <div class="center-block w-xxl w-auto-xs p-y-md" style="width: 450px;">
            <div style="margin-top: 60px;">
                <div class="navbar">
                    <div class="pull-center">
                        <a class="navbar-brand">
                            <img src="{{ asset('modules/skeleton/img/logo/'.(env('APP_LOGO') ?: 'erp').'.png') }}" alt="" class="" style="max-height: 93px;padding-bottom: 30px;vertical-align: -4px;">
                        </a>
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
    <script src="{{ asset('modules/skeleton/flatkit/scripts/ui-form.js') }}"></script>
    <script src="{{ asset('modules/skeleton/flatkit/scripts/ui-nav.js') }}"></script>
    <script src="{{ asset('modules/skeleton/flatkit/scripts/ui-scroll-to.js') }}"></script>
    <script src="{{ asset('modules/skeleton/flatkit/scripts/ui-toggle-class.js') }}"></script>
    <script src="{{ asset('modules/skeleton/flatkit/scripts/app.js') }}"></script>

    <script src="{{ asset('modules/skeleton/lib/axios/axios.min.js') }}"></script>
    <script src="{{ asset('modules/skeleton/lib/datepicker/bootstrap-datepicker.js') }}"></script>

    @yield('scripts')
    @stack('script-head')

    <!-- date picker related js -->
    

    <script type="text/javascript">
        $(document).ready(function () {
            $('th[data-toggle="tooltip"]').tooltip();
        });

        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            window.axios.defaults.headers.common = {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            };

            $('.datepicker').datepicker({
                format: 'mm/dd/yyyy',
                autoclose: true
            });

            $('.date-range input').each( function() {
                $(this).datepicker('clearDates');
            });

            $('[data-toggle="tooltip"]').tooltip();
            $('select.select2-input').select2();
        });
    </script>
</body>
</html>
