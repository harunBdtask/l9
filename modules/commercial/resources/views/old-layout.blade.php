{{--if current layout ok you can delete this--}}
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
                            <span class="hidden-folded inline">
                                <img src="{{ asset('modules/skeleton/img/logo/erp.png') }}"
                                     style="margin-left: 4px;max-height: 44px;vertical-align: -22px;" alt="...">
                            </span>
					</a>
					<ul class="nav" ui-nav>
						<li class="nav-header hidden-folded">
							<small class="text-muted" style="margin-left: -16px;">Main Navigation</small>
							<span class="btn btn-default btn-sm nav-caret nav-search" style="padding: 0px 4px; margin-top: 10px;"><i
										class="fa fa-search"></i></span>
						</li>
					</ul>
				</div>
			</div>
			<div flex class="hide-scroll">
				<nav class="scroll nav-active-warning">
					<div>
						@include('skeleton::partials/nav')
					</div>
				</nav>
			</div>
			<div flex-no-shrink>
				<div>
					@include('skeleton::partials/aside-top')
				</div>
			</div>
		</div>
	</div>

	<!-- content -->
	<div id="content" class="app-content box-shadow-z3" role="main">
		<div class="app-header white box-shadow">
			<div>
				@include('skeleton::partials/header')
			</div>
		</div>
		<div class="app-footer">
			<div>
				@include('skeleton::partials/footer')
			</div>
		</div>
		<div ui-view class="app-body" id="view">
			<!-- ############ PAGE START-->
			@yield('content')
			<!-- ############ PAGE END-->
		</div>
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
<script src="{{ asset('modules/skeleton/flatkit/scripts/app.js') }}"></script>

<script src="{{ asset('modules/skeleton/lib/axios/axios.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('modules/skeleton/lib/toaster/toaster.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/confirmation.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/common.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/common.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/moment.min.js') }}"></script>
<script src="{{ asset('modules/skeleton/js/daterangepicker.js') }}"></script>

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
	});
</script>
</body>
</html>
