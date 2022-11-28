<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>@yield("title") | goRMG</title>
    <meta name="description" content="RMG, ERP, Production Tracking"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('refresh')

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

    @stack('style')
    @yield('styles')

    @yield('head-script')
</head>
<body>
<div class="app" id="app">
    <div id="aside" class="app-aside modal fade nav-dropdown">
        <div class="left left navside black dk" layout="column">
            <div class="navbar no-radius">
                <div>
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <img class="small-logo" src="{{ asset('modules/skeleton/img/logo/erp-in.png') }}" alt="Logo">
                        <span class="hidden-folded inline">
                            <img src="{{ asset('modules/skeleton/img/logo/erp.png') }}"
                                 class="large-logo"
                                 style="margin-left: 4px;max-height: 44px;vertical-align: -22px;"
                                 alt="Logo">
                        </span>
                    </a>
                </div>
            </div>
            <div flex class="hide-scroll">
                <nav class="scroll nav-active-warning">
                    <div>
                        @include('skeleton::partials.nav')
                    </div>
                </nav>
            </div>
            <div flex-no-shrink="">
                <nav ui-nav="">
                    <ul class="nav">
                        <li>
                            <div class="b-b b m-v-sm"></div>
                        </li>
                        <li class="no-bg">
                            <a href="{{ url('/logout') }}">
                                <span class="nav-icon">
                                    <i class="material-icons"></i></span>
                                <span class="nav-text">Logout</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- content -->
    <div id="content" class="app-content box-shadow-z3" role="main">
        <div class="app-header white box-shadow">
            <div>
                @include('skeleton::partials.new_header_2')
            </div>
        </div>
        <div class="app-footer">
            <div>

                {{--                @include('skeleton::partials.new_footer')--}}
                @include('skeleton::partials.footer')
            </div>
        </div>
        <div ui-view class="app-body" id="view">
            <!-- ############ PAGE START-->
        @yield('content')
        <!-- ############ PAGE END-->
        </div>
        @include('skeleton::partials.switch')
    </div>
</div>
@include('skeleton::partials.confirm')
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
<script src="{{ asset('modules/skeleton/flatkit/assets/table_head_fixer/tableHeadFixer.js') }}"></script>

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

        $('.date-range input').each(function () {
            $(this).datepicker('clearDates');
        });

        $('.date-range').daterangepicker({
            opens: 'left'
        });

        $('[data-toggle="tooltip"]').tooltip();
        $('select.select2-input').select2();
        tableHeadFixer();
        //
        if (localStorage.getItem('full_screen') !== null) {
            GoOutFullscreen()
        }

        if (app.setting.folded === false) {
            jQuery('.small-logo').css('display', 'none')
        }
        if (app.setting.bg === '') {
            app.setting.bg = 'light';
        }
        jQuery('.material-icons').attr('style', 'color : ' + app.setting.color.primary)
    });

    function tableHeadFixer() {
        $(document).find("#fixTable").tableHeadFixer();
        $(document).find(".fixTable").tableHeadFixer();
    }

    function submitHeaderFactory(factory_id) {
        jQuery('#header_factory_id').val(factory_id)
        jQuery('#header_factory_form').submit()
    }

    function GoInFullscreen() {
        screenfull.toggle($('#container')[0]).then(function () {
            console.log('Fullscreen mode: ' + (screenfull.isFullscreen ? 'enabled' : 'disabled'))
        });
    }

    function GoOutFullscreen() {
        screenfull.toggle($('#container')[0]).then(function () {
            console.log('Fullscreen mode: ' + (screenfull.isFullscreen ? 'enabled' : 'disabled'))
        });
    }

    function IsFullScreenCurrently() {
        var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
        if (full_screen_element === null)
            return false;
        else
            return true;
    }

    $("#full-screen-button").on('click', function () {
        if (IsFullScreenCurrently()) {
            localStorage.removeItem('full_screen')
            GoOutFullscreen()
        } else {
            localStorage.setItem('full_screen', true)
            GoInFullscreen()
        }
    });

    $( "#autocomplete_id" ).keyup(function() {
        $.ajax({
            url: `/top-menu-search?q=${$('#autocomplete_id').val()}`,
            type: "GET",
            success:function(response) {
                $('#listBox2').empty();
                $.each(response.data.data, function (i, item) {
                    let newListItem = `<a href="${item.menu_url}" style="display: inline-flex;">
                                        <strong>${item.menu_name}</strong>
                                    </a>`;

                    $('#listBox2').append(newListItem);
                });
            }
        })
    });

    $(document).click(function() {
        $('#listBox2').empty();
    });

    $(window).load(function () {
        //, .select2-results__options, .select2-results
        jQuery('.select2-selection--single')
            .attr('style', 'background-color : ' + app.color[app.setting.bg])
    })
</script>
</body>
</html>
