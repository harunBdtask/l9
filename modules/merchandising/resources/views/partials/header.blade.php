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
                <div class="d-flex align-items-center">
                    <a  href="{{ url('/dashboard') }}">
                        <img src="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}"
                             style="margin-left: 4px;max-height: 42px; margin-top: 12px;height: 30px;margin-bottom: 7%;"
                             alt="...">
                        @include('merchandising::head-banner')
                    </a>
                </div>


            </li>
        </ul>
        <!-- / -->
    </div>
    <!-- / navbar collapse -->
</div>
