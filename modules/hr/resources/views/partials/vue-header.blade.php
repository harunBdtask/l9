<div class="navbar">

    <!-- Page title - Bind to $state's title -->
    <div class="navbar-item pull-left h5" id="pageTitle"></div>

    <!-- navbar right -->
    <ul class="nav navbar-nav pull-right">
    </ul>
    <!-- / navbar right -->

    <!-- navbar collapse -->
    <div class="navbar-toggleable-sm" id="collapse">
        <!-- link and dropdown -->
        <ul class="nav navbar-nav">
            <li class="nav-item">
                <a style="position: absolute" href="{{ url('/dashboard') }}">
                    <img src="{{ asset('modules/skeleton/img/logo/erp-in.png') }}"
                         style="margin-left: 4px;max-height: 42px; margin-top: 5px; padding-top: 4px"
                         alt="...">
                    @include('merchandising::head-banner')
                </a>

            </li>
        </ul>
        <!-- / -->
    </div>
    <!-- / navbar collapse -->
</div>
